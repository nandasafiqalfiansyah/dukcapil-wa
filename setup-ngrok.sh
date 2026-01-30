#!/bin/bash

echo "========================================"
echo "Setup ngrok untuk Webhook Development"
echo "========================================"
echo ""
echo "Langkah-langkah:"
echo ""
echo "1. Install ngrok: https://ngrok.com/download"
echo "2. Buat akun di ngrok.com dan dapatkan authtoken"
echo "3. Jalankan: ngrok config add-authtoken YOUR_TOKEN"
echo "4. Jalankan script ini lagi"
echo ""
echo "========================================"
echo ""

if ! command -v ngrok &> /dev/null; then
    echo "[ERROR] ngrok tidak ditemukan!"
    echo "Install dengan: brew install ngrok (Mac) atau download dari https://ngrok.com/download"
    exit 1
fi

echo "[OK] ngrok ditemukan"
echo ""
echo "Memulai Laravel server..."
php artisan serve &
LARAVEL_PID=$!
sleep 3

echo "Memulai ngrok tunnel..."
echo ""
echo "PENTING: Copy URL HTTPS dari ngrok dan tambahkan /api/webhook/whatsapp"
echo "Contoh: https://abc123.ngrok.io/api/webhook/whatsapp"
echo ""
echo "Paste URL tersebut di Fonnte dashboard webhook settings"
echo ""
echo "========================================"
echo ""

ngrok http 8000

# Cleanup
kill $LARAVEL_PID
