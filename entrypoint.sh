#!/usr/bin/env bash
set -e

# ==========================
# Konfigurasi (boleh diubah)
# ==========================
PHP_USER=${PHP_USER:-www-data}
PHP_GROUP=${PHP_GROUP:-www-data}

echo "==> Using PHP user: ${PHP_USER}:${PHP_GROUP}"

# ==========================
# Pastikan direktori Laravel
# ==========================
mkdir -p \
  bootstrap/cache \
  storage/framework/views \
  storage/framework/cache/data \
  storage/framework/sessions \
  storage/logs \
  storage/app/public/ebooks

# ==========================
# Permission & ownership
# ==========================
echo "==> Setting ownership..."
chown -R "${PHP_USER}:${PHP_GROUP}" bootstrap storage || true

echo "==> Setting permissions..."
# Folder: 775, File: 664 (cukup aman, bisa disesuaikan)
find bootstrap -type d -exec chmod 775 {} \; || true
find storage   -type d -exec chmod 775 {} \; || true
find bootstrap -type f -exec chmod 664 {} \; || true
find storage   -type f -exec chmod 664 {} \; || true

# Kalau darurat 403 dan mau test, sementara bisa:
# chmod -R 777 storage bootstrap/cache

# ==========================
# Symlink storage
# ==========================
echo "==> Creating storage symlink..."
php artisan storage:link || true

# ==========================
# Clear caches
# ==========================
echo "==> Clearing caches..."
php artisan view:clear      || true
php artisan cache:clear     || true
php artisan config:clear    || true
php artisan route:clear     || true
php artisan optimize:clear  || true

# ==========================
# Rebuild caches
# ==========================
if [ "${LARAVEL_OPTIMIZE:-1}" = "1" ]; then
  echo "==> Rebuilding caches..."
  php artisan config:cache  || true
  php artisan route:cache   || true
  php artisan view:cache    || true
fi

# ==========================
# Migrate (opsional)
# ==========================
if [ "${LARAVEL_MIGRATE:-0}" = "1" ]; then
  echo "==> Running migrations..."
  php artisan migrate --force || true
fi

# ==========================
# Jalanin perintah berikutnya
# ==========================
echo "==> Starting main process: $*"
exec "$@"