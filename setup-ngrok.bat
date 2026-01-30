@echo off
echo ========================================
echo Setup ngrok untuk Webhook Development
echo ========================================
echo.
echo Langkah-langkah:
echo.
echo 1. Download ngrok dari: https://ngrok.com/download
echo 2. Extract dan letakkan ngrok.exe di folder ini
echo 3. Buat akun di ngrok.com dan dapatkan authtoken
echo 4. Jalankan: ngrok config add-authtoken YOUR_TOKEN
echo 5. Jalankan script ini lagi
echo.
echo ========================================
echo.

if not exist ngrok.exe (
    echo [ERROR] ngrok.exe tidak ditemukan!
    echo Silakan download dari https://ngrok.com/download
    pause
    exit /b 1
)

echo [OK] ngrok.exe ditemukan
echo.
echo Memulai Laravel server...
start cmd /k "title Laravel Server && cd /d %~dp0 && php artisan serve"
timeout /t 3 /nobreak >nul

echo Memulai ngrok tunnel...
echo.
echo PENTING: Copy URL HTTPS dari ngrok dan tambahkan /api/webhook/whatsapp
echo Contoh: https://abc123.ngrok.io/api/webhook/whatsapp
echo.
echo Paste URL tersebut di Fonnte dashboard webhook settings
echo.
echo ========================================
pause
ngrok http 8000
