<?php
// delete.php - سكربت حذف .user.ini
$target = '.user.ini';

// محاولة الحذف بكل الطرق الممكنة
@chmod($target, 0777);
@unlink($target);
@chmod('../' . $target, 0777);
@unlink('../' . $target);

// البحث في المجلدات الشائعة
$folders = ['public_html', 'www', 'html', 'htdocs', 'wp-content', 'includes'];
foreach ($folders as $folder) {
    if (is_dir($folder)) {
        $path = $folder . '/' . $target;
        if (file_exists($path)) {
            @chmod($path, 0777);
            @unlink($path);
            echo "✅ تم الحذف من: " . $path . "<br>";
        }
    }
}

// عرض النتيجة
if (!file_exists($target) && !file_exists('../' . $target)) {
    echo "<h2 style='color:green;'>✅ تم حذف جميع ملفات .user.ini بنجاح!</h2>";
} else {
    echo "<h2 style='color:red;'>❌ لم يتم حذف الملفات التالية:</h2>";
    if (file_exists($target)) echo "- .user.ini في المجلد الحالي<br>";
    if (file_exists('../' . $target)) echo "- ../.user.ini في المجلد الأعلى<br>";
}

echo "<br><a href='?'>↩️ العودة</a>";
?>