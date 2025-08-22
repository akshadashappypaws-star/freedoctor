@echo off
echo ============================================
echo   FreeDoctor - ngrok Setup and URL Getter
echo ============================================
echo.

REM Check if ngrok.exe exists
if exist "ngrok.exe" (
    echo ✅ ngrok.exe found!
    goto :start_ngrok
)

echo ⬇️ Downloading ngrok...
powershell -Command "Invoke-WebRequest -Uri 'https://bin.equinox.io/c/bNyj1mQVY4c/ngrok-v3-stable-windows-amd64.zip' -OutFile 'ngrok.zip'"

if not exist "ngrok.zip" (
    echo ❌ Download failed! Please download manually from https://ngrok.com/download
    pause
    exit /b 1
)

echo 📦 Extracting ngrok...
powershell -Command "Expand-Archive -Path 'ngrok.zip' -DestinationPath '.' -Force"

if not exist "ngrok.exe" (
    echo ❌ Extraction failed! Please extract manually.
    pause
    exit /b 1
)

del ngrok.zip

:start_ngrok
echo.
echo 🚀 Starting ngrok tunnel on port 8000...
echo.
echo Keep this window open!
echo Press Ctrl+C to stop ngrok
echo.
echo ============================================
echo   Your ngrok URLs will appear below:
echo ============================================
echo.

REM Start ngrok and keep window open
ngrok.exe http 8000

echo.
echo ngrok stopped.
pause
