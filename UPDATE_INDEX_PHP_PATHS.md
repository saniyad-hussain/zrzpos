# Update index.php Paths for Shared Hosting

When you move the contents of the `public/` folder to `public_html/` root, you need to update the paths in `index.php`.

## The Problem

Laravel's `index.php` uses relative paths like `__DIR__.'/../vendor'` which assumes `vendor/` is one level up from the `public/` folder.

When you move `public/` contents to `public_html/` root, `vendor/` becomes a sibling (same level), not a parent.

## Solution: Update index.php

After moving `public/` contents to `public_html/`, edit `public_html/index.php`:

### Change these lines:

**From:**
```php
if (file_exists(__DIR__.'/../storage/framework/maintenance.php')) {
    require __DIR__.'/../storage/framework/maintenance.php';
}

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
```

**To:**
```php
if (file_exists(__DIR__.'/storage/framework/maintenance.php')) {
    require __DIR__.'/storage/framework/maintenance.php';
}

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
```

## Quick Find & Replace

In your File Manager editor, find and replace:
- `__DIR__.'/../storage` → `__DIR__.'/storage`
- `__DIR__.'/../vendor` → `__DIR__.'/vendor`
- `__DIR__.'/../bootstrap` → `__DIR__.'/bootstrap`

## Alternative: Keep Public Folder Structure

If you prefer not to move files, you can:
1. Keep `public/` folder as is
2. Point your domain's document root to `public_html/public/` instead of `public_html/`
3. No need to update `index.php` paths

However, this requires access to change the document root in Hostinger, which may not be available on shared hosting.

---

**Note:** The helper scripts (helper_*.php) automatically detect the correct paths, so they work in either configuration.

