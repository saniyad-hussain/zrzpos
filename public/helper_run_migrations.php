<?php
/**
 * Helper Script to Run Database Migrations
 * 
 * INSTRUCTIONS:
 * 1. Upload this file to your public_html/ directory
 * 2. Visit: https://yourdomain.com/helper_run_migrations.php
 * 3. Delete this file after use for security
 * 
 * WARNING: This will modify your database. Make sure you have a backup!
 */

// Security check - add a simple password protection
$password = 'CHANGE_THIS_PASSWORD'; // CHANGE THIS before uploading!

if (!isset($_GET['pass']) || $_GET['pass'] !== $password) {
    die('Access denied. Add ?pass=YOUR_PASSWORD to the URL.');
}

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
    
    echo '<h2>Running Database Migrations...</h2>';
    echo '<pre>';
    
    Artisan::call('migrate', ['--force' => true]);
    
    echo Artisan::output();
    echo '</pre>';
    
    echo '<h2>✅ Migrations Completed!</h2>';
    echo '<p>Please <strong>DELETE this file (helper_run_migrations.php)</strong> immediately for security.</p>';
    echo '<p><a href="/">Go to Homepage</a></p>';
} catch (Exception $e) {
    die('ERROR: ' . $e->getMessage() . '<br><br>Check your database credentials in .env file.');
}

