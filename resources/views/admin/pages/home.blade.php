@extends('../admin.dashboard')

@section('title', 'FreeDoctor - Admin Dashboard')

@section('content')
<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Include Laravel Echo and Pusher -->
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.0/dist/echo.iife.js"></script>

<div class="p-6 space-y-8">
    <!-- Dashboard Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
        <div>
            <h1 class="text-3xl font-bold text-white">Admin Dashboard</h1>
            <p class="text-gray-300 mt-2">Welcome back, {{ $admin->name }}</p>
            <p class="text-sm text-gray-400" id="lastUpdated">
                Last updated: <span id="updateTime">{{ now()->format('H:i:s') }}</span>
                <span id="updateIndicator" class="ml-2 text-green-400" style="display: none;">
                    <i class="fas fa-circle text-xs animate-pulse"></i> Live
                </span>
            </p>
        </div>
        <div class="flex space-x-3 mt-4 md:mt-0">
            <button onclick="refreshAll()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-sync mr-2"></i>Refresh
            </button>
            <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-download mr-2"></i>Export Report
            </button>
        </div>
    </div>

    <!-- Main Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Campaigns -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white card-animate">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Campaigns</p>
                    <p class="text-3xl font-bold" id="totalCampaigns">{{ $totalCampaigns }}</p>
                    <p class="text-blue-200 text-xs mt-1">Medical camps organized</p>
                </div>
                <div class="bg-blue-400 bg-opacity-30 rounded-lg p-3">
                    <i class="fas fa-calendar-plus text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Doctors -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white card-animate">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Total Doctors</p>
                    <p class="text-3xl font-bold" id="totalDoctors">{{ $totalDoctors }}</p>
                    <p class="text-green-200 text-xs mt-1">Registered professionals</p>
                </div>
                <div class="bg-green-400 bg-opacity-30 rounded-lg p-3">
                    <i class="fas fa-user-md text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Patients -->
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white card-animate">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Total Patients</p>
                    <p class="text-3xl font-bold" id="totalPatients">{{ $totalPatients }}</p>
                    <p class="text-purple-200 text-xs mt-1">Registered users</p>
                </div>
                <div class="bg-purple-400 bg-opacity-30 rounded-lg p-3">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Sponsors -->
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl p-6 text-white card-animate">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Total Sponsors</p>
                    <p class="text-3xl font-bold" id="totalSponsors">{{ $totalSponsors }}</p>
                    <p class="text-orange-200 text-xs mt-1">Campaign sponsors</p>
                </div>
                <div class="bg-orange-400 bg-opacity-30 rounded-lg p-3">
                    <i class="fas fa-handshake text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Business Opportunities & Earnings Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Business Opportunities -->
        <div class="bg-gradient-to-r from-teal-500 to-teal-600 rounded-xl p-6 text-white card-animate">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-teal-100 text-sm font-medium">Business Opportunities</p>
                    <p class="text-3xl font-bold" id="totalBusinessOpportunities">{{ $totalBusinessOpportunities }}</p>
                    <p class="text-teal-200 text-xs mt-1">Active requests</p>
                </div>
                <div class="bg-teal-400 bg-opacity-30 rounded-lg p-3">
                    <i class="fas fa-briefcase text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- User Registration Earnings -->
        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-xl p-6 text-white card-animate">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-indigo-100 text-sm font-medium">User Registration Earnings</p>
                    <p class="text-3xl font-bold" id="userRegistrationEarnings">₹{{ number_format($userRegistrationEarnings, 2) }}</p>
                    <p class="text-indigo-200 text-xs mt-1">From patient registrations</p>
                </div>
                <div class="bg-indigo-400 bg-opacity-30 rounded-lg p-3">
                    <i class="fas fa-user-plus text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Doctor Registration Earnings -->
        <div class="bg-gradient-to-r from-pink-500 to-pink-600 rounded-xl p-6 text-white card-animate">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-pink-100 text-sm font-medium">Doctor Register Earnings</p>
                    <p class="text-3xl font-bold" id="doctorRegistrationEarnings">₹{{ number_format($doctorRegistrationEarnings, 2) }}</p>
                    <p class="text-pink-200 text-xs mt-1">From doctor subscriptions</p>
                </div>
                <div class="bg-pink-400 bg-opacity-30 rounded-lg p-3">
                    <i class="fas fa-stethoscope text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Sponsor Earnings -->
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl p-6 text-white card-animate">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Sponsor Earnings</p>
                    <p class="text-3xl font-bold" id="sponsorEarnings">₹{{ number_format($sponsorEarnings, 2) }}</p>
                    <p class="text-yellow-200 text-xs mt-1">From sponsor commissions</p>
                </div>
                <div class="bg-yellow-400 bg-opacity-30 rounded-lg p-3">
                    <i class="fas fa-coins text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Earnings Card -->
    <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl p-8 text-white">
        <div class="text-center">
            <p class="text-emerald-100 text-lg font-medium mb-2">Total Earnings</p>
            <p class="text-5xl font-bold mb-4" id="totalEarnings">₹{{ number_format($totalEarnings, 2) }}</p>
            <p class="text-emerald-200">Combined revenue from all sources</p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Past 10 Days Earnings Chart -->
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-6">
            <h3 class="text-xl font-semibold text-white mb-4">
                <i class="fas fa-chart-line mr-2 text-blue-400"></i>
                Past 10 Days Earnings
            </h3>
            <div class="h-80">
                <canvas id="past10DaysChart"></canvas>
            </div>
        </div>

        <!-- Monthly Earnings Chart -->
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-6">
            <h3 class="text-xl font-semibold text-white mb-4">
                <i class="fas fa-chart-bar mr-2 text-green-400"></i>
                Monthly Earnings (Past 12 Months)
            </h3>
            <div class="h-80">
                <canvas id="monthlyEarningsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Doctor / Patient Payments -->
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-6">
            <h3 class="text-xl font-semibold text-white mb-4">
                <i class="fas fa-credit-card mr-2 text-purple-400"></i>
                Recent Doctor / Patient Payments
            </h3>
            <div class="space-y-4 max-h-80 overflow-y-auto" id="recentPaymentsContainer">
                @foreach($recentPayments as $payment)
                <div class="flex items-center justify-between p-3 bg-slate-700 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 {{ $payment->type == 'doctor' ? 'bg-purple-600' : 'bg-blue-600' }} rounded-full flex items-center justify-center">
                            <span class="text-white font-semibold text-sm">{{ substr($payment->name, 0, 2) }}</span>
                        </div>
                        <div class="ml-3">
                            <p class="text-white font-medium">{{ $payment->name }}</p>
                            <p class="text-gray-400 text-sm">{{ $payment->description }}</p>
                            <p class="text-gray-500 text-xs">{{ $payment->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                      <p class="text-green-400 font-semibold">₹{{ number_format($payment->amount, 2) }}</p>
<p class="text-gray-400 text-sm">Admin Share</p>
@if($payment->type == 'doctor')
    <p class="text-gray-500 text-xs">From ₹{{ number_format($payment->amount, 2) }}</p>
@else
    <p class="text-gray-500 text-xs">Patient Payment</p>
@endif
                    </div>
                </div>
                @endforeach
                @if($recentPayments->isEmpty())
                <div class="text-center py-8">
                    <p class="text-gray-400">No recent payments found</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Recent Campaigns -->
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-6">
            <h3 class="text-xl font-semibold text-white mb-4">
                <i class="fas fa-calendar-alt mr-2 text-orange-400"></i>
                Recent Campaigns
            </h3>
            <div class="space-y-4 max-h-80 overflow-y-auto" id="recentCampaignsContainer">
                @foreach($recentCampaigns as $campaign)
                <div class="p-3 bg-slate-700 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-white font-medium">{{ $campaign->title }}</p>
                            <p class="text-gray-400 text-sm">{{ $campaign->location }}</p>
                            @php
                                $campaignDate = null;
                                try {
                                    if ($campaign->start_date) {
                                        if ($campaign->start_date instanceof \Carbon\Carbon) {
                                            $campaignDate = $campaign->start_date;
                                        } else {
                                            $campaignDate = \Carbon\Carbon::parse($campaign->start_date);
                                        }
                                    }
                                } catch (\Exception $e) {
                                    $campaignDate = null;
                                }
                            @endphp
                            
                            @if($campaignDate)
                                <p class="text-blue-400 text-sm">{{ $campaignDate->format('M d, Y') }}</p>
                            @else
                                <p class="text-gray-500 text-sm">Date not set</p>
                            @endif
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($campaign->status === 'active') bg-green-100 text-green-800
                                @elseif($campaign->status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($campaign->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- End of additional content section -->
</div>

<style>
    .card-animate {
        transition: all 0.3s ease;
    }
    .card-animate:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    }
    
    /* Real-time value update animations */
    #totalCampaigns, #totalDoctors, #totalPatients, #totalSponsors, 
    #totalBusinessOpportunities, #userRegistrationEarnings, 
    #doctorRegistrationEarnings, #sponsorEarnings, #totalEarnings {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .value-update {
        animation: valueFlash 0.5s ease-in-out;
    }
    
    @keyframes valueFlash {
        0% { background-color: rgba(34, 197, 94, 0.3); }
        50% { background-color: rgba(34, 197, 94, 0.6); }
        100% { background-color: transparent; }
    }
    
    /* Rolling number animation */
    .number-roll {
        position: relative;
        display: inline-block;
        overflow: hidden;
        height: 1.2em;
    }
    
    .number-roll-container {
        position: relative;
        transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
    
    .number-digit {
        display: block;
        height: 1.2em;
        line-height: 1.2em;
    }
    
    /* Smooth counting animation */
    .count-up {
        animation: countUp 0.8s ease-out;
    }
    
    @keyframes countUp {
        from {
            transform: translateY(100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    /* Enhanced value change animation */
    .value-change {
        animation: smoothChange 0.6s ease-in-out;
    }
    
    @keyframes smoothChange {
        0% { 
            transform: scale(1) translateY(0);
            color: inherit;
        }
        25% { 
            transform: scale(1.05) translateY(-2px);
            color: #22c55e;
        }
        50% { 
            transform: scale(1.1) translateY(-4px);
            color: #16a34a;
            text-shadow: 0 0 10px rgba(34, 197, 94, 0.5);
        }
        75% { 
            transform: scale(1.05) translateY(-2px);
            color: #22c55e;
        }
        100% { 
            transform: scale(1) translateY(0);
            color: inherit;
            text-shadow: none;
        }
    }
    
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    /* Number transition effects */
    .number-transition {
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
</style>

<script>
// Chart data from PHP
const past10DaysData = @json($past10DaysEarnings);
const monthlyData = @json($monthlyEarnings);

// Initialize Laravel Echo with Pusher (with error handling)
window.Pusher = Pusher;

try {
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: '{{ env("PUSHER_APP_KEY") }}',
        cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
        forceTLS: true,
        auth: {
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Authorization': 'Bearer {{ session("admin_token") ?? "admin-auth" }}'
            }
        }
    });
    
    // Test connection
    Echo.connector.pusher.connection.bind('error', function(error) {
        console.warn('Pusher connection error:', error);
        console.log('Falling back to polling mode...');
        // Fallback to more frequent polling
        setInterval(refreshDashboard, 5000);
    });
} catch (error) {
    console.warn('Failed to initialize Pusher:', error);
    console.log('Using polling mode instead...');
    // Fallback to polling every 5 seconds
    setInterval(refreshDashboard, 5000);
}

// Initialize Past 10 Days Chart
const ctx1 = document.getElementById('past10DaysChart').getContext('2d');
const past10DaysChart = new Chart(ctx1, {
    type: 'line',
    data: {
        labels: past10DaysData.map(item => item.date),
        datasets: [{
            label: 'Daily Earnings (₹)',
            data: past10DaysData.map(item => item.amount),
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: {
                    color: '#fff'
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    color: '#94a3b8',
                    callback: function(value) {
                        return '₹' + value.toLocaleString();
                    }
                },
                grid: {
                    color: '#334155'
                }
            },
            x: {
                ticks: {
                    color: '#94a3b8'
                },
                grid: {
                    color: '#334155'
                }
            }
        }
    }
});

// Initialize Monthly Earnings Chart
const ctx2 = document.getElementById('monthlyEarningsChart').getContext('2d');
const monthlyEarningsChart = new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: monthlyData.map(item => item.month),
        datasets: [{
            label: 'Monthly Earnings (₹)',
            data: monthlyData.map(item => item.amount),
            backgroundColor: 'rgba(34, 197, 94, 0.8)',
            borderColor: 'rgb(34, 197, 94)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: {
                    color: '#fff'
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    color: '#94a3b8',
                    callback: function(value) {
                        return '₹' + value.toLocaleString();
                    }
                },
                grid: {
                    color: '#334155'
                }
            },
            x: {
                ticks: {
                    color: '#94a3b8'
                },
                grid: {
                    color: '#334155'
                }
            }
        }
    }
});

// Enhanced function to update value with smooth rolling animation
function updateValueWithAnimation(elementId, newValue, isNumber = false) {
    const element = document.getElementById(elementId);
    const oldValue = element.textContent;
    
    if (oldValue !== newValue) {
        if (isNumber && !isNaN(newValue)) {
            // Animate number counting
            animateNumber(element, parseInt(oldValue.replace(/[^\d]/g, '')) || 0, parseInt(newValue.toString().replace(/[^\d]/g, '')), newValue);
        } else {
            // Regular text update with animation
            element.classList.add('value-change');
            setTimeout(() => {
                element.textContent = newValue;
            }, 300);
            setTimeout(() => {
                element.classList.remove('value-change');
            }, 600);
        }
    }
}

// Function to animate numbers smoothly
function animateNumber(element, start, end, finalText) {
    const duration = 800;
    const startTime = performance.now();
    const isEarnings = finalText.includes('₹');
    
    function updateNumber(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        // Use easing function for smooth animation
        const easeOutCubic = 1 - Math.pow(1 - progress, 3);
        const current = Math.round(start + (end - start) * easeOutCubic);
        
        if (isEarnings) {
            element.textContent = '₹' + current.toLocaleString('en-IN', {minimumFractionDigits: 2});
        } else {
            element.textContent = current.toLocaleString();
        }
        
        // Add visual effects during animation
        if (progress < 1) {
            element.style.transform = `scale(${1 + Math.sin(progress * Math.PI) * 0.1})`;
            element.style.color = `rgb(${34 + Math.sin(progress * Math.PI) * 50}, ${197}, ${94})`;
            requestAnimationFrame(updateNumber);
        } else {
            // Final update and reset styles
            element.textContent = finalText;
            element.style.transform = 'scale(1)';
            element.style.color = '';
            element.classList.add('value-change');
            setTimeout(() => {
                element.classList.remove('value-change');
            }, 300);
        }
    }
    
    requestAnimationFrame(updateNumber);
}

// Real-time Broadcasting Event Listeners (with error handling)
if (window.Echo && window.Echo.connector) {
    Echo.private('admin-dashboard')
        .listen('.dashboard.updated', (e) => {
            console.log('📊 Dashboard Update Received:', e);
            
            // Show live indicator
            const indicator = document.getElementById('updateIndicator');
            indicator.style.display = 'inline';
            
            const data = e.data;
            
            // Update main statistics with smooth animations
            updateValueWithAnimation('totalCampaigns', data.totalCampaigns, true);
            updateValueWithAnimation('totalDoctors', data.totalDoctors, true);
            updateValueWithAnimation('totalPatients', data.totalPatients, true);
            updateValueWithAnimation('totalSponsors', data.totalSponsors, true);
            updateValueWithAnimation('totalBusinessOpportunities', data.totalBusinessOpportunities, true);
            
            // Update all earnings values with smooth rolling animations
            updateValueWithAnimation('userRegistrationEarnings', '₹' + parseFloat(data.userRegistrationEarnings).toLocaleString('en-IN', {minimumFractionDigits: 2}));
            updateValueWithAnimation('doctorRegistrationEarnings', '₹' + parseFloat(data.doctorRegistrationEarnings).toLocaleString('en-IN', {minimumFractionDigits: 2}));
            updateValueWithAnimation('sponsorEarnings', '₹' + parseFloat(data.sponsorEarnings).toLocaleString('en-IN', {minimumFractionDigits: 2}));
            updateValueWithAnimation('totalEarnings', '₹' + parseFloat(data.totalEarnings).toLocaleString('en-IN', {minimumFractionDigits: 2}));
            
            // Update timestamp
            document.getElementById('updateTime').textContent = data.timestamp;
            
            // Hide indicator after a moment
            setTimeout(() => {
                indicator.style.display = 'none';
            }, 1000);
        })
        .listen('.charts.updated', (e) => {
            console.log('📈 Charts Update Received:', e);
            
            const chartData = e.chartData;
            
            // Update Past 10 Days Chart
            past10DaysChart.data.labels = chartData.past10Days.map(item => item.date);
            past10DaysChart.data.datasets[0].data = chartData.past10Days.map(item => item.amount);
            past10DaysChart.update('none');
            
            // Update Monthly Chart
            monthlyEarningsChart.data.labels = chartData.monthly.map(item => item.month);
            monthlyEarningsChart.data.datasets[0].data = chartData.monthly.map(item => item.amount);
            monthlyEarningsChart.update('none');
        })
        .listen('.activities.updated', (e) => {
            console.log('🔄 Activities Update Received:', e);
            
            const activities = e.activities;
            
            // Update Recent Payments
            const paymentsContainer = document.getElementById('recentPaymentsContainer');
            if (activities.recentPayments.length > 0) {
                paymentsContainer.innerHTML = activities.recentPayments.map(payment => `
                    <div class="flex items-center justify-between p-3 bg-slate-700 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-10 h-10 ${payment.type === 'doctor' ? 'bg-purple-600' : 'bg-blue-600'} rounded-full flex items-center justify-center">
                                <span class="text-white font-semibold text-sm">${payment.name.substring(0, 2)}</span>
                            </div>
                            <div class="ml-3">
                                <p class="text-white font-medium">${payment.name}</p>
                                <p class="text-gray-400 text-sm">${payment.description}</p>
                                <p class="text-gray-500 text-xs">${payment.created_at}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-green-400 font-semibold">₹${parseFloat(payment.admin_commission).toLocaleString('en-IN', {minimumFractionDigits: 2})}</p>
                            <p class="text-gray-400 text-sm">Admin Share</p>
                            <p class="text-gray-500 text-xs">${payment.type === 'doctor' ? 'From ₹' + parseFloat(payment.amount).toLocaleString('en-IN', {minimumFractionDigits: 2}) : 'Patient Payment'}</p>
                        </div>
                    </div>
                `).join('');
            } else {
                paymentsContainer.innerHTML = '<div class="text-center py-8"><p class="text-gray-400">No recent payments found</p></div>';
            }

            // Update Recent Campaigns
            const campaignsContainer = document.getElementById('recentCampaignsContainer');
            campaignsContainer.innerHTML = activities.recentCampaigns.map(campaign => `
                <div class="p-3 bg-slate-700 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-white font-medium">${campaign.title}</p>
                            <p class="text-gray-400 text-sm">${campaign.location}</p>
                            <p class="text-blue-400 text-sm">${campaign.start_date}</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                                campaign.status === 'active' ? 'bg-green-100 text-green-800' :
                                campaign.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                'bg-red-100 text-red-800'
                            }">
                                ${campaign.status.charAt(0).toUpperCase() + campaign.status.slice(1)}
                            </span>
                        </div>
                    </div>
                </div>
            `).join('');
        });

    // Connection status monitoring
    Echo.connector.pusher.connection.bind('connected', function() {
        console.log('🟢 Real-time connection established');
        document.getElementById('updateIndicator').innerHTML = '<i class="fas fa-circle text-xs animate-pulse"></i> Live';
    });

    Echo.connector.pusher.connection.bind('disconnected', function() {
        console.log('🔴 Real-time connection lost');
        document.getElementById('updateIndicator').innerHTML = '<i class="fas fa-circle text-xs text-red-400"></i> Offline';
    });
} else {
    console.log('⚠️ Real-time broadcasting not available, using polling mode');
}

// Fallback AJAX functions (keep as backup)
function refreshDashboard() {
    fetch('/admin/dashboard-data')
        .then(response => response.json())
        .then(data => {
            updateValueWithAnimation('totalCampaigns', data.totalCampaigns, true);
            updateValueWithAnimation('totalDoctors', data.totalDoctors, true);
            updateValueWithAnimation('totalPatients', data.totalPatients, true);
            updateValueWithAnimation('totalSponsors', data.totalSponsors, true);
            updateValueWithAnimation('totalBusinessOpportunities', data.totalBusinessOpportunities, true);
            
            updateValueWithAnimation('userRegistrationEarnings', '₹' + parseFloat(data.userRegistrationEarnings).toLocaleString('en-IN', {minimumFractionDigits: 2}));
            updateValueWithAnimation('doctorRegistrationEarnings', '₹' + parseFloat(data.doctorRegistrationEarnings).toLocaleString('en-IN', {minimumFractionDigits: 2}));
            updateValueWithAnimation('sponsorEarnings', '₹' + parseFloat(data.sponsorEarnings).toLocaleString('en-IN', {minimumFractionDigits: 2}));
            updateValueWithAnimation('totalEarnings', '₹' + parseFloat(data.totalEarnings).toLocaleString('en-IN', {minimumFractionDigits: 2}));
            
            document.getElementById('updateTime').textContent = data.timestamp;
        })
        .catch(error => console.error('Error refreshing dashboard:', error));
}

// Manual refresh function for button
function refreshAll() {
    refreshDashboard();
    // Trigger manual broadcasts (for testing)
    fetch('/admin/trigger-broadcast', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    });
}

// Fallback polling (reduced frequency since we have real-time)
setInterval(refreshDashboard, 30000); // Every 30 seconds as backup

// Initial load after page is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Dashboard Initialized with Real-time Broadcasting');
    
    // Add animation to the cards
    const cards = document.querySelectorAll('.bg-gradient-to-r');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>

@endsection
