<?php
/**
 * Helper Script to Generate Application Key
 * 
 * INSTRUCTIONS:
 * 1. Upload this file to your public_html/ directory
 * 2. Visit: https://yourdomain.com/helper_generate_key.php
 * 3. Delete this file after use for security
 */

// Security check - only allow if APP_KEY is empty
// NOTE: After moving public folder contents to public_html root, 
// vendor and bootstrap will be siblings, so use: __DIR__.'/vendor'
// If helper is still in public/ subfolder, use: __DIR__.'/../vendor'

// Try both paths to work in either location
$vendorPath = file_exists(__DIR__.'/vendor/autoload.php') 
    ? __DIR__.'/vendor/autoload.php' 
    : __DIR__.'/../vendor/autoload.php';
    
$bootstrapPath = file_exists(__DIR__.'/bootstrap/app.php')
    ? __DIR__.'/bootstrap/app.php'
    : __DIR__.'/../bootstrap/app.php';

require $vendorPath;
$app = require_once $bootstrapPath;

// Check if .env exists
$envPath = file_exists(__DIR__.'/.env') ? __DIR__.'/.env' : __DIR__.'/../.env';
if (!file_exists($envPath)) {
    die('ERROR: .env file not found! Please create it first.');
}

// Read .env file
$envContent = file_get_contents($envPath);

// Check if key already exists
if (strpos($envContent, 'APP_KEY=base64:') !== false && strpos($envContent, 'APP_KEY=') !== false) {
    $lines = explode("\n", $envContent);
    foreach ($lines as $line) {
        if (strpos($line, 'APP_KEY=') === 0 && trim($line) !== 'APP_KEY=') {
            die('ERROR: APP_KEY already exists! Delete this helper file.');
        }
    }
}

try {
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    Artisan::call('key:generate');
    
    echo '<h2>✅ Application Key Generated Successfully!</h2>';
    echo '<p>Please <strong>DELETE this file (helper_generate_key.php)</strong> immediately for security.</p>';
    echo '<p><a href="/">Go to Homepage</a></p>';
} catch (Exception $e) {
    die('ERROR: ' . $e->getMessage());
}

