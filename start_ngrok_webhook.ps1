# PowerShell script to start ngrok and display webhook URLs
Write-Host "🚀 Starting ngrok tunnel for FreeDoctor webhooks..." -ForegroundColor Green
Write-Host ""

# Kill any existing ngrok processes
try {
    Stop-Process -Name "ngrok" -Force -ErrorAction SilentlyContinue
    Start-Sleep -Seconds 2
    Write-Host "✅ Cleaned up existing ngrok processes" -ForegroundColor Yellow
} catch {
    # Ignore errors
}

# Start ngrok
Write-Host "🌐 Starting ngrok tunnel..." -ForegroundColor Cyan
Start-Process -FilePath "ngrok" -ArgumentList "http 8000" -WindowStyle Hidden

# Wait for ngrok to initialize
Write-Host "⏳ Waiting for ngrok to initialize..." -ForegroundColor Yellow
Start-Sleep -Seconds 6

# Try to get tunnel information
try {
    $response = Invoke-RestMethod -Uri "http://127.0.0.1:4040/api/tunnels" -Method Get -ErrorAction Stop
    $tunnel = $response.tunnels | Where-Object { $_.config.addr -eq "http://localhost:8000" } | Select-Object -First 1
    
    if ($tunnel) {
        $ngrokUrl = $tunnel.public_url
        
        Write-Host ""
        Write-Host "✅ ngrok tunnel is running!" -ForegroundColor Green
        Write-Host ""
        Write-Host "📋 YOUR WEBHOOK URLs:" -ForegroundColor White -BackgroundColor DarkBlue
        Write-Host "================================================" -ForegroundColor Blue
        Write-Host ""
        Write-Host "📱 WhatsApp Business API:" -ForegroundColor Yellow
        Write-Host "   $ngrokUrl/webhook/whatsapp" -ForegroundColor White
        Write-Host ""
        Write-Host "💳 Payment Gateway:" -ForegroundColor Yellow  
        Write-Host "   $ngrokUrl/webhook/payment" -ForegroundColor White
        Write-Host ""
        Write-Host "💰 Razorpay:" -ForegroundColor Yellow
        Write-Host "   $ngrokUrl/webhook/razorpay" -ForegroundColor White
        Write-Host ""
        Write-Host "🔧 General Webhook:" -ForegroundColor Yellow
        Write-Host "   $ngrokUrl/webhook/general" -ForegroundColor White
        Write-Host ""
        Write-Host "🧪 Test Endpoint:" -ForegroundColor Yellow
        Write-Host "   $ngrokUrl/webhook/test" -ForegroundColor White
        Write-Host ""
        Write-Host "================================================" -ForegroundColor Blue
        Write-Host ""
        Write-Host "💡 Copy these URLs to configure webhooks in external services" -ForegroundColor Cyan
        Write-Host "🔄 This session will remain open to keep the tunnel active" -ForegroundColor Green
        Write-Host "📊 Access ngrok dashboard at: http://127.0.0.1:4040" -ForegroundColor Magenta
        Write-Host ""
        Write-Host "⚠️  DO NOT CLOSE THIS WINDOW - it will stop the tunnel" -ForegroundColor Red -BackgroundColor Yellow
        Write-Host ""
        
        # Ask if user wants to test webhooks
        $testChoice = Read-Host "🧪 Would you like to test the webhooks now? (y/n)"
        if ($testChoice -eq "y" -or $testChoice -eq "Y") {
            Write-Host ""
            Write-Host "🚀 Running webhook tests..." -ForegroundColor Green
            & php test_webhook_complete.php $ngrokUrl
        }
        
        Write-Host ""
        Write-Host "Press any key to stop ngrok and exit..." -ForegroundColor Yellow
        $null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
        
        # Stop ngrok
        Stop-Process -Name "ngrok" -Force -ErrorAction SilentlyContinue
        Write-Host "🛑 ngrok tunnel stopped." -ForegroundColor Red
        
    } else {
        Write-Host "❌ No tunnel found for localhost:8000" -ForegroundColor Red
    }
    
} catch {
    Write-Host "❌ Failed to connect to ngrok API: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host "Please make sure ngrok is installed and running" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "Press any key to exit..." -ForegroundColor Gray
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
