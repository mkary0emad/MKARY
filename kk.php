<?php

$token = "7719594992:AAHiVOjE5dY8JUT0d7msRDUF8ZyzvyU2HeM";

$main_admin_id = "7217896334"; // المطور الأساسي
$admin_ids = [$main_admin_id];

if (file_exists("admins.json")) {
    $extra_admins = json_decode(file_get_contents("admins.json"), true);
    if (is_array($extra_admins)) {
        $admin_ids = array_unique(array_merge($admin_ids, $extra_admins));
    }
}

$website = "https://api.telegram.org/bot$token/";

$update = json_decode(file_get_contents("php://input"), true);
$message = $update["message"] ?? null;
$callback = $update["callback_query"] ?? null;
$chat_id = $message["chat"]["id"] ?? $callback["from"]["id"] ?? null;
$text = $message["text"] ?? null;
$data = $callback["data"] ?? null;
$msg_id = $callback["message"]["message_id"] ?? null;
$user_id = $chat_id;

$channels = json_decode(file_get_contents("channels.json"), true);
$joined_all = true;
$joined_all = true;
$buttons = [];
foreach ($channels as $ch) {
    $check = json_decode(file_get_contents("https://api.telegram.org/bot$token/getChatMember?chat_id=@$ch&user_id=$chat_id"), true);
    $status = isset($check['result']['status']) ? $check['result']['status'] : 'left';

    $subscribed = !in_array($status, ['left', 'kicked']);
    if (!$subscribed) {
        $joined_all = false;
    }

    $status_emoji = $subscribed ? "✅ مشترك" : "❌ غير مشترك";
    $buttons[] = [
        [
            "text" => "📢 @$ch",
            "url" => "https://t.me/$ch"
        ],
        [
            "text" => $status_emoji,
            "callback_data" => "noop"
        ]
    ];
}
if (!$joined_all && !in_array($user_id, $admin_ids)) {
    $subscribe_message = file_exists("subscribe_message.txt") ? file_get_contents("subscribe_message.txt") : "🚸 عذراً، يجب الاشتراك في القنوات التالية أولاً:";
    $buttons = [];
    foreach ($channels as $ch) {
        $buttons[] = [["text" => "@$ch", "url" => "https://t.me/$ch"]];
    }
    $buttons[] = [["text" => "✅ تحقق", "callback_data" => "check_subscribe"]];
    sendInlineKeyboard($chat_id, "$subscribe_message\n\n$channels_text", $buttons);
    exit;
}


if (!file_exists("users.json")) file_put_contents("users.json", json_encode([]));
if (!file_exists("channels.json")) file_put_contents("channels.json", json_encode([]));
if (!file_exists("userlog")) mkdir("userlog");

if (!file_exists("settings.json")) {
    file_put_contents("settings.json", json_encode([
        "content_protection" => true,
        "media_exempt" => false,
        "links_exempt" => false,
        "text_exempt" => false,
        "join_notify" => false,
        "bot_status" => "on",
        "forward_from_users" => false
    ]));
}

$users = json_decode(file_get_contents("users.json"), true);
$settings = json_decode(file_get_contents("settings.json"), true);

if (!isset($settings["forward_from_users"])) {
    $settings["forward_from_users"] = false;
    file_put_contents("settings.json", json_encode($settings));
}

if (!in_array($user_id, $admin_ids) && $settings["bot_status"] == "off") {
    sendMessage($chat_id, "❌ البوت حالياً في وضع الصيانة. الرجاء المحاولة لاحقاً.");
    exit;
}

if ($message && !in_array($user_id, $users)) {
    $users[] = $user_id;
    file_put_contents("users.json", json_encode($users));
    file_put_contents("userlog/$user_id.txt", date("Y-m-d"));
    if ($settings["join_notify"]) {
        foreach ($admin_ids as $admin) {
            $name = $message["from"]["first_name"] ?? "";
            $username = $message["from"]["username"] ?? "لا يوجد";
            $bio = $message["from"]["bio"] ?? "❌ لا يوجد نبذة";
            $user_id = $message["from"]["id"];
            $info = "🚸 مستخدم جديد دخل البوت\n";
            $info .= "👤 الاسم: $name\n";
            $info .= "📛 المعرف: @$username\n";
            $info .= "🆔 الايدي: $user_id\n";
            $info .= "📜 النبذة: $bio";
            sendMessage($admin, $info);
        }
    }
}

if ($message && !in_array($user_id, $admin_ids) && $settings["forward_from_users"]) {
    foreach ($admin_ids as $admin) {
        $name = $message["from"]["first_name"] ?? "";
        $username = $message["from"]["username"] ?? "لا يوجد";
        $fromid = $message["from"]["id"];
        $content = $text ?? "رسالة غير نصية";
        $msg = "📩 رسالة جديدة من مستخدم:\n\n👤 الاسم: $name\n🔗 معرف: @$username\n🆔 ID: $fromid\n\n💬 الرسالة:\n$content";
        sendMessage($admin, $msg);
    }
}

if (in_array($user_id, $admin_ids)) {

    if ($text == "/start" || $data == "admin_back") {
        if (file_exists("step_$chat_id.txt")) unlink("step_$chat_id.txt");

        $admin_text = "• أهلاً بك في لوحة الأدمن الخاصة بالبوت 🤖\n\n- يمكنك التحكم في البوت الخاص بك من هنا\n~~~~~~~~~~~~~~~~~~~~";

        $admin_buttons = [
            [["text" => "حماية محتوى البوت", "callback_data" => "protect_menu"]],
            [["text" => "تشغيل البوت : " . ($settings["bot_status"] == "on" ? "مفعل ✅" : "معطل ❌"), "callback_data" => "toggle_bot_status"], ["text" => " إشعار الدخول : " . ($settings["join_notify"] ? "مفعل ✅" : "معطل ❌"), "callback_data" => "toggle_join_notify"]],
            [["text" => "الردود", "callback_data" => "replies"], ["text" => "تعديل الأزرار", "callback_data" => "edit_buttons"], ["text" => "توجيه الرسائل", "callback_data" => "forward_menu"]],
            [["text" => "رسالة ترحيب (/start)", "callback_data" => "edit_start"]],
            [["text" => "قسم الاشتراك الإجباري", "callback_data" => "force_menu"], ["text" => "قسم الأدمنيه", "callback_data" => "admins_menu"]],
            [["text" => "قسم الاذاعه", "callback_data" => "broadcast"], ["text" => "قسم الاحصائيات", "callback_data" => "stats"]],
        ];

        if ($data == "admin_back") {
            editMessage($chat_id, $msg_id, $admin_text, $admin_buttons);
        } else {
            sendInlineKeyboard($chat_id, $admin_text, $admin_buttons);
        }
        return;
    }

    if ($data == "toggle_bot_status") {
        $settings["bot_status"] = $settings["bot_status"] == "on" ? "off" : "on";
        file_put_contents("settings.json", json_encode($settings));
        $data = "admin_back";
    }

    if ($data == "toggle_join_notify") {
        $settings["join_notify"] = !$settings["join_notify"];
        file_put_contents("settings.json", json_encode($settings));
        $data = "admin_back";
    }

    if ($data == "toggle_forward_users") {
        $settings["forward_from_users"] = !$settings["forward_from_users"];
        file_put_contents("settings.json", json_encode($settings));
        $data = "forward_menu";
    }

    if ($data == "force_menu") {
        $buttons = [
            [["text" => "➕ إضافة قناة", "callback_data" => "add_channel"], ["text" => "🗑️ حذف قناة", "callback_data" => "del_channel"]],
            [["text" => "📋 عرض قنوات الاشتراك الاجباري", "callback_data" => "show_channels"]],
            [["text" => "✏️ تعيين رسالة الاشتراك الاجباري", "callback_data" => "set_force_msg"]],
            [["text" => "🔙 رجوع", "callback_data" => "admin_back"]],
        ];
        editMessage($chat_id, $msg_id, "• مرحبًا بك في قسم الاشتراك الإجباري 📌", $buttons);
    }

    if ($data == "forward_menu") {
        $text = "• مرحبًا بك في قسم توجيه الرسائل 💌\n\n- يمكنك من خلال هذا الخيار تفعيل أو تعطيل إرسال أي رسالة يكتبها المستخدم إلى المطور مباشرة.";
        $buttons = [
            [["text" => "📥 التوجيه من الأعضاء : " . ($settings["forward_from_users"] ? "مفعل ✅" : "معطل ❌"), "callback_data" => "toggle_forward_users"]],
            [["text" => "🔙 رجوع", "callback_data" => "admin_back"]],
        ];
        editMessage($chat_id, $msg_id, $text, $buttons);
    }

    if ($data == "protect_menu") {
        $s = $settings;
        $text = "• مرحبًا في قسم حماية محتوى البوت 🥷🏾\n\n- يمكنك حماية جميع رسائل البوت من الحفظ أو التوجيه خارج البوت";
        $buttons = [
            [["text" => "حماية محتوي البوت : " . ($s["content_protection"] ? "✅" : "❌"), "callback_data" => "toggle_content_protection"]],
            [["text" => "استثناء الوسائط من الحمايه : " . ($s["media_exempt"] ? "✅" : "❌"), "callback_data" => "toggle_media_exempt"]],
            [["text" => "استثناء للرسائل التي تحتوي علي رابط من الحمايه : " . ($s["links_exempt"] ? "✅" : "❌"), "callback_data" => "toggle_links_exempt"]],
            [["text" => "استثناء النصوص من الحمايه : " . ($s["text_exempt"] ? "✅" : "❌"), "callback_data" => "toggle_text_exempt"]],
            [["text" => "🔙 رجوع", "callback_data" => "admin_back"]],
        ];
        editMessage($chat_id, $msg_id, $text, $buttons);
    }

    if (strpos($data, "toggle_") === 0 && !in_array($data, ["toggle_join_notify", "toggle_bot_status", "toggle_forward_users"])) {
        $field_map = [
            "toggle_content_protection" => "content_protection",
            "toggle_media_exempt" => "media_exempt",
            "toggle_links_exempt" => "links_exempt",
            "toggle_text_exempt" => "text_exempt"
        ];
        if (isset($field_map[$data])) {
            $field = $field_map[$data];
            $settings[$field] = !$settings[$field];
            file_put_contents("settings.json", json_encode($settings));
            $data = "protect_menu";
        }
    }

    if ($data == "stats") {
        $today = 0;
        $yesterday = 0;
        $now = date("Y-m-d");
        $yes = date("Y-m-d", strtotime("-1 day"));
        foreach ($users as $u) {
            $d = @file_get_contents("userlog/$u.txt");
            if ($d == $now) $today++;
            if ($d == $yes) $yesterday++;
        }
        $text = "📊 الإحصائيات:\n👥 عدد المستخدمين الكلي: " . count($users) . "\n🟢 المستخدمين الجدد اليوم: $today\n🕓 المستخدمين الجدد امس: $yesterday";
        editMessage($chat_id, $msg_id, $text, [[["text" => "🔙 رجوع", "callback_data" => "admin_back"]]]);
    }

    if ($data == "add_channel") {
        file_put_contents("step_$chat_id.txt", "add_channel");
        editMessage($chat_id, $msg_id, "📥 أرسل اسم القناة بدون @", [[["text" => "🔙 رجوع", "callback_data" => "force_menu"]]]);
    }

    if ($data == "del_channel") {
        $channels = json_decode(file_get_contents("channels.json"), true);
        if (empty($channels)) {
            editMessage($chat_id, $msg_id, "❌ لا توجد قنوات.", [[["text" => "🔙 رجوع", "callback_data" => "force_menu"]]]);
        } else {
            $buttons = [];
            foreach ($channels as $ch) {
                $buttons[] = [["text" => "@$ch", "callback_data" => "removech_$ch"]];
            }
            $buttons[] = [["text" => "🔙 رجوع", "callback_data" => "force_menu"]];
            editMessage($chat_id, $msg_id, "📋 اختر القناة لحذفها:", $buttons);
        }
    }

    
if ($data == "set_force_msg") {
    file_put_contents("step_$chat_id.txt", "set_force_msg");
    editMessage($chat_id, $msg_id, "- قم بإرسال كليشة الاشتراك الاجباري الآن:", [
        [["text" => "🔙 رجوع", "callback_data" => "force_menu"]]
    ]);
}

if ($data == "broadcast") {
        file_put_contents("step_$chat_id.txt", "broadcast");
        editMessage($chat_id, $msg_id, "✍️ أرسل الرسالة التي تريد إذاعتها.", [[["text" => "🔙 رجوع", "callback_data" => "admin_back"]]]);
    }

    if (strpos($data, "removech_") === 0) {
        $ch = str_replace("removech_", "", $data);
        $channels = json_decode(file_get_contents("channels.json"), true);
        $channels = array_values(array_diff($channels, [$ch]));
        file_put_contents("channels.json", json_encode($channels));
        editMessage($chat_id, $msg_id, "✅ تم حذف @$ch.", [[["text" => "🔙 رجوع", "callback_data" => "admin_back"]]]);
    }

    
if ($message && file_exists("step_$chat_id.txt")) {
    $step = file_get_contents("step_$chat_id.txt");

    if ($step == "set_force_msg") {
        file_put_contents("subscribe_message.txt", $text);
        sendMessage($chat_id, "✅ تم تعيين كليشة الاشتراك الإجباري بنجاح.");
        unlink("step_$chat_id.txt");
    }
}

if ($message && file_exists("step_$chat_id.txt")) {
        $step = file_get_contents("step_$chat_id.txt");
        if ($step == "add_channel") {
            $ch = trim(str_replace("@", "", $text));
            if ($ch != "") {
                $channels = json_decode(file_get_contents("channels.json"), true);
                if (!in_array($ch, $channels)) {
                    $channels[] = $ch;
                    file_put_contents("channels.json", json_encode($channels));
                    sendMessage($chat_id, "✅ تم إضافة @$ch بنجاح.");
                } else {
                    sendMessage($chat_id, "⚠️ القناة @$ch موجودة بالفعل.");
                }
            } else {
                sendMessage($chat_id, "❌ اسم القناة غير صالح.");
            }
            unlink("step_$chat_id.txt");
        }

        if ($step == "broadcast") {
            foreach ($users as $u) {
                sendMessage($u, $text);
            }
            sendMessage($chat_id, "✅ تم إرسال الرسالة لـ " . count($users) . " مستخدم.");
            unlink("step_$chat_id.txt");
        }
    }
}

function sendMessage($chat_id, $text) {
    global $website;
    file_get_contents($website . "sendMessage?chat_id=$chat_id&text=" . urlencode($text));
}

function sendInlineKeyboard($chat_id, $text, $buttons) {
    global $website;
    $markup = json_encode(['inline_keyboard' => $buttons]);
    file_get_contents($website . "sendMessage?chat_id=$chat_id&text=" . urlencode($text) . "&reply_markup=$markup");
}

function editMessage($chat_id, $msg_id, $text, $buttons = []) {
    global $website;
    $data = [
        "chat_id" => $chat_id,
        "message_id" => $msg_id,
        "text" => $text,
        "reply_markup" => json_encode(["inline_keyboard" => $buttons])
    ];
    file_get_contents($website . "editMessageText?" . http_build_query($data));
}
    if ($data == "edit_buttons") {
        $text = "• مرحبًا بك في قسم تعديل أزرار البوت 👋🏼\n\n- يمكنك إضافة تعديلات للأزرار أو حذفها";
        $buttons = [
            [["text" => "📋 قسم تعديل الازرار", "callback_data" => "edit_buttons_section"]],
            [["text" => "🔙 رجوع", "callback_data" => "admin_back"]]
        ];
        editMessage($chat_id, $msg_id, $text, $buttons);
    }

    if ($data == "edit_buttons_section") {
        $buttons = [
            [["text" => "زر وهمي للتجربة", "callback_data" => "edit_button_dummy"]],
            [["text" => "🔙 رجوع", "callback_data" => "edit_buttons"]]
        ];
        editMessage($chat_id, $msg_id, "🧩 اختر الزر الذي تريد تغييره:", $buttons);
    }

    if ($data == "edit_button_dummy") {
        file_put_contents("step_$chat_id.txt", "rename_button_dummy");
        editMessage($chat_id, $msg_id, "✏️ اكتب اسم الزر الجديد:");
    }

    if ($message && file_exists("step_$chat_id.txt")) {
        $step = file_get_contents("step_$chat_id.txt");
        if ($step == "rename_button_dummy") {
            unlink("step_$chat_id.txt");
            sendMessage($chat_id, "✅ تم تغيير اسم الزر إلى: " . $text);
        }
    }



// حفظ الردود
if (!file_exists("replies.json")) file_put_contents("replies.json", json_encode([]));
$replies = json_decode(file_get_contents("replies.json"), true);

// التفاعل مع المستخدمين بناءً على الردود
if ($message && !in_array($user_id, $admin_ids)) {
    if (isset($replies[$text])) {
        sendMessage($chat_id, $replies[$text]);
    }
}

// إدارة الردود - من خلال الأدمن
if (in_array($user_id, $admin_ids)) {
    if ($data == "replies") {
        file_put_contents("step_$chat_id.txt", "await_trigger");
        editMessage($chat_id, $msg_id, "📩 أرسل الرسالة التي تريد الرد عليها.", [[["text" => "🔙 رجوع", "callback_data" => "admin_back"]]]);
    }

    if ($message && file_exists("step_$chat_id.txt")) {
        $step = file_get_contents("step_$chat_id.txt");
        if ($step == "await_trigger") {
            file_put_contents("step_$chat_id.txt", "await_response");
            file_put_contents("temp_trigger_$chat_id.txt", $text);
            sendMessage($chat_id, "✏️ اكتب الرد الذي تريده على هذه الرسالة.");
        } elseif ($step == "await_response") {
            $trigger = file_get_contents("temp_trigger_$chat_id.txt");
            $replies[$trigger] = $text;
            file_put_contents("replies.json", json_encode($replies));
            unlink("step_$chat_id.txt");
            unlink("temp_trigger_$chat_id.txt");
            sendMessage($chat_id, "✅ تم حفظ الرد.");
        }
    }
}

// حفظ رسالة الترحيب
if ($data == "edit_start") {
    file_put_contents("step_$chat_id.txt", "edit_start_text");
    editMessage($chat_id, $msg_id, "- قم بارسال نص رسالة /start", [[["text" => "🔙 رجوع", "callback_data" => "admin_back"]]]);
}

if ($message && file_exists("step_$chat_id.txt")) {
    $step = file_get_contents("step_$chat_id.txt");
    if ($step == "edit_start_text") {
        file_put_contents("start_msg.txt", $text);
        sendMessage($chat_id, "✅ تم حفظ رسالة الترحيب بنجاح.");
        unlink("step_$chat_id.txt");
    }
}

// عرض رسالة الترحيب للمستخدم
if ($text == "/start") {
    $start_msg = file_exists("start_msg.txt") ? file_get_contents("start_msg.txt") : "👋 أهلاً بك في البوت!";
    sendMessage($chat_id, $start_msg);
}


// ✅ قسم الادمنيه
if ($data == "admins_menu") {
    if ($chat_id != $main_admin_id) {
        $buttons = [
            [["text" => "🔙 رجوع", "callback_data" => "admin_back"]]
        ];
        editMessage($chat_id, $msg_id, "🚫 هذا القسم مخصص للمطور فقط", $buttons);
        return;
    }

    $text = "• مـرحـبـا بـك فـي قـسـم الادمـنيـة 👮\n\n- هـنا مـن الأدمـنيـة إدارة يمكنك -";
    $buttons = [
        [["text" => "➕ أضف أدمن", "callback_data" => "add_admin"]],
        [["text" => "🗑️ حذف ادمن", "callback_data" => "delete_admins"]],
        [["text" => "🔙 رجوع", "callback_data" => "admin_back"]],
    ];
    editMessage($chat_id, $msg_id, $text, $buttons);
}

if ($data == "add_admin") {
    file_put_contents("step_$chat_id.txt", "add_admin");
    editMessage($chat_id, $msg_id, "📩 أرسل أيدي أو معرف المستخدم الذي تريد إضافته كأدمن.", [[["text" => "🔙 رجوع", "callback_data" => "admins_menu"]]]);
}

if ($data == "delete_admins") {
    $buttons = [];
    foreach ($admin_ids as $id) {
        if ($id != "7217896334") {
            $buttons[] = [["text" => "$id", "callback_data" => "removeadmin_$id"]];
        }
    }
    $buttons[] = [["text" => "🔙 رجوع", "callback_data" => "admins_menu"]];
    editMessage($chat_id, $msg_id, "🗑️ اختر الأدمن الذي تريد حذفه:", $buttons);
}

if (strpos($data, "removeadmin_") === 0) {
    $target = str_replace("removeadmin_", "", $data);
    $admin_ids = array_values(array_diff($admin_ids, [$target]));
    file_put_contents("admins.json", json_encode($admin_ids));
    editMessage($chat_id, $msg_id, "✅ تم حذف الأدمن: $target", [[["text" => "🔙 رجوع", "callback_data" => "admins_menu"]]]);
}

if ($message && file_exists("step_$chat_id.txt")) {
    $step = file_get_contents("step_$chat_id.txt");
    if ($step == "add_admin") {
        $new_admin = str_replace("@", "", $text);
        if (!in_array($new_admin, $admin_ids)) {
            $admin_ids[] = $new_admin;
            file_put_contents("admins.json", json_encode($admin_ids));
            sendMessage($chat_id, "✅ تم إضافة $new_admin كأدمن.");
        } else {
            sendMessage($chat_id, "⚠️ هذا المستخدم موجود بالفعل كأدمن.");
        }
        unlink("step_$chat_id.txt");
    }
}

if ($data == "show_channels") {
    $channels = json_decode(file_get_contents("channels.json"), true);
    if (empty($channels)) {
        $text = "❌ لا توجد قنوات اشتراك إجباري.";
    } else {
        $text = "📋 قائمة القنوات:\n\n";
        foreach ($channels as $ch) {
            $text .= "🔹 @$ch\n";
        }
    }
    editMessage($chat_id, $msg_id, $text, [[["text" => "🔙 رجوع", "callback_data" => "admin_back"]]]);
}

// الإذاعة المتعددة
if ($data == "broadcast_menu") {
    $text = "• مرحبًا بك في قسم الإذاعة 📢\n\n- اختر نوع الإذاعة التي تريد إرسالها:";
    $buttons = [
        [["text" => "نص 📄", "callback_data" => "broadcast_text"]],
        [["text" => "صورة 🖼", "callback_data" => "broadcast_photo"]],
        [["text" => "فيديو 🎥", "callback_data" => "broadcast_video"]],
        [["text" => "صوت 🔊", "callback_data" => "broadcast_voice"]],
        [["text" => "ملصق 🪧", "callback_data" => "broadcast_sticker"]],
        [["text" => "متحركة 🎞", "callback_data" => "broadcast_animation"]],
        [["text" => "ملف 📎", "callback_data" => "broadcast_document"]],
        [["text" => "🔙 رجوع", "callback_data" => "admin_back"]],
    ];
    editMessage($chat_id, $msg_id, $text, $buttons);
}

// إعداد خطوة الإذاعة حسب نوعها
$broadcast_types = ["text", "photo", "video", "voice", "sticker", "animation", "document"];
foreach ($broadcast_types as $type) {
    if ($data == "broadcast_" . $type) {
        file_put_contents("step_$chat_id.txt", "broadcast_$type");
        editMessage($chat_id, $msg_id, "✍️ أرسل $type للإذاعة الآن.

🔙 يمكنك الرجوع من خلال /start", []);
    }
}

// تنفيذ الإذاعة بعد الاستلام
if ($message && file_exists("step_$chat_id.txt")) {
    $step = file_get_contents("step_$chat_id.txt");
    if (strpos($step, "broadcast_") === 0) {
        $type = str_replace("broadcast_", "", $step);
        unlink("step_$chat_id.txt");
        foreach ($users as $u) {
            switch ($type) {
                case "text":
                    sendMessage($u, $text);
                    break;
                case "photo":
                    if (isset($message["photo"])) {
                        $file_id = end($message["photo"])["file_id"];
                        sendPhoto($u, $file_id, $caption ?? "");
                    }
                    break;
                case "video":
                    if (isset($message["video"])) {
                        $file_id = $message["video"]["file_id"];
                        sendVideo($u, $file_id, $caption ?? "");
                    }
                    break;
                case "voice":
                    if (isset($message["voice"])) {
                        $file_id = $message["voice"]["file_id"];
                        sendVoice($u, $file_id, $caption ?? "");
                    }
                    break;
                case "sticker":
                    if (isset($message["sticker"])) {
                        $file_id = $message["sticker"]["file_id"];
                        sendSticker($u, $file_id);
                    }
                    break;
                case "animation":
                    if (isset($message["animation"])) {
                        $file_id = $message["animation"]["file_id"];
                        sendAnimation($u, $file_id, $caption ?? "");
                    }
                    break;
                case "document":
                    if (isset($message["document"])) {
                        $file_id = $message["document"]["file_id"];
                        sendDocument($u, $file_id, $caption ?? "");
                    }
                    break;
            }
        }
        sendMessage($chat_id, "✅ تم إرسال إذاعة من نوع: $type إلى " . count($users) . " مستخدم.");
    }
}


function sendPhoto($chat_id, $file_id, $caption = "") {
    global $website;
    file_get_contents($website . "sendPhoto?chat_id=$chat_id&photo=$file_id&caption=" . urlencode($caption));
}
function sendVideo($chat_id, $file_id, $caption = "") {
    global $website;
    file_get_contents($website . "sendVideo?chat_id=$chat_id&video=$file_id&caption=" . urlencode($caption));
}
function sendVoice($chat_id, $file_id, $caption = "") {
    global $website;
    file_get_contents($website . "sendVoice?chat_id=$chat_id&voice=$file_id&caption=" . urlencode($caption));
}
function sendSticker($chat_id, $file_id) {
    global $website;
    file_get_contents($website . "sendSticker?chat_id=$chat_id&sticker=$file_id");
}
function sendAnimation($chat_id, $file_id, $caption = "") {
    global $website;
    file_get_contents($website . "sendAnimation?chat_id=$chat_id&animation=$file_id&caption=" . urlencode($caption));
}
function sendDocument($chat_id, $file_id, $caption = "") {
    global $website;
    file_get_contents($website . "sendDocument?chat_id=$chat_id&document=$file_id&caption=" . urlencode($caption));
}
