@extends('admin.master')

@section('title', 'Machine Management')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Machine Management</h1>
                    <p class="mt-2 text-gray-600">Manage and configure your workflow machines</p>
                </div>
                <div class="flex space-x-3">
                    <button onclick="refreshMachines()" class="bg-white px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-sync-alt mr-2"></i>Refresh
                    </button>
                    <button onclick="exportConfigs()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-download mr-2"></i>Export All
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <i class="fas fa-play text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Executions</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_executions']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Successful</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['successful_executions']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 rounded-lg">
                        <i class="fas fa-times-circle text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Failed</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['failed_executions']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <i class="fas fa-cogs text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Active Machines</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['active_machines'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Machines Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($machines as $type => $machine)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
                <div class="p-6">
                    <!-- Machine Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="p-3 bg-{{ $machine['color'] }}-100 rounded-lg">
                                <i class="{{ $machine['icon'] }} text-{{ $machine['color'] }}-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $machine['name'] }}</h3>
                                <div class="flex items-center mt-1">
                                    @if($machine['status'] === 'active')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <div class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></div>
                                            Active
                                        </span>
                                    @elseif($machine['status'] === 'error')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <div class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></div>
                                            Error
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <div class="w-1.5 h-1.5 bg-gray-500 rounded-full mr-1.5"></div>
                                            Inactive
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Machine Description -->
                    <p class="text-gray-600 text-sm mb-4">{{ $machine['description'] }}</p>

                    <!-- Machine Stats -->
                    <div class="flex justify-between text-sm text-gray-500 mb-4">
                        <span>Configs: {{ $machine['configs']->count() }}</span>
                        <span>Active: {{ $machine['configs']->where('is_active', true)->count() }}</span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.whatsapp.machines.config', $type) }}" 
                           class="flex-1 bg-{{ $machine['color'] }}-600 text-white text-center py-2 px-4 rounded-lg hover:bg-{{ $machine['color'] }}-700 transition-colors duration-200 text-sm font-medium">
                            Configure
                        </a>
                        <button onclick="testMachine('{{ $type }}')" 
                                class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors duration-200 text-sm">
                            <i class="fas fa-flask"></i>
                        </button>
                        <button onclick="viewAnalytics('{{ $type }}')" 
                                class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors duration-200 text-sm">
                            <i class="fas fa-chart-line"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
            </div>
            <div class="p-6">
                @if($recentLogs->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentLogs as $log)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                            <div class="flex items-center space-x-4">
                                <div class="p-2 rounded-lg 
                                    @if($log->status === 'completed') bg-green-100 @elseif($log->status === 'failed') bg-red-100 @else bg-yellow-100 @endif">
                                    <i class="fas 
                                        @if($log->status === 'completed') fa-check text-green-600 
                                        @elseif($log->status === 'failed') fa-times text-red-600 
                                        @else fa-clock text-yellow-600 @endif"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ ucfirst($log->machine_type) }} Machine</p>
                                    <p class="text-sm text-gray-500">{{ $log->machine_action ?? 'Execution' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900 capitalize">{{ $log->status }}</p>
                                <p class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-history text-gray-400 text-3xl mb-4"></i>
                        <p class="text-gray-500">No recent activity found</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Test Machine Modal -->
<div id="testMachineModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-[9984]">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Test Machine</h3>
            </div>
            <div class="p-6">
                <form id="testMachineForm">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Machine Type</label>
                        <input type="text" id="testMachineType" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Test Data (JSON)</label>
                        <textarea id="testMachineData" rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder='{"test": "data"}'></textarea>
                    </div>
                    <div id="testMachineResult" class="hidden mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Result</label>
                        <pre class="bg-gray-100 p-4 rounded-lg text-sm overflow-auto max-h-40"></pre>
                    </div>
                </form>
            </div>
            <div class="px-6 py-4 bg-gray-50 rounded-b-xl flex justify-end space-x-3">
                <button onclick="closeTestModal()" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button onclick="runMachineTest()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Run Test
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function refreshMachines() {
    window.location.reload();
}

function exportConfigs() {
    // Implement export functionality
    alert('Export functionality will be implemented');
}

function testMachine(machineType) {
    document.getElementById('testMachineType').value = machineType;
    document.getElementById('testMachineData').value = '{\n  "test": "data",\n  "message": "Hello World"\n}';
    document.getElementById('testMachineResult').classList.add('hidden');
    document.getElementById('testMachineModal').classList.remove('hidden');
}

function closeTestModal() {
    document.getElementById('testMachineModal').classList.add('hidden');
}

function runMachineTest() {
    const machineType = document.getElementById('testMachineType').value;
    const testData = document.getElementById('testMachineData').value;
    
    try {
        const data = JSON.parse(testData);
        
        fetch(`/admin/whatsapp/machines/${machineType}/test`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ test_data: data })
        })
        .then(response => response.json())
        .then(data => {
            const resultDiv = document.getElementById('testMachineResult');
            const resultPre = resultDiv.querySelector('pre');
            
            if (data.success) {
                resultPre.textContent = JSON.stringify(data.result, null, 2);
                resultPre.className = 'bg-green-50 p-4 rounded-lg text-sm overflow-auto max-h-40 border border-green-200';
            } else {
                resultPre.textContent = `Error: ${data.message}`;
                resultPre.className = 'bg-red-50 p-4 rounded-lg text-sm overflow-auto max-h-40 border border-red-200';
            }
            
            resultDiv.classList.remove('hidden');
        })
        .catch(error => {
            const resultDiv = document.getElementById('testMachineResult');
            const resultPre = resultDiv.querySelector('pre');
            resultPre.textContent = `Network Error: ${error.message}`;
            resultPre.className = 'bg-red-50 p-4 rounded-lg text-sm overflow-auto max-h-40 border border-red-200';
            resultDiv.classList.remove('hidden');
        });
    } catch (error) {
        alert('Invalid JSON format in test data');
    }
}

function viewAnalytics(machineType) {
    window.open(`/admin/whatsapp/machines/${machineType}/analytics`, '_blank');
}

// Close modal on outside click
document.getElementById('testMachineModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeTestModal();
    }
});
</script>
@endsection
