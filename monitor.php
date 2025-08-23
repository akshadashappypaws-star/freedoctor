<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhatsApp Bot Monitor - Access Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .portal-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
            width: 90%;
        }

        .portal-icon {
            font-size: 4rem;
            color: #25d366;
            margin-bottom: 20px;
        }

        .portal-title {
            color: #2d3748;
            margin-bottom: 10px;
        }

        .portal-description {
            color: #718096;
            margin-bottom: 30px;
        }

        .launch-btn {
            background: linear-gradient(45deg, #25d366, #128c7e);
            border: none;
            color: white;
            padding: 15px 30px;
            border-radius: 50px;
            font-size: 1.1rem;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
        }

        .launch-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 211, 102, 0.4);
            color: white;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .info-item {
            background: rgba(37, 211, 102, 0.1);
            padding: 20px;
            border-radius: 15px;
            border: 1px solid rgba(37, 211, 102, 0.2);
        }

        .info-item i {
            color: #25d366;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .info-item h6 {
            color: #2d3748;
            margin-bottom: 5px;
        }

        .info-item p {
            color: #718096;
            font-size: 0.9rem;
            margin: 0;
        }

        .status-indicator {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 15px;
            padding: 8px 15px;
            background: rgba(40, 167, 69, 0.1);
            border-radius: 20px;
            color: #28a745;
            font-size: 0.9rem;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #28a745;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
    </style>
</head>
<body>
    <div class="portal-card">
        <div class="portal-icon">
            <i class="fab fa-whatsapp"></i>
        </div>
        
        <h2 class="portal-title">WhatsApp Bot Monitor</h2>
        <p class="portal-description">
            Real-time monitoring dashboard for incoming user messages on your WhatsApp bot system.
        </p>
        
        <a href="realtime_message_monitor.php" class="launch-btn">
            <i class="fas fa-rocket me-2"></i>
            Launch Monitor
        </a>
        
        <div class="status-indicator">
            <div class="status-dot"></div>
            <span>System Online</span>
        </div>
        
        <div class="info-grid">
            <div class="info-item">
                <i class="fas fa-comments"></i>
                <h6>Live Messages</h6>
                <p>See user messages as they arrive in real-time</p>
            </div>
            
            <div class="info-item">
                <i class="fas fa-chart-line"></i>
                <h6>Statistics</h6>
                <p>Track message volume and user activity</p>
            </div>
            
            <div class="info-item">
                <i class="fas fa-filter"></i>
                <h6>Filtering</h6>
                <p>Filter messages by phone number or content</p>
            </div>
            
            <div class="info-item">
                <i class="fas fa-bell"></i>
                <h6>Notifications</h6>
                <p>Get alerts for new incoming messages</p>
            </div>
        </div>
        
        <div class="mt-4">
            <small class="text-muted">
                <i class="fas fa-info-circle me-1"></i>
                This monitor connects to your WhatsApp bot database to display incoming user messages.
            </small>
        </div>
    </div>

    <script>
        // Simple status check
        document.addEventListener('DOMContentLoaded', function() {
            console.log('WhatsApp Bot Monitor Portal loaded');
            
            // Optional: Check system status
            checkSystemStatus();
        });

        function checkSystemStatus() {
            fetch('api/whatsapp/messages/recent.php?limit=1')
                .then(response => response.json())
                .then(data => {
                    const statusIndicator = document.querySelector('.status-indicator');
                    const statusDot = document.querySelector('.status-dot');
                    
                    if (data.success) {
                        statusIndicator.innerHTML = `
                            <div class="status-dot"></div>
                            <span>System Online - ${data.demo_mode ? 'Demo Mode' : 'Live Data'}</span>
                        `;
                    } else {
                        statusIndicator.style.background = 'rgba(220, 53, 69, 0.1)';
                        statusIndicator.style.color = '#dc3545';
                        statusDot.style.background = '#dc3545';
                        statusIndicator.innerHTML = `
                            <div class="status-dot"></div>
                            <span>System Error</span>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Status check failed:', error);
                });
        }
    </script>
</body>
</html>
