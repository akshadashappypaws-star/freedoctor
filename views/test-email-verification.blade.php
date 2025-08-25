<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Email Verification - FreeDoctor</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <h1 class="text-2xl font-bold text-center mb-6 text-gray-800">Test Email System</h1>
        
        <!-- Test Email Verification -->
        <div class="mb-6">
            <h2 class="text-lg font-semibold mb-3 text-blue-600">Test Email Verification</h2>
            <button onclick="testEmailVerification()" class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition duration-200">
                Send Test Verification Email
            </button>
            <div id="verification-result" class="mt-3"></div>
        </div>
        
        <!-- Test Password Reset -->
        <div class="mb-6">
            <h2 class="text-lg font-semibold mb-3 text-green-600">Test Password Reset</h2>
            <div class="space-y-3">
                <input type="email" id="reset-email" placeholder="Enter email address" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
                <button onclick="testPasswordReset()" class="w-full bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 transition duration-200">
                    Send Test Password Reset
                </button>
            </div>
            <div id="reset-result" class="mt-3"></div>
        </div>

        <!-- Instructions -->
        <div class="bg-yellow-50 border border-yellow-200 rounded p-4 text-sm">
            <p class="font-semibold text-yellow-800 mb-2">📋 Instructions:</p>
            <ul class="text-yellow-700 space-y-1">
                <li>• For verification test: Must be logged in as user</li>
                <li>• For password reset: Enter any user email</li>
                <li>• Check your email inbox for custom templates</li>
                <li>• Check browser console for detailed responses</li>
            </ul>
        </div>
        
        <!-- Navigation -->
        <div class="mt-6 text-center">
            <a href="{{ route('user.home') }}" class="text-blue-600 hover:text-blue-800 underline">
                ← Back to User Home
            </a>
        </div>
    </div>

    <script>
        async function testEmailVerification() {
            const btn = event.target;
            const resultDiv = document.getElementById('verification-result');
            
            btn.textContent = 'Sending...';
            btn.disabled = true;
            
            try {
                const response = await fetch('/test/email-verification', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                console.log('Email verification response:', data);
                
                if (data.success) {
                    resultDiv.innerHTML = `
                        <div class="bg-green-50 border border-green-200 text-green-800 px-3 py-2 rounded text-sm">
                            ✅ ${data.message}<br>
                            📧 Sent to: ${data.user_email}
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div class="bg-red-50 border border-red-200 text-red-800 px-3 py-2 rounded text-sm">
                            ❌ ${data.error || 'Failed to send email'}
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error:', error);
                resultDiv.innerHTML = `
                    <div class="bg-red-50 border border-red-200 text-red-800 px-3 py-2 rounded text-sm">
                        ❌ Network error: ${error.message}
                    </div>
                `;
            }
            
            btn.textContent = 'Send Test Verification Email';
            btn.disabled = false;
        }

        async function testPasswordReset() {
            const btn = event.target;
            const resultDiv = document.getElementById('reset-result');
            const email = document.getElementById('reset-email').value;
            
            if (!email) {
                resultDiv.innerHTML = `
                    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-3 py-2 rounded text-sm">
                        ⚠️ Please enter an email address
                    </div>
                `;
                return;
            }
            
            btn.textContent = 'Sending...';
            btn.disabled = true;
            
            try {
                const response = await fetch('/test/password-reset', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ email: email })
                });
                
                const data = await response.json();
                console.log('Password reset response:', data);
                
                if (data.success) {
                    resultDiv.innerHTML = `
                        <div class="bg-green-50 border border-green-200 text-green-800 px-3 py-2 rounded text-sm">
                            ✅ ${data.message}<br>
                            📧 Sent to: ${data.user_email}
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div class="bg-red-50 border border-red-200 text-red-800 px-3 py-2 rounded text-sm">
                            ❌ ${data.error || 'Failed to send email'}
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error:', error);
                resultDiv.innerHTML = `
                    <div class="bg-red-50 border border-red-200 text-red-800 px-3 py-2 rounded text-sm">
                        ❌ Network error: ${error.message}
                    </div>
                `;
            }
            
            btn.textContent = 'Send Test Password Reset';
            btn.disabled = false;
        }
    </script>
</body>
</html>
