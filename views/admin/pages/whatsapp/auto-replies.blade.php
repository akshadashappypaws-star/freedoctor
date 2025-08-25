@extends('admin.pages.whatsapp.layouts.whatsapp')


@section('title', 'Auto Replies')

@section('content')
<div class="container px-6 mx-auto grid">
    <div class="flex justify-between items-center mb-6 mt-4">
        <h2 class="text-2xl font-semibold text-gray-700">Auto Replies</h2>
        <button 
            data-action="create"
            onclick="openAutoReplyModal()"
            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-300 flex items-center">
            <i class="fas fa-plus mr-2"></i> New Auto Reply
        </button>
    </div>

    <!-- Auto Replies List -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <thead>
                        <tr class="text-left font-semibold">
                            <th class="px-4 py-3">Keyword</th>
                            <th class="px-4 py-3">Reply Type</th>
                            <th class="px-4 py-3">Content</th>
                            <th class="px-4 py-3">Last Updated</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($autoReplies ?? [] as $reply)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $reply->keyword }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $typeColors = [
                                        'text' => 'gray',
                                        'template' => 'blue',
                                        'gpt' => 'yellow'
                                    ];
                                    $color = $typeColors[$reply->reply_type] ?? 'gray';
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-{{ $color }}-100 text-{{ $color }}-800">
                                    {{ ucfirst($reply->reply_type) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if($reply->reply_type === 'text')
                                    {{ Str::limit($reply->reply_content, 50) }}
                                @elseif($reply->reply_type === 'template')
                                    Template: {{ $reply->template->name ?? 'N/A' }}
                                @else
                                    {{ Str::limit($reply->gpt_prompt, 50) }}
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $reply->updated_at->diffForHumans() }}</td>
                            <td class="px-4 py-3">
                                <button 
                                    onclick="editAutoReply({{ $reply->id }})"
                                    class="text-blue-500 hover:text-blue-700 mr-2">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button 
                                    onclick="deleteAutoReply({{ $reply->id }})"
                                    class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-center text-gray-500">
                                No auto replies configured yet
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Auto Reply Modal -->
<div id="autoReplyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-700" id="modalTitle">New Auto Reply</h3>
                    <button onclick="closeAutoReplyModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="autoReplyForm" onsubmit="submitAutoReply(event)">
                    <input type="hidden" name="id" id="replyId">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4 col-span-1 md:col-span-2">
                        <!-- Keyword -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Keyword(s)
                            </label>
                            <input 
                                type="text"
                                name="keyword"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                required
                                placeholder="Enter keyword(s)">
                            <p class="text-xs text-gray-500 mt-1">
                                You can use multiple keywords separated by commas
                            </p>
                        </div>

                        <!-- Reply Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Reply Type
                            </label>
                            <select 
                                name="reply_type"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                onchange="handleReplyTypeChange(this.value)"
                                required>
                                <option value="text">Text Response</option>
                                <option value="template">Message Template</option>
                                <option value="gpt">ChatGPT Response</option>
                            </select>
                        </div>

                        <!-- Text Response -->
                        <div id="textResponse">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Response Text
                            </label>
                            <textarea 
                                name="reply_content"
                                rows="4"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                placeholder="Enter your response text"></textarea>
                        </div>

                        <!-- Template Selection -->
                        <div id="templateSelection" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Select Template
                            </label>
                            <select 
                                name="template_id"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="">Select a template</option>
                                @foreach($templates ?? [] as $template)
                                    <option value="{{ $template->id }}">{{ $template->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- ChatGPT Prompt -->
                        <div id="gptPrompt" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                ChatGPT Prompt
                            </label>
                            <textarea 
                                name="gpt_prompt"
                                rows="4"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                placeholder="Enter the prompt for ChatGPT"></textarea>
                            <p class="text-xs text-gray-500 mt-1">
                                Use {message} to reference the user's message in your prompt
                            </p>
                        </div>

                        <!-- Advanced Settings -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-700 mb-3">Advanced Settings</h4>
                            
                            <!-- Response Sentiment -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Expected Response Type
                                </label>
                                <select 
                                    name="sentiment_type"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <option value="">Any</option>
                                    <option value="positive">Positive Response</option>
                                    <option value="negative">Negative Response</option>
                                    <option value="neutral">Neutral Response</option>
                                </select>
                            </div>

                            <!-- Priority -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Priority Level
                                </label>
                                <input 
                                    type="number"
                                    name="priority"
                                    min="1"
                                    max="10"
                                    value="5"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <p class="text-xs text-gray-500 mt-1">
                                    Higher priority (1-10) rules are checked first
                                </p>
                            </div>

                            <!-- Follow-up -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Follow-up Template
                                </label>
                                <select 
                                    name="follow_up_template_id"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <option value="">No follow-up</option>
                                    @foreach($templates ?? [] as $template)
                                        <option value="{{ $template->id }}">{{ $template->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Follow-up Delay -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Follow-up Delay (minutes)
                                </label>
                                <input 
                                    type="number"
                                    name="follow_up_delay"
                                    min="1"
                                    value="30"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            </div>

                            <!-- Smart Selection -->
                            <div class="mb-4">
                                <label class="flex items-center">
                                    <input 
                                        type="checkbox"
                                        name="smart_selection"
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                        checked>
                                    <span class="ml-2 text-sm text-gray-700">Enable smart template selection</span>
                                </label>
                                <p class="text-xs text-gray-500 mt-1">
                                    Automatically select the best template based on context
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Preview Section -->
                    <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-gray-700 mb-3">Response Preview</h4>
                        <div class="flex items-start space-x-3">
                            <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white">
                                <i class="fab fa-whatsapp"></i>
                            </div>
                            <div class="flex-1">
                                <div class="bg-white rounded-lg p-3 shadow-sm">
                                    <p id="previewContent" class="text-gray-800">
                                        Your response will appear here...
                                    </p>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">12:00 PM</div>
                            </div>
                        </div>

                        <!-- Test Section -->
                        <div class="mt-4">
                            <div class="flex space-x-2">
                                <input 
                                    type="text"
                                    id="testMessage"
                                    placeholder="Enter a test message"
                                    class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <button 
                                    type="button"
                                    onclick="testReply()"
                                    class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                                    Test
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button 
                            type="button"
                            onclick="closeAutoReplyModal()"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button 
                            type="submit"
                            class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                            Save Auto Reply
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

function openAutoReplyModal(action = 'create') {
    currentAction = action;
    document.getElementById('modalTitle').textContent = action === 'create' ? 'New Auto Reply' : 'Edit Auto Reply';
    document.getElementById('autoReplyModal').classList.remove('hidden');
}

function closeAutoReplyModal() {
    document.getElementById('autoReplyModal').classList.add('hidden');
    document.getElementById('autoReplyForm').reset();
}

function handleReplyTypeChange(type) {
    const textResponse = document.getElementById('textResponse');
    const templateSelection = document.getElementById('templateSelection');
    const gptPrompt = document.getElementById('gptPrompt');

    textResponse.classList.add('hidden');
    templateSelection.classList.add('hidden');
    gptPrompt.classList.add('hidden');

    switch(type) {
        case 'text':
            textResponse.classList.remove('hidden');
            break;
        case 'template':
            templateSelection.classList.remove('hidden');
            break;
        case 'gpt':
            gptPrompt.classList.remove('hidden');
            break;
    }
}

function editAutoReply(id) {
    fetch(`/admin/whatsapp/auto-replies/${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('replyId').value = data.id;
            document.querySelector('input[name="keyword"]').value = data.keyword;
            document.querySelector('select[name="reply_type"]').value = data.reply_type;
            
            handleReplyTypeChange(data.reply_type);

            if (data.reply_type === 'text') {
                document.querySelector('textarea[name="reply_content"]').value = data.reply_content;
            } else if (data.reply_type === 'template') {
                document.querySelector('select[name="template_id"]').value = data.template_id;
            } else if (data.reply_type === 'gpt') {
                document.querySelector('textarea[name="gpt_prompt"]').value = data.gpt_prompt;
            }

            openAutoReplyModal('edit');
        });
}

function deleteAutoReply(id) {
    if (confirm('Are you sure you want to delete this auto reply?')) {
        fetch(`/admin/whatsapp/auto-replies/${id}`, {
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
            alert('An error occurred while deleting the auto reply');
        });
    }
}

function submitAutoReply(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const id = document.getElementById('replyId').value;

    const url = id ? 
        `/admin/whatsapp/auto-replies/${id}` : 
        '/admin/whatsapp/auto-replies';

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
            closeAutoReplyModal();
            window.location.reload();
        } else {
            alert(data.message || 'An error occurred');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while saving the auto reply');
    });
}

// Initialize the form
handleReplyTypeChange('text');
</script>
@endsection
