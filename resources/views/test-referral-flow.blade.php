<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Referral Flow - FreeDoctor</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-4xl w-full">
        <h1 class="text-2xl font-bold text-center mb-6 text-gray-800">🔗 Test Referral System</h1>
        
        <!-- Step 1: Get User Data -->
        <div class="mb-6 p-4 border border-blue-200 rounded-lg bg-blue-50">
            <h2 class="text-lg font-semibold mb-3 text-blue-600">Step 1: Get User Referral Data</h2>
            <div class="flex space-x-3">
                <input type="number" id="user-id" placeholder="Enter User ID" class="flex-1 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button onclick="getUserReferralData()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-200">
                    Get Referral Data
                </button>
            </div>
            <div id="user-data-result" class="mt-3"></div>
        </div>
        
        <!-- Step 2: Test Campaign Visit -->
        <div class="mb-6 p-4 border border-green-200 rounded-lg bg-green-50">
            <h2 class="text-lg font-semibold mb-3 text-green-600">Step 2: Test Campaign Visit with Referral</h2>
            <div id="referral-links" class="space-y-2">
                <!-- Links will be populated by JavaScript -->
            </div>
            <div class="mt-3">
                <button onclick="checkSessionData()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition duration-200">
                    Check Session Data
                </button>
            </div>
            <div id="session-data-result" class="mt-3"></div>
        </div>
        
        <!-- Step 3: Test Registration -->
        <div class="mb-6 p-4 border border-purple-200 rounded-lg bg-purple-50">
            <h2 class="text-lg font-semibold mb-3 text-purple-600">Step 3: Test Registration Process</h2>
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Test Username:</label>
                    <input type="text" id="test-username" value="testuser123" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Test Email:</label>
                    <input type="email" id="test-email" value="testuser@example.com" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <a href="{{ route('user.register') }}" target="_blank" class="inline-block bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 transition duration-200">
                        Go to Registration Page
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Instructions -->
        <div class="bg-yellow-50 border border-yellow-200 rounded p-4 text-sm">
            <p class="font-semibold text-yellow-800 mb-2">📋 Testing Instructions:</p>
            <ol class="text-yellow-700 space-y-1 list-decimal list-inside">
                <li>Enter a User ID and click "Get Referral Data" to see their referral information</li>
                <li>Click on the generated referral link to visit the campaign with referral tracking</li>
                <li>Check session data to verify referral information is stored</li>
                <li>Go to registration page and complete registration</li>
                <li>Check database to see if referred_by field is populated correctly</li>
            </ol>
        </div>
        
        <!-- Database Query Section -->
        <div class="mt-6 p-4 border border-gray-200 rounded-lg bg-gray-50">
            <h2 class="text-lg font-semibold mb-3 text-gray-600">Database Verification</h2>
            <div class="space-y-2">
                <button onclick="checkRecentUsers()" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition duration-200">
                    Check Recent Registrations
                </button>
                <button onclick="checkReferralStats()" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition duration-200">
                    Check Referral Statistics
                </button>
            </div>
            <div id="database-result" class="mt-3"></div>
        </div>
    </div>

    <script>
        let currentReferralData = null;

        async function getUserReferralData() {
            const userId = document.getElementById('user-id').value;
            if (!userId) {
                alert('Please enter a User ID');
                return;
            }
            
            try {
                const response = await fetch(`/test/referral/simulate/${userId}`);
                const data = await response.json();
                
                if (data.error) {
                    document.getElementById('user-data-result').innerHTML = `
                        <div class="bg-red-50 border border-red-200 text-red-800 px-3 py-2 rounded text-sm">
                            ❌ ${data.error}
                        </div>
                    `;
                    return;
                }
                
                currentReferralData = data;
                
                document.getElementById('user-data-result').innerHTML = `
                    <div class="bg-green-50 border border-green-200 text-green-800 px-3 py-2 rounded text-sm">
                        ✅ User found: ${data.username} (ID: ${data.user_id})<br>
                        📋 Referral ID: ${data.referral_id}
                    </div>
                `;
                
                // Populate referral links
                document.getElementById('referral-links').innerHTML = `
                    <p class="text-sm text-gray-600 mb-2">Click these links to test referral tracking:</p>
                    <div class="space-y-2">
                        <a href="${data.referral_url}" target="_blank" class="block bg-blue-100 text-blue-800 px-3 py-2 rounded text-sm hover:bg-blue-200 transition duration-200">
                            🏥 Campaign View with Referral: ${data.referral_url}
                        </a>
                        <a href="${data.registration_url_with_ref}" target="_blank" class="block bg-green-100 text-green-800 px-3 py-2 rounded text-sm hover:bg-green-200 transition duration-200">
                            📝 Direct Registration with Referral: ${data.registration_url_with_ref}
                        </a>
                    </div>
                `;
                
            } catch (error) {
                document.getElementById('user-data-result').innerHTML = `
                    <div class="bg-red-50 border border-red-200 text-red-800 px-3 py-2 rounded text-sm">
                        ❌ Error: ${error.message}
                    </div>
                `;
            }
        }

        async function checkSessionData() {
            try {
                const response = await fetch('/test/check-referral-session');
                const data = await response.json();
                
                document.getElementById('session-data-result').innerHTML = `
                    <div class="bg-blue-50 border border-blue-200 text-blue-800 px-3 py-2 rounded text-sm">
                        <strong>Session Data Check:</strong><br>
                        📊 Has Session Data: ${data.has_session_data ? 'Yes' : 'No'}<br>
                        🔗 URL Ref Param: ${data.url_ref_param || 'None'}<br>
                        📄 Current URL: ${data.current_url}<br>
                        ${data.session_data ? `💾 Session Data: ${JSON.stringify(data.session_data, null, 2)}` : ''}
                    </div>
                `;
                
            } catch (error) {
                document.getElementById('session-data-result').innerHTML = `
                    <div class="bg-red-50 border border-red-200 text-red-800 px-3 py-2 rounded text-sm">
                        ❌ Error: ${error.message}
                    </div>
                `;
            }
        }

        async function checkRecentUsers() {
            try {
                const response = await fetch('/api/users/recent');
                const data = await response.json();
                
                document.getElementById('database-result').innerHTML = `
                    <div class="bg-blue-50 border border-blue-200 text-blue-800 px-3 py-2 rounded text-sm">
                        📊 Recent users query would go here...
                    </div>
                `;
                
            } catch (error) {
                document.getElementById('database-result').innerHTML = `
                    <div class="bg-red-50 border border-red-200 text-red-800 px-3 py-2 rounded text-sm">
                        ❌ Error: ${error.message}
                    </div>
                `;
            }
        }

        async function checkReferralStats() {
            try {
                document.getElementById('database-result').innerHTML = `
                    <div class="bg-blue-50 border border-blue-200 text-blue-800 px-3 py-2 rounded text-sm">
                        📊 Referral statistics query would go here...
                    </div>
                `;
                
            } catch (error) {
                document.getElementById('database-result').innerHTML = `
                    <div class="bg-red-50 border border-red-200 text-red-800 px-3 py-2 rounded text-sm">
                        ❌ Error: ${error.message}
                    </div>
                `;
            }
        }
    </script>
</body>
</html>
