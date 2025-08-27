@extends('admin.master')


@section('title', 'WhatsApp Templates & Campaign Linking')

@section('content')
<div class="container px-6 mx-auto grid">
    <div class="flex justify-between items-center mb-6 mt-4">
        <h2 class="text-2xl font-semibold text-gray-700">WhatsApp Templates & Campaign Linking</h2>
        <div class="flex gap-3">
            <button 
                onclick="refreshTemplates()"
                class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors duration-300 flex items-center">
                <i class="fas fa-sync mr-2"></i> Refresh Templates
            </button>
            <button 
                onclick="openLinkModal()"
                class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition-colors duration-300 flex items-center">
                <i class="fas fa-link mr-2"></i> Link Template
            </button>
        </div>
    </div>

    <!-- Statistics Row -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Approved Templates</p>
                    <p class="text-2xl font-semibold text-gray-900" id="approvedCount">{{ count($approvedTemplates) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-bullhorn text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Campaigns</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ count($campaigns) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-link text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Links</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ count($templateCampaigns) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-magic text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Dynamic Templates</p>
                    <p class="text-2xl font-semibold text-gray-900" id="dynamicCount">{{ count(array_filter($approvedTemplates, function($t) { return $t['has_dynamic_content'] ?? false; })) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button 
                    onclick="switchTab('approved-templates')"
                    class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm tab-button active"
                    id="approved-templates-tab">
                    <i class="fas fa-check-circle mr-2"></i>Approved Templates
                </button>
                <button 
                    onclick="switchTab('template-links')"
                    class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm tab-button"
                    id="template-links-tab">
                    <i class="fas fa-link mr-2"></i>Template-Campaign Links
                </button>
                <button 
                    onclick="switchTab('campaigns')"
                    class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm tab-button"
                    id="campaigns-tab">
                    <i class="fas fa-bullhorn mr-2"></i>Available Campaigns
                </button>
            </nav>
        </div>

        <div class="p-6">
            <!-- Approved Templates Tab -->
            <div id="approved-templates-content" class="tab-content">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @forelse($approvedTemplates as $template)
                    <div class="bg-white border rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 template-card" data-template-id="{{ $template['id'] }}">
                        <div class="bg-green-500 text-white px-4 py-3 rounded-t-lg flex justify-between items-center">
                            <div>
                                <h3 class="font-semibold">{{ $template['name'] }}</h3>
                                @if($template['has_dynamic_content'] ?? false)
                                <small><i class="fas fa-magic"></i> Dynamic</small>
                                @endif
                            </div>
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">{{ $template['status'] }}</span>
                        </div>
                        <div class="p-4">
                            <div class="mb-3 max-h-32 overflow-y-auto">
                                <pre class="text-sm text-gray-700 whitespace-pre-wrap">{{ $template['content'] ?? 'No content available' }}</pre>
                            </div>
                            
                            @if(!empty($template['dynamic_parameters']))
                            <div class="mb-3">
                                <h6 class="text-sm font-medium text-gray-600 mb-2">Dynamic Parameters:</h6>
                                <div class="flex flex-wrap gap-1">
                                    @foreach($template['dynamic_parameters'] as $param)
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">{{ '{' . $param['position'] . '}' }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <div class="text-xs text-gray-500 mb-3">
                                <div><strong>Category:</strong> {{ $template['category'] ?? 'N/A' }}</div>
                                <div><strong>Language:</strong> {{ $template['language'] ?? 'en' }}</div>
                            </div>
                        </div>
                        <div class="border-t bg-gray-50 px-4 py-3 rounded-b-lg">
                            <div class="flex gap-2">
                                <button onclick="linkTemplate('{{ $template['id'] }}', '{{ $template['name'] }}')" 
                                        class="flex-1 bg-blue-500 text-white px-3 py-2 rounded text-sm hover:bg-blue-600">
                                    <i class="fas fa-link mr-1"></i> Link to Campaign
                                </button>
                                <button onclick="sendTemplate('{{ $template['id'] }}', '{{ $template['name'] }}')" 
                                        class="bg-green-500 text-white px-3 py-2 rounded text-sm hover:bg-green-600">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                                <button onclick="previewTemplate('{{ $template['id'] }}')" 
                                        class="bg-gray-500 text-white px-3 py-2 rounded text-sm hover:bg-gray-600">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="viewTableData('{{ $template['id'] }}')" 
                                        class="bg-purple-500 text-white px-3 py-2 rounded text-sm hover:bg-purple-600">
                                    <i class="fas fa-database"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-3 text-center py-8">
                        <div class="text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-4"></i>
                            <p class="mb-4">No Approved Templates Found</p>
                            <p class="text-sm mb-4">Please create and approve templates in Meta Business Manager first.</p>
                            <button onclick="refreshTemplates()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                <i class="fas fa-sync mr-2"></i> Refresh Templates
                            </button>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Template-Campaign Links Tab -->
            <div id="template-links-content" class="tab-content hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Template</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campaign</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trigger Event</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Delay</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="templateLinksTable">
                            @forelse($templateCampaigns as $link)
                            <tr data-link-id="{{ $link->id }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $link->whatsappTemplate->name }}</div>
                                        @if($link->whatsappTemplate->parameters)
                                        <div class="text-sm text-gray-500">{{ count($link->whatsappTemplate->parameters) }} dynamic parameters</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $link->campaign->title }}</div>
                                        <div class="text-sm text-gray-500">Dr. {{ $link->campaign->doctor->name }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ ucfirst($link->trigger_event) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $link->delay_minutes }}m</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($link->is_active)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                    @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button onclick="testTemplateCampaign('{{ $link->id }}')" 
                                                class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-play"></i>
                                        </button>
                                        <button onclick="editLink('{{ $link->id }}')" 
                                                class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="unlinkTemplate('{{ $link->id }}')" 
                                                class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-unlink"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    <i class="fas fa-link text-2xl mb-2 block"></i>
                                    No template-campaign links found
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Available Campaigns Tab -->
            <div id="campaigns-content" class="tab-content hidden">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @forelse($campaigns as $campaign)
                    <div class="bg-white border rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                        <div class="bg-blue-500 text-white px-4 py-3 rounded-t-lg">
                            <h3 class="font-semibold">{{ $campaign->title }}</h3>
                        </div>
                        <div class="p-4">
                            <div class="text-sm text-gray-600 mb-2">
                                <div><strong>Doctor:</strong> {{ $campaign->doctor->name }}</div>
                                <div><strong>Category:</strong> {{ $campaign->category->name }}</div>
                                <div><strong>Type:</strong> {{ ucfirst($campaign->type) }}</div>
                            </div>
                            <p class="text-sm text-gray-500">{{ Str::limit($campaign->description, 100) }}</p>
                        </div>
                        <div class="border-t bg-gray-50 px-4 py-3 rounded-b-lg">
                            <button onclick="selectCampaignForLinking('{{ $campaign->id }}', '{{ $campaign->title }}')" 
                                    class="w-full bg-blue-500 text-white px-3 py-2 rounded text-sm hover:bg-blue-600">
                                <i class="fas fa-plus mr-1"></i> Link Template
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-3 text-center py-8">
                        <div class="text-gray-500">
                            <i class="fas fa-bullhorn text-4xl mb-4"></i>
                            <p>No Active Campaigns Found</p>
                            <p class="text-sm">Create some campaigns first to link them with templates.</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Link Template Modal -->
<div id="linkModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-5xl max-h-screen overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-700">Link Template to Database Tables</h3>
                    <button onclick="closeLinkModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="linkTemplateForm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Template</label>
                            <select name="template_id" id="templateSelect" required 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                <option value="">Select Template</option>
                                @foreach($approvedTemplates as $template)
                                <option value="{{ $template['id'] }}" 
                                        data-has-dynamic="{{ $template['has_dynamic_content'] ? 'true' : 'false' }}"
                                        data-parameters="{{ json_encode($template['dynamic_parameters'] ?? []) }}">
                                    {{ $template['name'] }}
                                    @if($template['has_dynamic_content'] ?? false) (Dynamic) @endif
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Message Priority</label>
                            <select name="priority" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                <option value="normal">Normal</option>
                                <option value="high">High Priority</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                    </div>

                    <!-- Database Tables Selection -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-database text-blue-500"></i> 
                            Link to Database Tables (Select one or more)
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 p-4 border rounded-lg bg-gray-50">
                            <div class="flex items-center">
                                <input type="checkbox" name="linked_tables[]" value="campaigns" id="table_campaigns_link" 
                                       class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                <label for="table_campaigns_link" class="ml-2 text-sm font-medium text-gray-700 cursor-pointer">
                                    <i class="fas fa-bullhorn text-primary"></i> Campaigns
                                    <div class="text-xs text-gray-500">{{ \App\Models\Campaign::count() }} records</div>
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="linked_tables[]" value="doctors" id="table_doctors_link" 
                                       class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                <label for="table_doctors_link" class="ml-2 text-sm font-medium text-gray-700 cursor-pointer">
                                    <i class="fas fa-user-md text-success"></i> Doctors
                                    <div class="text-xs text-gray-500">{{ \App\Models\Doctor::count() }} records</div>
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="linked_tables[]" value="patients" id="table_patients_link" 
                                       class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                <label for="table_patients_link" class="ml-2 text-sm font-medium text-gray-700 cursor-pointer">
                                    <i class="fas fa-users text-info"></i> Patients
                                    <div class="text-xs text-gray-500">{{ DB::table('patient_registrations')->count() }} records</div>
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="linked_tables[]" value="categories" id="table_categories_link" 
                                       class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                <label for="table_categories_link" class="ml-2 text-sm font-medium text-gray-700 cursor-pointer">
                                    <i class="fas fa-tags text-warning"></i> Categories
                                    <div class="text-xs text-gray-500">{{ \App\Models\Category::count() }} records</div>
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="linked_tables[]" value="payments" id="table_payments_link" 
                                       class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                <label for="table_payments_link" class="ml-2 text-sm font-medium text-gray-700 cursor-pointer">
                                    <i class="fas fa-credit-card text-success"></i> Payments
                                    <div class="text-xs text-gray-500">{{ DB::table('patient_payments')->count() }} records</div>
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="linked_tables[]" value="bookings" id="table_bookings_link" 
                                       class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                <label for="table_bookings_link" class="ml-2 text-sm font-medium text-gray-700 cursor-pointer">
                                    <i class="fas fa-calendar text-primary"></i> Bookings
                                    <div class="text-xs text-gray-500">{{ \App\Models\Booking::count() }} records</div>
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="linked_tables[]" value="messages" id="table_messages_link" 
                                       class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                <label for="table_messages_link" class="ml-2 text-sm font-medium text-gray-700 cursor-pointer">
                                    <i class="fas fa-comments text-info"></i> Messages
                                    <div class="text-xs text-gray-500">{{ \App\Models\WhatsappMessage::count() }} records</div>
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="linked_tables[]" value="all" id="table_all_link" 
                                       class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                <label for="table_all_link" class="ml-2 text-sm font-medium text-gray-700 cursor-pointer">
                                    <i class="fas fa-globe text-danger"></i> All Tables
                                    <div class="text-xs text-gray-500">Complete Database</div>
                                </label>
                            </div>
                        </div>
                        <div class="mt-2 flex gap-2">
                            <button type="button" onclick="selectAllTables()" class="text-xs bg-blue-500 text-white px-3 py-1 rounded">
                                <i class="fas fa-check-double"></i> Select All
                            </button>
                            <button type="button" onclick="clearAllTables()" class="text-xs bg-gray-500 text-white px-3 py-1 rounded">
                                <i class="fas fa-times"></i> Clear All
                            </button>
                            <span id="selectedTablesCount" class="text-xs text-gray-600 px-3 py-1 bg-gray-100 rounded">
                                0 tables selected
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Trigger Event</label>
                            <select name="trigger_event" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                <option value="">Select Event</option>
                                <option value="data_insert">New Record Added</option>
                                <option value="data_update">Record Updated</option>
                                <option value="data_delete">Record Deleted</option>
                                <option value="registration">Patient Registration</option>
                                <option value="reminder">Appointment Reminder</option>
                                <option value="follow_up">Follow-up</option>
                                <option value="confirmation">Booking Confirmation</option>
                                <option value="cancellation">Cancellation Notice</option>
                                <option value="payment_received">Payment Received</option>
                                <option value="campaign_start">Campaign Started</option>
                                <option value="manual_trigger">Manual Trigger</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Delay (minutes)</label>
                            <input type="number" name="delay_minutes" value="0" min="0"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                            <p class="text-xs text-gray-500 mt-1">Time to wait before sending after trigger</p>
                        </div>
                    </div>

                    <!-- Table Data Mapping Section -->
                    <div id="tableMappingSection" class="hidden mb-4">
                        <h4 class="text-lg font-medium text-gray-700 mb-3">
                            <i class="fas fa-link"></i> Table Data Mapping
                        </h4>
                        <div id="tableMappingContainer" class="space-y-3"></div>
                    </div>

                    <!-- Dynamic Parameters Section -->
                    <div id="dynamicParamsSection" class="hidden mb-4">
                        <h4 class="text-lg font-medium text-gray-700 mb-3">Dynamic Parameters Mapping</h4>
                        <div id="dynamicParamsContainer" class="space-y-3"></div>
                    </div>

                    <!-- Live Preview Section -->
                    <div id="previewSection" class="hidden mb-4">
                        <h4 class="text-lg font-medium text-gray-700 mb-3">
                            <i class="fas fa-eye"></i> Live Preview
                        </h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="mb-2">
                                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">WhatsApp Template</span>
                                <span id="previewTableInfo" class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded ml-2"></span>
                            </div>
                            <pre id="previewContent" class="whitespace-pre-wrap text-sm text-gray-700"></pre>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="generateTablePreview()" 
                                class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                            <i class="fas fa-eye mr-1"></i> Preview with Data
                        </button>
                        <button type="button" onclick="testTableLinking()" 
                                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                            <i class="fas fa-play mr-1"></i> Test Send
                        </button>
                        <button type="button" onclick="closeLinkModal()" 
                                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="button" onclick="saveTableLink()" 
                                class="px-4 py-2 bg-purple-500 text-white rounded-md hover:bg-purple-600">
                            <i class="fas fa-save mr-1"></i> Save Table Link
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
// Tab Management
function switchTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(tab => {
        tab.classList.remove('active', 'border-purple-500', 'text-purple-600');
        tab.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById(`${tabName}-content`).classList.remove('hidden');
    
    // Add active class to selected tab
    const activeTab = document.getElementById(`${tabName}-tab`);
    activeTab.classList.add('active', 'border-purple-500', 'text-purple-600');
    activeTab.classList.remove('border-transparent', 'text-gray-500');
}

// Modal Management
function openLinkModal() {
    document.getElementById('linkModal').classList.remove('hidden');
}

function closeLinkModal() {
    document.getElementById('linkModal').classList.add('hidden');
    document.getElementById('linkTemplateForm').reset();
    hideDynamicParametersSection();
    document.getElementById('previewSection').classList.add('hidden');
}

// Template Selection Handler
function initializeTemplateSelectHandler() {
    const templateSelect = document.getElementById('templateSelect');
    if (templateSelect) {
        templateSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const hasDynamic = selectedOption.dataset.hasDynamic === 'true';
            const parameters = JSON.parse(selectedOption.dataset.parameters || '[]');
            
            if (hasDynamic && parameters.length > 0) {
                showDynamicParametersSection(parameters);
            } else {
                hideDynamicParametersSection();
            }
        });
    }
}

function showDynamicParametersSection(parameters) {
    const container = document.getElementById('dynamicParamsContainer');
    container.innerHTML = '';
    
    parameters.forEach((param) => {
        const paramDiv = document.createElement('div');
        paramDiv.innerHTML = `
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Parameter {${param.position}} (${param.component})
            </label>
            <select name="dynamic_params[${param.position}]" required
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50 param-select">
                <option value="">Select Data Source</option>
                <option value="campaign.title">Campaign Title</option>
                <option value="campaign.description">Campaign Description</option>
                <option value="doctor.name">Doctor Name</option>
                <option value="doctor.specialization">Doctor Specialization</option>
                <option value="doctor.phone">Doctor Phone</option>
                <option value="campaign.location">Campaign Location</option>
                <option value="campaign.price">Campaign Price</option>
                <option value="campaign.duration">Campaign Duration</option>
                <option value="patient.name">Patient Name</option>
                <option value="patient.phone">Patient Phone</option>
                <option value="booking.date">Booking Date</option>
                <option value="booking.time">Booking Time</option>
                <option value="custom">Custom Value</option>
            </select>
            <input type="text" class="w-full mt-2 rounded-md border-gray-300 shadow-sm custom-value hidden" placeholder="Enter custom value">
        `;
        
        const select = paramDiv.querySelector('.param-select');
        const customInput = paramDiv.querySelector('.custom-value');
        
        select.addEventListener('change', function() {
            if (this.value === 'custom') {
                customInput.classList.remove('hidden');
                customInput.required = true;
            } else {
                customInput.classList.add('hidden');
                customInput.required = false;
                customInput.value = '';
            }
        });
        
        container.appendChild(paramDiv);
    });
    
    document.getElementById('dynamicParamsSection').classList.remove('hidden');
}

function hideDynamicParametersSection() {
    document.getElementById('dynamicParamsSection').classList.add('hidden');
    document.getElementById('dynamicParamsContainer').innerHTML = '';
}

// Template Actions
function linkTemplate(templateId, templateName) {
    document.getElementById('templateSelect').value = templateId;
    document.getElementById('templateSelect').dispatchEvent(new Event('change'));
    openLinkModal();
}

function sendTemplate(templateId, templateName) {
    Swal.fire({
        title: 'Send Template Message',
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <label class="form-label">Template: <strong>${templateName}</strong></label>
                </div>
                <div class="mb-3">
                    <label class="form-label">Phone Number (with country code)</label>
                    <input type="tel" class="form-control" id="sendPhone" placeholder="+91 9876543210" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Parameters (JSON format, optional)</label>
                    <textarea class="form-control" id="sendParameters" rows="3" placeholder='["param1", "param2"]'></textarea>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Send Message',
        cancelButtonText: 'Cancel',
        width: 600,
        preConfirm: () => {
            const phone = document.getElementById('sendPhone').value;
            const parametersText = document.getElementById('sendParameters').value;
            
            if (!phone) {
                Swal.showValidationMessage('Phone number is required');
                return false;
            }
            
            let parameters = [];
            if (parametersText.trim()) {
                try {
                    parameters = JSON.parse(parametersText);
                } catch (e) {
                    Swal.showValidationMessage('Invalid JSON format for parameters');
                    return false;
                }
            }
            
            return { phone, parameters };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            sendTemplateMessage(templateName, result.value.phone, result.value.parameters);
        }
    });
}

function sendTemplateMessage(templateName, phone, parameters) {
    fetch('/admin/whatsapp/templates/send', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            template_name: templateName,
            phone: phone,
            parameters: parameters
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Success!', data.message, 'success');
        } else {
            Swal.fire('Error!', data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error!', 'Failed to send message', 'error');
    });
}

function viewTableData(templateId) {
    Swal.fire({
        title: 'Database Table Access',
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <label class="form-label">Select Tables (Multiple selection allowed)</label>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="campaigns" id="table_campaigns">
                                <label class="form-check-label" for="table_campaigns">
                                    <i class="fas fa-bullhorn text-primary"></i> Campaigns
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="doctors" id="table_doctors">
                                <label class="form-check-label" for="table_doctors">
                                    <i class="fas fa-user-md text-success"></i> Doctors
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="categories" id="table_categories">
                                <label class="form-check-label" for="table_categories">
                                    <i class="fas fa-tags text-warning"></i> Categories
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="patients" id="table_patients">
                                <label class="form-check-label" for="table_patients">
                                    <i class="fas fa-users text-info"></i> Patients
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="payments" id="table_payments">
                                <label class="form-check-label" for="table_payments">
                                    <i class="fas fa-credit-card text-success"></i> Payments
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="bookings" id="table_bookings">
                                <label class="form-check-label" for="table_bookings">
                                    <i class="fas fa-calendar text-primary"></i> Bookings
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="messages" id="table_messages">
                                <label class="form-check-label" for="table_messages">
                                    <i class="fas fa-comments text-info"></i> WhatsApp Messages
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <button class="btn btn-primary btn-sm" onclick="loadMultipleTables()">
                        <i class="fas fa-download"></i> Load Selected Tables
                    </button>
                    <button class="btn btn-success btn-sm ml-2" onclick="selectAllTables()">
                        <i class="fas fa-check-double"></i> Select All
                    </button>
                    <button class="btn btn-secondary btn-sm ml-2" onclick="clearTableSelection()">
                        <i class="fas fa-times"></i> Clear All
                    </button>
                </div>
                <div id="multiTableDataContainer" class="mt-3" style="max-height: 500px; overflow-y: auto;">
                    <p class="text-muted">Select tables and click "Load Selected Tables" to view data</p>
                </div>
            </div>
        `,
        width: '90%',
        showCancelButton: true,
        confirmButtonText: 'Close',
        showConfirmButton: true,
        didOpen: () => {
            // Add event listeners for checkboxes
            document.querySelectorAll('[id^="table_"]').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateLoadButton();
                });
            });
        }
    });
}

function selectAllTables() {
    document.querySelectorAll('[id^="table_"]').forEach(checkbox => {
        checkbox.checked = true;
    });
    updateLoadButton();
}

function clearTableSelection() {
    document.querySelectorAll('[id^="table_"]').forEach(checkbox => {
        checkbox.checked = false;
    });
    updateLoadButton();
    document.getElementById('multiTableDataContainer').innerHTML = '<p class="text-muted">Select tables and click "Load Selected Tables" to view data</p>';
}

function updateLoadButton() {
    const selectedTables = getSelectedTables();
    const loadButton = document.querySelector('button[onclick="loadMultipleTables()"]');
    if (loadButton) {
        if (selectedTables.length > 0) {
            loadButton.disabled = false;
            loadButton.innerHTML = `<i class="fas fa-download"></i> Load Selected Tables (${selectedTables.length})`;
        } else {
            loadButton.disabled = true;
            loadButton.innerHTML = '<i class="fas fa-download"></i> Load Selected Tables';
        }
    }
}

function getSelectedTables() {
    const selectedTables = [];
    document.querySelectorAll('[id^="table_"]:checked').forEach(checkbox => {
        selectedTables.push(checkbox.value);
    });
    return selectedTables;
}

function loadMultipleTables() {
    const selectedTables = getSelectedTables();
    
    if (selectedTables.length === 0) {
        alert('Please select at least one table');
        return;
    }
    
    const container = document.getElementById('multiTableDataContainer');
    container.innerHTML = '<p class="text-primary"><i class="fas fa-spinner fa-spin"></i> Loading multiple tables...</p>';
    
    // Load all selected tables simultaneously
    Promise.all(selectedTables.map(tableName => loadSingleTableData(tableName)))
        .then(results => {
            displayMultipleTablesData(results, selectedTables);
        })
        .catch(error => {
            console.error('Error loading tables:', error);
            container.innerHTML = '<p class="text-danger">Error loading tables: ' + error.message + '</p>';
        });
}

function loadSingleTableData(tableName) {
    return fetch('/admin/whatsapp/templates/table-data', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ table: tableName })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            return { tableName, data: data.data };
        } else {
            throw new Error(data.message);
        }
    });
}

function displayMultipleTablesData(results, tableNames) {
    const container = document.getElementById('multiTableDataContainer');
    
    if (!results || results.length === 0) {
        container.innerHTML = '<p class="text-muted">No data found</p>';
        return;
    }
    
    let html = `<div class="mb-3">
        <h5 class="text-success">
            <i class="fas fa-database"></i> Multiple Tables Data (${results.length} tables loaded)
        </h5>
        <div class="btn-group mb-3" role="group">`;
    
    // Add tab buttons for each table
    results.forEach((result, index) => {
        const isActive = index === 0 ? 'active' : '';
        html += `<button type="button" class="btn btn-outline-primary btn-sm table-tab ${isActive}" 
                        onclick="showTableTab('${result.tableName}')" 
                        data-table="${result.tableName}">
                    ${getTableIcon(result.tableName)} ${result.tableName.charAt(0).toUpperCase() + result.tableName.slice(1)} 
                    (${result.data.length})
                 </button>`;
    });
    
    html += `</div>
        <div class="btn-group mb-3" role="group">
            <button type="button" class="btn btn-info btn-sm" onclick="showAllTablesData()">
                <i class="fas fa-th-list"></i> Show All Tables
            </button>
            <button type="button" class="btn btn-warning btn-sm" onclick="exportTablesData()">
                <i class="fas fa-download"></i> Export Data
            </button>
            <button type="button" class="btn btn-success btn-sm" onclick="searchAcrossTables()">
                <i class="fas fa-search"></i> Search Across Tables
            </button>
        </div>
    </div>`;
    
    // Add individual table content containers
    results.forEach((result, index) => {
        const isActive = index === 0 ? '' : 'style="display: none;"';
        html += `<div id="table-content-${result.tableName}" class="table-content" ${isActive}>
            ${generateTableHTML(result.data, result.tableName)}
        </div>`;
    });
    
    // Add combined view container
    html += `<div id="table-content-all" class="table-content" style="display: none;">
        ${generateCombinedTableHTML(results)}
    </div>`;
    
    container.innerHTML = html;
    
    // Store results globally for other functions
    window.currentTableResults = results;
}

function getTableIcon(tableName) {
    const icons = {
        'campaigns': '<i class="fas fa-bullhorn text-primary"></i>',
        'doctors': '<i class="fas fa-user-md text-success"></i>',
        'categories': '<i class="fas fa-tags text-warning"></i>',
        'patients': '<i class="fas fa-users text-info"></i>',
        'payments': '<i class="fas fa-credit-card text-success"></i>',
        'bookings': '<i class="fas fa-calendar text-primary"></i>',
        'messages': '<i class="fas fa-comments text-info"></i>'
    };
    return icons[tableName] || '<i class="fas fa-table"></i>';
}

function showTableTab(tableName) {
    // Hide all table contents
    document.querySelectorAll('.table-content').forEach(content => {
        content.style.display = 'none';
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.table-tab').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Show selected table content
    const selectedContent = document.getElementById(`table-content-${tableName}`);
    if (selectedContent) {
        selectedContent.style.display = 'block';
    }
    
    // Add active class to selected tab
    const selectedTab = document.querySelector(`[data-table="${tableName}"]`);
    if (selectedTab) {
        selectedTab.classList.add('active');
    }
}

function showAllTablesData() {
    // Hide individual table contents
    document.querySelectorAll('.table-content').forEach(content => {
        content.style.display = 'none';
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.table-tab').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Show combined content
    const allContent = document.getElementById('table-content-all');
    if (allContent) {
        allContent.style.display = 'block';
    }
}

function generateTableHTML(data, tableName) {
    if (!data || data.length === 0) {
        return `<p class="text-muted">No data found in ${tableName} table</p>`;
    }
    
    const recordCount = data.length;
    const displayCount = Math.min(20, recordCount);
    
    let html = `<div class="mb-2">
        <h6 class="text-primary">${getTableIcon(tableName)} ${tableName.charAt(0).toUpperCase() + tableName.slice(1)} Table 
            <span class="badge badge-primary">${recordCount} records</span>
        </h6>
    </div>`;
    
    html += '<div class="table-responsive">';
    html += '<table class="table table-sm table-striped table-hover">';
    
    // Table header
    const firstRecord = data[0];
    html += '<thead class="thead-dark"><tr>';
    Object.keys(firstRecord).forEach(key => {
        if (!key.includes('password') && !key.includes('token') && key !== 'updated_at') {
            html += `<th style="min-width: 120px;">${key.replace('_', ' ').toUpperCase()}</th>`;
        }
    });
    html += '</tr></thead>';
    
    // Table body
    html += '<tbody>';
    data.slice(0, displayCount).forEach((record, index) => {
        html += `<tr class="${index % 2 === 0 ? 'table-light' : ''}">`;
        Object.keys(firstRecord).forEach(key => {
            if (!key.includes('password') && !key.includes('token') && key !== 'updated_at') {
                let value = record[key];
                if (typeof value === 'object' && value !== null) {
                    value = JSON.stringify(value);
                }
                if (value && value.length > 50) {
                    value = value.substring(0, 50) + '...';
                }
                html += `<td>${value || '-'}</td>`;
            }
        });
        html += '</tr>';
    });
    html += '</tbody>';
    html += '</table>';
    
    if (recordCount > displayCount) {
        html += `<p class="text-muted small">Showing first ${displayCount} records out of ${recordCount}</p>`;
    }
    
    html += '</div>';
    return html;
}

function generateCombinedTableHTML(results) {
    let html = '<div class="mb-3"><h6 class="text-info"><i class="fas fa-th-list"></i> All Tables Combined View</h6></div>';
    
    results.forEach(result => {
        html += `<div class="mb-4 p-3 border rounded">
            <h6 class="text-success">${getTableIcon(result.tableName)} ${result.tableName.charAt(0).toUpperCase() + result.tableName.slice(1)} 
                <span class="badge badge-success">${result.data.length} records</span>
            </h6>
            ${generateTableHTML(result.data, result.tableName)}
        </div>`;
    });
    
    return html;
}

function searchAcrossTables() {
    const searchTerm = prompt('Enter search term to search across all loaded tables:');
    if (!searchTerm) return;
    
    const results = window.currentTableResults || [];
    let searchResults = [];
    
    results.forEach(result => {
        const filteredData = result.data.filter(record => {
            return Object.values(record).some(value => {
                return String(value).toLowerCase().includes(searchTerm.toLowerCase());
            });
        });
        
        if (filteredData.length > 0) {
            searchResults.push({
                tableName: result.tableName,
                data: filteredData
            });
        }
    });
    
    if (searchResults.length > 0) {
        displayMultipleTablesData(searchResults, searchResults.map(r => r.tableName));
    } else {
        alert(`No results found for "${searchTerm}"`);
    }
}

function exportTablesData() {
    const results = window.currentTableResults || [];
    let csvContent = '';
    
    results.forEach(result => {
        csvContent += `\n\n=== ${result.tableName.toUpperCase()} TABLE ===\n`;
        
        if (result.data.length > 0) {
            // Headers
            const headers = Object.keys(result.data[0]).filter(key => 
                !key.includes('password') && !key.includes('token')
            );
            csvContent += headers.join(',') + '\n';
            
            // Data rows
            result.data.forEach(record => {
                const values = headers.map(header => {
                    let value = record[header];
                    if (typeof value === 'object' && value !== null) {
                        value = JSON.stringify(value);
                    }
                    return `"${String(value || '').replace(/"/g, '""')}"`;
                });
                csvContent += values.join(',') + '\n';
            });
        }
    });
    
    // Download CSV
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `whatsapp_tables_data_${new Date().toISOString().slice(0, 10)}.csv`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
    
    alert('Tables data exported successfully!');
}

function loadTableData(tableName) {
    const container = document.getElementById('tableDataContainer');
    container.innerHTML = '<p class="text-muted">Loading...</p>';
    
    fetch('/admin/whatsapp/templates/table-data', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ table: tableName })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayTableData(data.data, tableName);
        } else {
            container.innerHTML = '<p class="text-danger">Error loading data: ' + data.message + '</p>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        container.innerHTML = '<p class="text-danger">Failed to load table data</p>';
    });
}

function displayTableData(data, tableName) {
    const container = document.getElementById('tableDataContainer');
    
    if (!data || data.length === 0) {
        container.innerHTML = '<p class="text-muted">No data found in ' + tableName + ' table</p>';
        return;
    }
    
    let html = '<h6 class="mb-3">Data from ' + tableName + ' table (' + data.length + ' records)</h6>';
    html += '<div class="table-responsive">';
    html += '<table class="table table-sm table-striped">';
    
    // Table header
    const firstRecord = data[0];
    html += '<thead class="table-dark"><tr>';
    Object.keys(firstRecord).forEach(key => {
        if (!key.includes('_at') && !key.includes('password') && !key.includes('token')) {
            html += '<th>' + key + '</th>';
        }
    });
    html += '</tr></thead>';
    
    // Table body
    html += '<tbody>';
    data.slice(0, 10).forEach(record => { // Show first 10 records
        html += '<tr>';
        Object.keys(firstRecord).forEach(key => {
            if (!key.includes('_at') && !key.includes('password') && !key.includes('token')) {
                let value = record[key];
                if (typeof value === 'object' && value !== null) {
                    value = JSON.stringify(value);
                }
                html += '<td>' + (value || '-') + '</td>';
            }
        });
        html += '</tr>';
    });
    html += '</tbody>';
    html += '</table>';
    
    if (data.length > 10) {
        html += '<p class="text-muted small">Showing first 10 records out of ' + data.length + '</p>';
    }
    
    html += '</div>';
    container.innerHTML = html;
}

// Table Selection Functions
function selectAllTables() {
    document.querySelectorAll('[name="linked_tables[]"]').forEach(checkbox => {
        if (checkbox.value !== 'all') {
            checkbox.checked = true;
        }
    });
    updateSelectedTablesCount();
    showTableMappingSection();
}

function clearAllTables() {
    document.querySelectorAll('[name="linked_tables[]"]').forEach(checkbox => {
        checkbox.checked = false;
    });
    updateSelectedTablesCount();
    hideTableMappingSection();
}

function updateSelectedTablesCount() {
    const selectedTables = getSelectedTables();
    const countElement = document.getElementById('selectedTablesCount');
    
    if (selectedTables.includes('all')) {
        countElement.textContent = 'All tables selected';
        countElement.className = 'text-xs text-white px-3 py-1 bg-purple-500 rounded';
    } else if (selectedTables.length > 0) {
        countElement.textContent = `${selectedTables.length} tables selected`;
        countElement.className = 'text-xs text-white px-3 py-1 bg-blue-500 rounded';
    } else {
        countElement.textContent = '0 tables selected';
        countElement.className = 'text-xs text-gray-600 px-3 py-1 bg-gray-100 rounded';
    }
}

function getSelectedTables() {
    const selectedTables = [];
    document.querySelectorAll('[name="linked_tables[]"]:checked').forEach(checkbox => {
        selectedTables.push(checkbox.value);
    });
    return selectedTables;
}

function showTableMappingSection() {
    const selectedTables = getSelectedTables();
    if (selectedTables.length > 0) {
        document.getElementById('tableMappingSection').classList.remove('hidden');
        generateTableMappingUI(selectedTables);
    }
}

function hideTableMappingSection() {
    document.getElementById('tableMappingSection').classList.add('hidden');
}

function generateTableMappingUI(selectedTables) {
    const container = document.getElementById('tableMappingContainer');
    container.innerHTML = '';
    
    selectedTables.forEach(tableName => {
        if (tableName === 'all') return;
        
        const mappingDiv = document.createElement('div');
        mappingDiv.className = 'border rounded-lg p-4 bg-gray-50';
        mappingDiv.innerHTML = `
            <h5 class="font-medium text-gray-700 mb-3">
                ${getTableIcon(tableName)} ${tableName.charAt(0).toUpperCase() + tableName.slice(1)} Table Mapping
            </h5>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                <div>
                    <label class="text-xs font-medium text-gray-600">Field Mapping</label>
                    <select name="table_fields[${tableName}]" class="w-full text-sm border rounded">
                        <option value="all">All Fields</option>
                        <option value="id,name,email">ID, Name, Email</option>
                        <option value="custom">Custom Selection</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-600">Row Limit</label>
                    <input type="number" name="row_limits[${tableName}]" value="100" min="1" max="1000" 
                           class="w-full text-sm border rounded">
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-600">Sort Order</label>
                    <select name="sort_orders[${tableName}]" class="w-full text-sm border rounded">
                        <option value="created_at_desc">Newest First</option>
                        <option value="created_at_asc">Oldest First</option>
                        <option value="id_desc">ID Descending</option>
                        <option value="name_asc">Name A-Z</option>
                    </select>
                </div>
            </div>
            <div class="mt-3">
                <label class="text-xs font-medium text-gray-600">Filter Conditions (Optional)</label>
                <input type="text" name="filters[${tableName}]" placeholder="e.g., status=active, created_at>2024-01-01" 
                       class="w-full text-sm border rounded">
            </div>
        `;
        
        container.appendChild(mappingDiv);
    });
}

function generateTablePreview() {
    const templateId = document.getElementById('templateSelect').value;
    const selectedTables = getSelectedTables();
    
    if (!templateId) {
        alert('Please select a template first');
        return;
    }
    
    if (selectedTables.length === 0) {
        alert('Please select at least one table');
        return;
    }
    
    const previewContainer = document.getElementById('previewContent');
    const tableInfoContainer = document.getElementById('previewTableInfo');
    
    previewContainer.textContent = 'Loading preview with live data...';
    tableInfoContainer.textContent = `Linked to: ${selectedTables.join(', ')}`;
    document.getElementById('previewSection').classList.remove('hidden');
    
    // Fetch template and table data for preview
    fetch('/admin/whatsapp/templates/table-preview', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            template_id: templateId,
            linked_tables: selectedTables,
            row_limits: getFormData('row_limits'),
            filters: getFormData('filters')
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            previewContainer.textContent = data.preview;
            tableInfoContainer.textContent = `${data.records_count} records from ${selectedTables.length} tables`;
        } else {
            previewContainer.textContent = 'Error: ' + data.message;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        previewContainer.textContent = 'Error generating preview';
    });
}

function getFormData(fieldPrefix) {
    const data = {};
    document.querySelectorAll(`[name^="${fieldPrefix}["]`).forEach(input => {
        const match = input.name.match(new RegExp(`${fieldPrefix}\\[([^\\]]+)\\]`));
        if (match) {
            data[match[1]] = input.value;
        }
    });
    return data;
}

function testTableLinking() {
    const templateId = document.getElementById('templateSelect').value;
    const selectedTables = getSelectedTables();
    
    if (!templateId || selectedTables.length === 0) {
        alert('Please select template and tables first');
        return;
    }
    
    const phone = prompt('Enter phone number to test (with country code):');
    if (!phone) return;
    
    // Send test message with table data
    fetch('/admin/whatsapp/templates/test-table-link', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            template_id: templateId,
            phone: phone,
            linked_tables: selectedTables,
            row_limits: getFormData('row_limits'),
            filters: getFormData('filters')
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Test message sent successfully!');
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error sending test message');
    });
}

function saveTableLink() {
    const templateId = document.getElementById('templateSelect').value;
    const selectedTables = getSelectedTables();
    const triggerEvent = document.querySelector('[name="trigger_event"]').value;
    
    if (!templateId || selectedTables.length === 0 || !triggerEvent) {
        alert('Please fill in all required fields');
        return;
    }
    
    const linkData = {
        template_id: templateId,
        linked_tables: selectedTables,
        trigger_event: triggerEvent,
        delay_minutes: document.querySelector('[name="delay_minutes"]').value,
        priority: document.querySelector('[name="priority"]').value,
        table_fields: getFormData('table_fields'),
        row_limits: getFormData('row_limits'),
        sort_orders: getFormData('sort_orders'),
        filters: getFormData('filters')
    };
    
    fetch('/admin/whatsapp/templates/save-table-link', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(linkData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Template linked to tables successfully!');
            closeLinkModal();
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error saving table link');
    });
}

function generatePreview() {
    const templateId = document.getElementById('templateSelect').value;
    const campaignId = document.getElementById('campaignSelect').value;
    
    if (!templateId || !campaignId) {
        alert('Please select both template and campaign');
        return;
    }
    
    // Collect dynamic parameters
    const dynamicParams = {};
    document.querySelectorAll('[name^="dynamic_params"]').forEach(function(select) {
        const name = select.name;
        const match = name.match(/dynamic_params\[(\d+)\]/);
        if (match && select.value) {
            const paramPosition = match[1];
            let value = select.value;
            
            // If custom value, get the custom input
            if (value === 'custom') {
                const customInput = select.parentNode.querySelector('.custom-value');
                value = customInput.value;
            }
            
            dynamicParams[paramPosition] = value;
        }
    });
    
    fetch('/admin/whatsapp/template-campaign-preview', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            template_id: templateId,
            campaign_id: campaignId,
            dynamic_params: dynamicParams
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('previewContent').textContent = data.preview;
            document.getElementById('previewSection').classList.remove('hidden');
        } else {
            alert(data.message || 'Failed to generate preview');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error generating preview');
    });
}

function saveLinkTemplate() {
    const formData = new FormData(document.getElementById('linkTemplateForm'));
    
    // Handle custom values in dynamic parameters
    document.querySelectorAll('[name^="dynamic_params"]').forEach(function(select) {
        if (select.value === 'custom') {
            const customInput = select.parentNode.querySelector('.custom-value');
            formData.set(select.name, customInput.value);
        }
    });
    
    fetch('/admin/whatsapp/link-template-campaign', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Template linked to campaign successfully!');
            closeLinkModal();
            location.reload();
        } else {
            alert(data.message || 'Failed to link template');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error linking template to campaign');
    });
}

function unlinkTemplate(linkId) {
    if (!confirm('Are you sure you want to unlink this template from the campaign?')) {
        return;
    }
    
    fetch(`/admin/whatsapp/unlink-template-campaign/${linkId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Template unlinked successfully!');
            document.querySelector(`tr[data-link-id="${linkId}"]`).remove();
        } else {
            alert(data.message || 'Failed to unlink template');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error unlinking template');
    });
}

function testTemplateCampaign(linkId) {
    const phone = prompt('Enter phone number to test (with country code):');
    if (!phone) return;
    
    fetch('/admin/whatsapp/send-campaign-template', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            link_id: linkId,
            phone: phone
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Test message sent successfully!');
        } else {
            alert(data.message || 'Failed to send test message');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error sending test message');
    });
}

function refreshTemplates() {
    const btn = document.querySelector('button[onclick="refreshTemplates()"]');
    const originalHTML = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Refreshing...';
    btn.disabled = true;
    
    fetch('/admin/whatsapp/refresh-templates', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Templates refreshed successfully!');
            location.reload();
        } else {
            alert(data.message || 'Failed to refresh templates');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error refreshing templates');
    })
    .finally(() => {
        btn.innerHTML = originalHTML;
        btn.disabled = false;
    });
}

function previewTemplate(templateId) {
    // Find template in approved templates
    const templates = @json($approvedTemplates);
    const template = templates.find(t => t.id === templateId);
    
    if (template) {
        const modalContent = `
            <div class="fixed inset-0 bg-black bg-opacity-50 z-50" id="templatePreviewModal">
                <div class="flex items-center justify-center min-h-screen px-4">
                    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-screen overflow-y-auto">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-xl font-semibold text-gray-700">Template Preview: ${template.name}</h3>
                                <button onclick="closePreviewModal()" class="text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        ${template.status}
                                    </span>
                                    <span class="ml-2 text-sm text-gray-600">
                                        <strong>Category:</strong> ${template.category || 'N/A'}
                                    </span>
                                    <span class="ml-2 text-sm text-gray-600">
                                        <strong>Language:</strong> ${template.language || 'en'}
                                    </span>
                                </div>
                                
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-medium text-gray-700 mb-2">Template Content</h4>
                                    <pre class="whitespace-pre-wrap text-sm text-gray-700">${template.content || 'No content available'}</pre>
                                </div>
                                
                                ${template.dynamic_parameters && template.dynamic_parameters.length > 0 ? `
                                <div>
                                    <h4 class="font-medium text-gray-700 mb-2">Dynamic Parameters</h4>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="grid grid-cols-3 gap-2 text-sm">
                                            <div class="font-medium">Position</div>
                                            <div class="font-medium">Component</div>
                                            <div class="font-medium">Type</div>
                                            ${template.dynamic_parameters.map(param => `
                                                <div>{${param.position}}</div>
                                                <div>${param.component}</div>
                                                <div>${param.type}</div>
                                            `).join('')}
                                        </div>
                                    </div>
                                </div>
                                ` : ''}
                            </div>
                            
                            <div class="mt-6 flex justify-end">
                                <button onclick="closePreviewModal()" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalContent);
    }
}

function closePreviewModal() {
    const modal = document.getElementById('templatePreviewModal');
    if (modal) {
        modal.remove();
    }
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    console.log('WhatsApp Templates page loaded');
    
    // Set default active tab
    switchTab('approved-templates');
    
    // Initialize template select handler
    initializeTemplateSelectHandler();
    
    // Initialize table selection handlers
    initializeTableSelectionHandlers();
    
    // Verify functions are available
    console.log('Functions available:', {
        refreshTemplates: typeof refreshTemplates,
        openLinkModal: typeof openLinkModal,
        closeLinkModal: typeof closeLinkModal,
        linkTemplate: typeof linkTemplate,
        previewTemplate: typeof previewTemplate,
        saveTableLink: typeof saveTableLink
    });
});

function initializeTableSelectionHandlers() {
    // Add event listeners for table checkboxes
    document.querySelectorAll('[name="linked_tables[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            // Handle "All Tables" checkbox
            if (this.value === 'all' && this.checked) {
                document.querySelectorAll('[name="linked_tables[]"]').forEach(cb => {
                    if (cb.value !== 'all') cb.checked = false;
                });
            } else if (this.value !== 'all' && this.checked) {
                // Uncheck "All Tables" if individual table is selected
                document.getElementById('table_all_link').checked = false;
            }
            
            updateSelectedTablesCount();
            
            if (getSelectedTables().length > 0) {
                showTableMappingSection();
            } else {
                hideTableMappingSection();
            }
        });
    });
}

// Ensure functions are globally available
window.refreshTemplates = refreshTemplates;
window.openLinkModal = openLinkModal;
window.closeLinkModal = closeLinkModal;
window.linkTemplate = linkTemplate;
window.previewTemplate = previewTemplate;
window.switchTab = switchTab;
</script>
@endsection
