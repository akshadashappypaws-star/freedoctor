<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhatsApp Bot - Real-time Message Monitor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .monitor-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 15px;
        }

        .monitor-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .console-panel {
            background: #1a1a1a;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border: 1px solid #333;
        }

        .console-header {
            background: linear-gradient(45deg, #2d3748, #4a5568);
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .console-body {
            height: 500px;
            overflow-y: auto;
            padding: 20px;
            background: #0d1117;
            color: #c9d1d9;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.6;
        }

        .console-body::-webkit-scrollbar {
            width: 8px;
        }

        .console-body::-webkit-scrollbar-track {
            background: #21262d;
        }

        .console-body::-webkit-scrollbar-thumb {
            background: #58a6ff;
            border-radius: 4px;
        }

        .message-entry {
            margin-bottom: 15px;
            padding: 12px;
            border-left: 3px solid #58a6ff;
            background: rgba(88, 166, 255, 0.05);
            border-radius: 0 8px 8px 0;
            animation: slideIn 0.3s ease-out;
        }

        .message-entry.new {
            border-left-color: #28a745;
            background: rgba(40, 167, 69, 0.1);
            animation: pulseGreen 1s ease-out;
        }

        .message-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 8px;
        }

        .message-time {
            color: #7dd3fc;
            font-size: 12px;
        }

        .message-phone {
            color: #fbbf24;
            font-weight: bold;
        }

        .message-content {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.05);
            padding: 8px 12px;
            border-radius: 6px;
            margin-top: 8px;
            word-wrap: break-word;
        }

        .status-indicator {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .status-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #28a745;
            animation: pulse 2s infinite;
        }

        .status-dot.error {
            background: #dc3545;
        }

        .control-buttons {
            display: flex;
            gap: 10px;
        }

        .stats-row {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            flex: 1;
            margin: 0 10px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #2d3748;
        }

        .stat-label {
            color: #718096;
            font-size: 0.9em;
            margin-top: 5px;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulseGreen {
            0% {
                background: rgba(40, 167, 69, 0.3);
                border-left-color: #28a745;
            }
            50% {
                background: rgba(40, 167, 69, 0.1);
            }
            100% {
                background: rgba(40, 167, 69, 0.05);
            }
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }

        .no-messages {
            text-align: center;
            color: #6b7280;
            font-style: italic;
            margin-top: 50px;
        }

        .filter-controls {
            margin-bottom: 15px;
        }

        .filter-input {
            background: #21262d;
            border: 1px solid #444;
            color: #c9d1d9;
            padding: 8px 12px;
            border-radius: 6px;
            width: 200px;
        }

        .filter-input:focus {
            outline: none;
            border-color: #58a6ff;
        }
    </style>
</head>
<body>
    <div class="monitor-container">
        <!-- Header -->
        <div class="monitor-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="mb-0">
                        <i class="fab fa-whatsapp text-success me-2"></i>
                        WhatsApp Bot - Real-time Message Monitor
                    </h2>
                    <p class="text-muted mb-0">Live monitoring of incoming user messages</p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="status-indicator">
                        <span id="statusText">Monitoring Active</span>
                        <div class="status-dot" id="statusDot"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-number" id="totalMessages">0</div>
                <div class="stat-label">Total Messages</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="uniqueUsers">0</div>
                <div class="stat-label">Unique Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="messagesPerMinute">0</div>
                <div class="stat-label">Msg/Min</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="lastMessageTime">--:--</div>
                <div class="stat-label">Last Message</div>
            </div>
        </div>

        <!-- Console Panel -->
        <div class="console-panel">
            <div class="console-header">
                <div>
                    <h5 class="mb-0">
                        <i class="fas fa-terminal me-2"></i>
                        Live Message Console
                    </h5>
                </div>
                <div class="control-buttons">
                    <input type="text" class="filter-input" id="phoneFilter" placeholder="Filter by phone...">
                    <button class="btn btn-outline-light btn-sm" onclick="clearConsole()">
                        <i class="fas fa-trash me-1"></i> Clear
                    </button>
                    <button class="btn btn-outline-light btn-sm" onclick="toggleAutoScroll()" id="autoScrollBtn">
                        <i class="fas fa-arrows-alt-v me-1"></i> Auto-scroll
                    </button>
                    <button class="btn btn-outline-light btn-sm" onclick="toggleMonitoring()" id="monitorBtn">
                        <i class="fas fa-pause me-1"></i> Pause
                    </button>
                </div>
            </div>
            <div class="console-body" id="consoleBody">
                <div id="messagesList">
                    <div class="no-messages">
                        <i class="fas fa-clock fa-2x mb-3"></i>
                        <p>Waiting for incoming messages...</p>
                        <small>Messages from users will appear here in real-time</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Configuration
        const CONFIG = {
            updateInterval: 2000, // Check every 2 seconds
            maxMessages: 100,     // Keep last 100 messages
            autoScroll: true,
            monitoring: true
        };

        // State
        let messagesCache = [];
        let uniqueUsers = new Set();
        let messageCount = 0;
        let lastCheckTime = new Date().toISOString();
        let messageTimestamps = [];

        // Initialize monitor
        function initializeMonitor() {
            console.log('WhatsApp Message Monitor initialized');
            addSystemMessage('Monitor started - listening for user messages');
            startMonitoring();
            
            // Setup filter
            document.getElementById('phoneFilter').addEventListener('input', filterMessages);
        }

        // Start monitoring for new messages
        function startMonitoring() {
            if (!CONFIG.monitoring) return;
            
            fetchNewMessages();
            setTimeout(startMonitoring, CONFIG.updateInterval);
        }

        // Fetch new messages from the backend
        function fetchNewMessages() {
            const statusDot = document.getElementById('statusDot');
            const statusText = document.getElementById('statusText');
            
            // Update status to checking
            statusDot.style.background = '#ffc107';
            statusText.textContent = 'Checking for messages...';
            
            // Simulate API call - replace with actual endpoint
            fetch(getMessagesEndpoint(), {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Cache-Control': 'no-cache'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.messages) {
                    processNewMessages(data.messages);
                    updateStatistics();
                }
                
                // Update status to active
                statusDot.style.background = '#28a745';
                statusText.textContent = 'Monitoring Active';
            })
            .catch(error => {
                console.error('Error fetching messages:', error);
                
                // Update status to error
                statusDot.style.background = '#dc3545';
                statusText.textContent = 'Connection Error';
                
                addSystemMessage('Error: Unable to fetch messages - ' + error.message, 'error');
            });
        }

        // Get the appropriate endpoint for fetching messages
        function getMessagesEndpoint() {
            // Try Laravel route first, fallback to direct API
            const baseUrl = window.location.origin;
            return `${baseUrl}/api/whatsapp/messages/recent?since=${encodeURIComponent(lastCheckTime)}`;
        }

        // Process incoming messages
        function processNewMessages(messages) {
            let newMessageCount = 0;
            
            messages.forEach(message => {
                if (!messagesCache.find(m => m.id === message.id)) {
                    messagesCache.unshift(message);
                    uniqueUsers.add(message.phone);
                    messageCount++;
                    newMessageCount++;
                    
                    // Track message timestamp for rate calculation
                    messageTimestamps.push(new Date());
                    
                    // Display the message
                    displayMessage(message, true);
                }
            });
            
            // Trim old messages
            if (messagesCache.length > CONFIG.maxMessages) {
                messagesCache = messagesCache.slice(0, CONFIG.maxMessages);
            }
            
            // Update last check time
            if (messages.length > 0) {
                lastCheckTime = new Date().toISOString();
            }
            
            // Show notification for new messages
            if (newMessageCount > 0) {
                addSystemMessage(`${newMessageCount} new message(s) received`);
                playNotificationSound();
            }
        }

        // Display a message in the console
        function displayMessage(message, isNew = false) {
            const messagesList = document.getElementById('messagesList');
            
            // Remove "no messages" placeholder
            const noMessages = messagesList.querySelector('.no-messages');
            if (noMessages) {
                noMessages.remove();
            }
            
            const messageDiv = document.createElement('div');
            messageDiv.className = `message-entry ${isNew ? 'new' : ''}`;
            messageDiv.setAttribute('data-phone', message.phone);
            
            const timestamp = new Date(message.timestamp || message.created_at).toLocaleString();
            const phoneDisplay = message.phone.replace(/^\+/, '');
            
            messageDiv.innerHTML = `
                <div class="message-header">
                    <span class="message-time">
                        <i class="fas fa-clock me-1"></i>${timestamp}
                    </span>
                    <span class="message-phone">
                        <i class="fas fa-phone me-1"></i>+${phoneDisplay}
                    </span>
                </div>
                <div class="message-content">
                    <i class="fas fa-comment me-2"></i>${escapeHtml(message.message || message.content)}
                </div>
            `;
            
            messagesList.insertBefore(messageDiv, messagesList.firstChild);
            
            // Auto-scroll if enabled
            if (CONFIG.autoScroll) {
                setTimeout(() => {
                    const consoleBody = document.getElementById('consoleBody');
                    consoleBody.scrollTop = consoleBody.scrollHeight;
                }, 100);
            }
            
            // Remove new class after animation
            if (isNew) {
                setTimeout(() => {
                    messageDiv.classList.remove('new');
                }, 1000);
            }
        }

        // Add system message
        function addSystemMessage(message, type = 'info') {
            const messagesList = document.getElementById('messagesList');
            
            const systemDiv = document.createElement('div');
            systemDiv.className = 'message-entry';
            systemDiv.style.borderLeftColor = type === 'error' ? '#dc3545' : '#6c757d';
            systemDiv.style.background = type === 'error' ? 'rgba(220, 53, 69, 0.1)' : 'rgba(108, 117, 125, 0.1)';
            
            const timestamp = new Date().toLocaleString();
            const icon = type === 'error' ? 'fas fa-exclamation-triangle' : 'fas fa-info-circle';
            
            systemDiv.innerHTML = `
                <div class="message-header">
                    <span class="message-time">
                        <i class="fas fa-clock me-1"></i>${timestamp}
                    </span>
                    <span style="color: #6c757d;">
                        <i class="${icon} me-1"></i>SYSTEM
                    </span>
                </div>
                <div class="message-content" style="background: rgba(108, 117, 125, 0.1);">
                    ${message}
                </div>
            `;
            
            messagesList.insertBefore(systemDiv, messagesList.firstChild);
        }

        // Update statistics
        function updateStatistics() {
            document.getElementById('totalMessages').textContent = messageCount;
            document.getElementById('uniqueUsers').textContent = uniqueUsers.size;
            
            // Calculate messages per minute
            const now = new Date();
            const oneMinuteAgo = new Date(now.getTime() - 60000);
            const recentMessages = messageTimestamps.filter(ts => ts > oneMinuteAgo);
            document.getElementById('messagesPerMinute').textContent = recentMessages.length;
            
            // Update last message time
            if (messagesCache.length > 0) {
                const lastMessage = messagesCache[0];
                const lastTime = new Date(lastMessage.timestamp || lastMessage.created_at);
                document.getElementById('lastMessageTime').textContent = lastTime.toLocaleTimeString();
            }
            
            // Clean old timestamps
            messageTimestamps = messageTimestamps.filter(ts => ts > oneMinuteAgo);
        }

        // Control functions
        function clearConsole() {
            const messagesList = document.getElementById('messagesList');
            messagesList.innerHTML = `
                <div class="no-messages">
                    <i class="fas fa-clock fa-2x mb-3"></i>
                    <p>Console cleared - waiting for new messages...</p>
                </div>
            `;
            
            messagesCache = [];
            messageCount = 0;
            uniqueUsers.clear();
            messageTimestamps = [];
            updateStatistics();
            
            addSystemMessage('Console cleared');
        }

        function toggleAutoScroll() {
            CONFIG.autoScroll = !CONFIG.autoScroll;
            const btn = document.getElementById('autoScrollBtn');
            
            if (CONFIG.autoScroll) {
                btn.innerHTML = '<i class="fas fa-arrows-alt-v me-1"></i> Auto-scroll';
                btn.classList.remove('btn-warning');
                btn.classList.add('btn-outline-light');
            } else {
                btn.innerHTML = '<i class="fas fa-hand-paper me-1"></i> Manual';
                btn.classList.remove('btn-outline-light');
                btn.classList.add('btn-warning');
            }
            
            addSystemMessage(`Auto-scroll ${CONFIG.autoScroll ? 'enabled' : 'disabled'}`);
        }

        function toggleMonitoring() {
            CONFIG.monitoring = !CONFIG.monitoring;
            const btn = document.getElementById('monitorBtn');
            const statusDot = document.getElementById('statusDot');
            const statusText = document.getElementById('statusText');
            
            if (CONFIG.monitoring) {
                btn.innerHTML = '<i class="fas fa-pause me-1"></i> Pause';
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-light');
                statusDot.style.background = '#28a745';
                statusText.textContent = 'Monitoring Active';
                startMonitoring();
                addSystemMessage('Monitoring resumed');
            } else {
                btn.innerHTML = '<i class="fas fa-play me-1"></i> Resume';
                btn.classList.remove('btn-outline-light');
                btn.classList.add('btn-success');
                statusDot.style.background = '#6c757d';
                statusText.textContent = 'Monitoring Paused';
                addSystemMessage('Monitoring paused');
            }
        }

        function filterMessages() {
            const filter = document.getElementById('phoneFilter').value.toLowerCase();
            const messages = document.querySelectorAll('.message-entry[data-phone]');
            
            messages.forEach(message => {
                const phone = message.getAttribute('data-phone').toLowerCase();
                if (phone.includes(filter) || filter === '') {
                    message.style.display = 'block';
                } else {
                    message.style.display = 'none';
                }
            });
        }

        // Utility functions
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function playNotificationSound() {
            try {
                const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJaajuaOSMtkjWvG');
                audio.volume = 0.2;
                audio.play().catch(() => {
                    // Silently fail if audio can't play
                });
            } catch (e) {
                // Ignore audio errors
            }
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', initializeMonitor);

        // Simulate real-time messages for demo (remove in production)
        function simulateMessage() {
            const phones = ['+1234567890', '+9876543210', '+1122334455'];
            const messages = [
                'Hello, I need help with my appointment',
                'Can you check my prescription status?',
                'I want to book a consultation',
                'What are your clinic hours?',
                'Is Dr. Smith available today?',
                'I need to reschedule my appointment',
                'Can I get a medical certificate?'
            ];
            
            const randomPhone = phones[Math.floor(Math.random() * phones.length)];
            const randomMessage = messages[Math.floor(Math.random() * messages.length)];
            
            const simulatedMessage = {
                id: Date.now(),
                phone: randomPhone,
                message: randomMessage,
                timestamp: new Date().toISOString()
            };
            
            processNewMessages([simulatedMessage]);
            updateStatistics();
        }

        // Demo mode - simulate messages every 10-30 seconds (remove in production)
        setInterval(() => {
            if (Math.random() > 0.7 && CONFIG.monitoring) {
                simulateMessage();
            }
        }, 15000);
    </script>
</body>
</html>
