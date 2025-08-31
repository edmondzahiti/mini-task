@echo off
echo Setting up Mini Task Application...
echo.

echo 1. Running database migrations...
php artisan migrate

echo.
echo 2. Seeding database with sample data...
php artisan db:seed

echo.
echo 3. Building Vue.js assets...
npm run build

echo.
echo 4. Starting Laravel development server...
echo Application will be available at: http://localhost:8000
echo.
php artisan serve --host=127.0.0.1 --port=8000
