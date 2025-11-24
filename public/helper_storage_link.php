<?php
/**
 * Helper Script to Create Storage Link
 * 
 * INSTRUCTIONS:
 * 1. Upload this file to your public_html/ directory
 * 2. Visit: https://yourdomain.com/helper_storage_link.php
 * 3. Delete this file after use for security
 */

// Try both paths to work in either location
$vendorPath = file_exists(__DIR__.'/vendor/autoload.php') 
    ? __DIR__.'/vendor/autoload.php' 
    : __DIR__.'/../vendor/autoload.php';
    
$bootstrapPath = file_exists(__DIR__.'/bootstrap/app.php')
    ? __DIR__.'/bootstrap/app.php'
    : __DIR__.'/../bootstrap/app.php';

require $vendorPath;
$app = require_once $bootstrapPath;

try {
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    // Check if link already exists
    $linkPath = __DIR__.'/storage';
    if (file_exists($linkPath) || is_link($linkPath)) {
        // Remove existing link/file
        if (is_link($linkPath)) {
            unlink($linkPath);
        } elseif (is_dir($linkPath)) {
            rmdir($linkPath);
        }
    }
    
    Artisan::call('storage:link');
    
    echo '<h2>✅ Storage Link Created Successfully!</h2>';
    echo '<p>Please <strong>DELETE this file (helper_storage_link.php)</strong> immediately for security.</p>';
    echo '<p><a href="/">Go to Homepage</a></p>';
} catch (Exception $e) {
    die('ERROR: ' . $e->getMessage());
}

