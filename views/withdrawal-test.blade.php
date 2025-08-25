<!DOCTYPE html>
<html>
<head>
    <title>Withdrawal System Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; }
        .status-box { padding: 15px; margin: 10px 0; border-radius: 5px; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        .info { background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; }
        button { padding: 10px 20px; margin: 5px; border: none; border-radius: 5px; cursor: pointer; }
        .btn-primary { background: #007bff; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-warning { background: #ffc107; color: black; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏦 Withdrawal System Test</h1>
        
        @if(auth('user')->check())
            <div class="status-box success">
                ✅ User logged in: {{ auth('user')->user()->name }} (ID: {{ auth('user')->user()->id }})
            </div>
            
            <div class="status-box info">
                💰 Available Balance: ₹{{ number_format(auth('user')->user()->available_balance ?? 0, 2) }}<br>
                🏪 Bank Account: {{ auth('user')->user()->bank_account_number ? 'Set (' . substr(auth('user')->user()->bank_account_number, -4) . ')' : 'Not Set' }}<br>
                🔑 Razorpay: {{ config('services.razorpay.key') ? 'Configured' : 'Not Configured' }}
            </div>
            
            <h3>Test Actions</h3>
            <button class="btn-warning" onclick="setupTestAccount()">Setup Test Bank Account</button>
            <button class="btn-primary" onclick="addTestBalance()">Add Test Balance (₹1500)</button>
            <button class="btn-success" onclick="testWithdrawal()">Test Withdrawal</button>
            
        @else
            <div class="status-box error">
                ❌ User not logged in. Please login first.
            </div>
            <a href="/user/login">Login Here</a>
        @endif
        
        <div id="results" style="margin-top: 20px;"></div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        function setupTestAccount() {
            fetch('/test/withdrawal/account-details', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    account_holder_name: 'Test User',
                    bank_account_number: '1234567890123456',
                    bank_ifsc_code: 'HDFC0001234',
                    bank_name: 'HDFC Bank'
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Response:', data);
                if (data.success) {
                    Swal.fire('Success', data.message + ' (Test mode)', 'success');
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Network error occurred', 'error');
            });
        }
        
        function addTestBalance() {
            // This would normally be done through earning referrals
            // For testing, we'll call a test endpoint
            fetch('/test/add-balance', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    amount: 1500
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Success', 'Test balance added: ₹1500', 'success')
                        .then(() => location.reload());
                } else {
                    Swal.fire('Error', data.message || 'Failed to add balance', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Network error occurred', 'error');
            });
        }
        
        function testWithdrawal() {
            Swal.fire({
                title: 'Test Withdrawal',
                text: 'This will process a withdrawal using Razorpay test mode.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Process Withdrawal',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    executeTestWithdrawal();
                }
            });
        }
        
        function executeTestWithdrawal() {
            Swal.fire({
                title: 'Processing...',
                html: '<div style="text-align: center;"><div style="border: 4px solid #f3f3f3; border-top: 4px solid #3498db; border-radius: 50%; width: 40px; height: 40px; animation: spin 2s linear infinite; margin: 0 auto;"></div></div><style>@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }</style>',
                showConfirmButton: false,
                allowOutsideClick: false
            });
            
            fetch('/test/withdrawal/process', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    amount: parseFloat('{{ auth('user')->check() ? auth('user')->user()->available_balance ?? 0 : 0 }}')
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Response:', data);
                if (data.success) {
                    Swal.fire({
                        title: 'Test Successful!',
                        html: `
                            <p><strong>Message:</strong> ${data.message}</p>
                            <p><strong>Authenticated:</strong> ${data.authenticated ? 'Yes' : 'No'}</p>
                            <p><strong>User ID:</strong> ${data.user_id || 'None'}</p>
                        `,
                        icon: 'success'
                    });
                } else {
                    Swal.fire('Test Failed', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Network error during test', 'error');
            });
        }
    </script>
</body>
</html>
