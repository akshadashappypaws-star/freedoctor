## STEP-BY-STEP NGROK TROUBLESHOOTING

### Step 1: Get Current ngrok URL
1. Open http://localhost:4040 in your browser
2. Look for the HTTPS URL (something like https://xxxx.ngrok-free.app)
3. Copy the HTTPS URL (not HTTP)

### Step 2: Test the ngrok URL
Open this URL in a new browser tab:
```
https://YOUR_NGROK_URL/api/webhook/whatsapp?hub.mode=subscribe&hub.verify_token=FreeDoctor2025SecureToken&hub.challenge=test123
```

Expected response: `test123`

### Step 3: Handle ngrok Browser Warning
If you see an ngrok warning page saying "Visit Site" or "Only visit this website if you trust the sender":
1. Click "Visit Site" button
2. This is normal for free ngrok accounts
3. Meta webhooks will work even with this warning

### Step 4: Update .env File
Update line 97 in your .env file:
```
WHATSAPP_WEBHOOK_URL=https://YOUR_NEW_NGROK_URL/api/webhook/whatsapp
```

### Step 5: Update Meta Business Manager
1. Go to Meta Business Manager
2. WhatsApp → Configuration → Webhooks
3. Webhook URL: https://YOUR_NEW_NGROK_URL/api/webhook/whatsapp
4. Verify Token: FreeDoctor2025SecureToken
5. Click "Verify and Save"

### Step 6: Monitor ngrok Dashboard
1. Keep http://localhost:4040 open
2. Click "Verify and Save" in Meta
3. You should see a GET request appear immediately
4. If you don't see ANY request, the URL is wrong or not accessible

## TROUBLESHOOTING CHECKLIST:

- [ ] ngrok is running (Process visible in Task Manager)
- [ ] ngrok dashboard accessible at http://localhost:4040
- [ ] HTTPS URL copied correctly (not HTTP)
- [ ] Laravel server running on port 8000
- [ ] URL responds with "test123" when tested
- [ ] .env file updated with new URL
- [ ] Meta webhook configured with exact URL
- [ ] Clicked through ngrok browser warning if present
