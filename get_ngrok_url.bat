@echo off
echo Getting ngrok URL...
echo.

timeout /t 3 /nobreak > nul

curl -s http://localhost:4040/api/tunnels > temp_ngrok.json

for /f "tokens=*" %%i in ('powershell -command "Get-Content temp_ngrok.json | ConvertFrom-Json | Select-Object -ExpandProperty tunnels | Where-Object { $_.proto -eq 'https' } | Select-Object -ExpandProperty public_url"') do (
    echo New ngrok URL: %%i
    echo.
    echo Update your .env file with:
    echo WHATSAPP_WEBHOOK_URL=%%i/api/webhook/whatsapp
    echo.
    echo Then use this URL in Meta Business Manager:
    echo %%i/api/webhook/whatsapp
)

del temp_ngrok.json 2>nul
pause
