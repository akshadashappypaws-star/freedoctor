@extends('admin.pages.whatsapp.layouts.whatsapp')


@section('title', 'ChatGPT Integration')

@section('content')
<div class="container px-6 mx-auto grid">
    <div class="flex justify-between items-center mb-6 mt-4">
        <h2 class="text-2xl font-semibold text-gray-700">ChatGPT Integration</h2>
        <button 
            onclick="openPromptModal()"
            class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors duration-300 flex items-center">
            <i class="fas fa-plus mr-2"></i> New Prompt Template
        </button>
    </div>

    <!-- Configuration Card -->
    <div class="bg-white rounded-lg shadow-md mb-8">
        <div class="p-6">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">Configuration</h3>
            <form id="configForm" class="space-y-4">
                <!-- API Key -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        OpenAI API Key
                    </label>
                    <div class="flex">
                        <input 
                            type="password"
                            id="apiKey"
                            name="openai_api_key"
                            class="flex-1 rounded-l-md border-gray-300 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                            value="{{ $config->openai_api_key ?? '' }}"
                            placeholder="Enter your OpenAI API key"
                            required>
                        <button 
                            type="button"
                            onclick="toggleApiKey()"
                            class="px-4 py-2 bg-gray-100 border border-l-0 border-gray-300 rounded-r-md hover:bg-gray-200">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Model Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        GPT Model
                    </label>
                    <select 
                        name="openai_model"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                        required>
                        <option value="gpt-4" {{ ($config->openai_model ?? '') === 'gpt-4' ? 'selected' : '' }}>GPT-4</option>
                        <option value="gpt-3.5-turbo" {{ ($config->openai_model ?? '') === 'gpt-3.5-turbo' ? 'selected' : '' }}>GPT-3.5 Turbo</option>
                    </select>
                </div>

                <!-- Temperature -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Temperature (0.0 - 1.0)
                    </label>
                    <input 
                        type="number"
                        name="temperature"
                        min="0"
                        max="1"
                        step="0.1"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                        value="{{ $config->temperature ?? '0.7' }}"
                        required>
                    <p class="text-xs text-gray-500 mt-1">
                        Higher values make the output more random, lower values make it more focused
                    </p>
                </div>

                <!-- Max Tokens -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Max Response Length (Tokens)
                    </label>
                    <input 
                        type="number"
                        name="max_tokens"
                        min="100"
                        max="4000"
                        step="100"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                        value="{{ $config->max_tokens ?? '1000' }}"
                        required>
                </div>

                <div class="flex justify-end">
                    <button 
                        type="submit"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600">
                        Save Configuration
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Prompt Templates -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">Prompt Templates</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($prompts ?? [] as $prompt)
                <div class="border rounded-lg p-4 hover:shadow-md transition-shadow duration-300">
                    <div class="flex justify-between items-start mb-2">
                        <h4 class="text-lg font-semibold">{{ $prompt->name }}</h4>
                        <div class="space-x-2">
                            <button 
                                onclick="editPrompt({{ $prompt->id }})"
                                class="text-blue-500 hover:text-blue-700">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button 
                                onclick="deletePrompt({{ $prompt->id }})"
                                class="text-red-500 hover:text-red-700">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    
                    <p class="text-gray-600 mb-4">{{ Str::limit($prompt->prompt_template, 150) }}</p>
                    
                    @if($prompt->system_message)
                    <div class="mb-4">
                        <span class="text-sm font-medium text-gray-600">System Message:</span>
                        <p class="text-gray-600 text-sm">{{ Str::limit($prompt->system_message, 100) }}</p>
                    </div>
                    @endif

                    @if($prompt->allowed_parameters)
                    <div>
                        <span class="text-sm font-medium text-gray-600">Parameters:</span>
                        <div class="flex flex-wrap gap-2 mt-1">
                            @foreach(json_decode($prompt->allowed_parameters) as $param)
                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">
                                {{ $param }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                @empty
                <div class="col-span-2 text-center py-8">
                    <p class="text-gray-500">No prompt templates available. Create one to get started.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Prompt Template Modal -->
<div id="promptModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-700" id="modalTitle">New Prompt Template</h3>
                    <button onclick="closePromptModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="promptForm" onsubmit="submitPrompt(event)">
                    <input type="hidden" name="id" id="promptId">
                    <div class="space-y-4">
                        <!-- Template Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Template Name
                            </label>
                            <input 
                                type="text"
                                name="name"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                                required
                                placeholder="Enter template name">
                        </div>

                        <!-- System Message -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                System Message
                            </label>
                            <textarea 
                                name="system_message"
                                rows="3"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                                placeholder="Enter system message (optional)"></textarea>
                            <p class="text-xs text-gray-500 mt-1">
                                This sets the behavior of the AI assistant
                            </p>
                        </div>

                        <!-- Prompt Template -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Prompt Template
                            </label>
                            <textarea 
                                name="prompt_template"
                                rows="6"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                                required
                                placeholder="Enter prompt template"></textarea>
                            <p class="text-xs text-gray-500 mt-1">
                                Use {parameter_name} syntax for dynamic content
                            </p>
                        </div>

                        <!-- Parameters -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Allowed Parameters
                            </label>
                            <div id="parametersList" class="space-y-2">
                                <!-- Parameters will be added here -->
                            </div>
                            <button 
                                type="button"
                                onclick="addParameter()"
                                class="mt-2 text-sm text-yellow-600 hover:text-yellow-700">
                                <i class="fas fa-plus mr-1"></i> Add Parameter
                            </button>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button 
                            type="button"
                            onclick="closePromptModal()"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button 
                            type="submit"
                            class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600">
                            Save Template
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let currentAction = 'create';

function openPromptModal(action = 'create') {
    currentAction = action;
    document.getElementById('modalTitle').textContent = action === 'create' ? 'New Prompt Template' : 'Edit Prompt Template';
    document.getElementById('promptModal').classList.remove('hidden');
}

function closePromptModal() {
    document.getElementById('promptModal').classList.add('hidden');
    document.getElementById('promptForm').reset();
    document.getElementById('parametersList').innerHTML = '';
}

function toggleApiKey() {
    const apiKey = document.getElementById('apiKey');
    if (apiKey.type === 'password') {
        apiKey.type = 'text';
    } else {
        apiKey.type = 'password';
    }
}

function addParameter() {
    const parametersList = document.getElementById('parametersList');
    const paramId = Date.now();
    
    const parameterDiv = document.createElement('div');
    parameterDiv.className = 'flex items-center gap-2';
    parameterDiv.innerHTML = `
        <input 
            type="text"
            name="allowed_parameters[]"
            class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
            placeholder="Parameter name"
            required>
        <button 
            type="button"
            onclick="removeParameter(${paramId})"
            class="text-red-500 hover:text-red-700">
            <i class="fas fa-times"></i>
        </button>
    `;
    parameterDiv.id = `param_${paramId}`;
    parametersList.appendChild(parameterDiv);
}

function removeParameter(paramId) {
    document.getElementById(`param_${paramId}`).remove();
}

function editPrompt(id) {
    fetch(`/admin/whatsapp/chatgpt-prompts/${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('promptId').value = data.id;
            document.querySelector('input[name="name"]').value = data.name;
            document.querySelector('textarea[name="system_message"]').value = data.system_message || '';
            document.querySelector('textarea[name="prompt_template"]').value = data.prompt_template;

            // Clear existing parameters
            document.getElementById('parametersList').innerHTML = '';

            // Add existing parameters
            const parameters = JSON.parse(data.allowed_parameters || '[]');
            parameters.forEach(param => {
                const paramId = Date.now();
                const parameterDiv = document.createElement('div');
                parameterDiv.className = 'flex items-center gap-2';
                parameterDiv.innerHTML = `
                    <input 
                        type="text"
                        name="allowed_parameters[]"
                        value="${param}"
                        class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                        placeholder="Parameter name"
                        required>
                    <button 
                        type="button"
                        onclick="removeParameter(${paramId})"
                        class="text-red-500 hover:text-red-700">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                parameterDiv.id = `param_${paramId}`;
                document.getElementById('parametersList').appendChild(parameterDiv);
            });

            openPromptModal('edit');
        });
}

function deletePrompt(id) {
    if (confirm('Are you sure you want to delete this prompt template?')) {
        fetch(`/admin/whatsapp/chatgpt-prompts/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.message || 'An error occurred');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the prompt template');
        });
    }
}

function submitPrompt(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const id = document.getElementById('promptId').value;

    const url = id ? 
        `/admin/whatsapp/chatgpt-prompts/${id}` : 
        '/admin/whatsapp/chatgpt-prompts';

    const method = id ? 'PUT' : 'POST';

    fetch(url, {
        method: method,
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closePromptModal();
            window.location.reload();
        } else {
            alert(data.message || 'An error occurred');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while saving the prompt template');
    });
}

// Handle configuration form submission
document.getElementById('configForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData(event.target);

    fetch('/admin/whatsapp/chatgpt-config', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Configuration saved successfully');
        } else {
            alert(data.message || 'An error occurred');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while saving the configuration');
    });
});
</script>
@endsection
