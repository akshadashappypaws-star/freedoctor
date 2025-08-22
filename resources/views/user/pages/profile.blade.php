@extends('user.master')

@section('title', 'My Profile - FreeDoctor')

@push('styles')
<style>
    /* Improved UI with better spacing and organization */
    .profile-header {
        background: #383F45!important;
        color: white;
        padding: 3rem 2rem;
        text-align: center;
        border-radius: 20px;
        margin-bottom: 3rem;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .profile-avatar {
        width: 100px;
        height: 100px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        border: 4px solid rgba(255, 255, 255, 0.3);
    }

    .profile-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .profile-subtitle {
        font-size: 1.2rem;
        opacity: 0.9;
        margin-bottom: 0;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .stat-card {
        background: white;
        padding: 2rem 1.5rem;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border: 1px solid #e8e9ea;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
        display: block;
    }

    .stat-label {
        color: #666;
        font-size: 1rem;
        font-weight: 500;
    }

    .section-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 2rem;
        padding-bottom: 0.5rem;
        border-bottom: 3px solid var(--secondary-color);
        display: inline-block;
    }

    .data-table {
        width: 100%;
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
    }

    .table-header {
        background: var(--primary-color);
        color: white;
        padding: 1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .table-content {
        overflow-x: auto;
    }

    .data-table table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        background: #f8f9fa;
        padding: 1rem 0.75rem;
        text-align: left;
        font-weight: 600;
        color: var(--primary-color);
        border-bottom: 2px solid #e9ecef;
        font-size: 0.9rem;
    }

    .data-table td {
        padding: 1rem 0.75rem;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
    }

    .data-table tr:hover {
        background: rgba(44, 42, 76, 0.02);
    }

    .status-badge {
        display: inline-block;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }

    .status-confirmed {
        background: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }

    .status-completed {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .status-hired {
        background: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }

    .action-btn {
        padding: 0.4rem 0.8rem;
        border: none;
        border-radius: 6px;
        font-size: 0.8rem;
        cursor: pointer;
        margin: 0 0.2rem;
        transition: all 0.3s ease;
    }

    .btn-view {
        background: #17a2b8;
        color: white;
    }

    .btn-download {
        background: var(--secondary-color);
        color: var(--primary-color);
    }

    .btn-cancel {
        background: #dc3545;
        color: white;
    }

    .action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #666;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #ccc;
    }

    .tab-container {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    }

    .tab-nav {
        display: flex;
        background: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
    }

    .tab-btn {
        flex: 1;
        padding: 1.2rem 2rem;
        background: none;
        border: none;
        cursor: pointer;
        font-weight: 600;
        color: #666;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 1rem;
    }

    .tab-btn:hover {
        background: rgba(44, 42, 76, 0.05);
        color: var(--primary-color);
    }

    .tab-btn.active {
        background: var(--primary-color);
        color: white;
        position: relative;
    }

    .tab-content {
        padding: 2.5rem;
    }

    .tab-pane {
        display: none;
    }

    .tab-pane.active {
        display: block;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.7rem;
        font-weight: 600;
        color: var(--primary-color);
        font-size: 1rem;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 1rem;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 1rem;
        transition: border-color 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 3px rgba(231, 165, 27, 0.1);
    }

    .btn-group {
        display: flex;
        gap: 1rem;
        margin-top: 2.5rem;
    }

    .menu-links {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 3rem;
        justify-content: center;
    }

    .menu-link {
        padding: 1rem 1.8rem;
        background: var(--secondary-color);
        color: var(--primary-color);
        text-decoration: none;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.95rem;
    }

    .menu-link:hover {
        background: #d4751a;
        transform: translateY(-2px);
        text-decoration: none;
        color: var(--primary-color);
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }

    .btn-danger {
        background: #dc3545;
        color: white;
        padding: 1rem 1.8rem;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-danger:hover {
        background: #c82333;
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    @media (max-width: 768px) {
        .profile-header {
            padding: 2rem 1rem;
        }

        .profile-title {
            font-size: 2rem;
        }

        .profile-subtitle {
            font-size: 1rem;
        }

        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }

        .stat-card {
            padding: 1.5rem 1rem;
        }

        .stat-number {
            font-size: 2rem;
        }

        .section-title {
            font-size: 1.4rem;
        }

        .tab-nav {
            flex-direction: column;
        }

        .tab-btn {
            padding: 1rem;
            font-size: 0.9rem;
        }

        .tab-content {
            padding: 1.5rem;
        }

        .form-row {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .btn-group {
            flex-direction: column;
        }

        .menu-links {
            justify-content: center;
            gap: 0.8rem;
        }

        .menu-link {
            padding: 0.8rem 1.2rem;
            font-size: 0.9rem;
        }

        .data-table {
            font-size: 0.85rem;
        }

        .data-table th,
        .data-table td {
            padding: 0.7rem 0.5rem;
        }

        .action-btn {
            padding: 0.3rem 0.6rem;
            font-size: 0.75rem;
            margin: 0.1rem;
        }

        .table-content {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .data-table table {
            min-width: 600px;
        }
    }

    @media (max-width: 480px) {
        .profile-header {
            padding: 1.5rem 0.8rem;
        }

        .profile-title {
            font-size: 1.8rem;
        }

        .stats-grid {
            grid-template-columns: 1fr 1fr;
        }

        .menu-links {
            gap: 0.5rem;
        }

        .menu-link {
            padding: 0.7rem 1rem;
            font-size: 0.85rem;
        }
    }
</style>
@endpush

@section('content')
@auth('user')
<div class="container mx-auto px-4 py-4">
    
    <!-- Profile Header -->
    <div class="profile-header">
        <div class="profile-avatar">
            <i class="fas fa-user text-4xl text-white"></i>
        </div>
        <h1 class="profile-title">{{ auth()->user()->username }}</h1>
        <p class="profile-subtitle">{{ auth()->user()->email }}</p>
    </div>

    <!-- Menu Links -->
    <div class="menu-links">
        <a href="{{ route('user.dashboard') }}" class="menu-link">
            <i class="fas fa-home"></i>Dashboard
        </a>
        <a href="{{ route('user.campaigns') }}" class="menu-link">
            <i class="fas fa-calendar"></i>Medical Camps
        </a>
        <a href="{{ route('user.organization-camp-request') }}" class="menu-link">
            <i class="fas fa-building"></i>Organization Request
        </a>
        <a href="{{ route('user.my-registrations') }}" class="menu-link">
            <i class="fas fa-list"></i>My Registrations
        </a>
          <a href="{{ route('user.referral-dashboard') }}" class="menu-link">
            <i class="fas fa-list"></i>Referral Dashboard
        </a>
        <a href="{{ route('user.logout') }}" class="menu-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i>Logout
        </a>
        <form id="logout-form" action="{{ route('user.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

    <!-- Profile Statistics -->
    <div class="stats-grid">
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

    <!-- Tab Container -->
    <div class="tab-container">
        <!-- Tab Navigation -->
        <div class="tab-nav">
            <button class="tab-btn active" onclick="showTab('editProfile', this)">
                <i class="fas fa-edit"></i>Edit Profile
            </button>
            <button class="tab-btn" onclick="showTab('changePassword', this)">
                <i class="fas fa-lock"></i>Change Password
            </button>
            <button class="tab-btn" onclick="showTab('deleteAccount', this)">
                <i class="fas fa-trash-alt"></i>Delete Account
            </button>
        </div>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Edit Profile Tab -->
            <div id="editProfile" class="tab-pane active">
                <h3 class="text-xl font-bold mb-4 text-primary-color">
                    <i class="fas fa-user-edit mr-2"></i>Edit Profile Information
                </h3>
                
                @if(session('success'))
                <div class="alert alert-success mb-4">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <i class="fas fa-exclamation-circle mr-2"></i>Please correct the errors below.
                </div>
                @endif
                
                <form action="{{ route('user.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="username">Full Name *</label>
                            <input type="text" id="username" name="username" 
                                   value="{{ old('username', auth()->user()->username) }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" 
                                   value="{{ old('email', auth()->user()->email) }}" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" 
                                   value="{{ old('phone', auth()->user()->phone) }}">
                        </div>
                        
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select id="gender" name="gender">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', auth()->user()->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', auth()->user()->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender', auth()->user()->gender) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea id="address" name="address" rows="3" 
                                  placeholder="Enter your complete address">{{ old('address', auth()->user()->address) }}</textarea>
                    </div>
                    
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>Update Profile
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            <i class="fas fa-undo mr-2"></i>Reset Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password Tab -->
            <div id="changePassword" class="tab-pane">
                <h3 class="text-xl font-bold mb-4 text-primary-color">
                    <i class="fas fa-key mr-2"></i>Change Password
                </h3>
                
                <form action="{{ route('user.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="current_password">Current Password *</label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="new_password">New Password *</label>
                            <input type="password" id="new_password" name="new_password" required>
                            <small class="text-muted">Minimum 8 characters</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password_confirmation">Confirm New Password *</label>
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation" required>
                        </div>
                    </div>
                    
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-shield-alt mr-2"></i>Update Password
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            <i class="fas fa-undo mr-2"></i>Clear Form
                        </button>
                    </div>
                </form>
            </div>

            <!-- Delete Account Tab -->
            <div id="deleteAccount" class="tab-pane">
                <h3 class="text-xl font-bold mb-4 text-red-600">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Delete Account
                </h3>
                
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-red-500 text-xl mr-3 mt-1"></i>
                        <div>
                            <h4 class="text-red-800 font-semibold mb-2">Warning: This action cannot be undone</h4>
                            <p class="text-red-700 text-sm mb-2">Deleting your account will permanently remove:</p>
                            <ul class="text-red-700 text-sm list-disc list-inside space-y-1">
                                <li>Your profile information and personal data</li>
                                <li>All medical camp registrations and history</li>
                                <li>Sponsorship requests and organization requests</li>
                                <li>Referral earnings and account balance</li>
                                <li>All associated records and communications</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                    <h4 class="text-gray-800 font-semibold mb-2">Account Summary</h4>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Camp Registrations:</span>
                            <span class="font-semibold">{{ $userRegistrations }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Referral Earnings:</span>
                            <span class="font-semibold">₹{{ number_format($referralEarnings, 2) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Member Since:</span>
                            <span class="font-semibold">{{ auth()->user()->created_at->format('M d, Y') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Account Status:</span>
                            <span class="font-semibold text-green-600">Active</span>
                        </div>
                    </div>
                </div>

                <form action="{{ route('user.account.delete') }}" method="POST" id="deleteAccountForm">
                    @csrf
                    @method('DELETE')
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm Your Password to Delete Account *</label>
                        <input type="password" id="confirm_password" name="password" required 
                               placeholder="Enter your current password to confirm deletion">
                    </div>
                    
                    <div class="form-group">
                        <label class="flex items-center">
                            <input type="checkbox" id="confirm_deletion" required class="mr-2">
                            <span class="text-sm">I understand that this action is permanent and cannot be undone</span>
                        </label>
                    </div>
                    
                    <div class="btn-group">
                        <button type="button" class="btn btn-danger" onclick="confirmAccountDeletion()">
                            <i class="fas fa-trash-alt mr-2"></i>Delete My Account Permanently
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="showTab('editProfile', document.querySelector('.tab-btn'))">
                            <i class="fas fa-arrow-left mr-2"></i>Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Simple tab functionality
function showTab(tabName, element) {
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
    element.classList.add('active');
}

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    // Profile form validation
    const profileForm = document.querySelector('form[action*="profile.update"]');
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const username = document.getElementById('username').value;
            
            if (!email || !username) {
                e.preventDefault();
                alert('Please fill in all required fields.');
                return false;
            }
        });
    }
    
    // Password form validation
    const passwordForm = document.querySelector('form[action*="user.password.update"]');
    if (passwordForm) {
        passwordForm.addEventListener('submit', function(e) {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('new_password_confirmation').value;
            
            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('New passwords do not match.');
                return false;
            }
            
            if (newPassword.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long.');
                return false;
            }
        });
    }
});

// Account deletion confirmation
function confirmAccountDeletion() {
    const passwordField = document.getElementById('confirm_password');
    const confirmCheckbox = document.getElementById('confirm_deletion');
    
    if (!passwordField.value) {
        alert('Please enter your password to confirm account deletion.');
        passwordField.focus();
        return;
    }
    
    if (!confirmCheckbox.checked) {
        alert('Please confirm that you understand this action is permanent.');
        confirmCheckbox.focus();
        return;
    }
    
    const confirmed = confirm(
        'FINAL WARNING: Are you absolutely sure you want to delete your account?\n\n' +
        'This will permanently remove:\n' +
        '• All your personal information\n' +
        '• Medical camp registrations\n' +
        '• Referral earnings\n' +
        '• All account data\n\n' +
        'This action CANNOT be undone!\n\n' +
        'Click OK to proceed with deletion, or Cancel to keep your account.'
    );
    
    if (confirmed) {
        document.getElementById('deleteAccountForm').submit();
    }
}
</script>

@else
<!-- Not authenticated - redirect to login -->
<div class="container mx-auto px-4 py-8 text-center">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md mx-auto">
        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-lock text-red-500 text-3xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Access Denied</h1>
        <p class="text-gray-600 mb-6">You need to be logged in to view your profile.</p>
        <a href="{{ route('login') }}" class="btn btn-primary">
            <i class="fas fa-sign-in-alt mr-2"></i>Login Now
        </a>
    </div>
</div>
@endauth

@endsection

