@extends('admin.master')

@section('title', 'Enhanced Visual Workflow Builder')

@push('styles')
<link href="{{ asset('css/visual-workflow-builder.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Enhanced UI Showcase Section -->
<div class="container-fluid mb-4" data-aos="fade-up">
    <div class="row g-4">
        <!-- Stats Cards with Amazing Animations -->
        <div class="col-xl-3 col-lg-6 col-md-6" data-aos="zoom-in" data-aos-delay="100">
            <div class="card border-0 shadow-epic h-100 hvr-float">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="p-3 rounded-3 bg-gradient-primary text-white shadow-strong">
                                <i class="fas fa-robot fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-4">
                            <div class="text-muted text-sm mb-1">Active Workflows</div>
                            <div class="h4 mb-0 fw-bold" data-counter="45">0</div>
                            <div class="text-success text-xs">
                                <i class="fas fa-arrow-up me-1"></i>12% from last month
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6" data-aos="zoom-in" data-aos-delay="200">
            <div class="card border-0 shadow-epic h-100 hvr-float">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="p-3 rounded-3 bg-gradient-success text-white shadow-strong">
                                <i class="fas fa-paper-plane fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-4">
                            <div class="text-muted text-sm mb-1">Messages Sent Today</div>
                            <div class="h4 mb-0 fw-bold" data-counter="1247">0</div>
                            <div class="text-success text-xs">
                                <i class="fas fa-arrow-up me-1"></i>8% increase
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6" data-aos="zoom-in" data-aos-delay="300">
            <div class="card border-0 shadow-epic h-100 hvr-float">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="p-3 rounded-3 bg-gradient-warning text-white shadow-strong">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-4">
                            <div class="text-muted text-sm mb-1">Active Conversations</div>
                            <div class="h4 mb-0 fw-bold" data-counter="892">0</div>
                            <div class="text-warning text-xs">
                                <i class="fas fa-minus me-1"></i>2% change
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6" data-aos="zoom-in" data-aos-delay="400">
            <div class="card border-0 shadow-epic h-100 hvr-float">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="p-3 rounded-3 bg-gradient-danger text-white shadow-strong">
                                <i class="fas fa-chart-line fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-4">
                            <div class="text-muted text-sm mb-1">Response Rate</div>
                            <div class="h4 mb-0 fw-bold" data-counter="87">0</div>
                            <div class="text-success text-xs">
                                <i class="fas fa-arrow-up me-1"></i>15% improvement
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Action Buttons -->
<div class="container-fluid mb-4" data-aos="fade-up" data-aos-delay="500">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-strong">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="card-title mb-0 d-flex align-items-center">
                        <i class="material-icons me-2">auto_awesome</i>
                        Enhanced Workflow Actions
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <button class="btn btn-primary w-100 hvr-bounce-to-right" onclick="showSuccess('Workflow saved successfully!', 'Great!')">
                                <i class="fas fa-save me-2"></i>
                                Save Workflow
                            </button>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <button class="btn btn-success w-100 hvr-bounce-to-right" onclick="showInfo('Testing workflow...', 'Testing')">
                                <i class="fas fa-play me-2"></i>
                                Test Workflow
                            </button>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <button class="btn btn-warning w-100 hvr-bounce-to-right" onclick="showWarning('This will export your workflow')">
                                <i class="fas fa-download me-2"></i>
                                Export
                            </button>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <button class="btn btn-info w-100 hvr-bounce-to-right" onclick="showInfo('Importing workflow...', 'Import')">
                                <i class="fas fa-upload me-2"></i>
                                Import
                            </button>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <button class="btn btn-secondary w-100 hvr-bounce-to-right" onclick="confirmAction('Are you sure you want to clear the workflow?', function(){ showSuccess('Workflow cleared!'); })">
                                <i class="fas fa-trash me-2"></i>
                                Clear
                            </button>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <button class="btn btn-dark w-100 hvr-bounce-to-right" onclick="window.open('/admin/whatsapp/automation', '_blank')">
                                <i class="fas fa-external-link-alt me-2"></i>
                                Full View
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="workflow-builder-container animate-fade-in">
    <!-- Header -->
    <div class="workflow-header">
        <div class="logo">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            WhatsApp Management
        </div>
        <div class="title">Visual Workflow Builder</div>
        <div class="actions">
            <button class="header-btn" onclick="saveWorkflow()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 16px; height: 16px; display: inline; margin-right: 4px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0120.25 6v12A2.25 2.25 0 0118 20.25H6A2.25 2.25 0 013.75 18V6A2.25 2.25 0 016 3.75h1.5m9 0h-9" />
                </svg>
                Save
            </button>
            <button class="header-btn" onclick="testWorkflow()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 16px; height: 16px; display: inline; margin-right: 4px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z" />
                </svg>
                Test
            </button>
            <button class="header-btn primary" onclick="publishWorkflow()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 16px; height: 16px; display: inline; margin-right: 4px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Publish
            </button>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="workflow-sidebar">
        <!-- Workflow Statistics -->
        <div class="sidebar-section">
            <h3>Workflow Overview</h3>
            <div class="workflow-stats">
                <div class="stat-card">
                    <span class="stat-number green">1,247</span>
                    <div class="stat-label">Flows Started</div>
                </div>
                <div class="stat-card">
                    <span class="stat-number blue">1,085</span>
                    <div class="stat-label">Completed</div>
                </div>
                <div class="stat-card">
                    <span class="stat-number orange">97.3%</span>
                    <div class="stat-label">Success Rate</div>
                </div>
            </div>
        </div>

        <!-- Workflow Components -->
        <div class="sidebar-section">
            <h3>Workflow Components</h3>
            
            <!-- Triggers -->
            <div class="component-group">
                <h4>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.58-5.84a14.927 14.927 0 01-2.58 5.84" />
                    </svg>
                    Triggers
                </h4>
                
                <div class="component-item" draggable="true" data-type="trigger" data-subtype="message_received">
                    <div class="icon trigger">📱</div>
                    <div>
                        <div class="component-title">WhatsApp Message Received</div>
                        <div class="component-desc">Triggers the workflow</div>
                    </div>
                </div>
                
                <div class="component-item" draggable="true" data-type="trigger" data-subtype="keyword_detected">
                    <div class="icon trigger">%</div>
                    <div>
                        <div class="component-title">Keyword Detection</div>
                        <div class="component-desc">System detects keywords</div>
                    </div>
                </div>
                
                <div class="component-item" draggable="true" data-type="trigger" data-subtype="time_trigger">
                    <div class="icon trigger">⏰</div>
                    <div>
                        <div class="component-title">Message Trigger</div>
                        <div class="component-desc">Start on WhatsApp message</div>
                    </div>
                </div>
            </div>
            
            <!-- Processing -->
            <div class="component-group">
                <h4>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Processing
                </h4>
                
                <div class="component-item" draggable="true" data-type="processing" data-subtype="ai_analysis">
                    <div class="icon processing">🤖</div>
                    <div>
                        <div class="component-title">AI Analysis</div>
                        <div class="component-desc">Intelligent message processing</div>
                    </div>
                </div>
                
                <div class="component-item" draggable="true" data-type="processing" data-subtype="decision_engine">
                    <div class="icon processing">🎯</div>
                    <div>
                        <div class="component-title">Decision Engine</div>
                        <div class="component-desc">Route based on conditions</div>
                    </div>
                </div>
                
                <div class="component-item" draggable="true" data-type="processing" data-subtype="data_lookup">
                    <div class="icon processing">📊</div>
                    <div>
                        <div class="component-title">Database Search</div>
                        <div class="component-desc">Query database records</div>
                    </div>
                </div>
            </div>
            
            <!-- Responses -->
            <div class="component-group">
                <h4>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                    </svg>
                    Responses
                </h4>
                
                <div class="component-item" draggable="true" data-type="response" data-subtype="template_message">
                    <div class="icon response">💬</div>
                    <div>
                        <div class="component-title">Template Message</div>
                        <div class="component-desc">Send pre-defined template</div>
                    </div>
                </div>
                
                <div class="component-item" draggable="true" data-type="response" data-subtype="custom_message">
                    <div class="icon response">✏️</div>
                    <div>
                        <div class="component-title">Custom Function</div>
                        <div class="component-desc">Execute custom logic</div>
                    </div>
                </div>
                
                <div class="component-item" draggable="true" data-type="response" data-subtype="data_visualization">
                    <div class="icon response">📈</div>
                    <div>
                        <div class="component-title">Data Visualization</div>
                        <div class="component-desc">Generate charts/graphs</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Workflow Scenario Preview -->
        <div class="sidebar-section">
            <h3>Workflow Scenario Preview</h3>
            <div style="font-size: 0.8rem; color: #64748b; margin-bottom: 1rem;">
                <div style="font-weight: 600; color: #374151; margin-bottom: 0.5rem;">User Message Received</div>
                <div style="margin-bottom: 0.75rem;">WhatsApp message triggers the workflow</div>
                
                <div style="font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Keyword Analysis</div>
                <div style="margin-bottom: 0.75rem;">System chooses the matching keywords</div>
                
                <div style="font-weight: 600; color: #374151; margin-bottom: 0.5rem;">AI Processing</div>
                <div style="margin-bottom: 0.75rem;">If no keywords match, AI analyses intent</div>
                
                <div style="font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Response Generated</div>
                <div>Appropriate template or custom message sent</div>
            </div>
        </div>
    </div>

    <!-- Main Canvas -->
    <div class="workflow-main">
        <div class="canvas-container">
            <div class="workflow-canvas" id="workflowCanvas">
                <div class="canvas-content">
                    <!-- Drop Zone -->
                    <div class="drop-zone" id="dropZone">
                        <div class="drop-zone-content">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <h4 style="margin: 0 0 0.5rem 0; font-weight: 600;">Drag components here to build your workflow</h4>
                                <p style="margin: 0; font-size: 0.875rem;">Connect components to create automation flow</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Properties Panel -->
    <div class="properties-panel" id="propertiesPanel">
        <div class="properties-header">
            <h3>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 16px; height: 16px; display: inline; margin-right: 8px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Properties
            </h3>
        </div>
        <div class="properties-content">
            <div id="noSelection" style="text-align: center; color: #64748b; padding: 2rem;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 48px; height: 48px; margin: 0 auto 1rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.042 21.672L13.684 16.6m0 0l-2.51 2.225.569-9.47 5.227 7.917-3.286-.672zM12 2.25V4.5m5.834.166l-1.591 1.591M20.25 10.5H18M7.757 14.743l-1.59 1.59M6 10.5H3.75m4.007-4.243l-1.59-1.59" />
                </svg>
                <p>Select a component to view properties</p>
            </div>
            
            <div id="componentProperties" style="display: none;">
                <!-- Dynamic properties will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Workflow Settings Modal -->
<div class="modal fade" id="workflowSettingsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Workflow Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="property-group">
                    <label>Workflow Name</label>
                    <input type="text" class="property-input" id="workflowName" placeholder="Enter workflow name">
                </div>
                
                <div class="property-group">
                    <label>Description</label>
                    <textarea class="property-input" id="workflowDescription" rows="3" placeholder="Describe this workflow"></textarea>
                </div>
                
                <div class="property-group">
                    <label>Priority</label>
                    <select class="property-select" id="workflowPriority">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                
                <div class="property-toggle">
                    <label>Active Workflow</label>
                    <div class="toggle-switch active" id="workflowActiveToggle"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveWorkflowSettings()">Save Changes</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/modular/sortable.min.js"></script>
<script>
// Visual Workflow Builder JavaScript
class VisualWorkflowBuilder {
    constructor() {
        this.canvas = document.getElementById('workflowCanvas');
        this.dropZone = document.getElementById('dropZone');
        this.propertiesPanel = document.getElementById('propertiesPanel');
        this.selectedNode = null;
        this.workflowNodes = [];
        this.connections = [];
        this.nodeCounter = 0;
        
        this.init();
    }
    
    init() {
        this.setupDragAndDrop();
        this.setupCanvasEvents();
        this.setupPropertyPanel();
        this.loadWorkflow();
    }
    
    setupDragAndDrop() {
        // Make component items draggable
        const componentItems = document.querySelectorAll('.component-item');
        componentItems.forEach(item => {
            item.addEventListener('dragstart', (e) => {
                e.dataTransfer.setData('text/plain', JSON.stringify({
                    type: item.dataset.type,
                    subtype: item.dataset.subtype,
                    title: item.querySelector('.component-title').textContent,
                    description: item.querySelector('.component-desc').textContent
                }));
            });
        });
        
        // Setup drop zone
        this.dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            this.dropZone.classList.add('active');
        });
        
        this.dropZone.addEventListener('dragleave', (e) => {
            if (!this.dropZone.contains(e.relatedTarget)) {
                this.dropZone.classList.remove('active');
            }
        });
        
        this.dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            this.dropZone.classList.remove('active');
            
            const componentData = JSON.parse(e.dataTransfer.getData('text/plain'));
            const rect = this.canvas.getBoundingClientRect();
            const x = e.clientX - rect.left - 100; // Offset for center
            const y = e.clientY - rect.top - 50;
            
            this.createWorkflowNode(componentData, x, y);
        });
    }
    
    setupCanvasEvents() {
        this.canvas.addEventListener('click', (e) => {
            if (e.target === this.canvas || e.target.classList.contains('canvas-content')) {
                this.deselectNode();
            }
        });
    }
    
    setupPropertyPanel() {
        // Toggle switches
        document.querySelectorAll('.toggle-switch').forEach(toggle => {
            toggle.addEventListener('click', () => {
                toggle.classList.toggle('active');
            });
        });
    }
    
    createWorkflowNode(componentData, x, y) {
        const nodeId = `node_${++this.nodeCounter}`;
        
        const node = document.createElement('div');
        node.className = `workflow-node ${componentData.type} animate-fade-in-up`;
        node.id = nodeId;
        node.style.left = `${x}px`;
        node.style.top = `${y}px`;
        
        node.innerHTML = `
            <div class="connection-point input" data-node="${nodeId}" data-type="input"></div>
            <div class="connection-point output" data-node="${nodeId}" data-type="output"></div>
            <div class="node-header">
                <div class="node-icon ${componentData.type}">
                    ${this.getNodeIcon(componentData.type)}
                </div>
                <div class="node-title">${componentData.title}</div>
            </div>
            <div class="node-description">${componentData.description}</div>
        `;
        
        // Hide drop zone if this is the first node
        if (this.workflowNodes.length === 0) {
            this.dropZone.style.display = 'none';
        }
        
        // Add to canvas
        this.canvas.querySelector('.canvas-content').appendChild(node);
        
        // Store node data
        this.workflowNodes.push({
            id: nodeId,
            type: componentData.type,
            subtype: componentData.subtype,
            title: componentData.title,
            x: x,
            y: y,
            properties: this.getDefaultProperties(componentData.type, componentData.subtype)
        });
        
        // Setup node events
        this.setupNodeEvents(node);
        
        // Select the new node
        this.selectNode(node);
    }
    
    setupNodeEvents(node) {
        // Node selection
        node.addEventListener('click', (e) => {
            e.stopPropagation();
            this.selectNode(node);
        });
        
        // Node dragging
        let isDragging = false;
        let startX, startY, nodeX, nodeY;
        
        node.addEventListener('mousedown', (e) => {
            if (e.target.classList.contains('connection-point')) return;
            
            isDragging = true;
            startX = e.clientX;
            startY = e.clientY;
            nodeX = parseInt(node.style.left);
            nodeY = parseInt(node.style.top);
            
            node.style.cursor = 'grabbing';
            e.preventDefault();
        });
        
        document.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            
            const dx = e.clientX - startX;
            const dy = e.clientY - startY;
            
            node.style.left = `${nodeX + dx}px`;
            node.style.top = `${nodeY + dy}px`;
            
            // Update node data
            const nodeData = this.workflowNodes.find(n => n.id === node.id);
            if (nodeData) {
                nodeData.x = nodeX + dx;
                nodeData.y = nodeY + dy;
            }
        });
        
        document.addEventListener('mouseup', () => {
            if (isDragging) {
                isDragging = false;
                node.style.cursor = 'move';
            }
        });
        
        // Connection points
        const connectionPoints = node.querySelectorAll('.connection-point');
        connectionPoints.forEach(point => {
            point.addEventListener('click', (e) => {
                e.stopPropagation();
                this.handleConnectionPoint(point);
            });
        });
    }
    
    selectNode(node) {
        // Remove previous selection
        this.deselectNode();
        
        // Select new node
        node.classList.add('selected');
        this.selectedNode = node;
        
        // Show properties
        this.showNodeProperties(node);
    }
    
    deselectNode() {
        if (this.selectedNode) {
            this.selectedNode.classList.remove('selected');
            this.selectedNode = null;
        }
        
        // Hide properties
        document.getElementById('noSelection').style.display = 'block';
        document.getElementById('componentProperties').style.display = 'none';
    }
    
    showNodeProperties(node) {
        const nodeData = this.workflowNodes.find(n => n.id === node.id);
        if (!nodeData) return;
        
        document.getElementById('noSelection').style.display = 'none';
        const propertiesDiv = document.getElementById('componentProperties');
        propertiesDiv.style.display = 'block';
        
        propertiesDiv.innerHTML = this.generatePropertyForm(nodeData);
    }
    
    generatePropertyForm(nodeData) {
        let html = `<h4 style="margin-bottom: 1rem; color: #1e293b;">${nodeData.title}</h4>`;
        
        switch (nodeData.type) {
            case 'trigger':
                html += this.generateTriggerProperties(nodeData);
                break;
            case 'processing':
                html += this.generateProcessingProperties(nodeData);
                break;
            case 'response':
                html += this.generateResponseProperties(nodeData);
                break;
        }
        
        return html;
    }
    
    generateTriggerProperties(nodeData) {
        return `
            <div class="property-group">
                <label>Trigger Name</label>
                <input type="text" class="property-input" value="${nodeData.title}" onchange="updateNodeProperty('${nodeData.id}', 'title', this.value)">
            </div>
            <div class="property-group">
                <label>Keywords (comma separated)</label>
                <input type="text" class="property-input" placeholder="help, support, question" onchange="updateNodeProperty('${nodeData.id}', 'keywords', this.value)">
            </div>
            <div class="property-toggle">
                <label>Case Sensitive</label>
                <div class="toggle-switch" onclick="toggleProperty('${nodeData.id}', 'caseSensitive')"></div>
            </div>
        `;
    }
    
    generateProcessingProperties(nodeData) {
        return `
            <div class="property-group">
                <label>Processing Name</label>
                <input type="text" class="property-input" value="${nodeData.title}" onchange="updateNodeProperty('${nodeData.id}', 'title', this.value)">
            </div>
            <div class="property-group">
                <label>AI Model</label>
                <select class="property-select" onchange="updateNodeProperty('${nodeData.id}', 'aiModel', this.value)">
                    <option value="gpt-3.5">GPT-3.5 Turbo</option>
                    <option value="gpt-4">GPT-4</option>
                    <option value="claude">Claude</option>
                </select>
            </div>
            <div class="property-group">
                <label>Processing Instructions</label>
                <textarea class="property-input" rows="3" placeholder="Instructions for processing..." onchange="updateNodeProperty('${nodeData.id}', 'instructions', this.value)"></textarea>
            </div>
        `;
    }
    
    generateResponseProperties(nodeData) {
        return `
            <div class="property-group">
                <label>Response Name</label>
                <input type="text" class="property-input" value="${nodeData.title}" onchange="updateNodeProperty('${nodeData.id}', 'title', this.value)">
            </div>
            <div class="property-group">
                <label>Message Template</label>
                <textarea class="property-input" rows="4" placeholder="Enter your response message..." onchange="updateNodeProperty('${nodeData.id}', 'template', this.value)"></textarea>
            </div>
            <div class="property-toggle">
                <label>Use AI Enhancement</label>
                <div class="toggle-switch" onclick="toggleProperty('${nodeData.id}', 'aiEnhanced')"></div>
            </div>
        `;
    }
    
    getNodeIcon(type) {
        const icons = {
            trigger: '⚡',
            processing: '⚙️',
            response: '💬'
        };
        return icons[type] || '📋';
    }
    
    getDefaultProperties(type, subtype) {
        return {
            title: '',
            type: type,
            subtype: subtype,
            enabled: true
        };
    }
    
    handleConnectionPoint(point) {
        // Connection logic will be implemented here
        console.log('Connection point clicked:', point.dataset);
    }
    
    loadWorkflow() {
        // Load existing workflow if any
        console.log('Loading workflow...');
    }
    
    saveWorkflow() {
        const workflowData = {
            nodes: this.workflowNodes,
            connections: this.connections,
            metadata: {
                name: document.getElementById('workflowName')?.value || 'Untitled Workflow',
                description: document.getElementById('workflowDescription')?.value || '',
                priority: document.getElementById('workflowPriority')?.value || 'medium',
                active: document.getElementById('workflowActiveToggle')?.classList.contains('active') || false
            }
        };
        
        console.log('Saving workflow:', workflowData);
        // Send to server via AJAX
    }
}

// Global functions for property updates
function updateNodeProperty(nodeId, property, value) {
    const builder = window.workflowBuilder;
    const nodeData = builder.workflowNodes.find(n => n.id === nodeId);
    if (nodeData) {
        if (property === 'title') {
            nodeData.title = value;
            // Update visual title
            const nodeElement = document.getElementById(nodeId);
            const titleElement = nodeElement.querySelector('.node-title');
            if (titleElement) titleElement.textContent = value;
        } else {
            nodeData.properties[property] = value;
        }
    }
}

function toggleProperty(nodeId, property) {
    const builder = window.workflowBuilder;
    const nodeData = builder.workflowNodes.find(n => n.id === nodeId);
    if (nodeData) {
        nodeData.properties[property] = !nodeData.properties[property];
    }
}

// Header button functions
function saveWorkflow() {
    window.workflowBuilder.saveWorkflow();
    // Show success message
    Swal.fire({
        icon: 'success',
        title: 'Workflow Saved',
        text: 'Your workflow has been saved successfully!',
        timer: 2000,
        showConfirmButton: false
    });
}

function testWorkflow() {
    console.log('Testing workflow...');
    // Implement test functionality
    Swal.fire({
        icon: 'info',
        title: 'Testing Workflow',
        text: 'Workflow test functionality coming soon!',
        timer: 2000,
        showConfirmButton: false
    });
}

function publishWorkflow() {
    console.log('Publishing workflow...');
    // Implement publish functionality
    Swal.fire({
        icon: 'success',
        title: 'Workflow Published',
        text: 'Your workflow is now active and ready to use!',
        timer: 2000,
        showConfirmButton: false
    });
}

function saveWorkflowSettings() {
    console.log('Saving workflow settings...');
    // Close modal
    bootstrap.Modal.getInstance(document.getElementById('workflowSettingsModal')).hide();
}

// Initialize the workflow builder when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.workflowBuilder = new VisualWorkflowBuilder();
});
</script>
@endpush
