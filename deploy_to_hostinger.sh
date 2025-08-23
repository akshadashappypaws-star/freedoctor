#!/bin/bash

echo "=== Deploying to Hostinger ==="

# Create deployment package
echo "Creating deployment package..."
zip -r freedoctor-production.zip . -x "*.git*" "node_modules/*" "vendor/*" "storage/logs/*"

echo "Package created: freedoctor-production.zip"
echo ""
echo "NEXT STEPS:"
echo "1. Upload freedoctor-production.zip to your Hostinger File Manager"
echo "2. Extract it in public_html/"
echo "3. Update .env with Hostinger database settings"
echo "4. Run: php artisan migrate"
echo "5. Test webhook: https://postauto.shop/api/webhook/whatsapp?hub.mode=subscribe&hub.verify_token=FreeDoctor2025SecureToken&hub.challenge=test123"
echo "6. Use this URL in Meta: https://postauto.shop/api/webhook/whatsapp"
echo ""
echo "✅ No more expiring URLs!"
