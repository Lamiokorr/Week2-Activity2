<?php
// sanitize a filename
function safe_filename($name) {
    $name = preg_replace('/[^A-Za-z0-9\-_\.]/', '_', $name);
    return $name;
}

// ensure path is inside uploads/ (relative)
function is_inside_uploads($path) {
    $normalized = str_replace('\\', '/', $path);
    return strpos($normalized, 'uploads/') === 0;
}

// recursively delete directory (only if inside uploads/)
function rrmdir_uploads($dir) {
    $dir = rtrim(str_replace('\\','/',$dir), '/').'/';
    if (!is_inside_uploads($dir)) return false;
    if (!is_dir($dir)) return false;
    $items = array_diff(scandir($dir), ['.','..']);
    foreach ($items as $item) {
        $path = $dir . $item;
        if (is_dir($path)) rrmdir_uploads($path);
        else @unlink($path);
    }
    return rmdir($dir);
}
?>
