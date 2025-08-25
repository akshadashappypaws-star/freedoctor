<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhatsApp v23.0 Webhook Simulator</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .btn { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; margin: 5px; }
        .btn:hover { background: #0056b3; }
        .btn.success { background: #28a745; }
        .btn.warning { background: #ffc107; color: #000; }
        .code { background: #f8f9fa; padding: 15px; border-radius: 4px; font-family: monospace; overflow-x: auto; white-space: pre-wrap; }
        .result { margin: 10px 0; padding: 10px; border-radius: 4px; }
        .result.success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .result.error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        textarea { width: 100%; height: 300px; font-family: monospace; font-size: 12px; }
        input[type="text"] { width: 100%; padding: 8px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px; }
        .status-box { padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid; }
        .info { background: #d1ecf1; border-color: #17a2b8; }
        .warning { background: #fff3cd; border-color: #ffc107; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 WhatsApp v23.0 Webhook Simulator</h1>
        
        <div class="status-box info">
            <strong>Testing with your configuration:</strong><br>
            Phone: +918519931876<br>
            API Version: v23.0<br>
            Webhook URL: https://freedoctor.in/webhook/whatsapp
        </div>

        <div class="grid">
            <div class="section">
                <h2>📱 Send Test Message</h2>
                
                <label>Phone Number:</label>
                <input type="text" id="phoneNumber" value="+918519931876" />
                
                <label>Message:</label>
                <input type="text" id="messageText" value="Hello! Testing v23.0 webhook" />
                
                <label>Webhook URL:</label>
                <input type="text" id="webhookUrl" value="https://freedoctor.in/webhook/whatsapp" />
                
                <div style="margin: 15px 0;">
                    <button class="btn success" onclick="simulateIncomingMessage()">📨 Simulate Incoming Message</button>
                    <button class="btn warning" onclick="testVerification()">🔐 Test Verification</button>
                    <button class="btn" onclick="sendRealMessage()">📤 Send Real Message</button>
                </div>
                
                <div id="result"></div>
            </div>
            
            <div class="section">
                <h2>📋 v23.0 Webhook Payload</h2>
                <p>This is the exact structure WhatsApp v23.0 sends:</p>
                <textarea id="webhookPayload" readonly></textarea>
                <button class="btn" onclick="updatePayload()">🔄 Update Payload</button>
            </div>
        </div>

        <div class="section">
            <h2>🔍 Webhook Response</h2>
            <div id="webhookResponse" class="code">Response will appear here...</div>
        </div>

        <div class="section">
            <h2>📊 Test Results</h2>
            <div id="testResults"></div>
        </div>
    </div>

    <script>
        // WhatsApp v23.0 webhook payload template
        const webhookTemplate = {
            "object": "whatsapp_business_account",
            "entry": [
                {
                    "id": "745838968612692", // Your business account ID
                    "changes": [
                        {
                            "value": {
                                "messaging_product": "whatsapp",
                                "metadata": {
                                    "display_phone_number": "+918519931876",
                                    "phone_number_id": "745838968612692" // Your phone number ID
                                },
                                "contacts": [
                                    {
                                        "profile": {
                                            "name": "Test User"
                                        },
                                        "wa_id": "918519931876"
                                    }
                                ],
                                "messages": [
                                    {
                                        "from": "918519931876",
                                        "id": "wamid.test." + Date.now(),
                                        "timestamp": Math.floor(Date.now() / 1000).toString(),
                                        "text": {
                                            "body": "Hello! Testing v23.0 webhook"
                                        },
                                        "type": "text"
                                    }
                                ]
                            },
                            "field": "messages"
                        }
                    ]
                }
            ]
        };

        function updatePayload() {
            const phone = document.getElementById('phoneNumber').value.replace(/[^0-9]/g, '');
            const message = document.getElementById('messageText').value;
            
            // Update the payload with current values
            webhookTemplate.entry[0].changes[0].value.contacts[0].wa_id = phone;
            webhookTemplate.entry[0].changes[0].value.messages[0].from = phone;
            webhookTemplate.entry[0].changes[0].value.messages[0].text.body = message;
            webhookTemplate.entry[0].changes[0].value.messages[0].id = "wamid.test." + Date.now();
            webhookTemplate.entry[0].changes[0].value.messages[0].timestamp = Math.floor(Date.now() / 1000).toString();
            
            document.getElementById('webhookPayload').value = JSON.stringify(webhookTemplate, null, 2);
        }

        function simulateIncomingMessage() {
            updatePayload();
            const webhookUrl = document.getElementById('webhookUrl').value;
            const payload = JSON.parse(document.getElementById('webhookPayload').value);
            
            document.getElementById('result').innerHTML = '<div class="result">🔄 Sending webhook simulation...</div>';
            
            fetch(webhookUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'User-Agent': 'Meta-WhatsApp/1.0'
                },
                body: JSON.stringify(payload)
            })
            .then(response => {
                const isSuccess = response.ok;
                return response.text().then(text => ({
                    status: response.status,
                    statusText: response.statusText,
                    body: text,
                    success: isSuccess
                }));
            })
            .then(result => {
                const resultClass = result.success ? 'success' : 'error';
                const icon = result.success ? '✅' : '❌';
                
                document.getElementById('result').innerHTML = `
                    <div class="result ${resultClass}">
                        ${icon} Webhook Response: ${result.status} ${result.statusText}<br>
                        Body: ${result.body}
                    </div>
                `;
                
                document.getElementById('webhookResponse').textContent = `
Status: ${result.status} ${result.statusText}
Body: ${result.body}
Timestamp: ${new Date().toISOString()}
                `;
                
                // Log test result
                logTestResult('Webhook Simulation', result.success, `${result.status}: ${result.body}`);
            })
            .catch(error => {
                document.getElementById('result').innerHTML = `
                    <div class="result error">❌ Error: ${error.message}</div>
                `;
                document.getElementById('webhookResponse').textContent = `Error: ${error.message}`;
                logTestResult('Webhook Simulation', false, error.message);
            });
        }

        function testVerification() {
            const webhookUrl = document.getElementById('webhookUrl').value;
            const verifyUrl = `${webhookUrl}?hub_mode=subscribe&hub_verify_token=FreeDoctor2025SecureToken&hub_challenge=TEST_v23_${Date.now()}`;
            
            document.getElementById('result').innerHTML = '<div class="result">🔐 Testing webhook verification...</div>';
            
            fetch(verifyUrl)
            .then(response => response.text())
            .then(challenge => {
                const success = challenge.includes('TEST_v23_');
                const resultClass = success ? 'success' : 'error';
                const icon = success ? '✅' : '❌';
                
                document.getElementById('result').innerHTML = `
                    <div class="result ${resultClass}">
                        ${icon} Verification Response: ${challenge}
                    </div>
                `;
                
                logTestResult('Webhook Verification', success, challenge);
            })
            .catch(error => {
                document.getElementById('result').innerHTML = `
                    <div class="result error">❌ Verification Error: ${error.message}</div>
                `;
                logTestResult('Webhook Verification', false, error.message);
            });
        }

        function sendRealMessage() {
            alert('🚀 To send a real message:\n\n1. Open WhatsApp\n2. Send a message to your business number\n3. Check the webhook monitor at /admin/webhook/monitor\n4. Look for the message in "Received Messages" tab');
        }

        function logTestResult(testType, success, details) {
            const resultsDiv = document.getElementById('testResults');
            const timestamp = new Date().toLocaleString();
            const icon = success ? '✅' : '❌';
            const statusClass = success ? 'success' : 'error';
            
            const resultHtml = `
                <div class="result ${statusClass}">
                    ${icon} <strong>${testType}</strong> - ${timestamp}<br>
                    Details: ${details}
                </div>
            `;
            
            resultsDiv.innerHTML = resultHtml + resultsDiv.innerHTML;
        }

        // Initialize payload on page load
        window.onload = function() {
            updatePayload();
        };
    </script>
</body>
</html>
