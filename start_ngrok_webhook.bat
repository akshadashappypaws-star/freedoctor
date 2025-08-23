@echo off
echo 🚀 Starting ngrok tunnel for FreeDoctor webhooks...
echo.

REM Kill any existing ngrok processes
taskkill /f /im ngrok.exe >nul 2>&1

REM Wait a moment
timeout /t 2 /nobreak >nul

REM Start ngrok in the background
start /b ngrok http 8000

REM Wait for ngrok to start
echo ⏳ Waiting for ngrok to initialize...
timeout /t 5 /nobreak >nul

REM Get tunnel information
echo.
echo 🌐 Fetching tunnel information...
curl -s http://127.0.0.1:4040/api/tunnels | jq -r ".tunnels[] | select(.config.addr==\"http://localhost:8000\") | .public_url" > temp_ngrok_url.txt

REM Check if we got a URL
if exist temp_ngrok_url.txt (
    set /p NGROK_URL=<temp_ngrok_url.txt
    if not "!NGROK_URL!"=="" (
        echo.
        echo ✅ ngrok tunnel is running!
        echo.
        echo 📋 YOUR WEBHOOK URLs:
        echo ================================================
        echo.
        echo 📱 WhatsApp Business API:
        echo    !NGROK_URL!/webhook/whatsapp
        echo.
        echo 💳 Payment Gateway:
        echo    !NGROK_URL!/webhook/payment
        echo.
        echo 💰 Razorpay:
        echo    !NGROK_URL!/webhook/razorpay
        echo.
        echo 🔧 General Webhook:
        echo    !NGROK_URL!/webhook/general
        echo.
        echo 🧪 Test Endpoint:
        echo    !NGROK_URL!/webhook/test
        echo.
        echo ================================================
        echo.
        echo 💡 Copy these URLs to configure webhooks in external services
        echo 🔄 This terminal will remain open to keep the tunnel active
        echo 📊 Access ngrok dashboard at: http://127.0.0.1:4040
        echo.
        echo ⚠️  DO NOT CLOSE THIS WINDOW - it will stop the tunnel
        echo.
        
        REM Run webhook tests
        echo 🧪 Would you like to test the webhooks now? (y/n)
        set /p choice=
        if /i "!choice!"=="y" (
            echo.
            echo 🚀 Running webhook tests...
            php test_webhook_complete.php !NGROK_URL!
        )
        
        echo.
        echo Press any key to stop ngrok and exit...
        pause >nul
        
        REM Stop ngrok
        taskkill /f /im ngrok.exe >nul 2>&1
        echo 🛑 ngrok tunnel stopped.
    ) else (
        echo ❌ Failed to get ngrok URL
    )
    del temp_ngrok_url.txt >nul 2>&1
) else (
    echo ❌ Failed to connect to ngrok API
)

echo.
echo Press any key to exit...
pause >nul
