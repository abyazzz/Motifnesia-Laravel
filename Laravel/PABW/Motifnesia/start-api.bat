@echo off
echo ========================================
echo   STARTING MOTIFNESIA API SERVER
echo ========================================
echo.
echo Server akan jalan di:
echo - Local: http://127.0.0.1:8000
echo - Network: http://0.0.0.0:8000
echo.
echo Untuk Flutter:
echo - Emulator Android: http://10.0.2.2:8000/api
echo - iOS Simulator: http://127.0.0.1:8000/api
echo - Device Fisik: http://YOUR_IP:8000/api
echo.
echo Press Ctrl+C untuk stop server
echo ========================================
echo.

cd /d "%~dp0"
php artisan serve --host=0.0.0.0 --port=8000

pause
