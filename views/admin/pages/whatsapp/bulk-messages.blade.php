@extends('admin.master')

@section('page_title', 'Bulk Messages')
@section('page_subtitle', 'Send messages to multiple recipients using WhatsApp templates')

@section('page_actions')
<button onclick="syncTemplates()" 
        class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-300 flex items-center">
    <i class="fas fa-sync-alt mr-2"></i> Sync Templates
</button>
<button onclick="openNewMessageModal()" 
        class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors duration-300 flex items-center">
    <i class="fas fa-plus mr-2"></i> New Message
</button>
<button onclick="openRecipientsModal()" 
        class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition-colors duration-300 flex items-center">
    <i class="fas fa-users mr-2"></i> Smart Recipients
</button>
<button onclick="exportBulkMessages()" 
        class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors duration-300 flex items-center">
    <i class="fas fa-download mr-2"></i> Export
</button>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-paper-plane text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-700">Total Sent</h3>
                <p class="text-2xl font-bold text-blue-600" data-stat="total-sent">{{ $bulkMessages->sum('sent_count') ?? 0 }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <i class="fas fa-check-circle text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-700">Success Rate</h3>
                @php
                    $totalSent = $bulkMessages->sum('sent_count');
                    $totalFailed = $bulkMessages->sum('failed_count');
                    $totalMessages = $totalSent + $totalFailed;
                    $successRate = $totalMessages > 0 ? round(($totalSent / $totalMessages) * 100, 1) : 0;
                @endphp
                <p class="text-2xl font-bold text-green-600" data-stat="success-rate">{{ $successRate }}%</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                <i class="fas fa-clock text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-700">Pending</h3>
                <p class="text-2xl font-bold text-purple-600" data-stat="pending">{{ $bulkMessages->where('status', 'pending')->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                <i class="fas fa-calendar text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-700">This Month</h3>
                <p class="text-2xl font-bold text-orange-600" data-stat="this-month">{{ $bulkMessages->where('created_at', '>=', now()->startOfMonth())->sum('sent_count') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-lg shadow-md mb-8">
    <div class="p-6">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">Quick Actions</h3>
        <div class="flex flex-wrap gap-4">
            <button 
                onclick="syncTemplates()"
                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-300 flex items-center">
                <i class="fas fa-sync-alt mr-2"></i> Sync Templates
            </button>
            <button 
                onclick="openNewMessageModal()"
                class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors duration-300 flex items-center">
                <i class="fas fa-plus mr-2"></i> New Message
            </button>
            <button 
                onclick="openRecipientsModal()"
                class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition-colors duration-300 flex items-center">
                <i class="fas fa-users mr-2"></i> Smart Recipients
            </button>
            <button 
                onclick="openAnalyticsModal()"
                class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition-colors duration-300 flex items-center">
                <i class="fas fa-chart-bar mr-2"></i> Analytics
            </button>
            <button 
                onclick="exportBulkMessages()"
                class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors duration-300 flex items-center">
                <i class="fas fa-download mr-2"></i> Export
            </button>
        </div>
    </div>
</div>

    <!-- Sync Status Alert -->
    <div id="syncAlert" class="hidden mb-4">
        <div class="p-4 rounded-lg">
            <div class="flex items-center">
                <div id="syncSpinner" class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <span id="syncMessage" class="text-sm font-medium"></span>
            </div>
        </div>
    </div>

    <!-- Message Templates Section -->
    <div class="bg-white rounded-lg shadow-md mb-8">
        <div class="p-6">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">Message Templates</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @forelse($templates ?? [] as $template)
                <div class="border rounded-lg p-4 hover:shadow-md transition-shadow duration-300">
                    <div class="flex justify-between items-start mb-2">
                        <h4 class="text-lg font-semibold">{{ $template->name }}</h4>
                        <button 
                            onclick="useTemplate({{ $template->id }})"
                            class="text-blue-500 hover:text-blue-700">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                    <p class="text-gray-600 text-sm mb-2">{{ Str::limit($template->content, 100) }}</p>
                    @if($template->parameters)
                        <div class="text-xs text-gray-500">
                            Parameters: {{ implode(', ', json_decode($template->parameters)) }}
                        </div>
                    @endif
                </div>
                @empty
                <div class="col-span-3 text-center text-gray-500">
                    No templates available. Create one from the Templates section.
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Message Status & Error Console -->
    <div class="bg-white rounded-lg shadow-md mb-8">
        <div class="p-4">
            <div class="flex justify-between items-center mb-3">
                <h3 class="text-lg font-semibold text-gray-700">Message Status & Error Console</h3>
                <div class="flex space-x-2">
                    <button onclick="clearConsole()" class="px-3 py-1 text-xs bg-gray-500 text-white rounded hover:bg-gray-600">
                        <i class="fas fa-trash mr-1"></i> Clear
                    </button>
                    <button onclick="refreshStatus()" class="px-3 py-1 text-xs bg-blue-500 text-white rounded hover:bg-blue-600">
                        <i class="fas fa-sync-alt mr-1"></i> Refresh
                    </button>
                    <button onclick="exportLog()" class="px-3 py-1 text-xs bg-green-500 text-white rounded hover:bg-green-600">
                        <i class="fas fa-download mr-1"></i> Export
                    </button>
                    <button onclick="toggleAutoScroll()" class="px-3 py-1 text-xs bg-purple-500 text-white rounded hover:bg-purple-600" id="autoScrollBtn">
                        Auto-scroll: ON
                    </button>
                </div>
            </div>
            
            <!-- Status Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <div class="bg-red-50 p-3 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                        <div>
                            <p class="text-xs text-red-600 font-medium">Total Errors</p>
                            <p class="text-lg font-bold text-red-700" id="totalErrorCount">0</p>
                        </div>
                    </div>
                </div>
                <div class="bg-orange-50 p-3 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-paper-plane text-orange-500 mr-2"></i>
                        <div>
                            <p class="text-xs text-orange-600 font-medium">Messages Sent</p>
                            <p class="text-lg font-bold text-orange-700" id="messagesSentCount">0</p>
                        </div>
                    </div>
                </div>
                <div class="bg-red-50 p-3 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-times text-red-500 mr-2"></i>
                        <div>
                            <p class="text-xs text-red-600 font-medium">Messages Failed</p>
                            <p class="text-lg font-bold text-red-700" id="messagesFailedCount">0</p>
                        </div>
                    </div>
                </div>
                <div class="bg-green-50 p-3 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <div>
                            <p class="text-xs text-green-600 font-medium">Success Rate</p>
                            <p class="text-lg font-bold text-green-700" id="successRate">0%</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Unified Console -->
            <div id="unifiedConsole" class="bg-gray-900 text-white font-mono text-xs p-4 rounded h-64 overflow-y-auto border-l-4 border-blue-500">
                <div class="text-gray-400">Console ready... Monitoring WhatsApp message status and errors...</div>
            </div>
            
            <div class="mt-2 flex justify-between text-xs text-gray-500">
                <span>Last updated: <span id="lastUpdated">Never</span></span>
                <div class="flex space-x-4">
                    <span>Status: <span id="consoleStatus" class="text-green-600">ACTIVE</span></span>
                    <span>Monitoring: <span id="monitoringStatus" class="text-green-600">ON</span></span>
                    <span>Total Entries: <span id="totalEntries" class="text-blue-600">0</span></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Message History -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">Message History</h3>
            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <thead>
                        <tr class="text-left font-semibold">
                            <th class="px-4 py-3">Template</th>
                            <th class="px-4 py-3">Recipients</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Sent/Failed</th>
                            <th class="px-4 py-3">Scheduled</th>
                            <th class="px-4 py-3">Created At</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bulkMessages ?? [] as $message)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $message->template->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $message->total_recipients }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $statusColors = [
                                        'pending' => 'gray',
                                        'processing' => 'blue',
                                        'completed' => 'green',
                                        'failed' => 'red'
                                    ];
                                    $color = $statusColors[$message->status] ?? 'gray';
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-{{ $color }}-100 text-{{ $color }}-800">
                                    {{ ucfirst($message->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                {{ $message->sent_count }}/{{ $message->failed_count }}
                            </td>
                            <td class="px-4 py-3">
                                @if($message->is_scheduled)
                                    {{ $message->scheduled_at->setTimezone('Asia/Kolkata')->format('Y-m-d H:i') }} IST
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $message->created_at->setTimezone('Asia/Kolkata')->format('Y-m-d H:i') }} IST</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-2">
                                    <button 
                                        onclick="viewDetails({{ $message->id }})"
                                        class="text-blue-500 hover:text-blue-700 p-1 rounded" 
                                        title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($message->status === 'pending')
                                    <button 
                                        onclick="sendMessage({{ $message->id }})"
                                        class="text-green-500 hover:text-green-700 p-1 rounded" 
                                        title="Send Now">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                    <button 
                                        onclick="cancelMessage({{ $message->id }})"
                                        class="text-red-500 hover:text-red-700 p-1 rounded" 
                                        title="Cancel">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @endif
                                    @if($message->status === 'completed')
                                    <button 
                                        onclick="duplicateMessage({{ $message->id }})"
                                        class="text-purple-500 hover:text-purple-700 p-1 rounded" 
                                        title="Duplicate">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                    @endif
                                    <button 
                                        onclick="exportMessage({{ $message->id }})"
                                        class="text-gray-500 hover:text-gray-700 p-1 rounded" 
                                        title="Export">
                                        <i class="fas fa-download"></i>
                                    </button>
                                    <button 
                                        onclick="deleteMessage({{ $message->id }})"
                                        class="text-red-500 hover:text-red-700 p-1 rounded" 
                                        title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-3 text-center text-gray-500">
                                No bulk messages sent yet
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- New Message Modal -->
<div id="newMessageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-screen overflow-hidden">
            <div class="flex flex-col h-full max-h-screen">
                <!-- Modal Header -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-semibold text-gray-700">New Bulk Message</h3>
                        <button onclick="closeNewMessageModal()" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Modal Body with Scrollbar -->
                <div class="flex-1 overflow-y-auto p-6">

                <form id="bulkMessageForm" onsubmit="submitBulkMessage(event)">
                    @csrf
                    <div class="space-y-4">
                        <!-- Template Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Message Template
                            </label>
                            <select 
                                name="template_id" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                                required>
                                <option value="">Select a template</option>
                                @foreach($templates ?? [] as $template)
                                    <option value="{{ $template->id }}">{{ $template->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Target Category -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Target Category
                            </label>
                            <select 
                                name="target_category" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                                required>
                                <option value="valuable">High Value Leads</option>
                                <option value="average">Average Leads</option>
                                <option value="not_interested">Re-engagement Targets</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">
                                This helps track campaign performance and lead scoring
                            </p>
                        </div>

                        <!-- Recipients -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Recipients
                            </label>
                            <div class="relative">
                                <textarea 
                                    name="recipients"
                                    rows="4"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                                    placeholder="Enter phone numbers, one per line"
                                    required></textarea>
                                <button 
                                    type="button"
                                    onclick="openSmartRecipientsModal()"
                                    class="absolute top-2 right-2 px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">
                                    Smart Select
                                </button>
                            </div>
                            <div class="flex justify-between items-center mt-1">
                                <p class="text-xs text-gray-500">
                                    Enter one phone number per line, with country code (e.g., +91XXXXXXXXXX)
                                </p>
                                <span id="recipientCount" class="text-xs text-blue-600 font-medium">0 recipients</span>
                            </div>
                        </div>

                        <!-- Parameters -->
                        <div id="templateParameters" class="hidden space-y-2">
                            <!-- Parameters will be added dynamically based on template selection -->
                        </div>

                        <!-- Schedule Option -->
                        <div class="flex items-center space-x-2">
                            <input 
                                type="checkbox" 
                                id="scheduleMessage"
                                class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                            <label for="scheduleMessage" class="text-sm text-gray-700">
                                Schedule for later
                            </label>
                        </div>

                        <!-- Schedule DateTime (hidden by default) -->
                        <div id="scheduleDateTime" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Schedule Date & Time (India Time)
                            </label>
                            <input 
                                type="datetime-local" 
                                name="scheduled_at"
                                id="scheduledAtInput"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                            <p class="text-xs text-gray-500 mt-1">Time will be set in Indian Standard Time (IST)</p>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="p-6 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-600">
                            <span id="formRecipientCount">0 recipients selected</span>
                            <span id="estimatedCost" class="ml-4"></span>
                        </div>
                        <div class="flex space-x-3">
                            <button 
                                type="button"
                                onclick="closeNewMessageModal()"
                                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                                Cancel
                            </button>
                            <button 
                                type="button"
                                onclick="saveDraft()"
                                class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                                Save Draft
                            </button>
                            <button 
                                type="submit"
                                id="submitBulkMessageBtn"
                                class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                                <span class="submit-text">Send Message</span>
                                <span class="submit-loading hidden">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Sending...
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Smart Recipients Modal -->
<div id="smartRecipientsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden" style="z-index: 9999;">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-3xl">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-700">Smart Recipient Selection</h3>
                    <button onclick="closeSmartRecipientsModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Filters -->
                    <div class="space-y-4">
                        <h4 class="font-semibold text-gray-700">Filter Criteria</h4>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lead Category</label>
                            <select id="leadCategory" class="w-full rounded-md border-gray-300">
                                <option value="valuable">High Value Leads</option>
                                <option value="average">Average Leads</option>
                                <option value="not_interested">Re-engagement</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Score</label>
                            <input type="range" id="minScore" min="0" max="100" value="50" class="w-full">
                            <span id="minScoreValue" class="text-sm text-gray-500">50</span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Interaction (Days)</label>
                            <select id="lastInteraction" class="w-full rounded-md border-gray-300">
                                <option value="7">Last 7 days</option>
                                <option value="30">Last 30 days</option>
                                <option value="90">Last 90 days</option>
                                <option value="">Any time</option>
                            </select>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" id="excludeRecent" checked>
                            <label for="excludeRecent" class="text-sm text-gray-700">
                                Exclude recent bulk message recipients
                            </label>
                        </div>
                        
                        <button onclick="loadSmartRecipients()" class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                            Load Recipients
                        </button>
                    </div>
                    
                    <!-- Results -->
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-4">Selected Recipients</h4>
                        <div id="recipientsList" class="max-h-80 overflow-y-auto border rounded-lg p-4">
                            <p class="text-gray-500 text-center">Click "Load Recipients" to see suggestions</p>
                        </div>
                        
                        <div class="mt-4 flex justify-end space-x-3">
                            <button onclick="closeSmartRecipientsModal()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                                Cancel
                            </button>
                            <button onclick="useSmartRecipients()" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                                Use These Recipients
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Analytics Modal -->
<div id="analyticsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden" style="z-index: 9998;">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-700">Bulk Message Analytics</h3>
                    <button onclick="closeAnalyticsModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <!-- Analytics Content -->
                <div class="space-y-6">
                    <!-- Performance Overview -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-blue-700">Total Messages</h4>
                            <p class="text-2xl font-bold text-blue-600" id="analyticsTotal">{{ $bulkMessages->count() }}</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-green-700">Average Success Rate</h4>
                            <p class="text-2xl font-bold text-green-600" id="analyticsSuccessRate">{{ $successRate }}%</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-purple-700">Best Template</h4>
                            <p class="text-lg font-bold text-purple-600" id="analyticsBestTemplate">Loading...</p>
                        </div>
                    </div>
                    
                    <!-- Charts Placeholder -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h4 class="font-semibold text-gray-700 mb-4">Performance Trends</h4>
                        <canvas id="performanceChart" width="400" height="200"></canvas>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end">
                    <button onclick="closeAnalyticsModal()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let templates = [];
let smartRecipients = [];

// Authentication and CSRF helper functions
function checkAuthentication() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrfToken) {
        console.warn('CSRF token not found, authentication may be invalid');
        return false;
    }
    return true;
}

function getCSRFToken() {
    const token = document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value;
    if (!token) {
        console.error('No CSRF token found');
    }
    return token;
}

function openNewMessageModal() {
    document.getElementById('newMessageModal').classList.remove('hidden');
}

function closeNewMessageModal() {
    document.getElementById('newMessageModal').classList.add('hidden');
}

function openSmartRecipientsModal() {
    document.getElementById('smartRecipientsModal').classList.remove('hidden');
}

function closeSmartRecipientsModal() {
    document.getElementById('smartRecipientsModal').classList.add('hidden');
}

function openAnalyticsModal() {
    document.getElementById('analyticsModal').classList.remove('hidden');
    loadAnalyticsData();
}

function closeAnalyticsModal() {
    document.getElementById('analyticsModal').classList.add('hidden');
}

function openRecipientsModal() {
    openSmartRecipientsModal();
}

function useTemplate(templateId) {
    document.querySelector('select[name="template_id"]').value = templateId;
    openNewMessageModal();
}

async function loadSmartRecipients() {
    const category = document.getElementById('leadCategory').value;
    const minScore = document.getElementById('minScore').value;
    const lastInteraction = document.getElementById('lastInteraction').value;
    const excludeRecent = document.getElementById('excludeRecent').checked;
    
    try {
        showAlert('Loading smart recipients...');
        
        const response = await fetch('/admin/whatsapp/bulk-messages/recipients', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                category: category,
                min_score: minScore,
                last_interaction_days: lastInteraction,
                exclude_recent_bulk: excludeRecent
            })
        });
        
        // Check if response is OK and contains JSON
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            // Likely received HTML (authentication redirect)
            showAlert('Authentication expired. Please refresh the page and try again.', true);
            setTimeout(() => window.location.reload(), 2000);
            return;
        }
        
        const data = await response.json();
        
        if (data.success) {
            smartRecipients = data.recipients;
            displaySmartRecipients(data.recipients, data.stats);
            showAlert(`Found ${data.total} recipients`);
        } else {
            throw new Error(data.message || 'Failed to load recipients');
        }
    } catch (error) {
        console.error('Load recipients error:', error);
        showAlert(error.message || 'Failed to load recipients', true);
    }
}

function displaySmartRecipients(recipients, stats) {
    const container = document.getElementById('recipientsList');
    
    if (recipients.length === 0) {
        container.innerHTML = '<p class="text-gray-500 text-center">No recipients found with current criteria</p>';
        return;
    }
    
    const html = `
        <div class="mb-4 p-3 bg-blue-50 rounded-lg">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="font-semibold">Total:</span> ${recipients.length}
                </div>
                <div>
                    <span class="font-semibold">Avg Score:</span> ${Math.round(stats.avg_score || 0)}
                </div>
            </div>
        </div>
        <div class="space-y-2 max-h-60 overflow-y-auto">
            ${recipients.map(recipient => `
                <div class="flex justify-between items-center p-2 border rounded">
                    <div>
                        <div class="font-medium">${recipient.phone}</div>
                        <div class="text-sm text-gray-500">
                            Score: ${recipient.interaction_score || 0} | 
                            Category: ${recipient.category}
                        </div>
                    </div>
                    <button onclick="removeRecipient('${recipient.phone}')" class="text-red-500 hover:text-red-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `).join('')}
        </div>
    `;
    
    container.innerHTML = html;
}

function removeRecipient(phone) {
    smartRecipients = smartRecipients.filter(r => r.phone !== phone);
    displaySmartRecipients(smartRecipients, {});
}

function useSmartRecipients() {
    const phones = smartRecipients.map(r => r.phone).join('\n');
    document.querySelector('textarea[name="recipients"]').value = phones;
    closeSmartRecipientsModal();
    openNewMessageModal();
}

async function sendMessage(messageId) {
    if (!confirm('Are you sure you want to send this bulk message now?')) return;
    
    // Check authentication before proceeding
    if (!checkAuthentication()) {
        showAlert('Authentication error. Please refresh the page and try again.', true);
        setTimeout(() => {
            window.location.href = '/admin/login';
        }, 2000);
        return;
    }
    
    try {
        showAlert('Sending bulk message...');
        
        console.log('Sending message with ID:', messageId);
        
        const response = await fetch('/admin/whatsapp/bulk-messages/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCSRFToken(),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                bulk_message_id: messageId
            })
        });

        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers.get('content-type'));

        // Check if response is HTML (likely authentication redirect)
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            console.log('Received HTML instead of JSON, likely authentication issue');
            // Likely received HTML (authentication redirect)
            window.location.href = '/admin/login';
            return;
        }
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('Response data:', data);
        
        if (data.success) {
            showAlert(`Message sent! ${data.sent} successful, ${data.failed} failed.`);
            setTimeout(() => window.location.reload(), 2000);
        } else {
            throw new Error(data.message || 'Failed to send message');
        }
    } catch (error) {
        console.error('Send error:', error);
        if (error.message.includes('Authentication expired')) {
            showAlert(error.message + ' Redirecting to login...', true);
            setTimeout(() => {
                window.location.href = '/admin/login';
            }, 2000);
        } else {
            showAlert(error.message || 'Failed to send message', true);
        }
    }
}

async function duplicateMessage(messageId) {
    try {
        const response = await fetch(`/admin/whatsapp/bulk-messages/${messageId}`);
        
        // Check if response is OK and contains JSON
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            // Likely received HTML (authentication redirect)
            window.location.href = '/admin/login';
            return;
        }
        
        const data = await response.json();
        
        if (data.template_id) {
            document.querySelector('select[name="template_id"]').value = data.template_id;
            
            if (data.recipients) {
                const recipients = JSON.parse(data.recipients);
                document.querySelector('textarea[name="recipients"]').value = recipients.join('\n');
            }
            
            if (data.parameters) {
                const params = JSON.parse(data.parameters);
                Object.keys(params).forEach(key => {
                    const input = document.querySelector(`input[name="params[${key}]"]`);
                    if (input) input.value = params[key];
                });
            }
            
            openNewMessageModal();
            showAlert('Message duplicated successfully');
        }
    } catch (error) {
        console.error('Duplicate error:', error);
        showAlert('Failed to duplicate message', true);
    }
}

async function exportMessage(messageId) {
    try {
        showAlert('Preparing export...');
        
        const response = await fetch(`/admin/whatsapp/bulk-messages/${messageId}/export`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (response.ok) {
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `bulk-message-${messageId}.csv`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
            showAlert('Export downloaded successfully');
        } else {
            throw new Error('Export failed');
        }
    } catch (error) {
        console.error('Export error:', error);
        showAlert('Failed to export message', true);
    }
}

async function exportBulkMessages() {
    try {
        showAlert('Preparing bulk export...');
        
        const response = await fetch('/admin/whatsapp/bulk-messages/export', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (response.ok) {
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `bulk-messages-export-${new Date().toISOString().split('T')[0]}.csv`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
            showAlert('Export downloaded successfully');
        } else {
            throw new Error('Export failed');
        }
    } catch (error) {
        console.error('Export error:', error);
        showAlert('Failed to export data', true);
    }
}

async function loadAnalyticsData() {
    try {
        const response = await fetch('/admin/whatsapp/bulk-messages/analytics');
        
        // Check if response is OK and contains JSON
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            // Likely received HTML (authentication redirect)
            console.log('Authentication expired, not updating analytics');
            return;
        }
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('analyticsBestTemplate').textContent = data.best_template || 'N/A';
            // Update other analytics data as needed
        }
    } catch (error) {
        console.error('Analytics error:', error);
    }
}

function openNewMessageModal() {
    document.getElementById('newMessageModal').classList.remove('hidden');
}

function closeNewMessageModal() {
    document.getElementById('newMessageModal').classList.add('hidden');
}

function useTemplate(templateId) {
    document.querySelector('select[name="template_id"]').value = templateId;
    openNewMessageModal();
}

function showSyncAlert(message, isError = false) {
    const alert = document.getElementById('syncAlert');
    const messageEl = document.getElementById('syncMessage');
    const spinner = document.getElementById('syncSpinner');
    
    alert.classList.remove('hidden');
    alert.className = `mb-4 p-4 rounded-lg ${isError ? 'bg-red-100' : 'bg-blue-100'}`;
    messageEl.className = `text-sm font-medium ${isError ? 'text-red-700' : 'text-blue-700'}`;
    messageEl.textContent = message;
    
    if (isError) {
        spinner.classList.add('hidden');
    } else {
        spinner.classList.remove('hidden');
    }
}

async function syncTemplates() {
    showSyncAlert('Syncing templates from WhatsApp...');
    
    try {
        const response = await fetch('/admin/whatsapp/templates/sync', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        // Check if response is OK and contains JSON
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            // Likely received HTML (authentication redirect)
            showSyncAlert('Authentication expired. Please refresh the page and try again.', true);
            setTimeout(() => window.location.reload(), 2000);
            return;
        }
        
        const data = await response.json();
        
        if (data.success) {
            templates = data.templates || [];
            updateTemplateUI();
            showSyncAlert(`Successfully synced ${templates.length} templates`, false);
            setTimeout(() => {
                document.getElementById('syncAlert').classList.add('hidden');
                location.reload(); // Reload to show updated templates
            }, 2000);
        } else {
            throw new Error(data.message || 'Failed to sync templates');
        }
    } catch (error) {
        console.error('Sync error:', error);
        showSyncAlert(error.message || 'Failed to sync templates', true);
        setTimeout(() => {
            document.getElementById('syncAlert').classList.add('hidden');
        }, 3000);
    }
}

function updateTemplateUI() {
    // Update template select in modal
    const select = document.querySelector('select[name="template_id"]');
    select.innerHTML = '<option value="">Select a template</option>';
    templates.forEach(template => {
        const option = document.createElement('option');
        option.value = template.id;
        option.textContent = template.name;
        select.appendChild(option);
    });
    
    // Update templates grid
    const grid = document.querySelector('.grid.grid-cols-1.md\\:grid-cols-3.gap-4');
    if (templates.length === 0) {
        grid.innerHTML = `
            <div class="col-span-3 text-center text-gray-500">
                No templates available. Click "Sync Templates" to fetch from WhatsApp.
            </div>
        `;
        return;
    }
    
    grid.innerHTML = templates.map(template => `
        <div class="border rounded-lg p-4 hover:shadow-md transition-shadow duration-300">
            <div class="flex justify-between items-start mb-2">
                <h4 class="text-lg font-semibold">${template.name}</h4>
                <button 
                    onclick="useTemplate(${template.id})"
                    class="text-blue-500 hover:text-blue-700">
                    <i class="fas fa-arrow-right"></i>
                </button>
            </div>
            <p class="text-gray-600 text-sm mb-2">${template.content.substring(0, 100)}${template.content.length > 100 ? '...' : ''}</p>
            ${template.parameters ? `
                <div class="text-xs text-gray-500">
                    Parameters: ${template.parameters.join(', ')}
                </div>
            ` : ''}
        </div>
    `).join('');
}

// Set up event listeners after DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    // Handle schedule checkbox
    const scheduleCheckbox = document.getElementById('scheduleMessage');
    if (scheduleCheckbox) {
        scheduleCheckbox.addEventListener('change', function(e) {
            const scheduleDateTime = document.getElementById('scheduleDateTime');
            const scheduledAtInput = document.getElementById('scheduledAtInput');
            
            if (!scheduleDateTime || !scheduledAtInput) {
                console.error('Schedule elements not found');
                return;
            }
            
            if (e.target.checked) {
                scheduleDateTime.classList.remove('hidden');
                
                // Set minimum datetime to current time in IST
                const now = new Date();
                
                // Get current IST time more accurately
                const istTime = new Date(now.toLocaleString("en-US", {timeZone: "Asia/Kolkata"}));
                
                // Format for datetime-local input (YYYY-MM-DDTHH:MM)
                const year = istTime.getFullYear();
                const month = String(istTime.getMonth() + 1).padStart(2, '0');
                const day = String(istTime.getDate()).padStart(2, '0');
                const hours = String(istTime.getHours()).padStart(2, '0');
                const minutes = String(istTime.getMinutes()).padStart(2, '0');
                
                const currentISTString = `${year}-${month}-${day}T${hours}:${minutes}`;
                
                scheduledAtInput.min = currentISTString;
                
                // Set default value to 5 minutes from now in IST
                const defaultTime = new Date(istTime.getTime() + (5 * 60 * 1000));
                const defaultYear = defaultTime.getFullYear();
                const defaultMonth = String(defaultTime.getMonth() + 1).padStart(2, '0');
                const defaultDay = String(defaultTime.getDate()).padStart(2, '0');
                const defaultHours = String(defaultTime.getHours()).padStart(2, '0');
                const defaultMinutes = String(defaultTime.getMinutes()).padStart(2, '0');
                
                const defaultISTString = `${defaultYear}-${defaultMonth}-${defaultDay}T${defaultHours}:${defaultMinutes}`;
                scheduledAtInput.value = defaultISTString;
                
                console.log('Current IST:', currentISTString);
                console.log('Default IST:', defaultISTString);
            } else {
                scheduleDateTime.classList.add('hidden');
                scheduledAtInput.value = '';
            }
        });
    }

    // Handle template selection
    const templateSelect = document.querySelector('select[name="template_id"]');
    if (templateSelect) {
        templateSelect.addEventListener('change', function(e) {
            const templateId = e.target.value;
            if (!templateId) {
                document.getElementById('templateParameters').classList.add('hidden');
                return;
            }

            fetch(`/admin/whatsapp/templates/${templateId}`)
                .then(response => {
                    // Check if response is OK and contains JSON
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        // Likely received HTML (authentication redirect)
                        window.location.href = '/admin/login';
                        return;
                    }
                    
                    return response.json();
                })
                .then(template => {
                    if (template) {
                        updateTemplateParameters(template);
                    }
                })
                .catch(error => {
                    console.error('Error loading template:', error);
                    showAlert('Failed to load template details. Please try again.', true);
                });
        });
    }

    // Handle recipients textarea
    const recipientsTextarea = document.querySelector('textarea[name="recipients"]');
    if (recipientsTextarea) {
        recipientsTextarea.addEventListener('input', updateRecipientCount);
    }

    // Handle min score range
    const minScoreRange = document.getElementById('minScore');
    if (minScoreRange) {
        minScoreRange.addEventListener('input', function(e) {
            document.getElementById('minScoreValue').textContent = e.target.value;
        });
    }
});

function updateRecipientCount() {
    const textarea = document.querySelector('textarea[name="recipients"]');
    const recipients = textarea.value.trim().split('\n').filter(line => line.trim().length > 0);
    const count = recipients.length;
    
    document.getElementById('recipientCount').textContent = `${count} recipients`;
    document.getElementById('formRecipientCount').textContent = `${count} recipients selected`;
    
    // Estimate cost (assuming $0.005 per message)
    const estimatedCost = (count * 0.005).toFixed(3);
    document.getElementById('estimatedCost').textContent = `Est. cost: $${estimatedCost}`;
}

async function saveDraft() {
    const form = document.getElementById('bulkMessageForm');
    if (!form) {
        console.error('Form not found');
        return;
    }
    
    const formData = new FormData(form);
    formData.append('is_draft', 'true');

    // Handle scheduled_at with Indian timezone
    const scheduleCheckbox = document.getElementById('scheduleMessage');
    const scheduledAtInput = document.getElementById('scheduledAtInput');
    
    if (scheduleCheckbox && scheduledAtInput && scheduleCheckbox.checked && scheduledAtInput.value) {
        // Convert IST datetime to UTC for database storage
        // Input format: YYYY-MM-DDTHH:MM (browser treats this as local time)
        const inputValue = scheduledAtInput.value;
        
        // Parse the input value and treat it as IST
        // We need to create a proper IST date and convert to UTC
        const istDate = new Date(inputValue + '+05:30'); // Add IST timezone offset
        
        // If the above doesn't work in all browsers, use manual calculation
        if (isNaN(istDate.getTime())) {
            // Fallback: manual timezone conversion
            const localDate = new Date(inputValue);
            // Since IST is UTC+5:30, subtract 5.5 hours to get UTC
            const utcDate = new Date(localDate.getTime() - (5.5 * 60 * 60 * 1000));
            var formattedDateTime = utcDate.toISOString().slice(0, 19).replace('T', ' ');
        } else {
            var formattedDateTime = istDate.toISOString().slice(0, 19).replace('T', ' ');
        }
        
        console.log('IST Input:', inputValue);
        console.log('UTC Output:', formattedDateTime);
        console.log('Current UTC:', new Date().toISOString().slice(0, 19).replace('T', ' '));
        
        formData.set('scheduled_at', formattedDateTime);
        formData.set('is_scheduled', '1');
    } else {
        formData.delete('scheduled_at');
        formData.set('is_scheduled', '0');
    }

    try {
        const response = await fetch('/admin/whatsapp/bulk-messages', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value
            }
        });

        // Check if response is HTML (likely authentication redirect)
        if (response.headers.get('content-type')?.includes('text/html')) {
            throw new Error('Authentication expired. Please refresh the page and try again.');
        }

        const data = await response.json();
        
        if (data.success) {
            showAlert('Draft saved successfully!');
            closeNewMessageModal();
            setTimeout(() => window.location.reload(), 1000);
        } else {
            throw new Error(data.message || 'Failed to save draft');
        }
    } catch (error) {
        console.error('Save draft error:', error);
        if (error.message.includes('Authentication expired')) {
            showAlert(error.message + ' Redirecting to login...', true);
            // Redirect to login page after a short delay
            setTimeout(() => {
                window.location.href = '/admin/login';
            }, 2000);
        } else {
            showAlert(error.message || 'Failed to save draft', true);
        }
    }
}

function updateTemplateParameters(template) {
    try {
        // Safely parse parameters with error handling
        let parameters = [];
        if (template.parameters) {
            if (typeof template.parameters === 'string') {
                parameters = JSON.parse(template.parameters);
            } else if (Array.isArray(template.parameters)) {
                parameters = template.parameters;
            }
        }
        
        const container = document.getElementById('templateParameters');
        container.innerHTML = '';
        
        if (parameters.length > 0) {
            container.classList.remove('hidden');
            parameters.forEach(param => {
                container.innerHTML += `
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            ${param}
                        </label>
                        <input 
                            type="text"
                            name="params[${param}]"
                        onkeyup="updateLivePreview('${template.id}')"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                        required>
                </div>
            `;
        });

        // Add test number field for preview
        container.innerHTML += `
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Test Phone Number (for preview)
                </label>
                <div class="flex space-x-2">
                    <input 
                        type="text"
                        id="testPhone"
                        placeholder="e.g., +1234567890"
                        class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                    <button 
                        type="button"
                        onclick="testTemplate('${template.id}')"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        Test
                    </button>
                </div>
            </div>
        `;
    } else {
        container.classList.add('hidden');
    }
    } catch (error) {
        console.error('Error loading template parameters:', error);
        const container = document.getElementById('templateParameters');
        container.innerHTML = '<p class="text-red-500">Error loading template parameters</p>';
        container.classList.remove('hidden');
    }
}

async function testTemplate(templateId) {
    const testPhone = document.getElementById('testPhone').value;
    if (!testPhone) {
        showAlert('Please enter a test phone number', true);
        return;
    }

    const params = {};
    const inputs = document.querySelectorAll('#templateParameters input[name^="params"]');
    inputs.forEach(input => {
        const paramName = input.name.match(/\[(.*?)\]/)[1];
        params[paramName] = input.value;
    });

    try {
        showAlert('Sending test message...');
        
        const response = await fetch('/admin/whatsapp/templates/test', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                template_id: templateId,
                phone: testPhone,
                parameters: params
            })
        });

        // Check if response is OK and contains JSON
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            // Likely received HTML (authentication redirect)
            showAlert('Authentication expired. Please refresh the page and try again.', true);
            setTimeout(() => window.location.reload(), 2000);
            return;
        }

        const data = await response.json();
        
        if (data.success) {
            showAlert('Test message sent successfully!');
        } else {
            throw new Error(data.message || 'Failed to send test message');
        }
    } catch (error) {
        console.error('Test error:', error);
        showAlert(error.message || 'Failed to send test message', true);
    }
}

function showAlert(message, isError = false) {
    const alert = document.createElement('div');
    alert.className = `fixed bottom-4 right-4 p-4 rounded-lg ${isError ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'} shadow-lg`;
    alert.textContent = message;
    document.body.appendChild(alert);
    setTimeout(() => alert.remove(), 3000);
}

function submitBulkMessage(event) {
    event.preventDefault();
    const form = event.target;
    
    if (!form) {
        console.error('Form element not found');
        return;
    }
    
    // Check authentication before proceeding
    if (!checkAuthentication()) {
        showAlert('Authentication error. Please refresh the page and try again.', true);
        setTimeout(() => {
            window.location.href = '/admin/login';
        }, 2000);
        return;
    }
    
    const formData = new FormData(form);

    // Handle scheduled_at with Indian timezone
    const scheduleCheckbox = document.getElementById('scheduleMessage');
    const scheduledAtInput = document.getElementById('scheduledAtInput');
    
    if (scheduleCheckbox && scheduledAtInput && scheduleCheckbox.checked && scheduledAtInput.value) {
        // Convert IST datetime to UTC for database storage
        // Input format: YYYY-MM-DDTHH:MM (browser treats this as local time)
        const inputValue = scheduledAtInput.value;
        
        // Parse the input value and treat it as IST
        // We need to create a proper IST date and convert to UTC
        const istDate = new Date(inputValue + '+05:30'); // Add IST timezone offset
        
        // If the above doesn't work in all browsers, use manual calculation
        if (isNaN(istDate.getTime())) {
            // Fallback: manual timezone conversion
            const localDate = new Date(inputValue);
            // Since IST is UTC+5:30, subtract 5.5 hours to get UTC
            const utcDate = new Date(localDate.getTime() - (5.5 * 60 * 60 * 1000));
            var formattedDateTime = utcDate.toISOString().slice(0, 19).replace('T', ' ');
        } else {
            var formattedDateTime = istDate.toISOString().slice(0, 19).replace('T', ' ');
        }
        
        console.log('IST Input:', inputValue);
        console.log('UTC Output:', formattedDateTime);
        console.log('Current UTC:', new Date().toISOString().slice(0, 19).replace('T', ' '));
        
        formData.set('scheduled_at', formattedDateTime);
        formData.set('is_scheduled', '1');
    } else {
        formData.delete('scheduled_at');
        formData.set('is_scheduled', '0');
    }

    // Show loading state
    const submitButton = document.getElementById('submitBulkMessageBtn') || 
                        document.querySelector('#bulkMessageForm button[type="submit"]') || 
                        form.querySelector('button[type="submit"]');
    if (!submitButton) {
        console.error('Submit button not found');
        console.log('Form:', form);
        console.log('Form HTML:', form ? form.outerHTML : 'No form');
        return;
    }
    
    const submitText = submitButton.querySelector('.submit-text');
    const submitLoading = submitButton.querySelector('.submit-loading');
    
    submitButton.disabled = true;
    if (submitText) submitText.classList.add('hidden');
    if (submitLoading) submitLoading.classList.remove('hidden');

    fetch('/admin/whatsapp/bulk-messages', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': getCSRFToken(),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        // Check if response is HTML (likely authentication redirect)
        if (response.headers.get('content-type')?.includes('text/html')) {
            throw new Error('Authentication expired. Please refresh the page and try again.');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showAlert('Bulk message created successfully!');
            closeNewMessageModal();
            // Refresh the page to show the new message
            setTimeout(() => window.location.reload(), 1000);
        } else {
            throw new Error(data.message || 'Failed to create bulk message');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (error.message.includes('Authentication expired')) {
            showAlert(error.message + ' Redirecting to login...', true);
            // Redirect to login page after a short delay
            setTimeout(() => {
                window.location.href = '/admin/login';
            }, 2000);
        } else {
            showAlert(error.message || 'An error occurred while sending the message', true);
        }
    })
    .finally(() => {
        // Reset button state
        if (submitButton) {
            submitButton.disabled = false;
            if (submitText) submitText.classList.remove('hidden');
            if (submitLoading) submitLoading.classList.add('hidden');
        }
    });
}

function viewDetails(messageId) {
    window.location.href = `/admin/whatsapp/bulk-messages/${messageId}`;
}

function cancelMessage(messageId) {
    if (confirm('Are you sure you want to cancel this message?')) {
        fetch(`/admin/whatsapp/bulk-messages/${messageId}/cancel`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            // Check if response is OK and contains JSON
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                // Likely received HTML (authentication redirect)
                window.location.href = '/admin/login';
                return;
            }
            
            return response.json();
        })
        .then(data => {
            if (data.success) {
                window.location.href = '/admin/login';
            } else {
                alert(data.message || 'An error occurred');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while canceling the message');
        });
    }
}

function deleteMessage(messageId) {
    if (confirm('Are you sure you want to delete this message? This action cannot be undone.')) {
        fetch(`/admin/whatsapp/bulk-messages/${messageId}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                action: 'delete'
            })
        })
        .then(response => {
            // Check if response is OK and contains JSON
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                // Likely received HTML (authentication redirect)
                window.location.href = '/admin/login';
                return;
            }
            
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showAlert('Message deleted successfully');
                window.location.href = '/admin/login';
            } else {
                alert(data.message || 'An error occurred');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the message');
        });
    }
}
</script>
@verbatim
<script>
function updateLivePreview(templateId) {
    const template = templates.find(t => t.id == templateId);
    if (!template) return;

    let previewText = template.content;
    const inputs = document.querySelectorAll('#templateParameters input[name^="params"]');

    const escapeRegex = str => str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');

    inputs.forEach(input => {
        const paramName = input.name.match(/\[(.*?)\]/)[1];
        const value = input.value || `[${paramName}]`;
        const regex = new RegExp(`{{${escapeRegex(paramName)}}}`, 'g');
        previewText = previewText.replace(regex, value);
    });

    document.getElementById('previewContent').textContent = previewText;
}

// Unified Console functionality
let autoScroll = true;
let refreshInterval;
let totalEntryCount = 0;

function addToConsole(message, type = 'info', skipVerbose = false) {
    // Skip verbose info messages
    if (type === 'info' && (
        message.includes('Running scheduled real-time update') ||
        message.includes('Fetching real-time data') ||
        message.includes('Real-time data updated successfully') ||
        message.includes('Console monitoring') ||
        message.includes('Status monitoring active')
    )) {
        return; // Don't show these verbose messages
    }
    
    const console = document.getElementById('unifiedConsole');
    const timestamp = new Date().toLocaleTimeString('en-US', { hour12: false });
    
    let colorClass = 'text-blue-400';
    let prefix = '[INFO]';
    let icon = 'ℹ️';
    
    switch(type) {
        case 'message_sent':
            colorClass = 'text-green-400';
            prefix = '[MESSAGE_SENT]';
            icon = '✅';
            updateCounter('messagesSentCount', 1);
            break;
        case 'message_failed':
            colorClass = 'text-red-400';
            prefix = '[MESSAGE_FAILED]';
            icon = '❌';
            updateCounter('messagesFailedCount', 1);
            updateCounter('totalErrorCount', 1);
            break;
        case 'template_error':
            colorClass = 'text-red-400';
            prefix = '[TEMPLATE_ERROR]';
            icon = '📋';
            updateCounter('totalErrorCount', 1);
            break;
        case 'api_error':
            colorClass = 'text-orange-400';
            prefix = '[API_ERROR]';
            icon = '🌐';
            updateCounter('totalErrorCount', 1);
            break;
        case 'phone_error':
            colorClass = 'text-yellow-400';
            prefix = '[PHONE_ERROR]';
            icon = '📱';
            updateCounter('totalErrorCount', 1);
            break;
        case 'success':
            colorClass = 'text-green-400';
            prefix = '[SUCCESS]';
            icon = '✅';
            break;
        case 'error':
            colorClass = 'text-red-400';
            prefix = '[ERROR]';
            icon = '❌';
            updateCounter('totalErrorCount', 1);
            break;
        case 'warning':
            colorClass = 'text-yellow-400';
            prefix = '[WARNING]';
            icon = '⚠️';
            break;
        case 'processing':
            colorClass = 'text-purple-400';
            prefix = '[PROCESSING]';
            icon = '⚡';
            break;
    }
    
    const logEntry = document.createElement('div');
    logEntry.className = `${colorClass} mb-1`;
    logEntry.innerHTML = `<span class="text-gray-400">${timestamp}</span> ${icon} <span class="font-bold">${prefix}</span> ${message}`;
    
    console.appendChild(logEntry);
    totalEntryCount++;
    
    // Keep only last 100 entries to prevent memory issues
    const entries = console.children;
    if (entries.length > 100) {
        console.removeChild(entries[0]);
    }
    
    // Update UI elements
    document.getElementById('lastUpdated').textContent = timestamp;
    document.getElementById('totalEntries').textContent = totalEntryCount;
    updateSuccessRate();
    
    // Auto-scroll to bottom if enabled
    if (autoScroll) {
        console.scrollTop = console.scrollHeight;
    }
}

function updateCounter(elementId, increment = 1) {
    const element = document.getElementById(elementId);
    if (element) {
        const currentCount = parseInt(element.textContent) || 0;
        element.textContent = currentCount + increment;
    }
}

function updateSuccessRate() {
    const sent = parseInt(document.getElementById('messagesSentCount').textContent) || 0;
    const failed = parseInt(document.getElementById('messagesFailedCount').textContent) || 0;
    const total = sent + failed;
    
    if (total > 0) {
        const rate = Math.round((sent / total) * 100);
        document.getElementById('successRate').textContent = rate + '%';
    }
}

function clearConsole() {
    const console = document.getElementById('unifiedConsole');
    console.innerHTML = '<div class="text-gray-400">Console cleared...</div>';
    
    // Reset all counters
    document.getElementById('totalErrorCount').textContent = '0';
    document.getElementById('messagesSentCount').textContent = '0';
    document.getElementById('messagesFailedCount').textContent = '0';
    document.getElementById('successRate').textContent = '0%';
    document.getElementById('totalEntries').textContent = '0';
    
    totalEntryCount = 0;
    document.getElementById('lastUpdated').textContent = 'Just now';
    addToConsole('Console cleared', 'info');
}

function toggleAutoScroll() {
    autoScroll = !autoScroll;
    const btn = document.getElementById('autoScrollBtn');
    btn.textContent = `Auto-scroll: ${autoScroll ? 'ON' : 'OFF'}`;
    btn.className = autoScroll ? 
        'px-3 py-1 text-xs bg-purple-500 text-white rounded hover:bg-purple-600' :
        'px-3 py-1 text-xs bg-gray-500 text-white rounded hover:bg-gray-600';
}

async function refreshStatus() {
    addToConsole('Refreshing message status...', 'processing');
    
    try {
        const response = await fetch('/admin/whatsapp/bulk-messages/status-summary', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.success) {
            // Update statistics without verbose logging
            if (data.recent_errors && data.recent_errors.length > 0) {
                data.recent_errors.forEach(error => {
                    addToConsole(error.message, error.type);
                });
            }
            
            if (data.recent_messages && data.recent_messages.length > 0) {
                data.recent_messages.forEach(msg => {
                    if (msg.status === 'sent') {
                        addToConsole(`Message to ${msg.phone} sent successfully`, 'message_sent');
                    } else if (msg.status === 'failed') {
                        addToConsole(`Message to ${msg.phone} failed: ${msg.error}`, 'message_failed');
                    }
                });
            }
            
            // Update counters if provided
            if (data.stats) {
                document.getElementById('messagesSentCount').textContent = data.stats.total_sent || 0;
                document.getElementById('messagesFailedCount').textContent = data.stats.total_failed || 0;
                document.getElementById('totalErrorCount').textContent = data.stats.total_errors || 0;
                updateSuccessRate();
            }
            
            addToConsole('Status updated successfully', 'success');
        } else {
            throw new Error(data.message || 'Failed to fetch status');
        }
    } catch (error) {
        console.error('Error refreshing status:', error);
        addToConsole(`Failed to refresh status: ${error.message}`, 'api_error');
    }
}

async function exportLog() {
    try {
        addToConsole('Preparing log export...', 'processing');
        
        const response = await fetch('/admin/whatsapp/bulk-messages/export-logs', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (response.ok) {
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `whatsapp-logs-${new Date().toISOString().split('T')[0]}.csv`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
            addToConsole('Log exported successfully', 'success');
        } else {
            throw new Error('Export failed');
        }
    } catch (error) {
        console.error('Export error:', error);
        addToConsole(`Failed to export logs: ${error.message}`, 'error');
    }
}

function startConsoleRefresh() {
    // Start monitoring without verbose logging
    refreshInterval = setInterval(() => {
        // Silent status check - only show actual errors/messages
        checkMessageStatus(true); // true = silent mode
    }, 10000); // Check every 10 seconds
    
    document.getElementById('consoleStatus').textContent = 'ACTIVE';
    document.getElementById('consoleStatus').className = 'text-green-600';
    document.getElementById('monitoringStatus').textContent = 'ON';
    document.getElementById('monitoringStatus').className = 'text-green-600';
    
    addToConsole('Console monitoring started', 'success');
}

function stopConsoleRefresh() {
    if (refreshInterval) {
        clearInterval(refreshInterval);
        refreshInterval = null;
    }
    
    document.getElementById('consoleStatus').textContent = 'STOPPED';
    document.getElementById('consoleStatus').className = 'text-red-600';
    document.getElementById('monitoringStatus').textContent = 'OFF';
    document.getElementById('monitoringStatus').className = 'text-red-600';
    
    addToConsole('Console monitoring stopped', 'warning');
}

function checkMessageStatus(silent = false) {
    // Get all processing messages and update their status
    fetch('/admin/whatsapp/bulk-messages/status-check', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success && data.messages) {
            // Track previously seen statuses to avoid duplicate logs
            if (!window.seenStatuses) {
                window.seenStatuses = {};
            }
            
            data.messages.forEach(message => {
                const statusKey = `${message.id}_${message.status}_${message.sent_count}`;
                
                if (!window.seenStatuses[statusKey]) {
                    window.seenStatuses[statusKey] = true;
                    
                    if (message.status === 'processing') {
                        addToConsole(`Processing message ID: ${message.id} (${message.sent_count}/${message.total_recipients} sent)`, 'processing');
                    } else if (message.status === 'completed') {
                        addToConsole(`Message ID: ${message.id} completed - ${message.sent_count} sent, ${message.failed_count} failed`, 'success');
                    } else if (message.status === 'failed') {
                        const errorCount = message.error_details ? JSON.parse(message.error_details).length : 0;
                        addToConsole(`Message ID: ${message.id} failed - ${errorCount} errors`, 'error');
                    }
                }
            });
        }
    })
    .catch(error => {
        if (!silent) {
            console.error('Status check error:', error);
            addToConsole(`Status check failed: ${error.message}`, 'api_error');
        }
    });
}

function checkMessageStatus() {
    // Get all processing messages and update their status
    fetch('/admin/whatsapp/bulk-messages/status-check', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success && data.messages) {
            // Track previously seen statuses to avoid duplicate logs
            if (!window.seenStatuses) {
                window.seenStatuses = {};
            }
            
            data.messages.forEach(message => {
                const statusKey = `${message.id}_${message.status}_${message.sent_count}`;
                
                if (!window.seenStatuses[statusKey]) {
                    window.seenStatuses[statusKey] = true;
                    
                    if (message.status === 'processing') {
                        addToConsole(`⚡ Processing message ID: ${message.id} (${message.sent_count}/${message.total_recipients} sent)`, 'processing');
                    } else if (message.status === 'completed') {
                        addToConsole(`✅ Message ID: ${message.id} completed - ${message.sent_count} sent, ${message.failed_count} failed`, 'success');
                    } else if (message.status === 'failed') {
                        const errorCount = message.error_details ? JSON.parse(message.error_details).length : 0;
                        addToConsole(`❌ Message ID: ${message.id} failed - ${errorCount} errors`, 'error');
                    }
                }
            });
        }
    })
    .catch(error => {
        console.error('Status check error:', error);
        // Don't log this to console as it would be too noisy
    });
}

// Override existing functions to add console logging
let originalSubmitBulkMessage;
let realTimeUpdateInterval;
let lastUpdateHash = '';

// Global error handler
window.addEventListener('error', function(event) {
    console.error('JavaScript Error:', event.error);
    if (typeof addToConsole === 'function') {
        addToConsole('🚨 JavaScript Error: ' + event.error.message, 'error');
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Add visible startup indicator
    document.title = '🔄 Loading WhatsApp Bulk Messages...';
    
    // Store original function if it exists
    if (typeof submitBulkMessage === 'function') {
        originalSubmitBulkMessage = submitBulkMessage;
        
        // Override with console logging
        window.submitBulkMessage = function(event) {
            addToConsole('Initiating bulk message creation...', 'processing');
            
            try {
                const result = originalSubmitBulkMessage(event);
                if (result && result.then) {
                    result.then(() => {
                        addToConsole('Bulk message created successfully!', 'success');
                    }).catch((error) => {
                        addToConsole(`Failed to create bulk message: ${error.message}`, 'error');
                    });
                } else {
                    addToConsole('Bulk message request submitted', 'success');
                }
                return result;
            } catch (error) {
                addToConsole(`Error creating bulk message: ${error.message}`, 'error');
                throw error;
            }
        };
    }

    // Override sendMessage function
    if (typeof sendMessage === 'function') {
        const originalSendMessage = sendMessage;
        window.sendMessage = async function(messageId) {
            addToConsole(`Sending individual message ID: ${messageId}...`, 'processing');
            
            try {
                const result = await originalSendMessage(messageId);
                addToConsole(`Message ID: ${messageId} sent successfully!`, 'success');
                return result;
            } catch (error) {
                addToConsole(`Failed to send message ID: ${messageId} - ${error.message}`, 'error');
                throw error;
            }
        };
    }

    startConsoleRefresh();
    addToConsole('WhatsApp Message Console initialized', 'success');
    
    // Update page title to show system is ready
    document.title = '✅ WhatsApp Bulk Messages - Console Active';
    
    // Start real-time updates with reduced verbosity
    try {
        addToConsole('Real-time monitoring enabled', 'success');
        startRealTimeUpdates();
    } catch (error) {
        console.error('Failed to start real-time updates:', error);
        addToConsole('Failed to start real-time updates: ' + error.message, 'error');
        document.title = '❌ WhatsApp Bulk Messages - Error';
    }
});

// ===== COMPREHENSIVE REAL-TIME UPDATE SYSTEM =====
// Variables already declared above

function startRealTimeUpdates() {
    // Initial load
    addToConsole('🔄 Starting real-time updates...', 'info');
    updateRealTimeData();
    
    // Update every 3 seconds for better responsiveness
    realTimeUpdateInterval = setInterval(() => {
        addToConsole('⏱️ Running scheduled real-time update...', 'info');
        updateRealTimeData();
    }, 3000);
    
    addToConsole('🔄 Comprehensive real-time updates started', 'info');
}

function stopRealTimeUpdates() {
    if (realTimeUpdateInterval) {
        clearInterval(realTimeUpdateInterval);
        realTimeUpdateInterval = null;
        addToConsole('⏸️ Real-time updates stopped', 'warning');
    }
}

async function updateRealTimeData() {
    try {
        // Update last call indicator (but don't log it)
        document.getElementById('lastApiCall').textContent = new Date().toLocaleTimeString();
        
        const response = await fetch('/admin/whatsapp/bulk-messages/realtime-stats', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        if (!response.ok) {
            // Handle specific error cases silently unless critical
            if (response.status === 404) {
                addToConsole('Route not found - Check route registration', 'error');
                stopRealTimeUpdates();
                return;
            } else if (response.status === 401 || response.status === 403) {
                addToConsole('Authentication error - Please refresh the page', 'error');
                stopRealTimeUpdates();
                return;
            } else if (response.status === 419) {
                addToConsole('CSRF token expired - Refreshing page...', 'warning');
                setTimeout(() => window.location.reload(), 2000);
                return;
            } else if (response.status === 500) {
                addToConsole('Server error - Check console for details', 'error');
                return;
            }
            throw new Error(`HTTP error! status: ${response.status} - ${response.statusText}`);
        }

        const data = await response.json();
        
        if (data.success) {
            // Update statistics cards silently
            updateStatisticsCards(data.stats);
            
            // Update table data silently
            updateTableData(data.table_data);
            
            // Update last updated time
            document.getElementById('lastUpdated').textContent = data.last_updated;
            
            // Create content hash for change detection
            const currentHash = JSON.stringify(data.stats) + JSON.stringify(data.table_data);
            
            // Only log significant changes, not routine updates
            if (lastUpdateHash && lastUpdateHash !== currentHash) {
                // Check if there are any actual errors or status changes to report
                if (data.new_errors && data.new_errors.length > 0) {
                    data.new_errors.forEach(error => {
                        addToConsole(error.message, error.type);
                    });
                }
                
                if (data.status_changes && data.status_changes.length > 0) {
                    data.status_changes.forEach(change => {
                        addToConsole(change.message, change.type);
                    });
                }
            }
            
            lastUpdateHash = currentHash;
            
            // Clear any previous error state silently
            document.getElementById('realTimeStatus').textContent = 'ON';
            document.getElementById('realTimeStatus').className = 'text-green-600';
            
            // Reset error count on success
            window.realTimeErrorCount = 0;
            
            // Don't log successful updates unless there are actual changes
            
        } else {
            throw new Error(data.error || 'Unknown server error');
        }
    } catch (error) {
        console.error('Real-time update error:', error);
        
        // Update status indicator
        document.getElementById('realTimeStatus').textContent = 'ERROR';
        document.getElementById('realTimeStatus').className = 'text-red-600';
        
        // Only log errors, not routine update failures
        addToConsole(`Real-time update failed: ${error.message}`, 'api_error');
        
        // If too many consecutive errors, stop updates
        if (!window.realTimeErrorCount) window.realTimeErrorCount = 0;
        window.realTimeErrorCount++;
        
        if (window.realTimeErrorCount >= 5) {
            addToConsole('Too many errors - Stopping real-time updates', 'error');
            stopRealTimeUpdates();
        }
    }
}

function updateStatisticsCards(stats) {
    try {
        // Update Total Sent
        const totalSentElement = document.querySelector('[data-stat="total-sent"]');
        if (totalSentElement) {
            updateElementWithAnimation(totalSentElement, stats.total_sent.toLocaleString());
        }
        
        // Update Success Rate
        const successRateElement = document.querySelector('[data-stat="success-rate"]');
        if (successRateElement) {
            updateElementWithAnimation(successRateElement, stats.success_rate + '%');
        }
        
        // Update Pending Count
        const pendingElement = document.querySelector('[data-stat="pending"]');
        if (pendingElement) {
            updateElementWithAnimation(pendingElement, stats.pending_count);
        }
        
        // Update This Month
        const thisMonthElement = document.querySelector('[data-stat="this-month"]');
        if (thisMonthElement) {
            updateElementWithAnimation(thisMonthElement, stats.this_month_count.toLocaleString());
        }
    } catch (error) {
        console.error('Error updating statistics cards:', error);
    }
}

function updateElementWithAnimation(element, newValue) {
    if (element.textContent !== newValue.toString()) {
        element.style.transition = 'all 0.3s ease';
        element.style.transform = 'scale(1.05)';
        element.style.color = '#10B981'; // Green color for updates
        element.textContent = newValue;
        
        setTimeout(() => {
            element.style.transform = 'scale(1)';
            element.style.color = '';
        }, 300);
    }
}

function updateTableData(tableData) {
    try {
        const tbody = document.querySelector('table tbody');
        if (!tbody) return;
        
        // Store current scroll position
        const tableContainer = tbody.closest('.overflow-x-auto');
        const scrollTop = tableContainer ? tableContainer.scrollTop : 0;
        
        // Generate new table rows
        const newRows = generateTableRows(tableData);
        
        // Only update if content has changed
        if (tbody.innerHTML !== newRows) {
            tbody.innerHTML = newRows;
            
            // Restore scroll position
            if (tableContainer) {
                tableContainer.scrollTop = scrollTop;
            }
        }
    } catch (error) {
        console.error('Error updating table data:', error);
    }
}

function generateTableRows(tableData) {
    if (!tableData || tableData.length === 0) {
        return `
            <tr>
                <td colspan="7" class="px-4 py-3 text-center text-gray-500">
                    No bulk messages sent yet
                </td>
            </tr>
        `;
    }
    
    return tableData.map(message => {
        const statusColors = {
            'pending': 'gray',
            'processing': 'blue',
            'completed': 'green',
            'failed': 'red'
        };
        const color = statusColors[message.status] || 'gray';
        
        return `
            <tr class="hover:bg-gray-50" data-message-id="${message.id}">
                <td class="px-4 py-3">${message.template_name}</td>
                <td class="px-4 py-3">${message.total_recipients}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-${color}-100 text-${color}-800">
                        ${message.status.charAt(0).toUpperCase() + message.status.slice(1)}
                    </span>
                </td>
                <td class="px-4 py-3">
                    ${message.sent_count}/${message.failed_count}
                </td>
                <td class="px-4 py-3">
                    ${message.scheduled_at}
                </td>
                <td class="px-4 py-3">${message.created_at}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center space-x-2">
                        <button 
                            onclick="viewDetails(${message.id})"
                            class="text-blue-500 hover:text-blue-700 p-1 rounded" 
                            title="View Details">
                            <i class="fas fa-eye"></i>
                        </button>
                        ${message.can_send ? `
                            <button 
                                onclick="sendMessage(${message.id})"
                                class="text-green-500 hover:text-green-700 p-1 rounded" 
                                title="Send Now">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                            <button 
                                onclick="cancelMessage(${message.id})"
                                class="text-red-500 hover:text-red-700 p-1 rounded" 
                                title="Cancel">
                                <i class="fas fa-times"></i>
                            </button>
                        ` : ''}
                        ${message.can_duplicate ? `
                            <button 
                                onclick="duplicateMessage(${message.id})"
                                class="text-purple-500 hover:text-purple-700 p-1 rounded" 
                                title="Duplicate">
                                <i class="fas fa-copy"></i>
                            </button>
                        ` : ''}
                        <button 
                            onclick="exportMessage(${message.id})"
                            class="text-gray-500 hover:text-gray-700 p-1 rounded" 
                            title="Export">
                            <i class="fas fa-download"></i>
                        </button>
                        <button 
                            onclick="deleteMessage(${message.id})"
                            class="text-red-500 hover:text-red-700 p-1 rounded" 
                            title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

// Add real-time update controls to the console
function toggleRealTimeUpdates() {
    if (realTimeUpdateInterval) {
        stopRealTimeUpdates();
        document.getElementById('realTimeStatus').textContent = 'OFF';
        document.getElementById('realTimeStatus').className = 'text-red-600';
    } else {
        startRealTimeUpdates();
        document.getElementById('realTimeStatus').textContent = 'ON';
        document.getElementById('realTimeStatus').className = 'text-green-600';
    }
}

// Basic fetch test function
function testBasicFetch() {
    addToConsole('🧪 Testing basic fetch functionality...', 'info');
    
    fetch('/admin/whatsapp/bulk-messages/status-check')
        .then(response => {
            addToConsole('✅ Basic fetch successful! Status: ' + response.status, 'success');
            return response.json();
        })
        .then(data => {
            addToConsole('✅ Response received: ' + JSON.stringify(data).substring(0, 100) + '...', 'success');
        })
        .catch(error => {
            addToConsole('❌ Basic fetch failed: ' + error.message, 'error');
        });
}

// Handle page visibility changes to optimize performance
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        stopRealTimeUpdates();
        addToConsole('⏸️ Real-time updates paused (page hidden)', 'info');
    } else {
        startRealTimeUpdates();
        addToConsole('▶️ Real-time updates resumed (page visible)', 'info');
    }
});

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    stopRealTimeUpdates();
    stopConsoleRefresh();
});
</script>

<style>
/* Enhanced animations for real-time updates */
.animate-pulse-green {
    animation: pulse-green 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse-green {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: .5;
        background-color: #10B981;
        color: white;
    }
}

.table-row-updated {
    background-color: #F0FDF4 !important;
    transition: background-color 0.5s ease;
}

.stat-card-updated {
    box-shadow: 0 0 10px rgba(16, 185, 129, 0.3);
    transition: box-shadow 0.3s ease;
}
</style>

@endsection
@endverbatim

@endsection
