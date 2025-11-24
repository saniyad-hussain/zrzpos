# Shared Hosting Deployment - Quick Checklist

Use this checklist to ensure you don't miss any steps when deploying to Hostinger.

## Pre-Upload Preparation

- [ ] Test application locally
- [ ] Backup local database
- [ ] Run `composer install --optimize-autoloader --no-dev` locally
- [ ] Prepare `.env` file content (with production values)
- [ ] Note your Hostinger database credentials
- [ ] Note your Hostinger email settings

## File Upload

- [ ] Upload all project files to `public_html/` (except `.env`, `.git`, `node_modules`)
- [ ] Upload `vendor/` folder (from local installation)
- [ ] Move contents of `public/` folder to `public_html/` root
- [ ] Delete empty `public/` folder (if moved contents)

## Server Configuration

- [ ] Create `.env` file in `public_html/`
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure database credentials
- [ ] Set `APP_URL` to your domain
- [ ] Set file permissions: `storage/` → 755/775
- [ ] Set file permissions: `bootstrap/cache/` → 755/775

## Application Setup

- [ ] Generate application key (`php artisan key:generate` or use helper script)
- [ ] Run database migrations (`php artisan migrate --force` or use helper script)
- [ ] Create storage link (`php artisan storage:link` or use helper script)
- [ ] Cache configuration (`php artisan config:cache`)
- [ ] Cache routes (`php artisan route:cache`)
- [ ] Cache views (`php artisan view:cache`)

## Testing

- [ ] Visit your domain - homepage loads
- [ ] Test login functionality
- [ ] Test key features (sales, purchases, etc.)
- [ ] Verify file uploads work
- [ ] Check CSS/JS loads correctly
- [ ] Test database operations

## Security

- [ ] Verify `APP_DEBUG=false`
- [ ] Verify `APP_ENV=production`
- [ ] Delete any temporary PHP helper scripts
- [ ] Verify `.env` file is not publicly accessible
- [ ] Enable SSL/HTTPS in Hostinger

## Post-Deployment

- [ ] Set up cron job for scheduler
- [ ] Configure email settings
- [ ] Set up database backups
- [ ] Monitor error logs
- [ ] Test all modules (Ecommerce, Manufacturing)

## Troubleshooting Checklist

If something doesn't work:

- [ ] Check `.env` file exists and is correct
- [ ] Check file permissions (storage, bootstrap/cache)
- [ ] Check `storage/logs/laravel.log` for errors
- [ ] Verify `APP_KEY` is generated
- [ ] Verify database connection
- [ ] Check `.htaccess` file exists in root
- [ ] Verify `vendor/` folder is uploaded
- [ ] Clear browser cache

---

**Quick Commands (if SSH available):**

```bash
cd public_html
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

