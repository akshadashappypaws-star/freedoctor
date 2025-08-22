@echo off
cls
echo ================================================
echo           FreeDoctor ngrok Quick Setup
echo ================================================
echo.

REM Try to run ngrok if it exists
if exist "ngrok.exe" (
    echo ✅ ngrok.exe found! Starting tunnel...
    echo.
    echo 📋 COPY THE HTTPS URL FROM BELOW:
    echo ================================================
    start /wait ngrok.exe http 8000
    goto :end
)

echo ❌ ngrok.exe not found in current directory
echo.
echo 📥 Please follow these steps:
echo.
echo 1. Go to: https://ngrok.com/download
echo 2. Download "Windows 64-bit" 
echo 3. Extract ngrok.exe to this folder:
echo    %CD%
echo 4. Run this script again
echo.
echo 🔄 Alternative: Copy ngrok.exe here and press any key
pause
cls
goto :start

:end
echo.
echo ⚠️  ngrok stopped. Run script again to restart.
pause
