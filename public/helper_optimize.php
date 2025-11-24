<?php
/**
 * Helper Script to Optimize Application
 * 
 * INSTRUCTIONS:
 * 1. Upload this file to your public_html/ directory
 * 2. Visit: https://yourdomain.com/helper_optimize.php
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
    
    echo '<h2>Optimizing Application...</h2>';
    echo '<pre>';
    
    // Clear first
    Artisan::call('config:clear');
    echo "Config cache cleared\n";
    
    Artisan::call('route:clear');
    echo "Route cache cleared\n";
    
    Artisan::call('view:clear');
    echo "View cache cleared\n";
    
    // Then cache
    Artisan::call('config:cache');
    echo "Config cached\n";
    
    Artisan::call('route:cache');
    echo "Routes cached\n";
    
    Artisan::call('view:cache');
    echo "Views cached\n";
    
    echo '</pre>';
    
    echo '<h2>✅ Application Optimized!</h2>';
    echo '<p>Please <strong>DELETE this file (helper_optimize.php)</strong> immediately for security.</p>';
    echo '<p><a href="/">Go to Homepage</a></p>';
} catch (Exception $e) {
    die('ERROR: ' . $e->getMessage());
}

