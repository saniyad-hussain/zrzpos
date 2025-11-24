# Quick Start: Deploy to Hostinger Shared Hosting

This is a condensed guide to get you started quickly. For detailed instructions, see `SHARED_HOSTING_DEPLOYMENT.md`.

## 🚀 Quick Steps

### 1. Prepare Locally (5 minutes)

```bash
# In your project directory
composer install --optimize-autoloader --no-dev
```

This creates the `vendor/` folder you'll need to upload.

### 2. Upload Files (10-30 minutes)

**Using File Manager:**
1. Log in to Hostinger hPanel
2. Go to **Files** → **File Manager** → `public_html`
3. Upload ALL files and folders EXCEPT:
   - `.env` (create on server)
   - `.git/`
   - `node_modules/`
   - Log files in `storage/logs/`
4. **IMPORTANT:** Upload the `vendor/` folder from your local installation

### 3. Move Public Folder Contents (2 minutes)

1. In File Manager, go to `public_html/public/`
2. Select ALL files inside `public/`
3. Move them to `public_html/` (one level up)
4. Delete empty `public/` folder

### 4. Update index.php (1 minute)

Edit `public_html/index.php` and change:
- `__DIR__.'/../vendor'` → `__DIR__.'/vendor'`
- `__DIR__.'/../bootstrap'` → `__DIR__.'/bootstrap'`
- `__DIR__.'/../storage'` → `__DIR__.'/storage'`

### 5. Create .env File (3 minutes)

1. In File Manager, create new file `.env` in `public_html/`
2. Copy content from `SHARED_HOSTING_DEPLOYMENT.md` (Step 5)
3. Update with your:
   - Domain name
   - Database credentials (from Hostinger)
   - Email settings

### 6. Set Permissions (1 minute)

In File Manager, set permissions:
- `storage/` → **755**
- `bootstrap/cache/` → **755**

### 7. Run Setup Scripts (5 minutes)

Upload helper scripts from `public/helper_*.php` to `public_html/`, then visit:

1. **Generate Key:** `https://yourdomain.com/helper_generate_key.php`
2. **Run Migrations:** `https://yourdomain.com/helper_run_migrations.php?pass=YOUR_PASSWORD`
   - ⚠️ Change password in the file first!
3. **Create Storage Link:** `https://yourdomain.com/helper_storage_link.php`
4. **Optimize:** `https://yourdomain.com/helper_optimize.php`

**⚠️ DELETE all helper_*.php files after use!**

### 8. Test (2 minutes)

1. Visit `https://yourdomain.com`
2. Test login
3. Verify everything works

## ✅ Done!

Your application should now be live!

## 📋 Files Created for You

- `SHARED_HOSTING_DEPLOYMENT.md` - Complete detailed guide
- `SHARED_HOSTING_CHECKLIST.md` - Step-by-step checklist
- `UPDATE_INDEX_PHP_PATHS.md` - How to update index.php
- `public/helper_*.php` - Helper scripts for setup

## 🆘 Need Help?

1. Check `SHARED_HOSTING_DEPLOYMENT.md` for detailed instructions
2. Check `storage/logs/laravel.log` for errors
3. Verify `.env` file is correct
4. Check file permissions

## 🔒 Security Reminder

- Delete all `helper_*.php` files after use
- Set `APP_DEBUG=false` in production
- Enable SSL/HTTPS in Hostinger
- Keep your `.env` file secure

---

**Total Time:** ~30-60 minutes (depending on upload speed)

