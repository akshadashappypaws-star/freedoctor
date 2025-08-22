@echo off
echo ================================
echo   WhatsApp Webhook Setup Guide
echo ================================
echo.
echo Step 1: Starting ngrok tunnel...
echo.

cd /d C:\ngrok
ngrok.exe http 8000

echo.
echo If ngrok asks for authentication:
echo 1. Go to https://ngrok.com/signup
echo 2. Get your authtoken
echo 3. Run: ngrok config add-authtoken YOUR_TOKEN
echo.
pause
