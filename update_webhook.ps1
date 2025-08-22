# PowerShell script to get ngrok URL and update .env
Write-Host "🔍 Getting ngrok URL..." -ForegroundColor Yellow

try {
    # Get ngrok tunnels
    $response = Invoke-RestMethod -Uri "http://127.0.0.1:4040/api/tunnels"
    $httpsUrl = $response.tunnels | Where-Object { $_.proto -eq "https" } | Select-Object -ExpandProperty public_url -First 1
    
    if ($httpsUrl) {
        $webhookUrl = "$httpsUrl/api/webhook/whatsapp"
        Write-Host "✅ ngrok URL found: $httpsUrl" -ForegroundColor Green
        Write-Host "🔄 Webhook URL: $webhookUrl" -ForegroundColor Cyan
        
        # Read .env content
        $envContent = Get-Content .env
        $newEnvContent = @()
        
        foreach ($line in $envContent) {
            if ($line -match "^WHATSAPP_WEBHOOK_URL=") {
                $newEnvContent += "WHATSAPP_WEBHOOK_URL=$webhookUrl"
                Write-Host "📝 Updated webhook URL in .env" -ForegroundColor Green
            } else {
                $newEnvContent += $line
            }
        }
        
        # Write back to .env
        $newEnvContent | Out-File -FilePath .env -Encoding UTF8
        
        Write-Host ""
        Write-Host "✅ Setup Complete!" -ForegroundColor Green
        Write-Host "🌐 Your webhook URL: $webhookUrl" -ForegroundColor White -BackgroundColor Blue
        Write-Host "🔑 Verify Token: FreeDoctor2025SecureToken" -ForegroundColor White -BackgroundColor Blue
        Write-Host ""
        Write-Host "📋 Next Steps:" -ForegroundColor Yellow
        Write-Host "1. Copy the webhook URL above"
        Write-Host "2. Go to Meta Business Manager"
        Write-Host "3. Update your WhatsApp webhook URL"
        Write-Host "4. Test by sending a message to your WhatsApp number"
        
    } else {
        Write-Host "❌ No HTTPS tunnel found. Make sure ngrok is running." -ForegroundColor Red
    }
} catch {
    Write-Host "❌ Could not connect to ngrok. Make sure it's running on port 4040." -ForegroundColor Red
    Write-Host "Error: $($_.Exception.Message)" -ForegroundColor Red
}
