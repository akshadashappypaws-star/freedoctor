@extends('../admin.dashboard')

@section('content')
<div style="padding:15px" class="p-6 rounded shadow bg-slate-800 border border-slate-700">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-white">Admin Settings</h1>
            <p class="text-gray-300 mt-2">Manage commission percentages and system settings</p>
        </div>
    </div>

    <!-- Commission Settings -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Registration Fee Settings -->
        <div class="bg-slate-700 border border-slate-600 rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold text-white mb-4">
                <i class="fas fa-user-plus mr-2 text-blue-400"></i>Registration Fee Commission
            </h3>
            
            @php
                $registrationSetting = $adminSettings->where('setting_key', 'registration_fee_percentage')->first();
            @endphp
            
            <form action="/admin/settings/update" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="setting_key" value="registration_fee_percentage">
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Commission Percentage</label>
                    <div class="flex items-center space-x-2">
                        <input type="number" name="percentage_value" 
                               value="{{ $registrationSetting->percentage_value ?? 5 }}"
                               min="0" max="100" step="0.1"
                               class="flex-1 px-4 py-2 bg-slate-600 border border-slate-500 rounded-lg focus:ring-2 focus:ring-blue-500 text-white">
                        <span class="text-gray-300">%</span>
                    </div>
                    <p class="text-sm text-gray-400 mt-1">Percentage of registration fee taken as commission</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                    <textarea name="description" rows="2"
                              class="w-full px-4 py-2 bg-slate-600 border border-slate-500 rounded-lg focus:ring-2 focus:ring-blue-500 text-white"
                              placeholder="Description of this setting...">{{ $registrationSetting->description ?? 'Commission percentage taken from patient registration fees' }}</textarea>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" 
                           {{ ($registrationSetting->is_active ?? true) ? 'checked' : '' }}
                           class="mr-2 rounded">
                    <label class="text-sm text-gray-300">Active</label>
                </div>
                
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-save mr-2"></i>Update Registration Settings
                </button>
            </form>
        </div>

        <!-- Sponsor Fee Settings -->
        <div class="bg-slate-700 border border-slate-600 rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold text-white mb-4">
                <i class="fas fa-hand-holding-usd mr-2 text-green-400"></i>Sponsor Fee Commission
            </h3>
            
            @php
                $sponsorSetting = $adminSettings->where('setting_key', 'sponsor_fee_percentage')->first();
            @endphp
            
            <form action="/admin/settings/update" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="setting_key" value="sponsor_fee_percentage">
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Commission Percentage</label>
                    <div class="flex items-center space-x-2">
                        <input type="number" name="percentage_value" 
                               value="{{ $sponsorSetting->percentage_value ?? 10 }}"
                               min="0" max="100" step="0.1"
                               class="flex-1 px-4 py-2 bg-slate-600 border border-slate-500 rounded-lg focus:ring-2 focus:ring-green-500 text-white">
                        <span class="text-gray-300">%</span>
                    </div>
                    <p class="text-sm text-gray-400 mt-1">Percentage of sponsor payment taken as commission</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                    <textarea name="description" rows="2"
                              class="w-full px-4 py-2 bg-slate-600 border border-slate-500 rounded-lg focus:ring-2 focus:ring-green-500 text-white"
                              placeholder="Description of this setting...">{{ $sponsorSetting->description ?? 'Commission percentage taken from sponsor payments' }}</textarea>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" 
                           {{ ($sponsorSetting->is_active ?? true) ? 'checked' : '' }}
                           class="mr-2 rounded">
                    <label class="text-sm text-gray-300">Active</label>
                </div>
                
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-save mr-2"></i>Update Sponsor Settings
                </button>
            </form>
        </div>

        <!-- Doctor Subscription Fee Settings -->
        <div class="bg-slate-700 border border-slate-600 rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold text-white mb-4">
                <i class="fas fa-user-md mr-2 text-purple-400"></i>Doctor Subscription Fee
            </h3>
            
            @php
                $doctorSubscriptionSetting = $adminSettings->where('setting_key', 'doctor_subscription_fee')->first();
            @endphp
            
            <form action="/admin/settings/update" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="setting_key" value="doctor_subscription_fee">
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Subscription Fee (₹)</label>
                    <div class="flex items-center space-x-2">
                        <span class="text-gray-300">₹</span>
                        <input type="number" name="percentage_value" 
                               value="{{ $doctorSubscriptionSetting->percentage_value ?? 500 }}"
                               min="0" step="0.01"
                               class="flex-1 px-4 py-2 bg-slate-600 border border-slate-500 rounded-lg focus:ring-2 focus:ring-purple-500 text-white">
                    </div>
                    <p class="text-sm text-gray-400 mt-1">Doctor registration fee (set to 0 for free registration)</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                    <textarea name="description" rows="2"
                              class="w-full px-4 py-2 bg-slate-600 border border-slate-500 rounded-lg focus:ring-2 focus:ring-purple-500 text-white"
                              placeholder="Description of this setting...">{{ $doctorSubscriptionSetting->description ?? 'Registration fee for doctors (set to 0 for free registration)' }}</textarea>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" 
                           {{ ($doctorSubscriptionSetting->is_active ?? true) ? 'checked' : '' }}
                           class="mr-2 rounded">
                    <label class="text-sm text-gray-300">Active</label>
                </div>
                
                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-save mr-2"></i>Update Doctor Fee Settings
                </button>
            </form>
        </div>
    </div>

    <!-- Current Settings Overview -->
    <div class="mt-8 bg-slate-700 border border-slate-600 rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold text-white mb-4">
            <i class="fas fa-chart-pie mr-2 text-purple-400"></i>Current Commission Overview
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-slate-600 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-300 text-sm">Registration Commission</p>
                        <p class="text-2xl font-bold text-blue-400">
                            {{ $adminSettings->where('setting_key', 'registration_fee_percentage')->first()->percentage_value ?? 5 }}%
                        </p>
                    </div>
                    <div class="text-blue-400">
                        <i class="fas fa-user-plus text-2xl"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-2">Per patient registration</p>
            </div>
            
            <div class="bg-slate-600 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-300 text-sm">Sponsor Commission</p>
                        <p class="text-2xl font-bold text-green-400">
                            {{ $adminSettings->where('setting_key', 'sponsor_fee_percentage')->first()->percentage_value ?? 10 }}%
                        </p>
                    </div>
                    <div class="text-green-400">
                        <i class="fas fa-hand-holding-usd text-2xl"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-2">Per sponsor payment</p>
            </div>

            <div class="bg-slate-600 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-300 text-sm">Doctor Subscription Fee</p>
                        <p class="text-2xl font-bold text-purple-400">
                            ₹{{ number_format($adminSettings->where('setting_key', 'doctor_subscription_fee')->first()->percentage_value ?? 500, 2) }}
                        </p>
                    </div>
                    <div class="text-purple-400">
                        <i class="fas fa-user-md text-2xl"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-2">
                    @if(($adminSettings->where('setting_key', 'doctor_subscription_fee')->first()->percentage_value ?? 500) == 0)
                        Free registration enabled
                    @else
                        Per doctor registration
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Settings Help -->
    <div class="mt-6 bg-blue-900/20 border border-blue-500/30 rounded-lg p-4">
        <h4 class="text-lg font-semibold text-blue-300 mb-2">
            <i class="fas fa-info-circle mr-2"></i>How Commission Works
        </h4>
        <div class="text-sm text-blue-200 space-y-2">
            <p><strong>Registration Fee:</strong> When a patient pays a registration fee, the set percentage goes to admin as commission, remainder goes to the doctor.</p>
            <p><strong>Sponsor Fee:</strong> When sponsors pay for campaigns, the set percentage goes to admin as commission, remainder goes to the doctor.</p>
            <p><strong>Doctor Subscription:</strong> Fee charged to doctors for registration. If set to ₹0, doctors can register for free without payment.</p>
            <p><strong>Calculation:</strong> Commission = Total Payment × Percentage ÷ 100</p>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // You can add any JavaScript for settings page here
    console.log('Settings page loaded');
});
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
