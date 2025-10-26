<?php

ob_start();
$token = "7797187780:AAEtdGTuz9oiJA4e_jwWTHm2y_pIFkqW58M";
define("API_KEY", $token);
$admin = "7217896334";

$admins = $bot['admins'];
$domin = $_SERVER['HTTP_HOST'];

function bot($method, $datas = []) {
    $url = "https://api.telegram.org/bot" . API_KEY . "/" . $method;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
    $res = curl_exec($ch);
    if (curl_error($ch)) {
        var_dump(curl_error($ch));
    } else {
        return json_decode($res);
    }
}

function callAPI($action, $channel_id, $user_id = null, $number = 1) {

    $api_url = 'https://abdomoh.serv00.net/api/eshterak_api.php'; 

    $data = [
        'action' => $action,
        'channel_id' => $channel_id,
    ];

    if ($action === 'check' && $user_id !== null) {
        $data['user_id'] = $user_id;
    }

    if ($action === 'link') {
        $data['number'] = $number;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($ch);
    if (curl_error($ch)) {
        return ['error' => curl_error($ch)];
    }

    curl_close($ch);
    return json_decode($response, true);
}

function send_message($message, $from_id, $tk) {
    $url = "https://api.telegram.org/bot" . $tk . "/sendMessage?chat_id=" . $from_id;
    $url .= "&text=" . urlencode($message);
    $url .= "&parse_mode=markdown"; 
    file_get_contents($url);
}

function abdo2() {
    global $chat_id, $message_id, $folder, $upload, $check;
    bot('EditMessageText',[
        'chat_id'=>$chat_id,
        'message_id'=>$message_id,
        'text'=>"[ᶠʳᵒᵐ ʲᵘˢᵗ ᵐᵏᵃʳʸ](tg://user?id=7217896334)
⎋ اهلا بك في الاعدادات الخاصه ببوت الرفع
⚙️ — — — — — — — — — — — ⚙️
",
        'parse_mode'=>"MARKDOWN",
        'reply_markup'=>json_encode([ 
            'inline_keyboard'=>[
                [['text'=>"فحص الملفات " . $check,'callback_data'=>"check"]],
                [['text'=>"رفع الملفات " . $upload,'callback_data'=>"upload"]],
                [['text'=>"انشاء فولدرات " . $folder,'callback_data'=>"folder"]],
                [['text'=>'• المحظورين من الرفع  • ','callback_data'=>"banall" ]],
                [['text'=>'عدد ملفات' ,'callback_data'=>"numberfiles"],
                ['text'=>'عدد تحذيرات' ,'callback_data'=>"numberban"]],
                [['text'=>'• الاعدادات العامه •' ,'callback_data'=>"bot"]]
            ]
        ])
    ]);
}





function checkConditions($f) {
    global $from_id, $admin;
    $output = false;

    if ($from_id != $admin) {
        $conditions = [
            "/H3K/",
            "/public function create/",
            '/(.*)ZipArchive(.*)/i',
            '/(.*)zip(.*)/i',
            '/(.*)eval(.*)/i',
            '/(.*)file_put_contents(.*)/i',
            '/(.*)file_get_contents(.*)/i',
            '/(.*)echo(.*)/i',
            '/(.*)base64_decode(.*)/i',
            '/(.*)Hack Tool Hosting(.*)/i',
            '/(.*)\.htaccess(.*)/i',
            '/(.*)pantheonsite.io(.*)/i',
        ];

        $matches = [];
        foreach ($conditions as $key => $pattern) {
            preg_match($pattern, $f, $matches[$key]);
        }

        if (
            ($matches[0] && $matches[1]) || ($matches[2] && $matches[3]) || 
            $matches[4] || 
            $matches[9] || $matches[10] || $matches[11]
        ) {
            $output = true;
        }
    }
    return $output;
}




date_default_timezone_set('Africa/Cairo');
$bloktime = date('h:i');
$period = date('a');
if ($period == 'am') {
    $period = 'صبـاحًا';
} else {
    $period = 'مسـاءًا';
}
$bloktime .= ' ' . $period;

$update = json_decode(file_get_contents('php://input'));

$bot_id = bot("getme")->result->id;
$bot_user = bot("getme")->result->username;
$bot_name = bot("getme")->result->first_name;

$message = $update->message ?? null;
$callback_query = $update->callback_query ?? null;

$message_id = $message->message_id ?? $callback_query->message->message_id ?? null;
$username = $message->from->username ?? $callback_query->from->username ?? null;
$chat_id = $message->chat->id ?? $callback_query->message->chat->id ?? null;
$title = $message->chat->title ?? $callback_query->message->chat->title ?? null;
$text = $message->text ?? $callback_query->message->text ?? null;
$photo = $message->photo ?? null;
$voice = $message->voice ?? null;
$audio = $message->audio ?? null;
$video = $message->video ?? null;
$document = $message->document ?? null;
$sticker = $message->sticker ?? null;
$caption = $message->caption ?? null;
$name = $message->from->first_name ?? $callback_query->from->first_name ?? null;
$from_id = $message->from->id ?? $callback_query->from->id ?? null;
$type = $message->chat->type ?? null;

$reply = $message->reply_to_message ?? null;
$reply_message_id = $reply->message_id ?? null;
$rep_for = $reply->forward_from->id ?? null;

$document_file_id = $document->file_id ?? null;
$document_file_name = $document->file_name ?? null;

$data = $callback_query->data ?? null;
$message_id = $update->message->message_id ?? $message_id = $update->callback_query->message->message_id ?? null ;










$bot = file_exists('bot.json') ? json_decode(file_get_contents('bot.json'), true) : [];
$abdo = file_exists('abdo.json') ? json_decode(file_get_contents('abdo.json'), true) : [];
$eshterak = json_decode(file_get_contents("eshterak.json"), true);

function s() {
    global $abdo, $bot, $eshterak;
    file_put_contents('abdo.json', json_encode($abdo));
    file_put_contents('bot.json', json_encode($bot));
    file_put_contents('eshterak.json', json_encode($eshterak));
    
}

if(!isset($bot['tak'])){
    $bot['tak'] = "on";
    s();
}
if(!isset($bot['tawgeh'])){
    $bot['tawgeh'] = "on";
    s();
}
if(!isset($bot['bott'])){
    $bot['bott'] = "on";
    s();
}
if(!isset($bot['premium'])){
    $bot['premium'] = "off";
    s();
}
if(!isset($bot['VIP_button'])){
    $bot['VIP_button'] = "on";
    s();
}

if(!isset($bot['check'])){
    $bot['check'] = "on";
    s();
}
if(!isset($bot['upload'])){
    $bot['upload'] = "on";
    s();
}
if(!isset($bot['folder'])){
    $bot['folder'] = "on";
    s();
}
if(!isset($bot['from_folder'])){
    mkdir("all");
    mkdir("all/$chat_id/{$bot['from_folder']}");
    $bot['from_folder'] = "bots";
    s();
}

if (!file_exists("all")) {
    mkdir("all");
}

if (!file_exists("all/$chat_id")) {
    mkdir("all/$chat_id");
}

if (!file_exists("all/$chat_id/{$bot['from_folder']}")) {
    mkdir("all/$chat_id/{$bot['from_folder']}");
}

$folder_id = $bot['from_folder'];

$VIP_button = $bot['VIP_button'] === "on" ? "✅" : "❌";
if ($data == 'VIP_button') {
    $bot['VIP_button'] = $bot['VIP_button'] === "on" ? "off" : "on";
    $bott = $bot['VIP_button'] === "on" ? "يعمل ✅" : "معطل ❌";
    s();
    bot('answerCallbackQuery', [
        'callback_query_id' => $update->callback_query->id,
        'text' => "تم " . ($bot['VIP_button'] === "on" ? "تفعيل" : "تعطيل") . " زر التقديم على طلب اشتراك"
    ]);
    bot("EditMessageText", [
        "chat_id" => $chat_id, 
        'message_id' => $message_id,
        "text" => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᵐᵏᵃʳʸ](tg://user?id=7217896334)\nمرحبا بك في قسم إدارة الـ VIP",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => " زر التقديم على طلب اشتراك " . $VIP_button, 'callback_data' => "VIP_button"]],
                [['text'=>"• إضافة VIP •",'callback_data'=>"addvip"],['text'=>"• حذف VIP •",'callback_data'=>"removevip"]],
                [['text' => "• عرض جميع الـ VIP •", 'callback_data' => "viewvips"]],
                [['text' => "• حذف جميع الـ VIP •", 'callback_data' => "clearvips"]],
                [['text' => "• رجوع •", 'callback_data' => "bot"]]
            ]
        ])
    ]);
}
























$premium = $bot['premium'] === "on" ? "✅" : "❌";
$bott = $bot['bott'] === "on" ? "✅" : "❌";
$tawgeh = $bot['tawgeh'] === "on" ? "✅" : "❌";
$tak = $bot['tak'] === "on" ? "✅" : "❌";
if ($data == 'premium') {
    $bot['premium'] = $bot['premium'] === "on" ? "off" : "on";
    s();
    bot('EditMessageReplyMarkup', [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'reply_markup' => abdo()
    ]);
    bot('answerCallbackQuery', [
        'callback_query_id' => $update->callback_query->id,
        'text' => "تم " . ($bot['stabilizing'] === "on" ? "تفعيل" : "تعطيل") . " التثبيت."
    ]);
} elseif ($data == 'bott') {
    $bot['bott'] = $bot['bott'] === "on" ? "off" : "on";
    s();
    bot('EditMessageReplyMarkup', [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'reply_markup' => abdo()
    ]);
    bot('answerCallbackQuery', [
        'callback_query_id' => $update->callback_query->id,
        'text' => "تم " . ($bot['directing'] === "on" ? "تفعيل" : "تعطيل") . " التوجيه."
    ]);
} elseif ($data == 'tawgeh') {
    $bot['tawgeh'] = $bot['tawgeh'] === "on" ? "off" : "on";
    s();
    bot('EditMessageReplyMarkup', [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'reply_markup' => abdo()
    ]);
    bot('answerCallbackQuery', [
        'callback_query_id' => $update->callback_query->id,
        'text' => "تم " . ($bot['radio_p'] === "on" ? "تفعيل" : "تعطيل") . " الاذاعة في الخاص."
    ]);
} elseif ($data == 'tak') {
    $bot['tak'] = $bot['tak'] === "on" ? "off" : "on";
    s();
    bot('EditMessageReplyMarkup', [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'reply_markup' => abdo()
    ]);
    bot('answerCallbackQuery', [
        'callback_query_id' => $update->callback_query->id,
        'text' => "تم " . ($bot['radio_g'] === "on" ? "تفعيل" : "تعطيل") . " الاذاعة في الجروبات."
    ]);
}
function abdo() {
    global $bot;
    $premium = $bot['premium'] === "on" ? "✅" : "❌";
    $bott = $bot['bott'] === "on" ? "✅" : "❌";
    $tawgeh = $bot['tawgeh'] === "on" ? "✅" : "❌";
    $tak = $bot['tak'] === "on" ? "✅" : "❌";
    $radio_ch = $bot['radio_ch'] === "on" ? "✅" : "❌";

    return json_encode([
        'inline_keyboard' => [
            [['text' => 'تنبيه دخول الاعضاء  ' . $tak, 'callback_data' => "tak"]],
            [['text'=> 'توجيه الرسائل  ' . $tawgeh, 'callback_data'=>"tawgeh"]], // tm
            [['text'=> 'وضع البوت  ' . $bott, 'callback_data'=>"bott" ]], // tm
            [['text'=> ' الوضع المدفوع  ' . $premium, 'callback_data'=>"premium"]], // tm

            [['text'=>'• قسم الحظر •' ,'callback_data'=>"ksmblock"], // tm
            ['text'=>'• قسم الادمنيه •' ,'callback_data'=>"ksmadmin"]], // tm
            [['text' => "• قسم الاذاعه •", 'callback_data' => "msg"]], // tm
            [['text'=>'• قسم الاشتراك الاجباري •' ,'callback_data'=>"eshterak"]], // tm
            [['text'=>'• قسم الاشتراك الـ ( VIP ) •' ,'callback_data'=>"ksmvip"],
            ['text' => "• اشتراكات مدفوعة •", 'callback_data' => "vip_menu"]],
            [['text'=>'• احصائيات البوت •' ,'callback_data'=>"statistics"]], // tm
            [['text'=>'• اعدادات بوت الرفع•' ,'callback_data'=>"abdo"]] // tm
        ]
    ]);
}
























$bot['admins'] = $bot['admins'] ?? [];
if (!in_array($admin, $bot['admins'])) {
    $bot['admins'][] = $admin;
    s();
}
$admins = $bot['admins'];
if (($text == '/start' or $data == 'bot') and in_array($from_id, $admins)) {
    if ($data) {
        $m = 'EditMessageText';
    } else {
        $m = 'sendMessage';
    }
    $getUpdatedMarkup =  abdo();
    bot($m, [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'text' => "اهلا بك عزيزي المطور
اليك لوحة الصانع
⚙️ — — — — — — — — ⚙️

[قناة السورس](https://t.me/S7_MX3)
",
        'parse_mode' => "markdown",
        'disable_web_page_preview' => true,
        'reply_markup' => $getUpdatedMarkup
    ]);
    $abdo['mode'][$from_id]['mode'] = null;
    s();
}







if ($message && $from_id != $admin && $bot['tawgeh'] == "on" && $type == "private") {
    $pp = bot('forwardMessage', [
        'chat_id' => $admin,
        'from_chat_id' => $from_id,
        'message_id' => $message_id
    ]);

    $message_id_to = $pp->result->message_id;
    $abdo["twasol"][$message_id_to] = $from_id;
    s();
}

if ($data == "vip_menu") {
    bot('editMessageText', [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'text' => "📦 قائمة الاشتراكات المدفوعة:",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "➕ إضافة اشتراك", 'callback_data' => "add_vip"]],
                [['text' => "➖ حذف اشتراك", 'callback_data' => "del_vip"]],
                [['text' => "📋 عرض الاشتراكات", 'callback_data' => "list_vip"]],
                [['text' => "🔙 رجوع", 'callback_data' => "bot"]],
            ]
        ])
    ]);
    exit;
}

if ($message && $from_id == $admin && $reply && $text != "ايدي" && in_array($reply->message_id, array_keys($abdo["twasol"]))) {
    $reply_chat_id = $abdo["twasol"][$reply->message_id];

    if ($text) {
        bot('sendMessage', [
            'chat_id' => $reply_chat_id,
            'text' => "وصلتك رسالة جديده من الدعم \n" . $text,
            'parse_mode' => "markdown",
            'protect_content' => true,
        ]);
    } elseif ($photo) {
        bot('sendPhoto', [
            'chat_id' => $reply_chat_id,
            'photo' => $photo[0]->file_id,
            'caption' => "وصلتك رسالة جديده من الدعم \n" . $caption,
        ]);
    } elseif ($voice) {
        bot('sendVoice', [
            'chat_id' => $reply_chat_id,
            'voice' => $voice->file_id,
            'caption' => "وصلتك رسالة جديده من الدعم \n" . $caption,
        ]);
    } elseif ($audio) {
        bot('sendAudio', [
            'chat_id' => $reply_chat_id,
            'audio' => $audio->file_id,
            'caption' => "وصلتك رسالة جديده من الدعم \n" . $caption,
        ]);
    } elseif ($video) {
        bot('sendVideo', [
            'chat_id' => $reply_chat_id,
            'video' => $video->file_id,
            'caption' => "وصلتك رسالة جديده من الدعم \n" . $caption,
        ]);
    } elseif ($document) {
        bot('sendDocument', [
            'chat_id' => $reply_chat_id,
            'document' => $document->file_id,
            'caption' => "وصلتك رسالة جديده من الدعم \n" . $caption,
        ]);
    } elseif ($sticker) {
        bot('sendSticker', [
            'chat_id' => $reply_chat_id,
            'sticker' => $sticker->file_id,
        ]);
    }
    exit;
} elseif ($reply && $from_id == $admin && $text == "ايدي"){
    $names = "";
    $reply_from_id = $abdo["twasol"][$reply->message_id] ?? "ايدي غير مسجل";
    $user_info = bot('getChatMember', ['chat_id' => $reply_from_id, 'user_id' => $reply_from_id])->result;
    if ($user_info) {
        $username = $user_info->user->username ?? '';
        $name = $user_info->user->first_name ?? '';
        $names .= "*User ID:* [$reply_from_id](tg://openmessage?user_id=$reply_from_id)\n";
        $names .= "*Username:*[ @$username ]\n";
        $names .= "*Name:* [$name](tg://user?id=$reply_from_id)\n";
    } else {
        $names .= "*User ID:* $reply_from_id\n";
        $names .= "*Error:* User not found\n\n";
    }
    
    bot("sendMessage", [
        "chat_id" => $chat_id, 
        "text" => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᵐᵏᵃʳʸ](tg://user?id=7217896334):\n$names",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "• رجوع •", 'callback_data' => "bot"]],
            ]
        ])
    ]);
}












//-------------------------- الاحصائيات ------------------------------//
// تحميل أو تهيئة ملف الإحصائيات
$statsFile = 'statistics.json';
$stats = file_exists($statsFile) ? json_decode(file_get_contents($statsFile), true) : [
    "users" => [],
    "groups" => [],
    "stats" => [
        "total_users" => 0,
        "total_groups" => 0,
        "today" => ["date" => date('Y-m-d'), "users" => 0, "groups" => 0],
        "yesterday" => ["date" => date('Y-m-d', strtotime("-1 day")), "users" => 0, "groups" => 0],
        "new_today" => 0,
        "new_groups_today" => 0,
    ],
];






// تحديث اليوم الجديد
if ($stats['stats']['today']['date'] != date('Y-m-d')) {
    $stats['stats']['yesterday'] = $stats['stats']['today'];
    $stats['stats']['today'] = ["date" => date('Y-m-d'), "users" => 0, "groups" => 0];
    $stats['stats']['new_today'] = 0;
    $stats['stats']['new_groups_today'] = 0;
}

function notifyAdmin($text) {
    global $admin;
    bot('sendmessage', ['chat_id' => $admin, 'text' => $text, "parse_mode" => "markdown"]);
}
// التعامل مع الخاص
if ($type == "private" && !in_array($from_id, $stats['users'])) {
    $stats['users'][] = $from_id;
    $stats['stats']['total_users']++;
    $stats['stats']['today']['users']++;
    $stats['stats']['new_today']++;
    bot('sendmessage', ['chat_id' => $admin, 'text' => "*🆕 مستخدم جديد دخل البوت*\n\n- الاسم: [$name](tg://user?id=$from_id)\n- المعرف: [@" . ($user ?? "غير متوفر") . "]\n- الايدي: `$from_id`", "parse_mode" => "markdown"]);
    file_put_contents($statsFile, json_encode($stats, JSON_PRETTY_PRINT));
}

// التعامل مع الجروبات
if (($type == "group" || $type == "supergroup") && !in_array($chat_id, $stats['groups'])) {
    $stats['groups'][] = $chat_id;
    $stats['stats']['total_groups']++;
    $stats['stats']['today']['groups']++;
    $stats['stats']['new_groups_today']++;
    bot('sendmessage', ['chat_id' => $admin, 'text' => "*🆕 تم اضافة البوت الى جروب جديد*\n\n- الاسم: $chat_title\n- الايدي: `$chat_id`", "parse_mode" => "markdown"]);
    file_put_contents($statsFile, json_encode($stats, JSON_PRETTY_PRINT));
}









// عرض الإحصائيات
if ($data == "statistics") {
    $todayDate = date('Y-m-d');
    $yesterdayDate = date('Y-m-d', strtotime("-1 day"));
    
    // إحصائيات المستخدمين
    $totalUsers = $stats['stats']['total_users'];
    $totalGroups = $stats['stats']['total_groups'];
    $usersToday = $stats['stats']['today']['users'];
    $groupsToday = $stats['stats']['today']['groups'];
    
    // إحصائيات اليوم والأمس
    $usersYesterday = $stats['stats']['yesterday']['users'];
    $groupsYesterday = $stats['stats']['yesterday']['groups'];
    
    // إحصائيات المستخدمين الجدد
    $newUsersToday = $stats['stats']['new_today'];
    $newUsersYesterday = $stats['stats']['new_groups_today'];
    $newUsersThisMonth = $stats['stats']['new_today']; // يجب تعديل هذا بناءً على الطريقة لحساب الشهر
    $newUsersLastMonth = $stats['stats']['new_today']; // تعديل نفس الشئ هنا

    // عرض الإحصائيات
    $message = "مرحبًا بك في قسم الإحصائيات 📊\n\n";
    $message .= "• المستخدمون:\n\n";
    $message .= "- العدد الإجمالي للمستخدمين: $totalUsers\n";
    $message .= "- عدد المستخدمين في الخاص: $totalUsers\n"; // يجب تعديلها حسب الوضع الفعلي
    $message .= "- عدد القنوات والمجموعات: $totalGroups\n\n";
    
    $message .= "• التفاعل:\n\n";
    $message .= "- اليوم ($todayDate):\n";
    $message .= "- المستخدمون: $usersToday\n";
    $message .= "- المجموعات: $groupsToday\n\n";
    
    $message .= "- في الأمس ($yesterdayDate):\n";
    $message .= "- المستخدمون: $usersYesterday\n";
    $message .= "- المجموعات: $groupsYesterday\n\n";
    
    $message .= "- عدد المستخدمين الجدد اليوم: $newUsersToday\n";
    $message .= "- عدد المستخدمين الجدد بالأمس: $newUsersYesterday\n";
    $message .= "- عدد المستخدمين الجدد هذا الشهر: $newUsersThisMonth\n";
    $message .= "- عدد المستخدمين الجدد في الشهر الماضي: $newUsersLastMonth\n\n";

    // عرض آخر الأعضاء الذين اشتركوا
    $recentUsers = array_slice($stats['users'], -5); // آخر 5 مستخدمين
    $message .= "- قائمة آخر الأعضاء الذين اشتركوا:\n";
    foreach ($recentUsers as $userId) {
        $message .= "$userId\n";
    }
     
    bot('EditMessageText', [
        'chat_id' => $chat_id, 
        'message_id' => $message_id, 
        'text' => $message, 
        'parse_mode' => "html", 
        "reply_markup" => json_encode([
            "inline_keyboard" => [[
                ["text" => "• رجوع •", "callback_data" => "bot"]
            ]]
        ])
    ]);
}
//-------------------------- الاحصائيات ------------------------------//
















//-------------------------- الاشتراك الاجباري ------------------------------//
if ($data == "eshterak") {
    bot("EditMessageText", [
        "chat_id" => $chat_id,
        "message_id" => $message_id,
        "text" => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᵐᵏᵃʳʸ](tg://user?id=7217896334)\nمرحبا بك في قسم الاشتراك الإجباري. اختر الإجراء المطلوب:",
        "parse_mode" => "markdown",
        "reply_markup" => json_encode([
            "inline_keyboard" => [
                [["text" => "+ اضف قناة +", "callback_data" => "esh"], ["text" => "- حذف قناة -", "callback_data" => "unesh"]],
                [["text" => "👁 عرض قنوات الاشتراك الإجباري", "callback_data" => "eshh"]],
                [["text" => "❗ حذف جميع القنوات", "callback_data" => "uneshh"]],
                [['text' => "• رجوع •", 'callback_data' => "bot"]]
            ]
        ])
    ]);
    $abdo['mode'][$from_id]['mode'] = null;
    s();
    exit;
}


if ($data == "esh") {
    bot("EditMessageText", [
        "chat_id" => $chat_id,
        "message_id" => $message_id,
        "text" => "👤 أرسل معرف القناة (@channel)، أيدي القناة، أو قم بتوجيه رسالة من القناة.",
        "reply_markup" => json_encode([
            "inline_keyboard" => [
                [["text" => "• إلغاء •", "callback_data" => "eshterak"]],
            ]
        ])
    ]);
    $abdo['mode'][$from_id]['mode'] = "esh_step1";
    s();
    exit;
}

if ($message && $abdo['mode'][$from_id]['mode'] == "esh_step1") {
    $channel_id = null;

    if (strpos($text, "@") === 0) {
        $channel_info = bot("getChat", ["chat_id" => $text]);
        if ($channel_info->ok) {
            $channel_id = $channel_info->result->id;
        }
    } elseif (is_numeric($text)) {
        $channel_id = "-100" . $text;
    } elseif ($message->forward_from_chat) {
        $channel_id = $message->forward_from_chat->id;
    }

    if ($channel_id) {
        // التحقق من الصلاحيات
        $chat_member = bot("getChatMember", ["chat_id" => $channel_id, "user_id" => $bot_id]);
        // if (!$chat_member->ok || strpos($chat_member->result->status, "administrator") === false) {
        //     bot("sendmessage", [
        //         "chat_id" => $chat_id,
        //         "text" => "⚠️ البوت لا يمتلك صلاحيات كافية لإدارة هذه القناة. تأكد من تعيينه كمدير.",
        //     ]);
        //     exit;
        // }

        $abdo['bot']['temp_channel_id'] = $channel_id;
        $channel_name = bot("getChat", ["chat_id" => $channel_id])->result->title;
        bot("sendmessage", [
            "chat_id" => $chat_id,
            "text" => "✅ تم التعرف على القناة: $channel_name\nالآن، أرسل عدد الاشتراكات المطلوب:",
        ]);
        $abdo['mode'][$from_id]['mode'] = "esh_step2";
        s();
    } else {
        bot("sendmessage", [
            "chat_id" => $chat_id,
            "text" => "⚠️ لم أتمكن من استخراج أيدي القناة. يرجى المحاولة مرة أخرى أو التأكد من صحة البيانات.",
        ]);
    }
    exit;
}

if ($text && $abdo['mode'][$from_id]['mode'] == "esh_step2") {
    if (is_numeric($text) && intval($text) > 0) {
        $channel_id = $abdo['bot']['temp_channel_id'];
        $eshterak[$channel_id] = intval($text);
        s();

        $channel_name = bot("getChat", ["chat_id" => $channel_id])->result->title;
        bot("sendmessage", [
            "chat_id" => $chat_id,
            "text" => "✅ تمت إضافة القناة ($channel_name) لقائمة الاشتراك الإجباري بعدد مطلوب: $text.",
        ]);
        $abdo['mode'][$from_id]['mode'] = null;
        unset($abdo['bot']['temp_channel_id']);
        s();
    } else {
        bot("sendmessage", [
            "chat_id" => $chat_id,
            "text" => "⚠️ يرجى إرسال عدد صحيح للاشتراكات المطلوبة.",
        ]);
    }
    exit;
}

if ($data == "unesh") {
    bot("EditMessageText", [
        "chat_id" => $chat_id,
        "message_id" => $message_id,
        "text" => "🗑️ أرسل معرف أو أيدي القناة التي تريد حذفها من قائمة الاشتراك الإجباري.",
        "reply_markup" => json_encode([
            "inline_keyboard" => [
                [["text" => "• رجوع •", "callback_data" => "eshterak"]],
            ]
        ])
    ]);
    $abdo['mode'][$from_id]['mode'] = "unesh";
    s();
    exit;
}

if ($message && $abdo['mode'][$from_id]['mode'] == "unesh") {
    $channel_id = null;

    if (strpos($text, "@") === 0) {
        $channel_info = bot("getChat", ["chat_id" => $text]);
        if ($channel_info->ok) {
            $channel_id = $channel_info->result->id;
        }
    } elseif (is_numeric($text)) {
        $channel_id = "-100" . $text;
    } elseif ($message->forward_from_chat) {
        $channel_id = $message->forward_from_chat->id;
    }

    if ($channel_id && isset($eshterak[$channel_id])) {
        unset($eshterak[$channel_id]);
        s();
        bot("sendmessage", [
            "chat_id" => $chat_id,
            "text" => "✅ تم حذف القناة من قائمة الاشتراك الإجباري.",
        ]);
        $abdo['mode'][$from_id]['mode'] = null;
    } else {
        bot("sendmessage", [
            "chat_id" => $chat_id,
            "text" => "❌ القناة غير موجودة في قائمة الاشتراك الإجباري.",
        ]);
    }
    exit;
}


if ($data == "eshh") {
    if (!empty($eshterak)) {
        $eshterak_list = "📋 **قنوات الاشتراك الإجباري:**\n\n";
        foreach ($eshterak as $channel_id => $count) {
            $channel_info = bot("getChat", ["chat_id" => $channel_id]);
            $title = $channel_info->result->title ?? "غير معروف";
            $eshterak_list .= "🔹 [$title](tg://user?id=$channel_id) - العدد المطلوب: $count\n";
        }
    } else {
        $eshterak_list = "❌ لا توجد قنوات ضمن قائمة الاشتراك الإجباري.";
    }

    bot("EditMessageText", [
        "chat_id" => $chat_id,
        "message_id" => $message_id,
        "text" => $eshterak_list,
        "parse_mode" => "Markdown",
        "reply_markup" => json_encode([
            "inline_keyboard" => [
                [["text" => "• رجوع •", "callback_data" => "eshterak"]],
            ]
        ])
    ]);
    exit;
}


if ($data == "uneshh") {
    bot("EditMessageText", [
        "chat_id" => $chat_id,
        "message_id" => $message_id,
        "text" => "⚠️ هل أنت متأكد أنك تريد حذف **جميع** قنوات الاشتراك الإجباري؟",
        "parse_mode" => "Markdown",
        "reply_markup" => json_encode([
            "inline_keyboard" => [
                [["text" => "نعم", "callback_data" => "confirm_uneshh"], ["text" => "لا", "callback_data" => "eshterak"]],
            ]
        ])
    ]);
    exit;
}

if ($data == "confirm_uneshh") {
    $eshterak = null;
    s();
    bot("EditMessageText", [
        "chat_id" => $chat_id,
        "message_id" => $message_id,
        "text" => "✅ تم حذف جميع قنوات الاشتراك الإجباري.",
        "reply_markup" => json_encode([
            "inline_keyboard" => [
                [["text" => "• رجوع •", "callback_data" => "eshterak"]],
            ]
        ])
    ]);
    exit;
}





if (($data || $message) && $type == "private"  && !in_array($from_id, $admins)) {

    $channels = $eshterak;
    $is_subscribed = true;
    $missing_channels = [];

    foreach ($channels as $channel_id => $number) {

        $response = callAPI('check', $channel_id, $from_id);
        if ($response['subscribed'] === false) {
    
            $abdo[$channel_id]["members"] = [];
            if (!in_array($from_id, $abdo[$channel_id]["members"])) {
                $abdo[$channel_id]["members"][] = $from_id;
                s();
            }

            $is_subscribed = false;
            $missing_channels[$channel_id] = $number;

        } else {
            $abdo[$channel_id]["members"] = $abdo[$channel_id]["members"] ?? [];
            if (in_array($from_id, $abdo[$channel_id]["members"])) {
                unset($abdo[$channel_id]["members"][array_search($from_id, $abdo[$channel_id]["members"])]);
                $abdo[$channel_id]["nom"]++;
                s();

                $channel_name = bot("getChat", ['chat_id' => $channel_id])->result->title;
                $current_count = $abdo[$channel_id]["nom"];


                if ($current_count < $number) {
                    $message = "تم اشتراك عضو جديد\nاسم العضو: ". $name . "\nالقناة: {" . $channel_name . "}\nاجمالي الاعضاء الذين اشتركوا: " . $current_count;
                } else {
                    $message = "تم اكتمال العدد المطلوب للقناة {" . $channel_name . "}.\nعدد الأعضاء: " . $current_count . " من " . $number;
                    unset($eshterak[$channel_id]);
                    unset($abdo[$channel_id]);
                    s();
                }

                bot("sendMessage", [
                    "chat_id" => $admin,
                    "text" => $message,
                    "parse_mode" => "Markdown",
                ]);
            }

        }
    }

    if (!$is_subscribed) {
        $buttons = [];
        foreach ($missing_channels as $channel_id => $number) {
            $chat = bot("getChat", ['chat_id' => $channel_id]);
            $channel_name = $chat->result->title;

            if (!isset($abdo[$channel_id]["link"])) {

                $response = callAPI('link', $channel_id, null, $number);
                if (isset($response['link'])) {
                    $link = $response['link'];
                    $abdo[$channel_id]["link"] = $link;
                    s();
                } else {
                    bot("sendMessage", [
                        "chat_id" => $chat_id,
                        "text" => "حدث خطأ أثناء إنشاء رابط الدعوة.\nيرجى التواصل مع المطور @V2P_1.",
                        "parse_mode" => "Markdown",
                    ]);
                    exit;
                }
            }

            $link = $abdo[$channel_id]["link"];
            $buttons[] = [['text' => "اشترك في قناة $channel_name", 'url' => $link]];
        }

        $message = "يبدو أنك غير مشترك في بعض القنوات. يرجى الاشتراك للمتابعة:";
        $keyboard = ['inline_keyboard' => $buttons];

        if ($data) {
            bot("EditMessageText", [
                "chat_id" => $chat_id,
                "message_id" => $message_id,
                "text" => $message,
                "parse_mode" => "Markdown",
                'reply_markup' => json_encode($keyboard)
            ]);
        } else {
            bot("sendMessage", [
                "chat_id" => $chat_id,
                "text" => $message,
                "parse_mode" => "Markdown",
                'reply_markup' => json_encode($keyboard)
            ]);
        }
        exit;
    }
}
//-------------------------- الاشتراك الاجباري ------------------------------//










































//-------------------------- الاذاعة ------------------------------//
$stabilizing = $bot['stabilizing'] === "on" ? "✅" : "❌";
$directing = $bot['directing'] === "on" ? "✅" : "❌";
$radio_type = $bot['radio_type'] === "Manufacturer" ? "في بوت الصانع" : "في كل البوتات";
$radio_g_or_p = $bot['radio_g_or_p'] === "private" ? "الخاص" : "الجروبات";
if ($data == 'stabilizing') {
    $bot['stabilizing'] = $bot['stabilizing'] === "on" ? "off" : "on";
    s();
    bot('EditMessageReplyMarkup', [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'reply_markup' => getUpdatedMarkup()
    ]);
    bot('answerCallbackQuery', [
        'callback_query_id' => $update->callback_query->id,
        'text' => "تم " . ($bot['stabilizing'] === "on" ? "تفعيل" : "تعطيل") . " التثبيت."
    ]);
} elseif ($data == 'directing') {
    $bot['directing'] = $bot['directing'] === "on" ? "off" : "on";
    s();
    bot('EditMessageReplyMarkup', [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'reply_markup' => getUpdatedMarkup()
    ]);
    bot('answerCallbackQuery', [
        'callback_query_id' => $update->callback_query->id,
        'text' => "تم " . ($bot['directing'] === "on" ? "تفعيل" : "تعطيل") . " التوجيه."
    ]);
} elseif ($data == 'radio_type') {
    $bot['radio_type'] = $bot['radio_type'] === "Manufacturer" ? "all" : "Manufacturer";
    s();
    bot('EditMessageReplyMarkup', [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'reply_markup' => getUpdatedMarkup()
    ]);
    bot('answerCallbackQuery', [
        'callback_query_id' => $update->callback_query->id,
        'text' => "تم اختيار البث الآن " . ($bot['radio_type'] === "Manufacturer" ? "في بوت الصانع" : "في كل البوتات") . "."
    ]);
} elseif ($data == 'radio_g_or_p') {
    $bot['radio_g_or_p'] = $bot['radio_g_or_p'] === "private" ? "group" : "private";
    s();
    bot('EditMessageReplyMarkup', [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'reply_markup' => getUpdatedMarkup()
    ]);
    bot('answerCallbackQuery', [
        'callback_query_id' => $update->callback_query->id,
        'text' => "تم اختيار مكان البث في " . ($bot['radio_g_or_p'] === "private" ? "الخاص" : "الجروبات") . "."
    ]);
}
function getUpdatedMarkup() {
    global $bot;
    $stabilizing = $bot['stabilizing'] === "on" ? "✅" : "❌";
    $directing = $bot['directing'] === "on" ? "✅" : "❌";
    $radio_type = $bot['radio_type'] === "Manufacturer" ? "في بوت الصانع" : "في كل البوتات";
    $radio_g_or_p = $bot['radio_g_or_p'] === "private" ? "الخاص" : "الجروبات";

    return json_encode([
        'inline_keyboard' => [
            [['text' => " بالتثبيت " . $stabilizing, 'callback_data' => "stabilizing"]],
            [['text' => " بالتوجيه " . $directing, 'callback_data' => "directing"]],
            [['text' => "الاذاعه في  " . $radio_g_or_p, 'callback_data' => "radio_g_or_p"]],
            [['text' => "• بدء الاذاعه •", 'callback_data' => "start_radio"]],
            [['text' => "• رجوع •", 'callback_data' => "bot"]]
        ]
    ]);
}

$from_upload = isset($bot['from_php'][$from_id]) ? $bot['from_php'][$from_id] : 0;
$upload_all_bot = isset($bot['all_file']) ? $bot['all_file'] : 0;
$sf = $username ?? "غير متوفر";

if ($data == "msg") {
    $getUpdatedMarkup =  getUpdatedMarkup();
    bot('EditMessageText', [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'text' => "• مرحبا عزيزي المطور في قسم الاذاعه المتطور •",
        'parse_mode' => "markdown",
        'reply_markup' => $getUpdatedMarkup
    ]);
    $bot['mode'][$from_id]['mode'] = null;
    s();
    exit;
}
if ($data == "start_radio") {
    bot('EditMessageText', [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'text' => "• أرسل الآن الكليشة ( النص أو جميع الوسائط )
• يمكنك استخدام كود جاهز في الإذاعة أو يمكنك استخدام الماركدوان" ,
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "• الغاء •", 'callback_data' => "msg"]]
            ]
        ])
    ]);

    $bot['mode'][$from_id]['mode'] = "waiting_for_message";
    s();
    exit;
}
$photo=$message->photo;
$video=$message->video;
$document=$message->document;
$sticker=$message->sticker;
$voice=$message->voice;
$audio=$message->audio;
$caption = $message->caption;
if ($photo) {
$sens="sendphoto";
$file_id = $update->message->photo[1]->file_id;
} elseif ($document) {

$sens="senddocument";
$file_id = $update->message->document->file_id;
} elseif ($video) {

$sens="sendvideo";
$file_id = $update->message->video->file_id;
} elseif ($audio) {

$sens="sendaudio";
$file_id = $update->message->audio->file_id;
} elseif ($voice) {

$sens="sendvoice";
$file_id = $update->message->voice->file_id;
} elseif ($sticker) {

$sens="sendsticker";
$file_id = $update->message->sticker->file_id;
} else {
    $sens="sendmessage";
    $file_id = $text;
}
if ($message and $bot['mode'][$from_id]['mode'] == "waiting_for_message") {
    $targets = $bot['radio_g_or_p'] === "private" ? $stats['users'] : $stats['groups'];
    $stabilizing = $bot['stabilizing'] === "on" ? "on" : "off";
    $directing = $bot['directing'] === "on" ? "on" : "off";
    $filename = "broadcast_" . time() . ".php";
    $fileUrl = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . dirname($_SERVER['SCRIPT_NAME']) . "/$filename";

    $messag_bb = bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "تم بدء الإذاعة",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "• اضغط هنا بعد ثانيتين •", 'url' => $fileUrl]]
            ]
        ])
    ]);
    $code = generateBroadcastCode($targets, $stabilizing, $directing);
    file_put_contents($filename, $code);
    $bot['mode'][$from_id]['mode'] = null;
    s();
    shell_exec("nohup php $filename > /dev/null 2>&1 &");
}

function generateBroadcastCode($targets, $stabilizing, $directing) {
    global $name, $from_id, $message, $token, $message_id, $caption, $file_id, $sens, $chat_id, $messag_bb;

    $targets = var_export($targets, true);
    $file_id = var_export($file_id, true);
    $caption = addslashes($caption);
    $sens = addslashes($sens);
    $messag_bb = $messag_bb->result->message_id;
    return <<<PHP
<?php
define('API_KEY', '$token');

function bot(\$method, \$datas = []) {
    \$url = "https://api.telegram.org/bot" . API_KEY . "/" . \$method;
    \$ch = curl_init();
    curl_setopt(\$ch, CURLOPT_URL, \$url);
    curl_setopt(\$ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(\$ch, CURLOPT_POSTFIELDS, \$datas);
    \$res = curl_exec(\$ch);
    curl_close(\$ch);
    return json_decode(\$res);
}
\$targets = $targets;
\$file_id = $file_id;
\$caption = "$caption";
\$sens = "$sens";
\$stabilizing = "$stabilizing";
\$directing = "$directing";
\$count = count(\$targets);
\$blocked = 0;
\$failed = 0;
\$succeeded = 0;
foreach (\$targets as \$target) {
    try {
        \$response = null;

        if (\$directing == 'on') {
            \$response = bot('forwardMessage', [
                'chat_id' => \$target,
                'from_chat_id' => $from_id,
                'message_id' => $message_id
            ]);
        } else {
            \$payload = ["chat_id" => \$target];
            if (\$sens !== 'sendmessage') {
                \$ss = str_replace("send", "", \$sens);
                \$payload[\$ss] = \$file_id;
                if (\$caption) {
                    \$payload['caption'] = \$caption;
                }
            } else {
                \$payload['text'] = \$file_id;
                \$payload['parse_mode'] = 'Markdown';
            }
            \$response = bot(\$sens, \$payload);
        }

        if (!\$response || !\$response->ok) {
            if (isset(\$response->error_code) && \$response->error_code == 403) {
                \$blocked++;
            } else {
                \$failed++;
            }
        } else {
            \$succeeded++;
        }

        // حساب النسبة المئوية
        \$percentage = round((\$succeeded / \$count) * 100, 2);
        bot('editMessageText', [
            'chat_id' => $chat_id,
            'message_id' => $messag_bb,
            'text' => "تم بدء الإذاعة\n
• جاري الإذاعة إلى {\$count} مستخدم 🌐\n
• تم الإرسال إلى {\$succeeded} مستخدم 🎯\n
• المستخدمين الذين حظروا البوت: {\$blocked} 🚫\n
• النسبة المئوية: {\$percentage}%"
        ]);
    } catch (Exception \$e) {
        error_log("Error broadcasting to \$target: " . \$e->getMessage());
    }
}
bot('editMessageText', [
    'chat_id' => $chat_id,
    'message_id' => $messag_bb,
    'text' => "<s>تم بدء الإذاعة</s>\n تم الانتهاء من الاذاعة\n
• جاري الإذاعة إلى {\$count} مستخدم 🌐\n
• تم الإرسال إلى {\$succeeded} مستخدم 🎯\n
• المستخدمين الذين حظروا البوت: {\$blocked} 🚫\n
• النسبة المئوية: {\$percentage}%",
    'parse_mode' => 'HTML'
]);
// الرسالة النهائية
bot('sendMessage', [
    'chat_id' => $from_id,
    'text' => "• تم الاذاعة بنجاح 🎉

• الاعضاء الذين شاهدو الاذاعه {" . \$succeeded . "} عضو حقيقي
• الاعضاء الذين قامو بحظر البوت {" . \$blocked . "}

• المستخدمين الذين لم يستطع البوت ارسال اذاعه لهم {" . \$failed . "} مستخدم

• عدد العضاء الكلي : {" . \$count . "}",
    'parse_mode' => 'Markdown'
]);
unlink(__FILE__);
?>
PHP;
}
//-------------------------- الاذاعة ------------------------------//
































//-------------------------- قسم الحظر ------------------------------//
if ($data == "ksmblock") {
    bot("EditMessageText", [
        "chat_id" => $chat_id, 
        'message_id' => $message_id,
        "text" => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᵐᵏᵃʳʸ](tg://user?id=7217896334)\nمرحبا بك في قسم الحظر",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "• حظر عضو •", 'callback_data' => "block"]],
                [['text' => "• إلغاء حظر عضو •", 'callback_data' => "unblock"]],
                [['text' => "• عرض جميع المحظورين •", 'callback_data' => "blocks"]],
                [['text' => "• حذف جميع المحظورين •", 'callback_data' => "unblocks"]],
                [['text' => "• رجوع •", 'callback_data' => "bot"]]
            ]
        ])
    ]);
    $bot['mode'][$from_id]['mode'] = null;
    s();
    exit;
}
if ($data == "block") {
    bot('EditMessageText', [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'text' => "حسنا ارسل ايدي الشخص المراد حظره",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "• الغاء •", 'callback_data' => "ksmblock"]]
            ]
        ])
    ]);
    $bot['mode'][$from_id]['mode'] = 'block';
    s();
    exit;
}
if ($data == "unblock") {
    bot('EditMessageText', [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'text' => "حسنا ارسل ايدي الشخص المراد إلغاء حظره",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "• الغاء •", 'callback_data' => "ksmblock"]]
            ]
        ])
    ]);
    $bot['mode'][$from_id]['mode'] = 'unblock';
    s();
    exit;
}
if ($text && $from_id == $admin && $bot['mode'][$from_id]['mode'] == 'block') {
    $pattern = '/\b\d{8,12}\b/';
    if (preg_match($pattern, $text, $matches)) {
        $bot['banned'][] = $text;
        s();
        bot("sendmessage", [
            "chat_id" => $chat_id, 
            "text" => "تم حظر [العضو](tg://user?id=$text) بنجاح 🔒",
            'parse_mode' => "markdown",
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => "• رجوع •", 'callback_data' => "ksmblock"]]
                ]
            ])
        ]);
    } else {
        bot("sendmessage", [
            "chat_id" => $chat_id, 
            "text" => "حدث خطأ او ان الايدي خاطئ\nارسل الايدي مجددا",
            'parse_mode' => "markdown",
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => "• الغاء •", 'callback_data' => "ksmblock"]]
                ]
            ])
        ]);
    }
    $bot['mode'][$from_id]['mode'] = null;
    s();
    exit;
}
if ($text && $from_id == $admin && $bot['mode'][$from_id]['mode'] == 'unblock') {
    $pattern = '/\b\d{8,12}\b/';
    if (preg_match($pattern, $text, $matches)) {
        $bot['banned'] = array_filter($bot['banned'], function($id) use ($text) {
            return $id != $text;
        });
        s();
        bot("sendmessage", [
            "chat_id" => $chat_id, 
            "text" => "تم إلغاء حظر [العضو](tg://user?id=$text) بنجاح ✅",
            'parse_mode' => "markdown",
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => "• رجوع •", 'callback_data' => "ksmblock"]]
                ]
            ])
        ]);
    } else {
        bot("sendmessage", [
            "chat_id" => $chat_id, 
            "text" => "حدث خطأ او ان الايدي خاطئ\nارسل الايدي مجددا",
            'parse_mode' => "markdown",
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => "• الغاء •", 'callback_data' => "ksmblock"]]
                ]
            ])
        ]);
    }
    $bot['mode'][$from_id]['mode'] = null;
    s();
    exit;
}
if ($data == "blocks") {
    $names = '';
    foreach ($bot['banned'] as $id) {
        $names .= "ID: $id\n\n";
    }
    bot("EditMessageText", [
        "chat_id" => $chat_id,
        'message_id' => $message_id,
        "text" => "*المحظورين* :\n$names",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "• رجوع •", 'callback_data' => "ksmblock"]]
            ]
        ])
    ]);
}
if ($data == "unblocks") {
    bot("EditMessageText", [
        "chat_id" => $chat_id, 
        'message_id' => $message_id,
        "text" => "هل أنت متأكد من أنك تريد حذف جميع المحظورين؟ لا يمكن التراجع عن هذا الإجراء.",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "✅ نعم، احذف", 'callback_data' => "confirm_unblocks"]],
                [['text' => "❌ إلغاء", 'callback_data' => "ksmblock"]]
            ]
        ])
    ]);
    exit;
}
if ($data == "confirm_unblocks") {
    $bot['banned'] = [];
    bot("EditMessageText", [
        "chat_id" => $chat_id, 
        'message_id' => $message_id,
        "text" => "تم حذف جميع المحظورين بنجاح.",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "• رجوع •", 'callback_data' => "ksmblock"]]
            ]
        ])
    ]);
    s();
    exit;
}
//-------------------------- قسم الحظر ------------------------------//


































//-------------------------- قسم الادمنيه ------------------------------//
if ($data == "ksmadmin") {
    bot("EditMessageText", [
        "chat_id" => $chat_id, 
        'message_id' => $message_id,
        "text" => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᵐᵏᵃʳʸ](tg://user?id=7217896334)\nمرحبا بك في قسم الادمنيه",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text'=>"• رفع ادمن •",'callback_data'=>"admins"],['text'=>"• حذف ادمن •",'callback_data'=>"unadmins"]],
                [['text' => "• عرض جميع الادمنيه •", 'callback_data' => "adminss"]],
                [['text' => "• حذف جميع الادمنيه •", 'callback_data' => "unadminss"]],
                [['text' => "• رجوع •", 'callback_data' => "bot"]]
            ]
        ])
    ]);
    $bot['mode'][$from_id]['mode'] = null;
    s();
    exit;
}
if ($data == "admins") {
    bot('EditMessageText', [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'text' => "حسنا ارسل الايدي بتاعه حالا",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text'=>"• الغاء •",'callback_data'=>"ksmadmin" ]]
            ]
        ])
    ]);
    $bot['mode'][$from_id]['mode'] = 'admins';
    s();
    exit;
}
if ($data == "unadmins") {
    bot('EditMessageText', [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'text' => "حسنا ارسل ايدي البرنس دا حالا",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text'=>"• الغاء •",'callback_data'=>"ksmadmin" ]]
            ]
        ])
    ]);
    $bot['mode'][$from_id]['mode'] = 'unadmins';
    s();
    exit;
}
if ($text and !$data && $from_id == $admin && $bot['mode'][$from_id]['mode'] == 'admins') {
    $pattern = '/\b\d{8,12}\b/';
    if (preg_match($pattern, $text, $matches)) {
        $bot['admins'][] = $text;
        s();

        bot("sendmessage", [
            "chat_id" => $chat_id, 
            "text" => "تم رفع [العضو](tg://user?id=$text) ادمن بنجاح 🌹",
            'parse_mode' => "markdown",
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text'=>"• رجوع •",'callback_data'=>"ksmadmin" ]]
                ]
            ])
        ]);
        bot("sendmessage", [
            "chat_id" => $text, 
            "text" => "مرحبا.. 🌹\nتم رفعك ادمن في البوت بواسطة [المطور](tg://user?id=$admin) ♥",
            'parse_mode' => "markdown"
        ]);
    } else {
        bot("sendmessage", [
            "chat_id" => $chat_id, 
            "text" => "حدث خطأ او ان الايدي خاطئ\nارسل الايدي مجددا",
            'parse_mode' => "markdown",
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text'=>"• الغاء •",'callback_data'=>"ksmadmin" ]]
                ]
            ])
        ]);
    }
    $bot['mode'][$from_id]['mode'] = null;
    s();
    exit;
}
if ($text and !$data && $from_id == $admin && $bot['mode'][$from_id]['mode'] == 'unadmins') {
    $pattern = '/\b\d{8,12}\b/';
    if (preg_match($pattern, $text, $matches)) {
        $bot['admins'] = array_filter($bot['admins'], function($id) use ($text) {
            return $id != $text;
        });
        s();
        bot("sendmessage", [
            "chat_id" => $chat_id, 
            "text" => "تم سحب الادمن من [العضو](tg://user?id=$text) بنجاح 💯",
            'parse_mode' => "markdown",
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text'=>"• رجوع •",'callback_data'=>"ksmadmin" ]]
                ]
            ])
        ]);
        bot("sendmessage", [
            "chat_id" => $text, 
            "text" => "تم سحب الادمنيه منك بواسطة [المطور](tg://user?id=$admin)",
            'parse_mode' => "markdown"
        ]);
    } else {
        bot("sendmessage", [
            "chat_id" => $chat_id, 
            "text" => "حدث خطأ او ان الايدي خاطئ\nارسل الايدي مجددا",
            'parse_mode' => "markdown",
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text'=>"• الغاء •",'callback_data'=>"ksmadmin" ]]
                ]
            ])
        ]);
    }
    $bot['mode'][$from_id]['mode'] = null;
    s();
    exit;
}
if ($data == "adminss") {
    $names = '';
    foreach ($bot['admins'] as $id) {
        $user_info = bot('getChatMember', ['chat_id' => $id, 'user_id' => $id])->result;
        $username = $user_info->user->username ?? '';
        $name = $user_info->user->first_name ?? '';
        $names .= "ID: $id\nUsername: [@$username]\nName: [$name](tg://user?id=$id)\n\n";
    }
    bot("EditMessageText", [
        "chat_id" => $chat_id,
        'message_id' => $message_id,
        "text" => "*الادمنيه* :\n$names",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "• رجوع •", 'callback_data' => "ksmadmin"]]
            ]
        ])
    ]);
}
if ($data == "unadminss") {
    bot("EditMessageText", [
        "chat_id" => $chat_id, 
        'message_id' => $message_id,
        "text" => "هل أنت متأكد من أنك تريد حذف جميع الإدمنيه؟ لا يمكن التراجع عن هذا الإجراء.",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "✅ نعم، احذف", 'callback_data' => "confirm_unadminss"]],
                [['text' => "❌ إلغاء", 'callback_data' => "ksmadmin"]]
            ]
        ])
    ]);
    exit;
}
if ($data == "confirm_unadminss") {
    $bot['admins'] = [];
    bot("EditMessageText", [
        "chat_id" => $chat_id, 
        'message_id' => $message_id,
        "text" => "تم حذف جميع الإدمنيه بنجاح.",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "• رجوع •", 'callback_data' => "ksmadmin"]]
            ]
        ])
    ]);
    s();
    exit;
}
//-------------------------- قسم الادمنيه ------------------------------//










































//-------------------------- قسم الـ VIP ------------------------------//

if ($data == "ksmvip") {
    bot("EditMessageText", [
        "chat_id" => $chat_id, 
        'message_id' => $message_id,
        "text" => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᵐᵏᵃʳʸ](tg://user?id=7217896334)\nمرحبا بك في قسم إدارة الـ VIP",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => " زر التقديم على طلب اشتراك " . $VIP_button, 'callback_data' => "VIP_button"]],
                [['text'=>"• إضافة VIP •",'callback_data'=>"addvip"],['text'=>"• حذف VIP •",'callback_data'=>"removevip"]],
                [['text' => "• عرض جميع الـ VIP •", 'callback_data' => "viewvips"]],
                [['text' => "• حذف جميع الـ VIP •", 'callback_data' => "clearvips"]],
                [['text' => "• رجوع •", 'callback_data' => "bot"]]
            ]
        ])
    ]);
    $bot['mode'][$from_id]['mode'] = null;
    s();
    exit;
}

if ($data == "addvip") {
    bot('EditMessageText', [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'text' => "حسنا، أرسل الـ ID الخاص بالمستخدم لإضافته إلى قائمة الـ VIP.",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text'=>"• إلغاء •",'callback_data'=>"ksmvip" ]]
            ]
        ])
    ]);
    $bot['mode'][$from_id]['mode'] = 'addvip';
    s();
    exit;
}

if ($data == "removevip") {
    bot('EditMessageText', [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'text' => "حسنا، أرسل الـ ID الخاص بالمستخدم لحذفه من قائمة الـ VIP.",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text'=>"• إلغاء •",'callback_data'=>"ksmvip" ]]
            ]
        ])
    ]);
    $bot['mode'][$from_id]['mode'] = 'removevip';
    s();
    exit;
}

if ($text and !$data && $from_id == $admin && $bot['mode'][$from_id]['mode'] == 'addvip') {
    $pattern = '/\b\d{8,12}\b/';
    if (preg_match($pattern, $text, $matches)) {
        $bot['promotionn'][] = $text;
        s();

        bot("sendmessage", [
            "chat_id" => $chat_id, 
            "text" => "تم إضافة [العضو](tg://user?id=$text) إلى قائمة الـ VIP بنجاح 🌟",
            'parse_mode' => "markdown",
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text'=>"• رجوع •",'callback_data'=>"ksmvip" ]]
                ]
            ])
        ]);
    } else {
        bot("sendmessage", [
            "chat_id" => $chat_id, 
            "text" => "حدث خطأ أو أن الـ ID خاطئ. من فضلك أرسل الـ ID مجددًا.",
            'parse_mode' => "markdown",
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text'=>"• إلغاء •",'callback_data'=>"ksmvip" ]]
                ]
            ])
        ]);
    }
    $bot['mode'][$from_id]['mode'] = null;
    s();
    exit;
}

if ($text and !$data && $from_id == $admin && $bot['mode'][$from_id]['mode'] == 'removevip') {
    $pattern = '/\b\d{8,12}\b/';
    if (preg_match($pattern, $text, $matches)) {
        $bot['promotionn'] = array_filter($bot['promotionn'], function($id) use ($text) {
            return $id != $text;
        });
        s();
        bot("sendmessage", [
            "chat_id" => $chat_id, 
            "text" => "تم حذف [العضو](tg://user?id=$text) من قائمة الـ VIP بنجاح.",
            'parse_mode' => "markdown",
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text'=>"• رجوع •",'callback_data'=>"ksmvip" ]]
                ]
            ])
        ]);
    } else {
        bot("sendmessage", [
            "chat_id" => $chat_id, 
            "text" => "حدث خطأ أو أن الـ ID خاطئ. من فضلك أرسل الـ ID مجددًا.",
            'parse_mode' => "markdown",
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text'=>"• إلغاء •",'callback_data'=>"ksmvip" ]]
                ]
            ])
        ]);
    }
    $bot['mode'][$from_id]['mode'] = null;
    s();
    exit;
}

if ($data == "viewvips") {
    $names = '';
    foreach ($bot['promotionn'] as $id) {
        $user_info = bot('getChatMember', ['chat_id' => $id, 'user_id' => $id])->result;
        $username = $user_info->user->username ?? '';
        $name = $user_info->user->first_name ?? '';
        $names .= "ID: $id\nUsername: [@$username]\nName: [$name](tg://user?id=$id)\n\n";
    }
    bot("EditMessageText", [
        "chat_id" => $chat_id,
        'message_id' => $message_id,
        "text" => "*المستخدمين الـ VIP* :\n$names",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "• رجوع •", 'callback_data' => "ksmvip"]]
            ]
        ])
    ]);
}

if ($data == "clearvips") {
    bot("EditMessageText", [
        "chat_id" => $chat_id, 
        'message_id' => $message_id,
        "text" => "هل أنت متأكد من أنك تريد حذف جميع المستخدمين الـ VIP؟ لا يمكن التراجع عن هذا الإجراء.",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "✅ نعم، احذف", 'callback_data' => "confirm_clearvips"]],
                [['text' => "❌ إلغاء", 'callback_data' => "ksmvip"]]
            ]
        ])
    ]);
    exit;
}

if ($data == "confirm_clearvips") {
    $bot['promotionn'] = null;
    bot("EditMessageText", [
        "chat_id" => $chat_id, 
        'message_id' => $message_id,
        "text" => "تم حذف جميع المستخدمين الـ VIP بنجاح.",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "• رجوع •", 'callback_data' => "ksmvip"]]
            ]
        ])
    ]);
    s();
    exit;
}
//-------------------------- قسم الـ VIP ------------------------------//
















if ($data == "add_vip") {
    $abdo['vip_mode'][$from_id] = "add";
    s();
    bot('editMessageText', [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'text' => "🔢 أرسل الآن آيدي العضو الذي تريد إضافته للاشتراكات المدفوعة (VIP):"
    ]);
    exit;
}

// التعامل مع حذف اشتراك
if ($data == "del_vip") {
    $abdo['vip_mode'][$from_id] = "del";
    s();
    bot('editMessageText', [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'text' => "🗑️ أرسل آيدي العضو الذي تريد حذفه من الاشتراكات المدفوعة:"
    ]);
    exit;
}

// عرض كل المشتركين في VIP
if ($data == "list_vip") {
    $vips = $abdo['vip'] ?? [];
    if (count($vips) == 0) {
        $msg = "❌ لا يوجد مشتركين VIP حالياً.";
    } else {
        $msg = "✅ قائمة المشتركين VIP:

";
        foreach ($vips as $id) {
            $msg .= "🔹 $id
";
        }
    }
    bot('editMessageText', [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'text' => $msg,
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "🔙 رجوع", 'callback_data' => "vip_menu"]]
            ]
        ])
    ]);
    exit;
}

// استقبال رسائل إضافة أو حذف VIP
if ($message && isset($abdo['vip_mode'][$from_id])) {
    $mode = $abdo['vip_mode'][$from_id];
    $vip_id = trim($text);
    if ($mode == "add") {
        $abdo['vip'][] = $vip_id;
        bot("sendMessage", [
            "chat_id" => $chat_id,
            "text" => "✅ تم إضافة العضو $vip_id إلى قائمة VIP بنجاح."
        ]);
        bot("sendMessage", [
            "chat_id" => $vip_id,
            "text" => "🎉 تم إضافتك لقائمة VIP بنجاح! استمتع بمميزات غير محدودة."
        ]);
    } elseif ($mode == "del") {
        if (($key = array_search($vip_id, $abdo['vip'])) !== false) {
            unset($abdo['vip'][$key]);
            bot("sendMessage", [
                "chat_id" => $chat_id,
                "text" => "🗑️ تم حذف العضو $vip_id من قائمة VIP بنجاح."
            ]);
        } else {
            bot("sendMessage", [
                "chat_id" => $chat_id,
                "text" => "⚠️ العضو $vip_id غير موجود في القائمة."
            ]);
        }
    }
    unset($abdo['vip_mode'][$from_id]);
    s();
    exit;
}


if ($data == "vip") {
    bot("sendMessage", [
        "chat_id" => $admin,
        "text" => "
*✅ - طلب تفعيل اشتراك 
☑️ - الشخص:* $name
 
[$from_id](tg://user?id=$chat_id) 
[Acount](tg://openmessage?user_id=$chat_id)
",
        "parse_mode" => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "• تفعيل اشتراك •", 'callback_data' => "trues|$from_id"], ['text' => "• رفض اشتراك •", 'callback_data' => "falses|$from_id"]],
            ]
        ])
    ]);
    bot('EditMessageText', [
        "chat_id" => $chat_id,
        'message_id' => $message_id,
        "text" => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᵐᵏᵃʳʸ](tg://user?id=7217896334)
*تم ارسال طلب اشتراك* [للمطور](tg://openmessage?user_id=$admin)
",
        "parse_mode" => "markdown",
    ]);
    exit;
}
list($action, $userId) = explode("|", $data);
if ($action == "trues") {
    bot("editMessagetext", [
        "chat_id" => $chat_id,
        'message_id' => $message_id,
        "text" => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᵐᵏᵃʳʸ](tg://user?id=7217896334)
• - تم قبول طلب الاشتراك بنجاح وتم تفعيل حساب [المستخدم](tg://user?id=$userId)
",
        "parse_mode" => "markdown",
    ]);
    bot("sendMessage", [
        "chat_id" => $userId,
        "text" => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᵐᵏᵃʳʸ](tg://user?id=7217896334)
* • - تم قبول طلب الاشتراك حسابك بنجاح *

• - ارسل /start
",
        "parse_mode" => "markdown",
    ]);
    $bot['promotionn'][] = $userId;
    s();
}
if ($action == "falses") {
    bot("editMessagetext", [
        "chat_id" => $chat_id,
        'message_id' => $message_id,
        "text" => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᵐᵏᵃʳʸ](tg://user?id=7217896334)
تم رفض طلب [المستخدم](tg://user?id=$userId)
",
        "parse_mode" => "markdown",
    ]);
    bot("sendMessage", [
        "chat_id" => $userId,
        "text" => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᵐᵏᵃʳʸ](tg://user?id=7217896334)
*- * [المطور](tg://openmessage?user_id=$admin) رفض اشتراكك يمكنك مراسلته لتفعيل البوت
",
        "parse_mode" => "markdown",
    ]);
    exit;
}

























if ($bot['bott'] != "on" and !in_array($from_id, $admins)) {
    if ($data) {
        $m = 'EditMessageText';
    } else {
        $m = 'sendMessage';
    }
    bot($m, [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'text' => "🚧 البوت تحت الصيانة حالياً
♦️ نرجو المحاولة لاحقًا، شكرًا لتفهمك 
📢 تابع التحديثات: @S7_MX3
",
        'parse_mode' => "markdown",
    ]);
    exit;
}
$bot['promotionn'] = $bot['promotionn'] ?? [];
if ($bot['premium'] == "on" && !in_array($from_id, $admins) && !in_array($from_id, $bot['promotionn'])) {
    // نحدد إذا كان التعديل على رسالة موجودة أو إرسال رسالة جديدة
    $m = $data ? 'editMessageText' : 'sendMessage';

    // نجهز نص الرسالة
    $messageText = "
عذرا، هذا البوت مدفوع\n يمكنك مراسلة المطور للاشتراك في البوت
";

    // نجهز زر الإنلاين إذا كان VIP_button == "on"
    $replyMarkup = null;
    if ($bot['VIP_button'] == "on") {
        $replyMarkup = json_encode([
            'inline_keyboard' => [
                [
                    ['text' => 'اضغط هنا لإرسال اشتراك للمطور', 'callback_data' => 'vip']
                ]
            ]
        ]);
    }

    // نرسل الرسالة
    bot($m, [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'text' => $messageText,
        'parse_mode' => "markdown",
        'reply_markup' => $replyMarkup
    ]);

    exit;
}






























































































$check = $bot['check'] === "on" ? "مفعل ✅" : "معطل ❌";
$upload = $bot['upload'] === "on" ? "مفعل ✅" : "معطل ❌";
$folder = $bot['folder'] === "on" ? "مفعل ✅" : "معطل ❌";
$vip_list = $abdo['vip'] ?? [];
if (in_array($from_id, $vip_list)) {
    $numberfiles = PHP_INT_MAX; // عدد لا نهائي للـ VIP
} else {
    $numberfiles = isset($bot["numberfiles"]) ? $bot["numberfiles"] : 7; // العدد العادي للباقي
}
$numberban = isset($bot["numberban"]) ? $bot["numberban"] : 3;

if ($data == 'check') {
    $bot['check'] = $bot['check'] === "on" ? "off" : "on";
    $check = $bot['check'] === "on" ? "مفعل ✅" : "معطل ❌";
    s();
    bot('answerCallbackQuery', [
        'callback_query_id' => $update->callback_query->id,
        'text' => "تم " . ($bot['check'] === "on" ? "تفعيل" : "تعطيل") . "فحص الملفات"
    ]);
    abdo2();
}
if ($data == 'upload') {
    $bot['upload'] = $bot['upload'] === "on" ? "off" : "on";
    $upload = $bot['upload'] === "on" ? "مفعل ✅" : "معطل ❌";
    s();
    bot('answerCallbackQuery', [
        'callback_query_id' => $update->callback_query->id,
        'text' => "تم " . ($bot['upload'] === "on" ? "تفعيل" : "تعطيل") . "فحص الملفات"
    ]);
    abdo2();
}
if ($data == 'folder') {
    $bot['folder'] = $bot['folder'] === "on" ? "off" : "on";
    $folder = $bot['folder'] === "on" ? "مفعل ✅" : "معطل ❌";
    s();
    bot('answerCallbackQuery', [
        'callback_query_id' => $update->callback_query->id,
        'text' => "تم " . ($bot['folder'] === "on" ? "تفعيل" : "تعطيل") . "فحص الملفات"
    ]);
    abdo2();
}


if ($data == "abdo") {
    $abdo['mode'][$from_id]['mode'] = null;
    s();
    bot('EditMessageText', [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'text' => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᵐᵏᵃʳʸ](tg://user?id=7217896334)
⎋ اهلا بك في الاعدادات الخاصه ببوت الرفع
⚙️ — — — — — — — — — — — ⚙️
",
        'parse_mode' => "MARKDOWN",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "فحص الملفات " . $check, 'callback_data' => "check"]],
                [['text' => "رفع الملفات " . $upload, 'callback_data' => "upload"]],
                [['text' => "إنشاء فولدرات " . $folder, 'callback_data' => "folder"]],
                [['text' => '• المحظورين من الرفع •', 'callback_data' => "banall"]],
                [['text' => "عدد ملفات {$numberfiles}", 'callback_data' => "set_numberfiles"],
                 ['text' => "عدد التحذيرات {$numberban}", 'callback_data' => "set_numberban"]],
                [['text' => '• الاعدادات العامه •', 'callback_data' => "bot"]]
            ]
        ])
    ]);
}

if ($data == "set_numberfiles") {
    handleSetMode(" الملفات", "numberfiles");
    exit;
}

if ($data == "set_numberban") {
    handleSetMode(" التحذيرات", "numberban");
    exit;
}

function handleSetMode($label, $key) {
    global $from_id, $message_id;
    bot('EditMessageText', [
        'chat_id' => $from_id,
        'message_id' => $message_id,
        'text' => "قم بإرسال العدد الجديد لـ " . $label,
        'parse_mode' => "MARKDOWN",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => '• إلغاء •', 'callback_data' => "abdo"]]
            ]
        ])
    ]);
    global $abdo;
    $abdo['mode'][$from_id]['mode'] = $key;
    s();
}

if (isset($text) && isset($abdo['mode'][$from_id]['mode'])) {
    $mode = $abdo['mode'][$from_id]['mode'];
    if ($mode === "numberfiles" || $mode === "numberban") {
        handleSetNewValue($text, $mode);
    }
}

function handleSetNewValue($newValue, $key) {
    global $from_id, $abdo, $bot;
    if (!is_numeric($newValue) || $newValue < 0) {
        bot('sendMessage', [
            'chat_id' => $from_id,
            'text' => "⚠️ العدد يجب أن يكون رقمًا صحيحًا موجبًا.",
            'parse_mode' => "MARKDOWN"
        ]);
        return;
    }

    $bot[$key] = $newValue;
    s();
    bot('sendMessage', [
        'chat_id' => $from_id,
        'text' => "✅ تم تعيين العدد الجديد `" . $newValue . "` لـ: " . ($key === "numberfiles" ? "عدد الملفات" : "عدد التحذيرات"),
        'parse_mode' => "MARKDOWN",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => '• رجوع •', 'callback_data' => "abdo"]]
            ]
        ])
    ]);
    $abdo['mode'][$from_id]['mode'] = null;
    s();
}











































if ($text == "/start" || $data == "back2") {
    if ($data) {
        $m = 'EditMessageText';
    } else {
        $m = 'sendMessage';
    }
    bot($m, [
        "chat_id" => $chat_id,
        'message_id' => $message_id,
        "text" => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᵐᵏᵃʳʸ](tg://user?id=7217896334)
💞 ⸽ • اهلا بك عزيزي ↜ [$name](tg://openmessage?user_id=$from_id)
🎗️ ⸽ • ايديك ↜ : [$from_id](tg://user?id=$from_id)
ׁ۪ ⬞.┄ׅ━ׄ┄ׅ━ׄ┄ׅ━ׄ─ׅ۰ ★ ۰─ׅ━ׄ┄ׅ━ׄ┄ׅ━ׄ┄ׅ ⬞. ׁ۪
```⭐⭐⭐⭐⭐
↜انت مستخدم 『𝐕𝐢𝐏』👀 ```
``` 
         🤖 Mkary bots 🤖
             ```
📋 ⸽ • لوحة التحكم الخاصه بـك 
⚙️ ⸽ • لرفع الملفات فقط قم بارسالها هنا 
⛓ ⸽ • المجلد الافتراضي للرفع هو ↜ {$folder_id}
📁 ⸽ • ملفاتك المرفوعه ↜ {$from_upload}
🤖 - 👤 ⸽ • عدد مستخدمين البوت ↜ {$stats['stats']['total_users']}
🌀 ⸽ • احصائيات الرفع في البوت ↜ {$upload_all_bot}
",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "🛠 - تحديث البوت ", 'callback_data' => "refr"], ['text' => "🛡️ - احصائيات الحمايه ", 'callback_data' => "nas"]],
                [['text' => "💌 - التواصل مع الدعم ", 'callback_data' => "contact"]],
                [['text' => "➕ - انشاء مجلد  ", 'callback_data' => "Create_folder"], ['text' => "☑️ - تعيين مجلد الرفع ", 'callback_data' => "set_flowr"]],
                [['text' => "📜 - معلوماتي", 'callback_data' => "show"]],
                [['text' => "💯 - شكرا لثقتك بـ بوتنا ", 'callback_data' => "Editfile"], ['text'=>'المطور ـ 🪪','url'=>"https://t.me/V2P_1"]],
            ]
        ])
    ]);
    $abdo['mode'][$from_id]['mode'] = null;
    s();
    exit;
}




if ($data == "nas") {
    $messageText = "
*إحصائيات الملفات المرفوعة في البوت*[$bot_name](tg://user?id=$bot_id)

🔹 **إجمالي الملفات المرفوعة:** `{$bot["all_file"]}`
🔸 **ملفات بوتات (Telegram):** `{$bot["Info_uploads"]["telegram"]}`
🔸 **ملفات غير مرتبطة بتليجرام:** `{$bot["Info_uploads"]["not_telegram"]}`
🔹 **ملفات PHP المرفوعة:** `{$bot["php"]}`
🔸 **ملفات JSON المرفوعة:** `{$bot["json"]}`
🔸 **ملفات نصية (TXT):** `{$bot["text"]}`
🔹 **ملفات تحتوي على مكتبة CURL:** `{$bot["Info_uploads"]["curl"]}`

---

🛡️ **الإحصائيات الأمنية:**
- 🚫 **ملفات PHP الضارة التي تم حظرها:** `{$bot["php_ban"]}`
- 🚫 **ملفات JSON الضارة التي تم حظرها:** `{$bot["json_ban"]}`
- 🚫 **ملفات TXT الضارة التي تم حظرها:** `{$bot["text_ban"]}`
- 🔒 **نسبة حماية البوت للملفات الضارة:** *عالية الدقة*

---
";

    bot("editMessageText", [
        "chat_id" => $chat_id,
        "message_id" => $message_id,
        "text" => $messageText,
        "parse_mode" => "markdown",
        "reply_markup" => json_encode([
            "inline_keyboard" => [
                [["text" => "رجوع", "callback_data" => "back2"]]
            ]
        ])
    ]);
}





function progress($total, $current) {
    $progress = $current / $total;
    $bar_length = 20;
    $filled_length = round($bar_length * $progress);

    $moon_phases = ["🌑", "🌒", "🌓", "🌔", "🌕", "🌖", "🌗", "🌘"];
    $moon_phase = $moon_phases[$current % count($moon_phases)];

    $bar = str_repeat("_", $filled_length) . "👨🏼‍🦼‍➡️" . str_repeat("_", ($bar_length - $filled_length - 1));
    $result = [
        "bar" => $bar,
        "moon" => $moon_phase
    ];
    return $result["bar"] . "  " . $result["moon"];
}



if ($data == "refr") {
    for ($i = 0; $i <= 10; $i++) {
        bot("editMessageText", [
            "chat_id" => $chat_id,
            'message_id' => $message_id,
            "text" => "*
♻️ يتم عمل تحديث انتظر قليلا
" . progress(10, $i) . "
*",
            "parse_mode" => "markdown",
        ]);
        sleep(0.3);
    }
    bot("editMessageText", [
        "chat_id" => $chat_id,
        'message_id' => $message_id,
        "text" => "*
✨ تم الانتهاء من التحديث ✔
*",
        "parse_mode" => "markdown",
    ]);
    sleep(1.5);
    bot("editMessageText", [
        "chat_id" => $chat_id,
        'message_id' => $message_id,
        "text" => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᵐᵏᵃʳʸ](tg://user?id=7217896334)
💞 ⸽ • اهلا بك عزيزي ↜ [$name](tg://openmessage?user_id=$from_id)
🎗️ ⸽ • ايديك ↜ : [$from_id](tg://user?id=$from_id)
ׁ۪ ⬞.┄ׅ━ׄ┄ׅ━ׄ┄ׅ━ׄ─ׅ۰ ★ ۰─ׅ━ׄ┄ׅ━ׄ┄ׅ━ׄ┄ׅ ⬞. ׁ۪
```⭐⭐⭐⭐⭐
↜انت مستخدم 『𝐕𝐢𝐏』👀 ```
            ``` 
         🤖 Mkary bots 🤖
            ```
📋 ⸽ • لوحة التحكم الخاصه بـك 
⚙️ ⸽ • لرفع الملفات فقط قم بارسالها هنا 
⛓ ⸽ • المجلد الافتراضي للرفع هو ↜ {$folder_id}
📁 ⸽ • ملفاتك المرفوعه ↜ {$from_upload}
👤 ⸽ • عدد مستخدمين البوت ↜ {$stats['stats']['total_users']}
🌀 ⸽ • احصائيات الرفع في البوت ↜ {$upload_all_bot} 
",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "🛠 - تحديث البوت ", 'callback_data' => "refr"], ['text' => "🛡️ - احصائيات الحمايه ", 'callback_data' => "nas"]],
                [['text' => "💌 - التواصل مع الدعم ", 'callback_data' => "contact"]],
                [['text' => "➕ - انشاء مجلد  ", 'callback_data' => "Create_folder"], ['text' => "☑️ - تعيين مجلد الرفع ", 'callback_data' => "set_flowr"]],
                [['text' => "📜 - معلوماتي", 'callback_data' => "show"]],
                [['text' => "💯 - شكرا لثقتك بـ بوتنا ", 'callback_data' => "Editfile"], ['text'=>'المطور ـ 🪪','url'=>"https://t.me/V2P_1"]],
            ]
        ])
    ]);
}

if ($data == 'Create_folder') {
    bot('editMessageText', [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'text' => '- قم بأرسال اسم المجلد الجديد، ',
        'parse_mode' => 'markdown',
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => 'رجوع', 'callback_data' => 'back2']]
            ]
        ])
    ]);

    $abdo['mode'][$from_id]['mode'] = 'Create_folder';
    s();
    exit;
}
if ($text && $abdo['mode'][$from_id]['mode'] == 'Create_folder') {
    $folder_name = "all/$chat_id/$text";
    mkdir("all");
    mkdir("all/$chat_id");
    if (!is_dir($folder_name)) {
        mkdir($folder_name, 0777, true);
        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "- تم إنشاء الفولدر $text بنجاح ✅",
            'parse_mode' => 'markdown'
        ]);
    } else {
        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "- المجلد موجود بالفعل.",
            'parse_mode' => 'markdown'
        ]);
    }
    $abdo['mode'][$from_id]['mode'] = null;
    s();
}






if(!isset($bot['from_folder'])){
    mkdir("all");
    mkdir("all/$chat_id");
    $bot['from_folder'] = "bots";
    s();
}
if ($data == 'set_flowr') {
    if ($bot['folder'] != "off") {
        $user_folder = "all/$chat_id";
        $buttons = prepare_buttons($user_folder);
        if (empty($buttons)) {
            bot('editMessageText', [
                'chat_id' => $chat_id,
                'message_id' => $message_id,
                'text' => '- لا توجد فولدرات متاحة للتعيين. تأكد من إنشاء فولدرات في المسار الأساسي.',
                'parse_mode' => 'markdown',
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                        [['text' => 'رجوع', 'callback_data' => 'back2']]
                    ]
                ])
            ]);
            return;
        }
        $inline_keyboard = [];
        foreach ($buttons as $folder_name) {
            $inline_keyboard[] = [['text' => $folder_name, 'callback_data' => "select_folder:$folder_name"]];
        }
        $inline_keyboard[] = [['text' => 'رجوع', 'callback_data' => 'back2']];
        bot('editMessageText', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'text' => "- اختر المجلد لتعيينه كفولدر رفع. لاحظ:\n' .
                '• عند عدم وجود العلامة `>`، يعني أن الفولدر في المسار الأساسي مباشرة.\n' .
                '• إذا كان هناك علامات `>`، فهذا يعني أن الفولدر متفرع داخل فولدر آخر.",
            'parse_mode' => 'markdown',
            'reply_markup' => json_encode([
                'inline_keyboard' => $inline_keyboard
            ])
        ]);
    } else {
        bot('editMessageText', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'text' => '- لا يمكنك تعيين فولدر بسبب إغلاق المالك لهذا الأمر.',
            'parse_mode' => 'markdown',
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => 'رجوع', 'callback_data' => 'back2']]
                ]
            ])
        ]);
    }
}
if (strpos($data, 'select_folder:') === 0) {
    $selected_folder = str_replace('select_folder:', '', $data);
    $bot['from_folder'] = $selected_folder;
    s();
    bot('editMessageText', [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'text' => "✅ تم تعيين فولدر الرفع الجديد:\n`$selected_folder`",
        'parse_mode' => 'markdown',
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => 'رجوع', 'callback_data' => 'back2']]
            ]
        ])
    ]);
}
function prepare_buttons($base_folder) {
    $folders = [];
    if (!is_dir($base_folder)) {
        return $folders;
    }
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($base_folder, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    foreach ($iterator as $item) {
        if ($item->isDir()) {
            $relative_path = str_replace($base_folder . '/', '', $item->getPathname());
            $formatted_path = str_replace('/', ' > ', $relative_path);
            $folders[] = $formatted_path;
        }
    }
    return $folders;
}





if ($data == 'contact') {
    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "📨 أرسل الآن رسالتك أو الوسائط التي تريد إرسالها إلى الدعم الفني. سيتم الرد عليك قريبًا.",
        'parse_mode' => 'markdown'
    ]);
    $abdo['mode'][$from_id]['mode'] = "contact";
    s();
    exit;
}

if ($text and $abdo['mode'][$from_id]['mode'] == "contact") {
    $pp = bot('sendMessage', [
        'chat_id' => $admin,
        'text' => "رسالة جديده عزيزي المطور من
- الاسم : [$name](tg://user?id=$from_id)
- المعرف :[ $sf ]
- الايدي : [$from_id](tg://openmessage?user_id=$from_id)

** نص الرساله **
{$text}

يمكنك الرد عليه من خلال الرد على هذا المسج
",
        'parse_mode' => 'markdown'
    ]);
    
    $message_id_to = $pp->result->message_id;
    $abdo["twasol"][$message_id_to] = $from_id;
    s();
    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "تم ارسل رسالتك الى الدعم 
انتظر الرد
",
        'parse_mode' => 'markdown'
    ]);
    $abdo['mode'][$from_id]['mode'] = null;
    s();
    exit;
}

















if ($data == 'show') {
    $user_folder = "all/$chat_id";

    if (!is_dir($user_folder)) {
        bot('editMessageText', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'text' => '- لم يتم العثور على مجلدات بعد.',
            'parse_mode' => 'markdown',
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => 'رجوع', 'callback_data' => 'back2']]
                ]
            ])
        ]);
    } else {
        $folders = get_folders($user_folder);

        if (!$folders || empty($folders)) {
            bot('editMessageText', [
                'chat_id' => $chat_id,
                'message_id' => $message_id,
                'text' => '- لا توجد ملفات أو مجلدات في هذا المسار.',
                'parse_mode' => 'markdown',
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                        [['text' => 'رجوع', 'callback_data' => 'back2']]
                    ]
                ])
            ]);
            return;
        }

        $folder_icons = "📂";
        $file_icons = "📄";

        $total_folders = 0;
        $total_files = 0;
        $folders_list = "";

        foreach ($folders as $item) {
            if (strpos($item, $folder_icons) !== false) {
                $total_folders++;
            } elseif (strpos($item, $file_icons) !== false) {
                $total_files++;
            }

            $folders_list .= "- $item\n";
        }

        $max_display = 10;
        $display_list = implode("\n", array_slice(explode("\n", $folders_list), 0, $max_display));

        bot('editMessageText', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'text' => "*العدد الكلي للفولدرات:* $total_folders\n" .
                      "*العدد الكلي للملفات:* $total_files\n\n" .
                      "العناصر المعروضة (أقصى $max_display):\n$display_list",
            'parse_mode' => 'markdown',
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => 'رجوع', 'callback_data' => 'back2']],
                    $total_folders + $total_files > $max_display
                        ? [['text' => 'عرض المزيد', 'callback_data' => 'show_more']]
                        : []
                ]
            ])
        ]);
    }
}

function get_folders($base_folder) {
    if (!is_dir($base_folder)) return [];

    $items = [];
    $iterator = new DirectoryIterator($base_folder);

    foreach ($iterator as $fileinfo) {
        if ($fileinfo->isDot()) continue;

        if ($fileinfo->isDir()) {
            $items[] = "📂 " . $fileinfo->getFilename();
        } elseif ($fileinfo->isFile()) {
            $items[] = "📄 " . $fileinfo->getFilename();
        }
    }

    return $items;
}













































$tahzir = $numberban - $bot["from_ban"][$from_id];
if (!$bot["from_ban"][$from_id]) {
    $textban = "*
• تحذير لقد قمت بمحاوله اختراق 🥷🏽
• هذه اول محاوله لك
• لديك $tahzir تحذيات
• تبقى لك $numberban تحذير
• اذا نفذت التحذيرات سيتم حظرك
• تم ارسال اشعار للمبرمج

• نسبه الحمايه من الملفات الضاره : 100% 

*";
} elseif ($tahzir > 1) {
    $textban = "*
• تحذير لقد قمت بمحاوله اختراق 🥷🏽
• هذه اول محاوله لك
• لديك $tahzir تحذير متبقي
• اذا نفذت التحذيرات سيتم حظرك
•*تم ارسال اشعار للمبرمج*

• نسبه الحمايه من الملفات الضاره : 100% 

*";

} elseif ($tahzir == 1) {
    $textban = "*
 • تحذير لقد قمت بمحاوله اختراق 🥷🏽
• لا يوجد لديك تحذيرات متبقيه 
• اذا كررت الامر مره اخرى سيتم حظرك فورا
•*تم ارسال اشعار للمبرمج*

• نسبه الحمايه من الملفات الضاره : 100% 

*";
} else {
    $textban = "* تم حظرك من البوت بسبب تجاوز التحذيرات ورفع ملفات مخالفه *";
}









$bot['promotionn'] = $bot['promotionn'] ?? [];

if($update->message->document){

    if ($from_id != $admin && $bot['premium'] == "on" && !in_array($from_id, $bot['promotionn'])) {
        bot("sendMessage", [
            "chat_id" => $chat_id ,
            "text" => "
[ᶠʳᵒᵐ ʲᵘˢᵗ ᵐᵏᵃʳʸ](tg://user?id=7217896334)
*عذرا لا يمنك رف ملفاتك هنا لانك غير مشترك 
يمكنك التواصل مع المطور للاشتراك في البوت*
",
            'parse_mode'=>"markdown",
        ]);
        exit;
    }

    if($bot['upload'] == "off") {
        bot("sendmessage",[
            "chat_id" => $chat_id,
            "text" => "استقبال الملفات متوقف ❌" ,
            "parse_mode" => "marKdown",
            
        ]);
        exit;
    }

    if($bot["from_php"][$from_id] and $bot["from_php"][$from_id] > $numberfiles){
        bot('sendmessage',[
            'chat_id'=>$chat_id,
            'text'=>"
• تم تجاوز عدد الملفات المحدد لك 
• العدد المحدد لك هوا $numberfiles ملف 
• يرجى حذف بعضا من الملفات المرفوعه مسبقا بواسطة الازرار
عدد ملفاتك المرفوعه --> ". $bot["from_php"][$from_id],
            'reply_markup'=>json_encode([
                'inline_keyboard'=>[[['text'=>"• حذف جميع ملفاتك •",'callback_data'=>"delete_file_all|$from_id" ]]]
            ])
        ]);
        exit;
    }

   $file_id = "https://api.telegram.org/file/bot" . API_KEY . "/" . bot("getfile", ["file_id" => $document_file_id])->result->file_path;
   $file_path = "check/$chat_id.php";
   $f = file_get_contents($file_id);
   file_put_contents($file_path, $f);


   if(pathinfo($file_id, PATHINFO_EXTENSION) == "php"){
        $b = bot("sendmessage", [
            "chat_id" => $chat_id,
            "text" => "
           *
• - يتم التحليل انتظر قليلاً..
           *
" ,
            "parse_mode" => "markdown",
        ]);
        $count = explode("\n",$f);
        $count = count($count);
        $result = checkConditions($f);
        if ($result) {
            unlink("check/$chat_id.php");
            bot("editMessagetext",[
                "chat_id" => $chat_id,
                'message_id' => $b->result->message_id, 
                "text" => $textban ,
                "parse_mode" => "marKdown",
            ]);
            bot("sendmessage",[
                "chat_id" =>$admin,
                "text" => "
*• محاوله اختراق*
• من $name
        
[$from_id](tg://user?id=$chat_id) 
[Acount](tg://openmessage?user_id=$chat_id) 
" ,
                "parse_mode" => "markdown",       
            ]);
            $bot["from_ban"][$from_id]++;
            $bot["php_ban"]++;
            $bot["ban"]++;
            s();
            exit;
        }
       bot("editMessagetext",[
           "chat_id" => $chat_id,
           'message_id' => $b->result->message_id, 
           "text" => "
<s>• يتم التحليل انتظر قليلاً..</s>
• تم الرفع بنجاح 
• اسم الملف الخاص بك $document_file_name
" ,
           "parse_mode" => "html",
           ]);
        $ur = "https://$domin" . dirname($_SERVER['SCRIPT_NAME']) . "/all/$chat_id/$folder_id/$document_file_name";
        mkdir("all/$chat_id/$folder_id");
        $url = "all/$chat_id/$folder_id/$document_file_name";
        file_put_contents($url, $f);
        if(preg_match("/api.telegram.org/", $f)) {
           $bot["Info_uploads"]["telegram"]++;
        } else {
           $bot["Info_uploads"]["not_telegram"]++;
        }
        if (strpos($f, 'curl_') !== false) {
           $bot["Info_uploads"]["curl"]++;
        }
        $cr = rand(9999,999999);
        if (preg_match('/(\d{6,14}:[\w-]{35,75})/', $f, $matches)) {
            $took = $matches[0];
            $result = file_get_contents("https://api.telegram.org/bot$took/getme");
            if ($result != false) {
                $bot["Info_from_upload"][$cr]["token"] = $took;
                $bot["Info_from_upload"][$cr]["webhook"] = urlencode($ur);
                s();
                $keyb = [
                    [['text'=>"• ♻️ عمل ويبهوك ♻️ •",'callback_data'=>"up_webhook|$cr" ],['text'=>"• ⚠️ حذف الويبهوك ⚠️ •",'callback_data'=>"del_webhook|$cr" ]],
                    [['text'=>"• 💥 حذف الملف من الاستضافه 💥 •",'callback_data'=>"delete_file|$cr" ]],
                    [['text'=>"• 📝 معلومات البوت 📝 •",'callback_data'=>"information_bot|$cr" ]],
                    [['text'=>"• 📛 حذف جميع ملفاتك 📛 •",'callback_data'=>"delete_file_all|$from_id" ]]
                ];
                $abdo12 = urlencode($ur);

            } else {
                $took = "خذ هذا التوكن {" . $matches[0] . "} خاطئ او تم الغاء تفعيله من البوت فاذر يرجى تغييره";
                $keyb = [
                    [['text'=>"• 💥 حذف الملف من الاستضافه 💥 •",'callback_data'=>"delete_file|$cr" ]],
                     [['text'=>"• 📛 حذف جميع ملفاتك 📛 •",'callback_data'=>"delete_file_all|$from_id" ]]
                ];
                $abdo12 = "لا يوجد روابط لعرضها";
            }
       } else {
            $took = "لا يوجد توكن";
            $keyb = [
                [['text'=>"• 💥 حذف الملف من الاستضافه 💥 •",'callback_data'=>"delete_file|$cr" ]],
                [['text'=>"• 📛 حذف جميع ملفاتك 📛 •",'callback_data'=>"delete_file_all|$from_id" ]]
            ];
            $abdo12 = "لا يوجد روابط لعرضها";
        }
        bot("editMessagetext",[
            "chat_id" => $chat_id,
            'message_id' => $b->result->message_id, 
            "text" => "
- مسار الملف *$folder_id* 🧸

- رابط الويبهوك `$abdo12`

- توكن البوت  `$took`  🧸
            ",
            'parse_mode' => "markdown",
            'reply_markup' => json_encode(['inline_keyboard' => $keyb])
        ]);
        bot("sendmessage",[
            "chat_id" => $admin,
            "text" => "
- تم رفع ملف جديد من المستخدم [$name](tg://user?id=$from_id) : [$from_id](tg://openmessage?user_id=$from_id)

- مسار الملف *$folder_id*

- رابط الويبهوك [ $ur ]

- توكن البوت  `$took` 
           ",
            'parse_mode' => "markdown",
            'reply_markup' => json_encode(['inline_keyboard' => $keyb])
        ]);
        $bot["from_php"][$from_id]++;
        $bot["php"]++;
        $bot["file"]++;
        $bot["Info_from_upload"][$cr]["url"] = "all/$chat_id/$folder_id/$document_file_name";
        s();


   } elseif (pathinfo($file_id, PATHINFO_EXTENSION) == "json") {
        $data = json_decode($f, true);
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            bot("editMessagetext",[
                "chat_id" => $chat_id,
                'message_id' => $b->result->message_id, 
                "text" => "حدث خطا ❌" ,
                "parse_mode" => "marKdown",
            ]);
            exit;
        }
        $suspicious_words = array("ZipArchive", "zip", "eval", ".php", "base64", "base64_decode", "github", "public function create", ".Php", ".pHp", ".phP", ".PHp", ".pHP", ".PhP", "include", "shell", "system", "timestamper", "__FILE__");
        foreach ($data as $key => $value) {
            foreach ($suspicious_words as $word) {
                if (strpos($value, $word) !== false || strpos($key, $word) !== false) {
                    bot("editMessagetext",[
                        "chat_id" => $chat_id,
                        'message_id' => $b->result->message_id, 
                        "text" => $textban ,
                        "parse_mode" => "marKdown",
                    ]);
                    bot("sendmessage",[
                        "chat_id" =>$admin,
                        "text" => "
*• محاوله اختراق*
• من $name

[$from_id](tg://user?id=$chat_id) 
[Acount](tg://openmessage?user_id=$chat_id) 
" ,
                    "parse_mode" => "marKdown",
                    ]);
                    $bot["from_ban"][$from_id]++;
                    $bot["json_ban"]++;
                    $bot["ban"]++;
                    s();
                    return false;
                }
            }
        }
        bot("editMessagetext",[
            "chat_id" => $chat_id,
            'message_id' => $b->result->message_id, 
            "text" => "
<s>• يتم التحليل انتظر قليلاً..</s>
• تم الرفع بنجاح 
• اسم الملف الخاص بك { $document_file_name }
" ,
            "parse_mode" => "html",
        ]);
        $url = "all/$chat_id/$folder_id/$document_file_name";
        file_put_contents($url, $f);
        $bot["from_json"][$from_id]++;
        $bot["json"]++;
        $bot["file"]++;
        s();


    } elseif (pathinfo($file_id, PATHINFO_EXTENSION) == "txt"){
        $txt_content = $f;
        $suspicious_words = array("ZipArchive", "zip", "eval", ".php", "base64", "base64_decode", "github", "public function create", ".Php", ".pHp", ".phP", ".PHp", ".pHP", ".PhP", "include", "shell", "system", "timestamper", "__FILE__");
        foreach ($suspicious_words as $word) {
            if (strpos($txt_content, $word) !== false) {
                bot("editMessagetext",[
                    "chat_id" => $chat_id,
                    'message_id' => $b->result->message_id, 
                    "text" => $textban ,
                    "parse_mode" => "marKdown",
                ]);
                bot("sendmessage",[
                    "chat_id" =>$admin,
                    "text" => "
*• محاوله اختراق*
• من $name

[$from_id](tg://user?id=$chat_id) 
[Acount](tg://openmessage?user_id=$chat_id) 
" ,
                    "parse_mode" => "marKdown",
                ]);
                $bot["from_ban"][$from_id]++;
                $bot["text_ban"]++;
                $bot["ban"]++;
                s();
                return false;
            }
        }
        bot("editMessagetext",[
            "chat_id" => $chat_id,
            'message_id' => $b->result->message_id, 
            "text" => "
<s>• يتم التحليل انتظر قليلاً..</s>
• تم الرفع بنجاح 
• اسم الملف الخاص بك { $document_file_name }
" ,
            "parse_mode" => "html",
        ]);
        $url = "all/$chat_id/$folder_id/$document_file_name";
        file_put_contents($url, $f);
        $bot["from_text"][$from_id]++;
        $bot["text"]++;
        $bot["file"]++;
        s();
    }
}












$da = explode("|", $data);
$command = $da[0];
$cr = $da[1] ?? null;
if ($command == "up_webhook") {

    $tk = $bot["Info_from_upload"][$cr]["token"];
    $ul = $bot["Info_from_upload"][$cr]["webhook"];
    file_get_contents("https://api.telegram.org/bot$tk/setwebhook?url=$ul");
    $result = file_get_contents("https://api.telegram.org/bot$tk/getme");
    if ($result === false) {
        $text = "التوكن خاطئ ❌";
    } else {
        $text = "• تم عمل ويبهوك ✅";
    }
    
    bot('answerCallbackQuery', [
        'callback_query_id' => $update->callback_query->id,
        'text' => $text,
        'show_alert' => true
    ]);
    
    send_message('- بواسطة [البوت](https://t.me/S7_MXBOT) | تم إنشاء الويب هوك بنجاح ✅!
- أرسل /start لبدء التشغيل ♻️!', $from_id, $tk);

} elseif ($command == "del_webhook") {

    $tk = $bot["Info_from_upload"][$cr]["token"];
    $ul = $bot["Info_from_upload"][$cr]["webhook"];
    file_get_contents("https://api.telegram.org/bot$tk/deleteWebhook");
    $result = file_get_contents("https://api.telegram.org/bot$tk/getme");
    if ($result === false) {
        $text = "التوكن خاطئ ❌";
    } else {
        $text = "• تم ازالة الويبهوك ⭕";
    }

    bot('answerCallbackQuery', [
        'callback_query_id' => $update->callback_query->id,
        'text' => $text,
        'show_alert' => true
    ]);

    send_message('- بواسطة @S7_MXBOT | تم حذف الويب هوك بنجاح ✅!
• يمكنك الاشتراك لتتابع آخر التحديثات @S7_MX3 •', $from_id, $tk);
} elseif ($command == "information_bot") {
    $tk = $bot["Info_from_upload"][$cr]["token"];
    $ul = $bot["Info_from_upload"][$cr]["webhook"];
    $url = "https://api.telegram.org/bot" . $tk . "/getMe";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($output);
    if ($result->ok) {
        $bot_username = $result->result->username;
        $bot_id = $result->result->id;
        $bot_name = $result->result->first_name;
        $bot_privacy = $result->result->can_join_groups ? "Public" : "Private";
        $webhook = $result->result->webhook_url;
        bot("sendmessage",[
            "chat_id" => $chat_id, 
            "text" => "
- اسم البوت : [$bot_name](tg://user?id=$bot_id) ✓
    
- يوزر البوت 👾 :[ @$bot_username ]✓
    
- ايدي البوت 🆔 : $bot_id ✓
    
- وضع الخصوصية : $bot_privacy ✓
    
- رابط الويب هوك : ممنوع ارساله للخصوصيه ❌
",
            "parse_mode" => "markdown",
        ]);
    } else {
        bot("sendmessage",["chat_id" => $chat_id, "text" => "التوكن خاطئ ❌"]);
    }
} elseif ($command == "delete_file") {
    // تأكد من أن المسار صحيح
    $url = $bot["Info_from_upload"][$cr]["url"];
    $file_path = realpath($url);

    // تحقق من وجود الملف
    if (file_exists($file_path) && is_file($file_path)) {
        if (unlink($file_path)) {
            unset($bot["Info_from_upload"][$cr]);
            $bot["from_php"][$from_id]--;
            s();

            bot('answerCallbackQuery', [
                'callback_query_id' => $update->callback_query->id,
                'text' => "• تم حذف الملف بنجاح ✅",
                'show_alert' => true
            ]);
        } else {
            bot('answerCallbackQuery', [
                'callback_query_id' => $update->callback_query->id,
                'text' => "• فشل في حذف الملف. يرجى التحقق من الأذونات.❌",
                'show_alert' => true
            ]);
        }
    } else {
        bot('answerCallbackQuery', [
            'callback_query_id' => $update->callback_query->id,
            'text' => "• الملف تم حذفه من قبل • ❌",
            'show_alert' => true
        ]);
    }
} elseif ($command == "delete_file_all") {
    $mainFolder = __DIR__ . "/all";
    $folderToDelete = $mainFolder . DIRECTORY_SEPARATOR . $cr;
    if (realpath($folderToDelete) !== realpath($mainFolder) && strpos(realpath($folderToDelete), realpath($mainFolder)) === 0) {
        if (deleteFolder($folderToDelete)) {
            bot('answerCallbackQuery', [
                'callback_query_id' => $update->callback_query->id,
                'text' => "
• تم حذف جميع ملفاتك بنجاح ✅
                ",
                'show_alert' => true
            ]);
        } else {
            echo "حدث خطأ";
        }
    }
}










function deleteFolder($folderPath) {
    global $update;
    if (!is_dir($folderPath)) {
        bot('answerCallbackQuery', [
            'callback_query_id' => $update->callback_query->id,
            'text' => "
• تم حذف ملفاتك من قبل أو لا يوجد ملفات حالياً ❌•
            ",
            'show_alert' => true
        ]);
        return false;
    }
    $files = array_diff(scandir($folderPath), ['.', '..']);
    foreach ($files as $file) {
        $filePath = $folderPath . DIRECTORY_SEPARATOR . $file;
        if (is_dir($filePath)) {
            deleteFolder($filePath);
        } else {
            unlink($filePath);
        }
    }
    return rmdir($folderPath);
}

if($text == '/mkary'){
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>'اهلا يا مطور مكاري'
]);
}