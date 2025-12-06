<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Generate image URL with public path for shared hosting
     * 
     * @param string $path Image path relative to public folder
     * @return string Full URL with /public prefix
     */
    public static function url($path)
    {
        // Remove leading slash if present
        $path = ltrim($path, '/');
        
        // Check if we're on shared hosting (check if public folder exists in URL structure)
        // For shared hosting, we need to add /public prefix
        $baseUrl = config('app.url');
        
        // If path doesn't start with public/, add it
        if (!str_starts_with($path, 'public/')) {
            $path = 'public/' . $path;
        }
        
        return rtrim($baseUrl, '/') . '/' . $path;
    }
    
    /**
     * Generate image URL - checks if public prefix is needed
     * 
     * @param string $path Image path relative to public folder
     * @return string Full URL
     */
    public static function imageUrl($path)
    {
        $path = ltrim($path, '/');
        
        // For shared hosting compatibility, add /public prefix
        $baseUrl = config('app.url');
        
        // Check if we need to add public prefix
        // This will work for both local (with public) and shared hosting
        if (!str_starts_with($path, 'public/')) {
            return rtrim($baseUrl, '/') . '/public/' . $path;
        }
        
        return rtrim($baseUrl, '/') . '/' . $path;
    }
}

