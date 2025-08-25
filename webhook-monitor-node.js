const express = require('express');
const axios = require('axios');
const cors = require('cors');
const bodyParser = require('body-parser');
const fs = require('fs');
const path = require('path');

const app = express();
const PORT = process.env.NODE_PORT || 3000;

// Middleware
app.use(cors());
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));
app.use(express.static('public'));

// Configuration
const CONFIG = {
    WEBHOOK_VERIFY_TOKEN: 'FreeDoctor2025SecureToken',
    LARAVEL_BASE_URL: 'https://freedoctor.in',
    WEBHOOK_MONITOR_URL: 'https://freedoctor.in/admin/webhook/monitor',
    PHONE_NUMBER: '+918519931876',
    LOG_FILE: path.join(__dirname, 'node_webhook.log')
};

// Utility function to log messages
function logMessage(message, data = null) {
    const timestamp = new Date().toISOString();
    const logEntry = `[${timestamp}] ${message}${data ? '\nData: ' + JSON.stringify(data, null, 2) : ''}\n\n`;
    
    console.log(logEntry);
    fs.appendFileSync(CONFIG.LOG_FILE, logEntry);
}

// WhatsApp Webhook Handler (Node.js version)
app.get('/webhook/whatsapp', (req, res) => {
    const mode = req.query['hub.mode'];
    const token = req.query['hub.verify_token'];
    const challenge = req.query['hub.challenge'];
    
    logMessage('📋 Node.js Webhook Verification Attempt', {
        mode,
        token: token ? 'PROVIDED' : 'MISSING',
        challenge: challenge ? 'PROVIDED' : 'MISSING'
    });
    
    if (mode === 'subscribe' && token === CONFIG.WEBHOOK_VERIFY_TOKEN) {
        logMessage('✅ Node.js Webhook Verification Successful', { challenge });
        return res.status(200).send(challenge);
    }
    
    logMessage('❌ Node.js Webhook Verification Failed');
    return res.status(403).json({ error: 'Verification failed' });
});

app.post('/webhook/whatsapp', (req, res) => {
    logMessage('📨 Node.js Webhook POST Request Received', req.body);
    
    try {
        const webhookData = req.body;
        
        // Process WhatsApp v23.0 webhook data
        if (webhookData.entry && webhookData.entry[0]) {
            const entry = webhookData.entry[0];
            const changes = entry.changes[0];
            const value = changes.value;
            const messages = value.messages || [];
            const contacts = value.contacts || [];
            
            logMessage('📊 Node.js Processing WhatsApp Data', {
                messagesCount: messages.length,
                contactsCount: contacts.length,
                field: changes.field
            });
            
            // Process each message
            messages.forEach((message, index) => {
                processMessage(message, contacts, index);
            });
            
            // Forward to Laravel webhook (optional)
            forwardToLaravel(webhookData);
        }
        
        res.status(200).send('EVENT_RECEIVED');
    } catch (error) {
        logMessage('❌ Node.js Webhook Error', { error: error.message });
        res.status(500).json({ error: 'Internal server error' });
    }
});

function processMessage(message, contacts, index) {
    const from = message.from;
    const type = message.type;
    const id = message.id;
    const timestamp = message.timestamp;
    
    let content = '';
    
    switch (type) {
        case 'text':
            content = message.text.body;
            break;
        case 'image':
            content = `[Image] ${message.image.caption || ''}`;
            break;
        case 'location':
            content = `[Location] ${message.location.latitude}, ${message.location.longitude}`;
            break;
        default:
            content = `[${type} message]`;
    }
    
    // Find contact name
    const contact = contacts.find(c => c.wa_id === from);
    const contactName = contact ? contact.profile.name : 'Unknown';
    
    logMessage(`💬 Node.js Message #${index + 1} Processed`, {
        from,
        contactName,
        type,
        content,
        id,
        timestamp
    });
    
    // Store in local database/file (you can extend this)
    storeMessage({
        from,
        contactName,
        type,
        content,
        id,
        timestamp,
        processed_at: new Date().toISOString()
    });
}

function storeMessage(messageData) {
    const messagesFile = path.join(__dirname, 'received_messages.json');
    
    let messages = [];
    if (fs.existsSync(messagesFile)) {
        try {
            messages = JSON.parse(fs.readFileSync(messagesFile, 'utf8'));
        } catch (error) {
            logMessage('⚠️ Error reading messages file', { error: error.message });
        }
    }
    
    messages.unshift(messageData); // Add to beginning
    messages = messages.slice(0, 100); // Keep only last 100 messages
    
    fs.writeFileSync(messagesFile, JSON.stringify(messages, null, 2));
    logMessage('💾 Message stored in Node.js database');
}

async function forwardToLaravel(webhookData) {
    try {
        const response = await axios.post('https://freedoctor.in/webhook/whatsapp', webhookData, {
            headers: {
                'Content-Type': 'application/json',
                'User-Agent': 'Node.js-Forwarder/1.0'
            },
            timeout: 10000
        });
        
        logMessage('🔄 Forwarded to Laravel webhook', {
            status: response.status,
            response: response.data
        });
    } catch (error) {
        logMessage('❌ Error forwarding to Laravel', {
            error: error.message,
            status: error.response?.status
        });
    }
}

// API endpoint to get received messages
app.get('/api/messages', (req, res) => {
    const messagesFile = path.join(__dirname, 'received_messages.json');
    
    if (fs.existsSync(messagesFile)) {
        try {
            const messages = JSON.parse(fs.readFileSync(messagesFile, 'utf8'));
            res.json({
                success: true,
                messages,
                count: messages.length,
                last_updated: new Date().toISOString()
            });
        } catch (error) {
            res.status(500).json({ error: 'Error reading messages' });
        }
    } else {
        res.json({
            success: true,
            messages: [],
            count: 0,
            last_updated: new Date().toISOString()
        });
    }
});

// API endpoint to check Laravel webhook monitor
app.get('/api/check-laravel-monitor', async (req, res) => {
    try {
        const response = await axios.get(CONFIG.WEBHOOK_MONITOR_URL, {
            timeout: 10000,
            headers: {
                'User-Agent': 'Node.js-Monitor-Checker/1.0'
            }
        });
        
        res.json({
            success: true,
            status: response.status,
            accessible: true,
            url: CONFIG.WEBHOOK_MONITOR_URL,
            checked_at: new Date().toISOString()
        });
    } catch (error) {
        res.json({
            success: false,
            error: error.message,
            status: error.response?.status || 0,
            accessible: false,
            url: CONFIG.WEBHOOK_MONITOR_URL,
            checked_at: new Date().toISOString()
        });
    }
});

// API endpoint to send test message to Laravel
app.post('/api/send-test-message', async (req, res) => {
    try {
        const { phone_number, message } = req.body;
        
        const response = await axios.post('https://freedoctor.in/admin/webhook/send-test-message', {
            phone_number: phone_number || CONFIG.PHONE_NUMBER,
            message: message || 'Test from Node.js server'
        }, {
            headers: {
                'Content-Type': 'application/json',
                'User-Agent': 'Node.js-Test-Sender/1.0'
            },
            timeout: 10000
        });
        
        logMessage('📤 Test message sent via Laravel', {
            phone_number,
            message,
            response: response.data
        });
        
        res.json({
            success: true,
            response: response.data,
            sent_at: new Date().toISOString()
        });
    } catch (error) {
        logMessage('❌ Error sending test message via Laravel', {
            error: error.message
        });
        
        res.status(500).json({
            success: false,
            error: error.message,
            sent_at: new Date().toISOString()
        });
    }
});

// Dashboard route
app.get('/', (req, res) => {
    res.send(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Node.js WhatsApp Webhook Monitor</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
                .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
                .btn { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; margin: 5px; }
                .btn:hover { background: #0056b3; }
                .section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
                .code { background: #f8f9fa; padding: 10px; border-radius: 4px; font-family: monospace; }
                .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
                .status { padding: 10px; border-radius: 4px; margin: 10px 0; }
                .success { background: #d4edda; border: 1px solid #c3e6cb; }
                .error { background: #f8d7da; border: 1px solid #f5c6cb; }
                .info { background: #d1ecf1; border: 1px solid #bee5eb; }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>🚀 Node.js WhatsApp Webhook Monitor</h1>
                
                <div class="section">
                    <h2>📊 Server Status</h2>
                    <div class="status success">
                        ✅ Node.js Server Running on Port ${PORT}
                    </div>
                    <div class="code">
                        <strong>Webhook URL:</strong> http://localhost:${PORT}/webhook/whatsapp<br>
                        <strong>Laravel Monitor:</strong> ${CONFIG.WEBHOOK_MONITOR_URL}<br>
                        <strong>Test Phone:</strong> ${CONFIG.PHONE_NUMBER}
                    </div>
                </div>

                <div class="grid">
                    <div class="section">
                        <h3>🔗 Quick Actions</h3>
                        <button class="btn" onclick="checkLaravelMonitor()">Check Laravel Monitor</button><br>
                        <button class="btn" onclick="loadMessages()">Load Messages</button><br>
                        <button class="btn" onclick="sendTestMessage()">Send Test Message</button><br>
                        <button class="btn" onclick="viewLogs()">View Logs</button>
                    </div>
                    
                    <div class="section">
                        <h3>📱 Test Webhook</h3>
                        <div class="code">
                            <a href="/webhook/whatsapp?hub_mode=subscribe&hub_verify_token=${CONFIG.WEBHOOK_VERIFY_TOKEN}&hub_challenge=TEST123" target="_blank">
                                Test Verification
                            </a>
                        </div>
                    </div>
                </div>

                <div class="section">
                    <h3>📨 Recent Messages</h3>
                    <div id="messages">Loading messages...</div>
                </div>

                <div class="section">
                    <h3>📋 Response Log</h3>
                    <div id="responseLog" class="code">Responses will appear here...</div>
                </div>
            </div>

            <script>
                async function checkLaravelMonitor() {
                    try {
                        const response = await fetch('/api/check-laravel-monitor');
                        const data = await response.json();
                        
                        document.getElementById('responseLog').innerHTML = 
                            'Laravel Monitor Check: ' + JSON.stringify(data, null, 2);
                        
                        if (data.accessible) {
                            window.open('${CONFIG.WEBHOOK_MONITOR_URL}', '_blank');
                        }
                    } catch (error) {
                        document.getElementById('responseLog').innerHTML = 'Error: ' + error.message;
                    }
                }

                async function loadMessages() {
                    try {
                        const response = await fetch('/api/messages');
                        const data = await response.json();
                        
                        let html = '<h4>Messages (' + data.count + ')</h4>';
                        data.messages.forEach(msg => {
                            html += \`
                                <div class="status info">
                                    <strong>\${msg.from}</strong> (\${msg.contactName})<br>
                                    <strong>Type:</strong> \${msg.type}<br>
                                    <strong>Content:</strong> \${msg.content}<br>
                                    <small>Time: \${msg.processed_at}</small>
                                </div>
                            \`;
                        });
                        
                        document.getElementById('messages').innerHTML = html;
                    } catch (error) {
                        document.getElementById('messages').innerHTML = 'Error loading messages: ' + error.message;
                    }
                }

                async function sendTestMessage() {
                    try {
                        const response = await fetch('/api/send-test-message', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                phone_number: '${CONFIG.PHONE_NUMBER}',
                                message: 'Test message from Node.js at ' + new Date().toLocaleString()
                            })
                        });
                        
                        const data = await response.json();
                        document.getElementById('responseLog').innerHTML = 
                            'Test Message Response: ' + JSON.stringify(data, null, 2);
                    } catch (error) {
                        document.getElementById('responseLog').innerHTML = 'Error: ' + error.message;
                    }
                }

                function viewLogs() {
                    window.open('/api/logs', '_blank');
                }

                // Auto-load messages on page load
                loadMessages();
                
                // Auto-refresh messages every 30 seconds
                setInterval(loadMessages, 30000);
            </script>
        </body>
        </html>
    `);
});

// Start server
app.listen(PORT, () => {
    logMessage(`🚀 Node.js WhatsApp Webhook Server started on port ${PORT}`);
    logMessage(`📋 Webhook URL: http://localhost:${PORT}/webhook/whatsapp`);
    logMessage(`🔗 Dashboard: http://localhost:${PORT}`);
    logMessage(`📊 Laravel Monitor: ${CONFIG.WEBHOOK_MONITOR_URL}`);
});

// Error handling
process.on('uncaughtException', (error) => {
    logMessage('❌ Uncaught Exception', { error: error.message, stack: error.stack });
});

process.on('unhandledRejection', (reason, promise) => {
    logMessage('❌ Unhandled Rejection', { reason, promise });
});
