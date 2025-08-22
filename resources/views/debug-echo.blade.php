<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Echo Debug Test</title>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .status { padding: 10px; margin: 10px 0; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        .debug { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
        button { padding: 10px 15px; margin: 5px; border: none; border-radius: 5px; cursor: pointer; }
        .btn-primary { background: #007bff; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-test { background: #17a2b8; color: white; }
        #logs { height: 300px; overflow-y: scroll; background: #000; color: #00ff00; padding: 10px; font-family: monospace; }
    </style>
</head>
<body>
    <h1>🔧 Echo Real-time Notification Debug Test</h1>
    
    <div id="status-pusher" class="status info">⏳ Checking Pusher availability...</div>
    <div id="status-echo" class="status info">⏳ Checking Echo availability...</div>
    <div id="status-initialization" class="status info">⏳ Initializing Echo...</div>
    
    <div style="margin: 20px 0;">
        <button id="test-doctor-notification" class="btn-test">📱 Test Doctor Notification</button>
        <button id="test-user-notification" class="btn-test">👤 Test User Notification</button>
        <button id="test-admin-notification" class="btn-test">⚙️ Test Admin Notification</button>
        <button id="clear-logs" class="btn-primary">🗑️ Clear Logs</button>
    </div>
    
    <h3>🔍 Debug Console:</h3>
    <div id="logs"></div>
    
    <script>
        const log = (message, type = 'info') => {
            const timestamp = new Date().toLocaleTimeString();
            const logs = document.getElementById('logs');
            const color = type === 'error' ? '#ff4444' : type === 'success' ? '#44ff44' : '#00ff00';
            logs.innerHTML += `<div style="color: ${color}">[${timestamp}] ${message}</div>`;
            logs.scrollTop = logs.scrollHeight;
            console.log(`[Echo Debug] ${message}`);
        };
        
        // Check Pusher availability
        if (typeof Pusher !== 'undefined') {
            document.getElementById('status-pusher').className = 'status success';
            document.getElementById('status-pusher').textContent = '✅ Pusher loaded successfully';
            log('✅ Pusher is available', 'success');
        } else {
            document.getElementById('status-pusher').className = 'status error';
            document.getElementById('status-pusher').textContent = '❌ Pusher not available';
            log('❌ Pusher is NOT available', 'error');
        }
        
        // Check Echo availability
        if (typeof Echo !== 'undefined') {
            document.getElementById('status-echo').className = 'status success';
            document.getElementById('status-echo').textContent = '✅ Echo loaded successfully';
            log('✅ Echo is available', 'success');
        } else {
            document.getElementById('status-echo').className = 'status error';
            document.getElementById('status-echo').textContent = '❌ Echo not available';
            log('❌ Echo is NOT available', 'error');
        }
        
        // Initialize Echo
        let echoInstance = null;
        try {
            if (typeof window.Echo !== 'undefined' && typeof window.Pusher !== 'undefined') {
                // Echo is already available globally
                echoInstance = window.Echo;
                log('✅ Using global Echo instance');
            } else if (typeof window.Pusher !== 'undefined') {
                // Create custom Echo instance
                log('🔧 Creating custom Echo instance...');
                
                echoInstance = {
                    pusher: new window.Pusher('{{ config("broadcasting.connections.pusher.key") }}', {
                        cluster: '{{ config("broadcasting.connections.pusher.options.cluster") }}',
                        forceTLS: true,
                        encrypted: true
                    }),
                    channel: function(channelName) {
                        const channel = this.pusher.subscribe(channelName);
                        return {
                            listen: function(eventName, callback) {
                                const cleanEventName = eventName.startsWith('.') ? eventName.substring(1) : eventName;
                                channel.bind(cleanEventName, callback);
                                return this;
                            }
                        };
                    }
                };
                
                window.Echo = echoInstance;
                log('✅ Custom Echo instance created');
            } else {
                throw new Error('Pusher not available');
            }
                
            document.getElementById('status-initialization').className = 'status success';
            document.getElementById('status-initialization').textContent = '✅ Echo initialized successfully!';
            log('✅ Echo initialized successfully with Pusher config', 'success');
            log(`📡 Pusher Key: {{ config("broadcasting.connections.pusher.key") }}`);
            log(`🌐 Pusher Cluster: {{ config("broadcasting.connections.pusher.options.cluster") }}`);
            
            window.EchoDebug = echoInstance;
        } catch (error) {
            document.getElementById('status-initialization').className = 'status error';
            document.getElementById('status-initialization').textContent = '❌ Echo initialization failed: ' + error.message;
            log('❌ Echo initialization failed: ' + error.message, 'error');
        }
        
        // Test buttons
        document.getElementById('test-doctor-notification').addEventListener('click', () => {
            log('🧪 Testing doctor notification...');
            fetch('/test/notifications/business-request')
                .then(response => response.json())
                .then(data => {
                    log('✅ Doctor notification test sent: ' + JSON.stringify(data), 'success');
                })
                .catch(error => {
                    log('❌ Doctor notification test failed: ' + error.message, 'error');
                });
        });
        
        document.getElementById('test-user-notification').addEventListener('click', () => {
            log('🧪 Testing user notification...');
            fetch('/test/notifications/proposal-approved')
                .then(response => response.json())
                .then(data => {
                    log('✅ User notification test sent: ' + JSON.stringify(data), 'success');
                })
                .catch(error => {
                    log('❌ User notification test failed: ' + error.message, 'error');
                });
        });
        
        document.getElementById('test-admin-notification').addEventListener('click', () => {
            log('🧪 Testing admin notification...');
            fetch('/test/notifications/admin-proposal')
                .then(response => response.json())
                .then(data => {
                    log('✅ Admin notification test sent: ' + JSON.stringify(data), 'success');
                })
                .catch(error => {
                    log('❌ Admin notification test failed: ' + error.message, 'error');
                });
        });
        
        document.getElementById('clear-logs').addEventListener('click', () => {
            document.getElementById('logs').innerHTML = '';
            log('🗑️ Logs cleared');
        });
        
        // Listen for Echo events if available
        if (echoInstance) {
            try {
                // Test listening to a general channel
                log('👂 Setting up Echo event listeners...');
                
                // You can add specific channel listeners here for testing
                // Example: echoInstance.channel('test').listen('.test-event', (data) => {
                //     log('📨 Received test event: ' + JSON.stringify(data), 'success');
                // });
                
            } catch (error) {
                log('❌ Failed to set up Echo listeners: ' + error.message, 'error');
            }
        }
        
        log('🚀 Echo Debug Test initialized');
        log('📝 Broadcasting Driver: {{ config("broadcasting.default") }}');
        log('🔧 Environment: {{ config("app.env") }}');
    </script>
</body>
</html>
