@extends('user.master')

@section('title', 'My Profile - FreeDoctor')

@push('styles')
<style>
    :root {
        --primary-color: #2C2A4C;
        --secondary-color: #E7A51B;
        --background-color: #f5f5f5;
        --text-primary: #2C2A4C;
        --text-secondary: #6c757d;
        --border-color: #dee2e6;
        --white: #ffffff;
        --success-color: #28a745;
        --danger-color: #dc3545;
        --warning-color: #ffc107;
        --info-color: #17a2b8;
        --shadow-light: 0 2px 8px rgba(44, 42, 76, 0.1);
        --shadow-medium: 0 4px 20px rgba(44, 42, 76, 0.15);
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 16px;
    }

    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        color: var(--text-primary);
        background: var(--background-color);
        margin: 0;
        padding: 0;
        overflow-x: hidden;
    }

    .profile-container {
        min-height: 100vh;
        padding: 2rem 0;
    }

    .profile-card {
        background: var(--white);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-medium);
        border: 1px solid var(--border-color);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .profile-header {
        background: var(--primary-color);
        padding: 3rem 2rem;
        color: var(--white);
        text-align: center;
        position: relative;
    }

    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: var(--secondary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        border: 4px solid rgba(255, 255, 255, 0.2);
    }

    .profile-info h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .profile-info p {
        font-size: 1.1rem;
        opacity: 0.9;
        margin: 0;
    }

    .profile-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        padding: 2rem;
        background: var(--white);
    }

    .stat-card {
        background: var(--background-color);
        padding: 1.5rem;
        border-radius: var(--radius-md);
        text-align: center;
        border: 1px solid var(--border-color);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: var(--text-secondary);
        font-size: 0.9rem;
        font-weight: 500;
    }

    .section-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background: var(--secondary-color);
        color: var(--primary-color);
        padding: 0.875rem 2rem;
        border-radius: var(--radius-md);
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-primary:hover {
        background: #d4751a;
        transform: translateY(-1px);
        box-shadow: var(--shadow-light);
        text-decoration: none;
        color: var(--primary-color);
    }

    .btn-secondary {
        background: var(--text-secondary);
        color: var(--white);
        padding: 0.875rem 2rem;
        border-radius: var(--radius-md);
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-1px);
        text-decoration: none;
        color: var(--white);
    }

    .referral-info {
        background: linear-gradient(135deg, rgba(231, 165, 27, 0.1) 0%, rgba(44, 42, 76, 0.05) 100%);
        border: 1px solid var(--secondary-color);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .referral-code {
        background: var(--primary-color);
        color: var(--white);
        padding: 0.75rem 1rem;
        border-radius: var(--radius-sm);
        font-family: 'Courier New', monospace;
        font-weight: 600;
        letter-spacing: 1px;
        text-align: center;
        margin: 1rem 0;
    }

    .download-section {
        background: var(--white);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        margin-top: 1.5rem;
    }

    .download-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: var(--background-color);
        border-radius: var(--radius-sm);
        margin-bottom: 1rem;
    }

    .download-item:last-child {
        margin-bottom: 0;
    }

    .download-info {
        flex: 1;
    }

    .download-title {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .download-desc {
        font-size: 0.85rem;
        color: var(--text-secondary);
    }

    .profile-tabs {
        background: var(--white);
        border-radius: var(--radius-lg) var(--radius-lg) 0 0;
        border: 1px solid var(--border-color);
        border-bottom: none;
        overflow: hidden;
        margin-bottom: 0;
    }

    .tab-buttons {
        display: flex;
        background: var(--background-color);
        border-bottom: 1px solid var(--border-color);
    }

    .tab-btn {
        flex: 1;
        background: transparent;
        border: none;
        padding: 1.5rem 2rem;
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-secondary);
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .tab-btn:hover {
        color: var(--primary-color);
        background: rgba(44, 42, 76, 0.05);
    }

    .tab-btn.active {
        color: var(--primary-color);
        background: var(--white);
        border-bottom: 3px solid var(--secondary-color);
    }

    .tab-content {
        background: var(--white);
        border: 1px solid var(--border-color);
        border-top: none;
        border-radius: 0 0 var(--radius-lg) var(--radius-lg);
        padding: 2rem;
    }

    .tab-pane {
        display: none;
    }

    .tab-pane.active {
        display: block;
    }

    .profile-form {
        margin-top: 1.5rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: var(--radius-md);
        font-size: 1rem;
        transition: all 0.3s ease;
        background: var(--white);
        color: var(--text-primary);
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 3px rgba(231, 165, 27, 0.1);
    }

    .password-input {
        position: relative;
    }

    .password-toggle {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--text-secondary);
        cursor: pointer;
        padding: 0.25rem;
        transition: color 0.3s ease;
    }

    .password-toggle:hover {
        color: var(--primary-color);
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        flex-wrap: wrap;
    }

    .text-info {
        color: var(--info-color);
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }

    .text-secondary {
        color: var(--text-secondary);
    }

    .grid {
        display: grid;
    }

    .grid-cols-1 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }

    .grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .gap-4 {
        gap: 1rem;
    }

    .mb-3 {
        margin-bottom: 0.75rem;
    }

    .mt-4 {
        margin-top: 1rem;
    }

    .mb-4 {
        margin-bottom: 1rem;
    }

    @media (min-width: 768px) {
        .md\:grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {
        .profile-container {
            padding: 1rem 0;
        }

        .profile-header {
            padding: 2rem 1rem;
        }

        .profile-info h1 {
            font-size: 1.5rem;
        }

        .profile-stats {
            grid-template-columns: 1fr;
            padding: 1rem;
        }

        .tab-btn {
            padding: 1rem;
            font-size: 0.9rem;
        }

        .tab-content {
            padding: 1.5rem;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .form-actions button {
            width: 100%;
            justify-content: center;
        }

        .download-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
    }

    .alert {
        position: fixed;
        top: 2rem;
        right: 2rem;
        z-index: 1000;
        padding: 1rem 1.5rem;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-medium);
        min-width: 300px;
        animation: slideInRight 0.3s ease;
    }

    .alert-success {
        background: rgba(40, 167, 69, 0.1);
        border: 1px solid var(--success-color);
        color: #155724;
    }

    .alert-error {
        background: rgba(220, 53, 69, 0.1);
        border: 1px solid var(--danger-color);
        color: #721c24;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
</style>
@endpush

@section('content')
@auth('user')
<div class="profile-container">
    <div class="container mx-auto px-4 max-w-4xl">
        
        <!-- Profile Header -->
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">
                    <i class="fas fa-user text-4xl text-white"></i>
                </div>
                <div class="profile-info">
                    <h1>{{ auth()->user()->username }}</h1>
                    <p>{{ auth()->user()->email }}</p>
                </div>
            </div>

            <!-- Profile Statistics -->
            <div class="profile-stats">
                @php
                    $userRegistrations = \App\Models\PatientRegistration::where('email', auth()->user()->email)->count();
                    $sponsorRequests = \App\Models\CampaignSponsor::where('user_id', auth()->user()->id)->count();
                    $orgRequests = \App\Models\BusinessOrganizationRequest::where('email', auth()->user()->email)->count();
                    $referralEarnings = auth()->user()->total_earnings ?? 0;
                @endphp
                
                <div class="stat-card">
                    <div class="stat-number">{{ $userRegistrations }}</div>
                    <div class="stat-label">Camp Registrations</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number">{{ $sponsorRequests }}</div>
                    <div class="stat-label">Sponsorship Requests</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number">{{ $orgRequests }}</div>
                    <div class="stat-label">Organization Requests</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number">₹{{ number_format($referralEarnings, 2) }}</div>
                    <div class="stat-label">Referral Earnings</div>
                </div>
            </div>
        </div>

        <!-- Referral Information Card -->
        @if(auth()->user()->your_referral_id)
        <div class="profile-card">
            <div class="referral-info">
                <h3 class="section-title">
                    <i class="fas fa-share-alt text-secondary-color"></i>
                    Your Referral Information
                </h3>
                <p class="text-secondary mb-3">Share your referral code and earn rewards when others register for medical camps!</p>
                
                <div class="referral-code">
                    {{ auth()->user()->your_referral_id }}
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <strong>Total Referrals Made:</strong> {{ auth()->user()->referralsMade()->count() }}
                    </div>
                    <div>
                        <strong>Completed Referrals:</strong> {{ auth()->user()->referralsMade()->where('status', 'completed')->count() }}
                    </div>
                    <div>
                        <strong>Total Earnings:</strong> ₹{{ number_format(auth()->user()->total_earnings ?? 0, 2) }}
                    </div>
                    <div>
                        <strong>Available Balance:</strong> ₹{{ number_format(auth()->user()->available_balance ?? 0, 2) }}
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Download Documents Section -->
        <div class="profile-card">
            <div class="download-section">
                <h3 class="section-title">
                    <i class="fas fa-download text-info-color"></i>
                    Download Your Information
                </h3>
                <p class="text-secondary mb-4">Download important documents and invoices for your medical records and treatments.</p>
                
                <div class="download-item">
                    <div class="download-info">
                        <div class="download-title">User Profile Summary</div>
                        <div class="download-desc">Complete profile information including personal details and registration history</div>
                    </div>
                    <button class="btn-primary" onclick="generateProfileSummary()">
                        <i class="fas fa-file-pdf"></i>Download PDF
                    </button>
                </div>
                
                <div class="download-item">
                    <div class="download-info">
                        <div class="download-title">Registration Invoices</div>
                        <div class="download-desc">All medical camp registration receipts and payment confirmations</div>
                    </div>
                    <button class="btn-primary" onclick="generateInvoices()">
                        <i class="fas fa-receipt"></i>Download Invoices
                    </button>
                </div>
                
                <div class="download-item">
                    <div class="download-info">
                        <div class="download-title">Referral Earnings Report</div>
                        <div class="download-desc">Detailed report of your referral activities and earnings</div>
                    </div>
                    <button class="btn-primary" onclick="generateReferralReport()">
                        <i class="fas fa-chart-line"></i>Download Report
                    </button>
                </div>
                
                <div class="download-item">
                    <div class="download-info">
                        <div class="download-title">Medical Records Summary</div>
                        <div class="download-desc">Summary of medical camps attended and health screenings (for hospital visits)</div>
                    </div>
                    <button class="btn-primary" onclick="generateMedicalSummary()">
                        <i class="fas fa-file-medical"></i>Download Summary
                    </button>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="profile-tabs">
            <div class="tab-buttons">
                <button class="tab-btn active" id="editProfileTab" onclick="showTab('editProfile')">
                    <i class="fas fa-edit"></i>Edit Profile
                </button>
                <button class="tab-btn" id="changePasswordTab" onclick="showTab('changePassword')">
                    <i class="fas fa-lock"></i>Change Password
                </button>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Edit Profile Tab -->
            <div id="editProfile" class="tab-pane active">
                <div class="profile-card">
                    <h3 class="section-title">
                        <i class="fas fa-user-edit text-primary-color"></i>
                        Edit Profile Information
                    </h3>
                    
                    <form action="{{ route('user.profile.update') }}" method="POST" class="profile-form">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="username">Full Name</label>
                                <input type="text" id="username" name="username" 
                                       value="{{ auth()->user()->username }}" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" 
                                       value="{{ auth()->user()->email }}" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone" 
                                       value="{{ auth()->user()->phone }}">
                            </div>
                            
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <select id="gender" name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ auth()->user()->gender == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ auth()->user()->gender == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ auth()->user()->gender == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea id="address" name="address" rows="3" 
                                      placeholder="Enter your complete address">{{ auth()->user()->address }}</textarea>
                        </div>
                        
                        @if(auth()->user()->your_referral_id)
                        <div class="form-group">
                            <label for="referral_code">Your Referral Code (Read Only)</label>
                            <input type="text" id="referral_code" value="{{ auth()->user()->your_referral_id }}" readonly>
                            <small class="text-info">Share this code with friends to earn referral rewards!</small>
                        </div>
                        @endif
                        
                        <div class="form-actions">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save"></i>Update Profile
                            </button>
                            <button type="reset" class="btn-secondary">
                                <i class="fas fa-undo"></i>Reset Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password Tab -->
            <div id="changePassword" class="tab-pane">
                <div class="profile-card">
                    <h3 class="section-title">
                        <i class="fas fa-key text-primary-color"></i>
                        Change Password
                    </h3>
                    
                    <form action="{{ route('user.password.update') }}" method="POST" class="profile-form">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <div class="password-input">
                                <input type="password" id="current_password" name="current_password" required>
                                <button type="button" class="password-toggle" onclick="togglePassword('current_password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <div class="password-input">
                                <input type="password" id="new_password" name="new_password" required>
                                <button type="button" class="password-toggle" onclick="togglePassword('new_password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <small class="text-info">Password must be at least 8 characters long and contain letters and numbers.</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password_confirmation">Confirm New Password</label>
                            <div class="password-input">
                                <input type="password" id="new_password_confirmation" name="new_password_confirmation" required>
                                <button type="button" class="password-toggle" onclick="togglePassword('new_password_confirmation')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-shield-alt"></i>Update Password
                            </button>
                            <button type="reset" class="btn-secondary">
                                <i class="fas fa-undo"></i>Clear Form
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Tab functionality
function showTab(tabName) {
    // Hide all tab panes
    const tabPanes = document.querySelectorAll('.tab-pane');
    tabPanes.forEach(pane => {
        pane.classList.remove('active');
    });
    
    // Remove active class from all tab buttons
    const tabButtons = document.querySelectorAll('.tab-btn');
    tabButtons.forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected tab pane
    document.getElementById(tabName).classList.add('active');
    
    // Add active class to clicked button
    document.getElementById(tabName + 'Tab').classList.add('active');
}

// Password visibility toggle
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = input.parentElement.querySelector('.password-toggle i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

// Generate and download profile summary PDF
function generateProfileSummary() {
    window.open('/user/download/profile-summary', '_blank');
}

// Generate and download invoices
function generateInvoices() {
    window.open('/user/download/invoices', '_blank');
}

// Generate and download referral report
function generateReferralReport() {
    window.open('/user/download/referral-report', '_blank');
}

// Generate and download medical summary
function generateMedicalSummary() {
    window.open('/user/download/medical-summary', '_blank');
}

// Show success/error messages
function showMessage(message, type = 'success') {
    const messageDiv = document.createElement('div');
    messageDiv.className = `alert alert-${type}`;
    messageDiv.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        ${message}
    `;
    
    document.body.insertBefore(messageDiv, document.body.firstChild);
    
    setTimeout(() => {
        messageDiv.remove();
    }, 5000);
}

// Form validation and submission
document.addEventListener('DOMContentLoaded', function() {
    // Profile form validation
    const profileForm = document.querySelector('form[action*="profile.update"]');
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const username = document.getElementById('username').value;
            
            if (!email || !username) {
                e.preventDefault();
                showMessage('Please fill in all required fields.', 'error');
                return false;
            }
        });
    }
    
    // Password form validation
    const passwordForm = document.querySelector('form[action*="password.update"]');
    if (passwordForm) {
        passwordForm.addEventListener('submit', function(e) {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('new_password_confirmation').value;
            
            if (newPassword !== confirmPassword) {
                e.preventDefault();
                showMessage('New passwords do not match.', 'error');
                return false;
            }
            
            if (newPassword.length < 8) {
                e.preventDefault();
                showMessage('Password must be at least 8 characters long.', 'error');
                return false;
            }
        });
    }
});
</script>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        showMessage('{{ session("success") }}', 'success');
    });
</script>
@endif

@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        showMessage('{{ session("error") }}', 'error');
    });
</script>
@endif

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        showMessage('Please correct the errors in the form.', 'error');
    });
</script>
@endif

@else
<!-- Not authenticated - redirect to login -->
<div class="min-h-screen flex items-center justify-center">
    <div class="profile-card max-w-md text-center">
        <div class="p-8">
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-lock text-red-500 text-3xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Access Denied</h1>
            <p class="text-gray-600 mb-6">You need to be logged in to view your profile.</p>
            <a href="{{ route('login') }}" class="btn-primary">
                <i class="fas fa-sign-in-alt"></i>Login Now
            </a>
        </div>
    </div>
</div>
@endauth

@endsection
