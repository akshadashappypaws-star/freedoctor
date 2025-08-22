@extends('../doctor.master')

@section('title', 'FreeDoctor - Doctor Dashboard')

@push('styles')
<style>
    .text-white {
        --tw-text-opacity: 1;
        color: rgb(255 255 255 / var(--tw-text-opacity, 1));
    }
    
    .glass-effect {
        background: rgba(30, 41, 59, 0.15);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(148, 163, 184, 0.2);
    }
    
    .card-animate {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .card-animate:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .stats-gradient {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(147, 51, 234, 0.1) 100%);
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: rgba(30, 41, 59, 0.1);
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: rgba(59, 130, 246, 0.3);
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: rgba(59, 130, 246, 0.5);
    }
    .textwhite {
    --tw-text-opacity: 1;
    color: rgb(255 255 255 / var(--tw-text-opacity, 1))!important;
}
</style>
@endpush

@section('content')
<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 p-6 space-y-8">
    <!-- Dashboard Header -->
    <div class="bg-gradient-to-r from-slate-800/80 to-slate-700/60 rounded-xl p-8 text-white border border-slate-600/30 backdrop-blur-sm">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-2xl">
                    <i class="fas fa-chart-line text-2xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-bold textwhite bg-gradient-to-r from-blue-400 to-purple-300 bg-clip-text text-transparent">
                        Doctor Earning
                    </h1>
                    <p class="textwhite mt-2 text-lg">Welcome back, Dr. {{ auth('doctor')->user()->doctor_name }}</p>
                    <p class="text-sm textwhite" id="lastUpdated">
                        Last updated: <span id="updateTime" class="text-blue-400">{{ now()->format('H:i:s') }}</span>
                        <span id="updateIndicator" class="ml-2 text-green-400" style="display: none;">
                            <i class="fas fa-circle text-xs animate-pulse"></i> Live
                        </span>
                    </p>
                </div>
            </div>
            <!-- <div class="flex gap-3">
                <button onclick="refreshAll()" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-xl font-medium transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 border border-blue-500/20">
                    <i class="fas fa-sync mr-2"></i>Refresh
                </button>
                <button class="bg-gradient-to-r from-emerald-600 to-teal-700 hover:from-emerald-700 hover:to-teal-800 text-white px-6 py-3 rounded-xl font-medium transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 border border-emerald-500/20">
                    <i class="fas fa-download mr-2"></i>Export Report
                </button>
            </div> -->
        </div>
    </div>

    <!-- Main Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
        <!-- My Campaigns -->
        <div class="glass-effect rounded-xl p-3 text-white card-animate border border-blue-500/20 hover:border-blue-400/40">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white text-sm font-medium uppercase tracking-wider mb-2">My Campaigns</p>
                    <p class="text-3xl font-bold text-white" id="myCampaigns">{{ $doctorCampaigns ?? 0 }}</p>
                    <p class="text-white text-xs mt-1">Active medical camps</p>
                </div>
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-4 rounded-xl shadow-lg">
                    <i class="fas fa-calendar-plus text-2xl text-white"></i>
                </div>
            </div>
        </div>

        <!-- My Patients -->
        <div class="glass-effect rounded-xl p-6 text-white card-animate border border-emerald-500/20 hover:border-emerald-400/40">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white text-sm font-medium uppercase tracking-wider mb-2">My Patients</p>
                    <p class="text-2xl font-bold text-white" id="myPatients">{{ $doctorPatients ?? 0 }}</p>
                    <p class="text-white text-xs mt-1">Total registrations</p>
                </div>
                <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 p-4 rounded-xl shadow-lg">
                    <i class="fas fa-user-md text-2xl text-white"></i>
                </div>
            </div>
        </div>

        <!-- Pending Approvals -->
        <div class="glass-effect rounded-xl p-6 text-white card-animate border border-purple-500/20 hover:border-purple-400/40">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white text-sm font-medium uppercase tracking-wider mb-2">Pending Approvals</p>
                    <p class="text-2xl font-bold text-white" id="pendingApprovals">{{ $pendingCampaigns ?? 0 }}</p>
                    <p class="text-white text-xs mt-1">Awaiting admin approval</p>
                </div>
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-4 rounded-xl shadow-lg">
                    <i class="fas fa-clock text-2xl text-white"></i>
                </div>
            </div>
        </div>

        <!-- Total Earnings -->
        <!-- <div class="glass-effect rounded-xl p-6 text-white card-animate border border-orange-500/20 hover:border-orange-400/40">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Total Earnings</p>
                    <p class="text-2xl font-bold" id="totalEarnings">₹{{ number_format($doctorEarnings ?? 0, 2) }}</p>
                    <p class="text-orange-200 text-xs mt-1">From registrations</p>
                </div>
                <div class="bg-orange-400 bg-opacity-30 rounded-lg p-3">
                    <i class="fas fa-rupee-sign text-2xl"></i>
                </div>
            </div>
        </div> -->
    </div>

    <!-- Business Opportunities & Earnings Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-3">
        <!-- Business Opportunities -->
      
        <!-- Patient Registration Earnings -->
        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-xl p-6 text-white card-animate">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-indigo-100 text-sm font-medium">Patient Registration Earnings</p>
                    <p class="text-3xl font-bold" id="userRegistrationEarnings">₹{{ number_format($userRegistrationEarnings ?? 0, 2) }}</p>
                    <p class="text-indigo-200 text-xs mt-1">From your campaigns</p>
                </div>
                <div class="bg-indigo-400 bg-opacity-30 rounded-lg p-3">
                    <i class="fas fa-user-plus text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Sponsor Earnings -->
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl p-6 text-white card-animate">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Sponsor Earnings</p>
                    <p class="text-2xl font-bold" id="sponsorEarnings">₹{{ number_format($sponsorEarnings ?? 0, 2) }}</p>
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
            <p class="text-4xl font-bold mb-4" id="totalEarnings">₹{{ number_format($totalEarnings ?? 0, 2) }}</p>
            <p class="text-emerald-200">Combined revenue from all sources</p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Past 10 Days Earnings Chart -->
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-6">
            <h3 class="text-xl font-semibold textwhite mb-4">
                <i class="fas fa-chart-line mr-2 text-blue-400"></i>
                Past 10 Days Earnings
            </h3>
            <div class="h-80">
                <canvas id="past10DaysChart"></canvas>
            </div>
        </div>

        <!-- Monthly Earnings Chart -->
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-6">
            <h3 class="text-xl font-semibold textwhite mb-4">
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
            <h3 class="text-xl font-semibold textwhite mb-4">
                <i class="fas fa-credit-card mr-2 text-purple-400"></i>
                Sponser Payments / Patient Payments
            </h3>
            <div class="space-y-4 max-h-80 overflow-y-auto" id="recentPaymentsContainer">
                @foreach(($recentPayments ?? collect()) as $payment)
                <div class="flex items-center justify-between p-3 bg-slate-700 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 {{ $payment->type == 'doctor' ? 'bg-purple-600' : 'bg-blue-600' }} rounded-full flex items-center justify-center">
                            <span class="textwhite font-semibold text-sm">{{ substr($payment->name, 0, 2) }}</span>
                        </div>
                        <div class="ml-3">
                            <p class="text-white font-medium">{{ $payment->name }}</p>
                            <p class="text-gray-400 text-sm">{{ $payment->description }}</p>
                            <p class="text-gray-500 text-xs">{{ $payment->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-green-400 font-semibold">₹{{ number_format($payment->admin_commission, 2) }}</p>
                        <p class="text-gray-400 text-sm">Admin Share</p>
                        @if($payment->type == 'doctor')
                            <p class="text-gray-500 text-xs">From ₹{{ number_format($payment->amount, 2) }}</p>
                        @else
                            <p class="text-gray-500 text-xs">Patient Payment</p>
                        @endif
                    </div>
                </div>
                @endforeach
                @if(($recentPayments ?? collect())->isEmpty())
                <div class="text-center py-8">
                    <p class="text-gray-400">No recent payments found</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Recent Campaigns -->
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-6">
            <h3 class="text-xl font-semibold textwhite mb-4">
                <i class="fas fa-calendar-alt mr-2 text-orange-400"></i>
                Recent Campaigns
            </h3>
            <div class="space-y-4 max-h-80 overflow-y-auto" id="recentCampaignsContainer">
                @foreach(($recentCampaigns ?? collect()) as $campaign)
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
    #sponsorEarnings, #totalEarnings {
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

console.log(past10DaysData);
console.log(monthlyData);


// Past 10 Days Chart
const ctx1 = document.getElementById('past10DaysChart').getContext('2d');

const past10DaysChart = new Chart(ctx1, {
    type: 'line',
    data: {
        labels: past10DaysData.map(item => item.date),
        datasets: [{
            label: 'Daily Earnings (₹)',
            data: past10DaysData.map(item => item.earnings), // FIXED: was item.amount
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


// Monthly Earnings Chart
const ctx2 = document.getElementById('monthlyEarningsChart').getContext('2d');
const monthlyEarningsChart = new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: monthlyData.map(item => item.month),
        datasets: [{
            label: 'Monthly Earnings (₹)',
            data: monthlyData.map(item => item.earnings), // ✅ fixed key name
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
            conosole.log(past10DaysChart.data);
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
            updateValueWithAnimation('sponsorEarnings', '₹' + parseFloat(data.sponsorEarnings).toLocaleString('en-IN', {minimumFractionDigits: 2}));
            updateValueWithAnimation('totalEarnings', '₹' + parseFloat(data.totalEarnings).toLocaleString('en-IN', {minimumFractionDigits: 2}));
            
            document.getElementById('updateTime').textContent = data.timestamp;
        })
        .catch(error => console.error('Error refreshing dashboard:', error));
}
}
</script>

@endsection
