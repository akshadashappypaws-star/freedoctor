@echo off
echo Starting ngrok tunnel for WhatsApp webhook...
echo.
echo If you don't have ngrok installed:
echo 1. Download from https://ngrok.com/download
echo 2. Extract to C:\ngrok\ 
echo 3. Run this script again
echo.
pause

C:\ngrok\ngrok.exe http 8000
