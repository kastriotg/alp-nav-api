<?php
/**
 * Simple PSR-4 compatible autoloader for AlpNav Plugin.
 * Place this in the plugin root and require it once in the main plugin file.
 */
spl_autoload_register(function ($class) {
    // Only autoload classes in the Alp_Nav_Api namespace
    if (strpos($class, 'Alp_Nav_Api') !== 0) {
        return;
    }
    $base_dir = __DIR__;
    $class = ltrim($class, '\\');
    $relative_class = str_replace('Alp_Nav_Api', '', $class);
    $relative_class = str_replace('_', '-', strtolower($relative_class));
    $relative_class = str_replace('\\', DIRECTORY_SEPARATOR, $relative_class);
    // Try includes/ first
    $file = $base_dir . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . str_replace('-', DIRECTORY_SEPARATOR, $relative_class) . '.php';
    if (file_exists($file)) {
        require_once $file;
        return;
    }
    // Try admin/ for admin classes
    $admin_file = $base_dir . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . str_replace('-', DIRECTORY_SEPARATOR, $relative_class) . '.php';
    if (file_exists($admin_file)) {
        require_once $admin_file;
        return;
    }
});
