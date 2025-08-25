@extends('admin.master')

@section('title', 'WhatsApp Dashboard')
@section('page-title', 'WhatsApp Manager Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">WhatsApp Manager Dashboard</h1>
                    <p class="mt-2 text-gray-600">Monitor and manage your WhatsApp workflow automation system</p>
                </div>
                <div class="flex space-x-3">
                    <button onclick="refreshDashboard()" class="bg-white px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-sync-alt mr-2"></i>Refresh
                    </button>
                    <button onclick="exportReport()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-download mr-2"></i>Export Report
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i class="fas fa-project-diagram text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Workflows</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_workflows'] ?? 0) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <i class="fas fa-comments text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Active Conversations</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['active_conversations'] ?? 0) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Success Rate</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['success_rate'] ?? 0 }}%</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-100 rounded-lg">
                        <i class="fas fa-file-alt text-orange-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Message Templates</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['templates_count'] ?? 0) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    <a href="{{ route('admin.whatsapp.automation') }}" 
                       class="group bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl border border-blue-200 hover:from-blue-100 hover:to-blue-200 transition-all duration-200 text-center">
                        <div class="p-3 bg-blue-600 text-white rounded-lg inline-flex mb-3 group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-robot text-xl"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 text-sm">Automation</h4>
                        <p class="text-xs text-gray-600 mt-1">Automated rules & triggers</p>
                    </a>

                    <a href="{{ route('admin.whatsapp.automation.rules') }}" 
                       class="group bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl border border-purple-200 hover:from-purple-100 hover:to-purple-200 transition-all duration-200 text-center">
                        <div class="p-3 bg-purple-600 text-white rounded-lg inline-flex mb-3 group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-cogs text-xl"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 text-sm">Rules</h4>
                        <p class="text-xs text-gray-600 mt-1">Automation rules management</p>
                    </a>

                    <a href="{{ route('admin.whatsapp.conversations') }}" 
                       class="group bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl border border-green-200 hover:from-green-100 hover:to-green-200 transition-all duration-200 text-center">
                        <div class="p-3 bg-green-600 text-white rounded-lg inline-flex mb-3 group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-comments text-xl"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 text-sm">Conversations</h4>
                        <p class="text-xs text-gray-600 mt-1">Monitor live conversations</p>
                    </a>

                    <a href="{{ route('admin.whatsapp.templates') }}" 
                       class="group bg-gradient-to-br from-orange-50 to-orange-100 p-6 rounded-xl border border-orange-200 hover:from-orange-100 hover:to-orange-200 transition-all duration-200 text-center">
                        <div class="p-3 bg-orange-600 text-white rounded-lg inline-flex mb-3 group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-file-alt text-xl"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 text-sm">Templates</h4>
                        <p class="text-xs text-gray-600 mt-1">Message templates</p>
                    </a>

                    <a href="{{ route('admin.whatsapp.bulk-messages') }}" 
                       class="group bg-gradient-to-br from-indigo-50 to-indigo-100 p-6 rounded-xl border border-indigo-200 hover:from-indigo-100 hover:to-indigo-200 transition-all duration-200 text-center">
                        <div class="p-3 bg-indigo-600 text-white rounded-lg inline-flex mb-3 group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-paper-plane text-xl"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 text-sm">Bulk Messages</h4>
                        <p class="text-xs text-gray-600 mt-1">Send campaign messages</p>
                    </a>

                    <a href="{{ route('admin.whatsapp.settings') }}" 
                       class="group bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-xl border border-gray-200 hover:from-gray-100 hover:to-gray-200 transition-all duration-200 text-center">
                        <div class="p-3 bg-gray-600 text-white rounded-lg inline-flex mb-3 group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-cog text-xl"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 text-sm">Settings</h4>
                        <p class="text-xs text-gray-600 mt-1">System configuration</p>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Activity & System Status -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Workflow Activity -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Workflow Activity</h3>
                </div>
                <div class="p-6">
                    @if(isset($recentWorkflows) && $recentWorkflows->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentWorkflows as $workflow)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                                <div class="flex items-center space-x-4">
                                    <div class="p-2 rounded-lg 
                                        @if($workflow->status === 'completed') bg-green-100 
                                        @elseif($workflow->status === 'failed') bg-red-100 
                                        @else bg-yellow-100 @endif">
                                        <i class="fas 
                                            @if($workflow->status === 'completed') fa-check text-green-600 
                                            @elseif($workflow->status === 'failed') fa-times text-red-600 
                                            @else fa-clock text-yellow-600 @endif"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $workflow->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $workflow->intent ?? 'General workflow' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($workflow->status === 'completed') bg-green-100 text-green-800 
                                        @elseif($workflow->status === 'failed') bg-red-100 text-red-800 
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst($workflow->status) }}
                                    </span>
                                    <p class="text-xs text-gray-500 mt-1">{{ $workflow->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-inbox text-gray-400 text-3xl mb-4"></i>
                            <p class="text-gray-500">No recent workflow activity</p>
                            <a href="{{ route('admin.whatsapp.automation') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                Configure your first automation →
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- System Status -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">System Status</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-700">WhatsApp API</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <div class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></div>
                            Connected
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-gray-700">AI Service</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <div class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></div>
                            Ready
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-gray-700">Workflow Engine</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <div class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></div>
                            Running
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-gray-700">Message Queue</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <div class="w-1.5 h-1.5 bg-yellow-500 rounded-full mr-1.5"></div>
                            {{ $stats['queue_size'] ?? 0 }} pending
                        </span>
                    </div>

                    <hr class="border-gray-200">

                    <div class="text-center">
                        <p class="text-sm font-medium text-gray-700">Last System Check</p>
                        <p class="text-xs text-gray-500 mt-1">{{ now()->format('M j, Y g:i A') }}</p>
                        <button onclick="checkSystemStatus()" class="mt-2 text-blue-600 hover:text-blue-700 text-xs font-medium">
                            <i class="fas fa-sync-alt mr-1"></i>Check Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function refreshDashboard() {
    window.location.reload();
}

function exportReport() {
    // Implement export functionality
    alert('Export functionality will be implemented');
}

function checkSystemStatus() {
    // Implement real-time system status check
    alert('Checking system status...');
}

// Auto-refresh dashboard every 30 seconds
setInterval(function() {
    // Only refresh if the page is visible to avoid unnecessary requests
    if (!document.hidden) {
        refreshDashboard();
    }
}, 30000);
</script>
@endsection
