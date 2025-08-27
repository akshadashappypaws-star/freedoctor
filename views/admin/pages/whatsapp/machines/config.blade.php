@extends('admin.master')

@section('page_title', ucfirst($machine) . ' Machine Configuration')
@section('page_subtitle', 'Configure and monitor ' . ucfirst($machine) . ' machine settings')

@section('page_actions')
    <div class="flex space-x-3">
        <a href="{{ route('admin.whatsapp.machines') }}" 
           class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Machines
        </a>
        <button onclick="exportConfigs()" 
                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
            <i class="fas fa-download mr-2"></i>
            Export Config
        </button>
        <button onclick="openAddConfigModal()" 
                class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Add Config
        </button>
    </div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Machine Overview -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-{{ $machine === 'ai' ? 'purple' : ($machine === 'function' ? 'blue' : ($machine === 'datatable' ? 'green' : ($machine === 'template' ? 'orange' : 'pink'))) }}-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-{{ $machine === 'ai' ? 'brain' : ($machine === 'function' ? 'cogs' : ($machine === 'datatable' ? 'table' : ($machine === 'template' ? 'file-alt' : 'chart-bar'))) }} text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 capitalize">{{ $machine }} Machine</h2>
                    <p class="text-gray-600">Configure settings and monitor performance</p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-sm text-gray-500">Last Updated</div>
                <div class="text-lg font-semibold text-gray-900">{{ now()->format('M d, Y H:i') }}</div>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-green-50 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-700">Total Runs</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($performance['total_runs']) }}</p>
                    </div>
                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-play text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-700">Successful</p>
                        <p class="text-2xl font-bold text-blue-600">{{ number_format($performance['successful_runs']) }}</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-red-50 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-red-700">Failed</p>
                        <p class="text-2xl font-bold text-red-600">{{ number_format($performance['failed_runs']) }}</p>
                    </div>
                    <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-times text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-purple-50 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-700">Avg Time</p>
                        <p class="text-2xl font-bold text-purple-600">{{ number_format($performance['avg_execution_time']) }}ms</p>
                    </div>
                    <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Rate Progress Bar -->
        @php
            $successRate = $performance['total_runs'] > 0 ? ($performance['successful_runs'] / $performance['total_runs']) * 100 : 0;
        @endphp
        <div class="mb-4">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-medium text-gray-700">Success Rate</span>
                <span class="text-sm font-semibold text-gray-900">{{ number_format($successRate, 1) }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-{{ $successRate >= 90 ? 'green' : ($successRate >= 70 ? 'yellow' : 'red') }}-500 h-2 rounded-full transition-all duration-500" 
                     style="width: {{ $successRate }}%"></div>
            </div>
        </div>
    </div>

    <!-- Default Settings -->
    @if(!empty($settings))
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-sliders-h mr-2 text-blue-500"></i>
            Default Settings
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($settings as $key => $value)
                <div class="bg-gray-50 rounded-lg p-3">
                    <div class="text-sm font-medium text-gray-700 capitalize">{{ str_replace('_', ' ', $key) }}</div>
                    <div class="text-sm text-gray-600 mt-1">
                        @if(is_array($value))
                            {{ implode(', ', array_slice($value, 0, 3)) }}@if(count($value) > 3)...@endif
                        @elseif(is_bool($value))
                            {{ $value ? 'Enabled' : 'Disabled' }}
                        @else
                            {{ $value }}
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Configuration Items -->
    <div class="bg-white rounded-lg shadow-lg">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-cog mr-2 text-gray-500"></i>
                Configuration Items
            </h3>
        </div>

        @if($configs->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Key</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Updated</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($configs as $config)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $config->config_key }}</div>
                                @if($config->description)
                                    <div class="text-sm text-gray-500">{{ $config->description }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs truncate">
                                    @if($config->config_type === 'json')
                                        <code class="bg-gray-100 px-2 py-1 rounded text-xs">{{ Str::limit($config->config_value, 50) }}</code>
                                    @elseif($config->config_type === 'boolean')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config->config_value === 'true' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $config->config_value === 'true' ? 'Enabled' : 'Disabled' }}
                                        </span>
                                    @else
                                        {{ $config->config_value }}
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 capitalize">
                                    {{ $config->config_type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $config->updated_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <button onclick="editConfig({{ $config->id }})" 
                                            class="text-blue-600 hover:text-blue-900 transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deleteConfig({{ $config->id }})" 
                                            class="text-red-600 hover:text-red-900 transition-colors">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-8 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-cog text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Configuration Items</h3>
                <p class="text-gray-500 mb-4">This machine doesn't have any custom configurations yet.</p>
                <button onclick="openAddConfigModal()" 
                        class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Add First Config
                </button>
            </div>
        @endif
    </div>

    <!-- Recent Errors -->
    @if($recentErrors->count() > 0)
    <div class="bg-white rounded-lg shadow-lg">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-exclamation-triangle mr-2 text-red-500"></i>
                Recent Errors
            </h3>
        </div>
        <div class="divide-y divide-gray-200">
            @foreach($recentErrors as $error)
            <div class="p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                {{ $error->error_type }}
                            </span>
                            <span class="text-sm text-gray-500">{{ $error->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-gray-900 mb-2">{{ $error->error_message }}</p>
                        @if($error->context)
                            <details class="text-xs text-gray-600">
                                <summary class="cursor-pointer hover:text-gray-800">Context</summary>
                                <pre class="mt-2 bg-gray-50 p-2 rounded overflow-x-auto">{{ json_encode($error->context, JSON_PRETTY_PRINT) }}</pre>
                            </details>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Add Config Modal -->
<div id="addConfigModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Add Configuration</h3>
                    <button onclick="closeAddConfigModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form id="addConfigForm" onsubmit="submitConfig(event)">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Configuration Key</label>
                            <input type="text" name="config_key" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="e.g., max_retries">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Value</label>
                            <input type="text" name="config_value" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Configuration value">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <select name="config_type" required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="string">String</option>
                                <option value="integer">Integer</option>
                                <option value="boolean">Boolean</option>
                                <option value="json">JSON</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description (Optional)</label>
                            <textarea name="description" rows="2" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Brief description of this configuration"></textarea>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeAddConfigModal()" 
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                            Add Configuration
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openAddConfigModal() {
    document.getElementById('addConfigModal').classList.remove('hidden');
}

function closeAddConfigModal() {
    document.getElementById('addConfigModal').classList.add('hidden');
    document.getElementById('addConfigForm').reset();
}

function submitConfig(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData.entries());
    data.machine_type = '{{ $machine }}';
    
    showLoading('Adding configuration...');
    
    fetch('{{ route("admin.whatsapp.whatsapp.machines.config.update", $machine) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showAlert('Configuration added successfully!', 'success');
            closeAddConfigModal();
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert(data.message || 'Failed to add configuration', 'error');
        }
    })
    .catch(error => {
        hideLoading();
        showAlert('An error occurred while adding configuration', 'error');
        console.error('Error:', error);
    });
}

function editConfig(configId) {
    // Implementation for editing configuration
    showAlert('Edit functionality will be implemented soon', 'info');
}

function deleteConfig(configId) {
    if (confirm('Are you sure you want to delete this configuration?')) {
        showLoading('Deleting configuration...');
        
        fetch(`{{ route("admin.whatsapp.machines.config.delete", [$machine, ""]) }}${configId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showAlert('Configuration deleted successfully!', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert(data.message || 'Failed to delete configuration', 'error');
            }
        })
        .catch(error => {
            hideLoading();
            showAlert('An error occurred while deleting configuration', 'error');
            console.error('Error:', error);
        });
    }
}

function exportConfigs() {
    window.open('{{ route("admin.whatsapp.machines.export", $machine) }}', '_blank');
}

// Close modal when clicking outside
document.getElementById('addConfigModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAddConfigModal();
    }
});
</script>
@endsection
