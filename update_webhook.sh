#!/bin/bash

# Get ngrok URL and update .env file
echo "🔍 Checking ngrok status..."

# Wait for ngrok to start
sleep 5

# Get the HTTPS URL from ngrok
NGROK_URL=$(curl -s http://127.0.0.1:4040/api/tunnels | jq -r '.tunnels[] | select(.proto=="https") | .public_url')

if [ ! -z "$NGROK_URL" ]; then
    echo "✅ ngrok URL found: $NGROK_URL"
    
    # Update the .env file
    WEBHOOK_URL="$NGROK_URL/api/webhook/whatsapp"
    echo "🔄 Updating webhook URL to: $WEBHOOK_URL"
    
    # Update .env file
    sed -i "s|WHATSAPP_WEBHOOK_URL=.*|WHATSAPP_WEBHOOK_URL=$WEBHOOK_URL|g" .env
    
    echo "✅ .env file updated!"
    echo "🌐 Your webhook URL is: $WEBHOOK_URL"
    echo ""
    echo "📋 Next steps:"
    echo "1. Copy this URL: $WEBHOOK_URL"
    echo "2. Go to Meta Business Manager"
    echo "3. Update your webhook URL"
    echo "4. Use verify token: FreeDoctor2025SecureToken"
else
    echo "❌ Could not get ngrok URL. Make sure ngrok is running."
fi
