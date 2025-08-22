@echo off
echo Setting up ngrok...

REM Check if ngrok.exe exists in current directory
if exist "ngrok.exe" (
    echo ngrok.exe found, starting tunnel...
    start /b ngrok.exe http 8000
    echo Waiting for ngrok to start...
    timeout /t 5 /nobreak > nul
    goto :geturl
)

echo ngrok.exe not found. Please:
echo 1. Download ngrok from https://ngrok.com/download
echo 2. Extract ngrok.exe to this folder: %CD%
echo 3. Run this script again
echo.
echo Or use the existing ngrok URL and update manually:
echo.
echo Current .env has: https://aabad6532de5.ngrok-free.app/api/webhook/whatsapp
echo.
echo If you want to use a new ngrok URL:
echo 1. Start ngrok manually: ngrok http 8000
echo 2. Copy the HTTPS URL
echo 3. Update .env file
pause
exit /b

:geturl
echo Getting ngrok URL...
powershell -Command "try { Start-Sleep 3; $response = Invoke-RestMethod 'http://127.0.0.1:4040/api/tunnels'; $httpsUrl = ($response.tunnels | Where-Object { $_.proto -eq 'https' }).public_url; if($httpsUrl) { Write-Host 'New ngrok URL:' $httpsUrl -ForegroundColor Green; Write-Host 'Webhook URL:' $httpsUrl'/api/webhook/whatsapp' -ForegroundColor Yellow } else { Write-Host 'No tunnel found' -ForegroundColor Red } } catch { Write-Host 'Error:' $_.Exception.Message -ForegroundColor Red }"
pause
