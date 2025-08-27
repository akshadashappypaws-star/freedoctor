@extends('admin.master')

@section('title', 'Visual Workflow Builder')

@push('styles')
<link href="{{ asset('css/visual-workflow-builder.css') }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="workflow-builder-container">
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
            <h3>Workflow Statistics</h3>
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
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.58-5.84a14.927 14.927 0 01-2.58 5.84M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.58-5.84a14.927 14.927 0 01-2.58 5.84" />
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
                    <div class="icon trigger">🔍</div>
                    <div>
                        <div class="component-title">Keyword Detection</div>
                        <div class="component-desc">System detects keywords</div>
                    </div>
                </div>
                
                <div class="component-item" draggable="true" data-type="trigger" data-subtype="time_trigger">
                    <div class="icon trigger">⏰</div>
                    <div>
                        <div class="component-title">Time Trigger</div>
                        <div class="component-desc">Schedule based trigger</div>
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
                        <div class="component-title">Custom Message</div>
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
            <h3>User Message Received</h3>
            <div style="font-size: 0.8rem; color: #64748b; margin-bottom: 0.5rem;">WhatsApp message triggers the workflow</div>
            
            <div style="margin: 1rem 0;">
                <div style="font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Keyword Analysis</div>
                <div style="font-size: 0.8rem; color: #64748b;">System chooses the matching keywords</div>
            </div>
            
            <div style="margin: 1rem 0;">
                <div style="font-weight: 600; color: #374151; margin-bottom: 0.5rem;">AI Processing</div>
                <div style="font-size: 0.8rem; color: #64748b;">AI analyses intent</div>
            </div>
            
            <div style="margin: 1rem 0;">
                <div style="font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Response Generated</div>
                <div style="font-size: 0.8rem; color: #64748b;">Appropriate template or custom message generated</div>
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
        height: fit-content;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .workflow-node {
        background: white;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 1rem;
        margin: 1rem;
        min-width: 200px;
        position: relative;
        cursor: move;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .workflow-node:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        border-color: #667eea;
    }

    .workflow-node.dragging {
        opacity: 0.7;
        transform: rotate(5deg);
    }

    .node-header {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .node-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.75rem;
        color: white;
        font-size: 1rem;
    }

    .node-trigger { background: linear-gradient(135deg, #28a745, #20c997); }
    .node-ai { background: linear-gradient(135deg, #007bff, #6610f2); }
    .node-function { background: linear-gradient(135deg, #fd7e14, #e83e8c); }
    .node-template { background: linear-gradient(135deg, #ffc107, #fd7e14); }
    .node-datatable { background: linear-gradient(135deg, #17a2b8, #007bff); }
    .node-visualization { background: linear-gradient(135deg, #6f42c1, #e83e8c); }

    .node-title {
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
        font-size: 0.9rem;
    }

    .node-description {
        font-size: 0.8rem;
        color: #6c757d;
        margin: 0.5rem 0;
        line-height: 1.4;
    }

    .node-config {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        background: #f8f9fa;
        border-radius: 6px;
        color: #495057;
    }

    .node-connector {
        position: absolute;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #667eea;
        border: 2px solid white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .connector-input {
        top: 50%;
        left: -6px;
        transform: translateY(-50%);
    }

    .connector-output {
        top: 50%;
        right: -6px;
        transform: translateY(-50%);
    }

    .toolbox-item {
        background: white;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 0.75rem;
        margin-bottom: 0.75rem;
        cursor: grab;
        transition: all 0.3s ease;
        text-align: center;
    }

    .toolbox-item:hover {
        transform: translateX(5px);
        border-color: #667eea;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
    }

    .toolbox-item:active {
        cursor: grabbing;
    }

    .toolbox-icon {
        width: 50px;
        height: 50px;
        margin: 0 auto 0.5rem;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }

    .workflow-connection {
        position: absolute;
        pointer-events: none;
        z-index: 1;
    }

    .connection-line {
        stroke: #667eea;
        stroke-width: 3;
        fill: none;
        stroke-dasharray: 5,5;
        animation: dash 1s linear infinite;
    }

    @keyframes dash {
        to { stroke-dashoffset: -10; }
    }

    .scenario-preview {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 15px;
        padding: 1.5rem;
        color: white;
        margin-bottom: 2rem;
    }

    .scenario-step {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(5px);
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .scenario-step:last-child {
        margin-bottom: 0;
    }

    .step-number {
        background: rgba(255, 255, 255, 0.2);
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .action-buttons {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
    }

    .floating-btn {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        border: none;
        color: white;
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
    }

    .floating-btn:hover {
        transform: scale(1.1);
    }

    .workflow-properties {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 20px;
    }

    .zoom-controls {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 25px;
        padding: 0.5rem;
        display: flex;
        gap: 0.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .zoom-btn {
        width: 40px;
        height: 40px;
        border: none;
        border-radius: 50%;
        background: #667eea;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .zoom-btn:hover {
        background: #5a6fd8;
        transform: scale(1.1);
    }

    .mini-map {
        position: absolute;
        bottom: 20px;
        left: 20px;
        width: 200px;
        height: 150px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 10px;
        border: 2px solid #e9ecef;
        padding: 0.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .drop-zone {
        border: 2px dashed #667eea;
        border-radius: 10px;
        padding: 2rem;
        text-align: center;
        color: #667eea;
        background: rgba(102, 126, 234, 0.05);
        margin: 1rem 0;
        transition: all 0.3s ease;
    }

    .drop-zone.drag-over {
        background: rgba(102, 126, 234, 0.1);
        border-color: #5a6fd8;
        transform: scale(1.02);
    }

    /* Workflow Roadmap Styles */
    .workflow-roadmap {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .roadmap-container {
        position: relative;
        padding: 2rem 0;
    }

    .roadmap-flow {
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .roadmap-node {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        text-align: center;
        position: relative;
        min-width: 150px;
        border: 3px solid #e9ecef;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .roadmap-node:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }

    .roadmap-node.start-node {
        border-color: #28a745;
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }

    .roadmap-node.processing-node {
        border-color: #ffc107;
        background: linear-gradient(135deg, #ffc107, #fd7e14);
        color: white;
    }

    .roadmap-node.ai-node {
        border-color: #007bff;
        background: linear-gradient(135deg, #007bff, #6610f2);
        color: white;
    }

    .roadmap-node.decision-node {
        border-color: #6f42c1;
        background: linear-gradient(135deg, #6f42c1, #e83e8c);
        color: white;
    }

    .roadmap-node.response-node {
        border-color: #17a2b8;
        background: linear-gradient(135deg, #17a2b8, #007bff);
        color: white;
    }

    .node-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.5rem;
    }

    .node-content h6 {
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 1rem;
    }

    .node-content span {
        font-size: 0.85rem;
        opacity: 0.9;
    }

    .roadmap-connection {
        flex: 1;
        height: 3px;
        background: linear-gradient(to right, #667eea, #764ba2);
        position: relative;
        margin: 0 1rem;
        border-radius: 2px;
    }

    .roadmap-connection::after {
        content: '';
        position: absolute;
        right: -8px;
        top: 50%;
        transform: translateY(-50%);
        width: 0;
        height: 0;
        border-left: 8px solid #764ba2;
        border-top: 6px solid transparent;
        border-bottom: 6px solid transparent;
    }

    .roadmap-connection.diagonal {
        transform: rotate(15deg);
        width: 80px;
    }

    .roadmap-branch {
        position: relative;
        width: 50px;
        height: 100px;
    }

    .branch-connection {
        position: absolute;
        width: 3px;
        height: 50px;
        background: linear-gradient(to bottom, #667eea, #764ba2);
        left: 50%;
        transform: translateX(-50%);
    }

    .branch-connection.top {
        top: 0;
        transform: translateX(-50%) rotate(-30deg);
        transform-origin: bottom;
    }

    .branch-connection.bottom {
        bottom: 0;
        transform: translateX(-50%) rotate(30deg);
        transform-origin: top;
    }

    .flow-stats {
        border-top: 1px solid #e9ecef;
        padding-top: 1.5rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .stat-card i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .stat-number {
        font-size: 1.8rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.9rem;
        color: #6c757d;
        font-weight: 500;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .roadmap-flow {
            flex-direction: column;
            align-items: center;
        }
        
        .roadmap-connection {
            width: 3px;
            height: 50px;
            margin: 1rem 0;
            transform: rotate(90deg);
        }
        
        .roadmap-connection::after {
            right: -6px;
            top: 50%;
            border-left: 6px solid #764ba2;
            border-top: 8px solid transparent;
            border-bottom: 8px solid transparent;
            transform: translateY(-50%) rotate(90deg);
        }
        
        .roadmap-branch {
            width: 100px;
            height: 50px;
        }
    }
</style>
@endpush

@section('content')
<div class="workflow-builder">
    <div class="container-fluid">
        <!-- Header -->
        <div class="builder-header animate__animated animate__fadeInDown">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="mb-2">
                        <i class="fas fa-project-diagram text-primary me-2"></i>
                        Visual Workflow Builder
                    </h2>
                    <p class="text-muted mb-0">
                        Create automation workflows with drag-and-drop interface
                    </p>
                </div>
                <div class="col-lg-4 text-end">
                    <div class="btn-group">
                        <button class="btn btn-outline-primary" onclick="saveWorkflow()">
                            <i class="fas fa-save me-1"></i> Save
                        </button>
                        <button class="btn btn-outline-success" onclick="testWorkflow()">
                            <i class="fas fa-play me-1"></i> Test
                        </button>
                        <button class="btn btn-primary" onclick="publishWorkflow()">
                            <i class="fas fa-rocket me-1"></i> Publish
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Workflow Roadmap Visualization -->
        <div class="workflow-roadmap animate__animated animate__fadeInUp mb-4">
            <h4 class="mb-3">
                <i class="fas fa-route me-2"></i>
                Workflow Roadmap
            </h4>
            <div class="roadmap-container">
                <div class="roadmap-flow">
                    <!-- Start Node -->
                    <div class="roadmap-node start-node" data-step="1">
                        <div class="node-icon">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div class="node-content">
                            <h6>WhatsApp</h6>
                            <span>Message Received</span>
                        </div>
                    </div>

                    <!-- Connection Line -->
                    <div class="roadmap-connection"></div>

                    <!-- Processing Node -->
                    <div class="roadmap-node processing-node" data-step="2">
                        <div class="node-icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <div class="node-content">
                            <h6>Router</h6>
                            <span>Keyword Detection</span>
                        </div>
                    </div>

                    <!-- Branch Connections -->
                    <div class="roadmap-branch">
                        <div class="branch-connection top"></div>
                        <div class="branch-connection bottom"></div>
                    </div>

                    <!-- AI Processing Node -->
                    <div class="roadmap-node ai-node" data-step="3">
                        <div class="node-icon">
                            <i class="fas fa-brain"></i>
                        </div>
                        <div class="node-content">
                            <h6>AI ChatGPT</h6>
                            <span>Intent Analysis</span>
                        </div>
                    </div>

                    <!-- Connection to Decision -->
                    <div class="roadmap-connection diagonal"></div>

                    <!-- Decision Node -->
                    <div class="roadmap-node decision-node" data-step="4">
                        <div class="node-icon">
                            <i class="fas fa-project-diagram"></i>
                        </div>
                        <div class="node-content">
                            <h6>Stack</h6>
                            <span>Decision Engine</span>
                        </div>
                    </div>

                    <!-- Final Connection -->
                    <div class="roadmap-connection"></div>

                    <!-- Response Node -->
                    <div class="roadmap-node response-node" data-step="5">
                        <div class="node-icon">
                            <i class="fas fa-reply"></i>
                        </div>
                        <div class="node-content">
                            <h6>SpeechBot</h6>
                            <span>Response Generation</span>
                        </div>
                    </div>
                </div>

                <!-- Flow Statistics -->
                <div class="flow-stats mt-4">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="stat-card">
                                <i class="fas fa-play-circle text-success"></i>
                                <div class="stat-number">1,247</div>
                                <div class="stat-label">Flows Started</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <i class="fas fa-check-circle text-primary"></i>
                                <div class="stat-number">1,089</div>
                                <div class="stat-label">Completed</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <i class="fas fa-clock text-warning"></i>
                                <div class="stat-number">0.8s</div>
                                <div class="stat-label">Avg Response</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <i class="fas fa-percentage text-info"></i>
                                <div class="stat-number">87.3%</div>
                                <div class="stat-label">Success Rate</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scenario Preview -->
        <div class="scenario-preview animate__animated animate__fadeInUp">
            <h4 class="mb-3">
                <i class="fas fa-eye me-2"></i>
                Workflow Scenario Preview
            </h4>
            <div id="scenario-steps">
                <div class="scenario-step d-flex align-items-center">
                    <div class="step-number">1</div>
                    <div>
                        <strong>User Message Received</strong>
                        <div class="small mt-1 opacity-75">WhatsApp message triggers the workflow</div>
                    </div>
                </div>
                <div class="scenario-step d-flex align-items-center">
                    <div class="step-number">2</div>
                    <div>
                        <strong>Keyword Analysis</strong>
                        <div class="small mt-1 opacity-75">System checks for matching keywords</div>
                    </div>
                </div>
                <div class="scenario-step d-flex align-items-center">
                    <div class="step-number">3</div>
                    <div>
                        <strong>AI Processing</strong>
                        <div class="small mt-1 opacity-75">If no keywords match, AI analyzes intent</div>
                    </div>
                </div>
                <div class="scenario-step d-flex align-items-center">
                    <div class="step-number">4</div>
                    <div>
                        <strong>Response Generated</strong>
                        <div class="small mt-1 opacity-75">Appropriate template or custom message sent</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Toolbox -->
            <div class="col-lg-3">
                <div class="toolbox animate__animated animate__fadeInLeft">
                    <h5 class="mb-3">
                        <i class="fas fa-toolbox text-primary me-2"></i>
                        Workflow Components
                    </h5>
                    
                    <div class="toolbox-section mb-4">
                        <h6 class="text-muted mb-2">Triggers</h6>
                        <div class="toolbox-item" draggable="true" data-type="trigger" data-subtype="message">
                            <div class="toolbox-icon node-trigger">
                                <i class="fab fa-whatsapp"></i>
                            </div>
                            <div class="small font-weight-bold">Message Trigger</div>
                            <div class="text-muted small">Start on WhatsApp message</div>
                        </div>
                        <div class="toolbox-item" draggable="true" data-type="trigger" data-subtype="keyword">
                            <div class="toolbox-icon node-trigger">
                                <i class="fas fa-key"></i>
                            </div>
                            <div class="small font-weight-bold">Keyword Trigger</div>
                            <div class="text-muted small">Match specific keywords</div>
                        </div>
                    </div>

                    <div class="toolbox-section mb-4">
                        <h6 class="text-muted mb-2">Processing</h6>
                        <div class="toolbox-item" draggable="true" data-type="ai" data-subtype="analysis">
                            <div class="toolbox-icon node-ai">
                                <i class="fas fa-brain"></i>
                            </div>
                            <div class="small font-weight-bold">AI Analysis</div>
                            <div class="text-muted small">Intelligent message processing</div>
                        </div>
                        <div class="toolbox-item" draggable="true" data-type="function" data-subtype="custom">
                            <div class="toolbox-icon node-function">
                                <i class="fas fa-code"></i>
                            </div>
                            <div class="small font-weight-bold">Custom Function</div>
                            <div class="text-muted small">Execute custom logic</div>
                        </div>
                        <div class="toolbox-item" draggable="true" data-type="datatable" data-subtype="search">
                            <div class="toolbox-icon node-datatable">
                                <i class="fas fa-database"></i>
                            </div>
                            <div class="small font-weight-bold">Database Search</div>
                            <div class="text-muted small">Query database records</div>
                        </div>
                    </div>

                    <div class="toolbox-section">
                        <h6 class="text-muted mb-2">Responses</h6>
                        <div class="toolbox-item" draggable="true" data-type="template" data-subtype="message">
                            <div class="toolbox-icon node-template">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="small font-weight-bold">Template Message</div>
                            <div class="text-muted small">Send pre-defined template</div>
                        </div>
                        <div class="toolbox-item" draggable="true" data-type="visualization" data-subtype="chart">
                            <div class="toolbox-icon node-visualization">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div class="small font-weight-bold">Data Visualization</div>
                            <div class="text-muted small">Generate charts/graphs</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Workflow Canvas -->
            <div class="col-lg-6">
                <div class="workflow-canvas animate__animated animate__fadeInUp" id="workflow-canvas">
                    <div class="workflow-grid"></div>
                    
                    <!-- Zoom Controls -->
                    <div class="zoom-controls">
                        <button class="zoom-btn" onclick="zoomIn()">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button class="zoom-btn" onclick="zoomOut()">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button class="zoom-btn" onclick="resetZoom()">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>

                    <!-- Drop Zone -->
                    <div class="drop-zone" id="canvas-drop-zone">
                        <i class="fas fa-mouse-pointer fa-3x mb-3 opacity-50"></i>
                        <h5>Drag components here to build your workflow</h5>
                        <p class="text-muted">Connect components to create automation flow</p>
                    </div>

                    <!-- Mini Map -->
                    <div class="mini-map">
                        <div class="small text-muted mb-1">Workflow Overview</div>
                        <div id="mini-map-content" class="h-100 bg-light rounded"></div>
                    </div>
                </div>
            </div>

            <!-- Properties Panel -->
            <div class="col-lg-3">
                <div class="workflow-properties animate__animated animate__fadeInRight">
                    <h5 class="mb-3">
                        <i class="fas fa-cogs text-primary me-2"></i>
                        Properties
                    </h5>
                    
                    <div id="properties-content">
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-hand-pointer fa-2x mb-2 opacity-50"></i>
                            <p>Select a component to view properties</p>
                        </div>
                    </div>
                </div>

                <!-- Workflow Settings -->
                <div class="workflow-properties mt-3">
                    <h6 class="mb-3">
                        <i class="fas fa-sliders-h text-info me-2"></i>
                        Workflow Settings
                    </h6>
                    
                    <div class="form-group mb-3">
                        <label class="form-label small">Workflow Name</label>
                        <input type="text" class="form-control form-control-sm" id="workflow-name" placeholder="Enter workflow name">
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label small">Description</label>
                        <textarea class="form-control form-control-sm" id="workflow-description" rows="2" placeholder="Describe this workflow"></textarea>
                    </div>
                    
                    <div class="form-group mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="workflow-active" checked>
                            <label class="form-check-label small" for="workflow-active">
                                Active Workflow
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label small">Priority</label>
                        <select class="form-select form-select-sm" id="workflow-priority">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Floating Action Buttons -->
<div class="action-buttons">
    <button class="floating-btn btn-success" onclick="addConnection()" title="Add Connection">
        <i class="fas fa-link"></i>
    </button>
    <button class="floating-btn btn-warning" onclick="clearCanvas()" title="Clear Canvas">
        <i class="fas fa-trash"></i>
    </button>
    <button class="floating-btn btn-info" onclick="previewWorkflow()" title="Preview Workflow">
        <i class="fas fa-eye"></i>
    </button>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jsplumb/2.15.5/js/jsplumb.min.js"></script>
<script>
    let workflowData = {
        nodes: [],
        connections: [],
        settings: {}
    };
    
    let selectedNode = null;
    let nodeCounter = 0;
    let currentZoom = 1;
    
    // Initialize when page loads
    $(document).ready(function() {
        initializeDragAndDrop();
        initializeCanvas();
        loadSampleWorkflow();
    });
    
    function initializeDragAndDrop() {
        // Make toolbox items draggable
        $('.toolbox-item').each(function() {
            this.addEventListener('dragstart', function(e) {
                e.dataTransfer.setData('text/plain', JSON.stringify({
                    type: this.dataset.type,
                    subtype: this.dataset.subtype,
                    title: this.querySelector('.font-weight-bold').textContent
                }));
                this.style.opacity = '0.5';
            });
            
            this.addEventListener('dragend', function(e) {
                this.style.opacity = '1';
            });
        });
        
        // Make canvas accept drops
        const canvas = document.getElementById('workflow-canvas');
        const dropZone = document.getElementById('canvas-drop-zone');
        
        [canvas, dropZone].forEach(element => {
            element.addEventListener('dragover', function(e) {
                e.preventDefault();
                dropZone.classList.add('drag-over');
            });
            
            element.addEventListener('dragleave', function(e) {
                if (!canvas.contains(e.relatedTarget)) {
                    dropZone.classList.remove('drag-over');
                }
            });
            
            element.addEventListener('drop', function(e) {
                e.preventDefault();
                dropZone.classList.remove('drag-over');
                
                try {
                    const data = JSON.parse(e.dataTransfer.getData('text/plain'));
                    const rect = canvas.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    
                    createWorkflowNode(data, x, y);
                } catch (error) {
                    console.error('Drop failed:', error);
                }
            });
        });
    }
    
    function initializeCanvas() {
        // Hide drop zone when nodes are present
        updateDropZoneVisibility();
    }
    
    function createWorkflowNode(data, x, y) {
        nodeCounter++;
        const nodeId = `node-${nodeCounter}`;
        
        const nodeElement = document.createElement('div');
        nodeElement.className = `workflow-node animate__animated animate__bounceIn`;
        nodeElement.id = nodeId;
        nodeElement.style.position = 'absolute';
        nodeElement.style.left = `${x - 100}px`;
        nodeElement.style.top = `${y - 50}px`;
        nodeElement.setAttribute('data-type', data.type);
        nodeElement.setAttribute('data-subtype', data.subtype);
        
        const iconClass = getNodeIconClass(data.type);
        const description = getNodeDescription(data.type, data.subtype);
        
        nodeElement.innerHTML = `
            <div class="node-header">
                <div class="node-icon node-${data.type}">
                    <i class="${iconClass}"></i>
                </div>
                <div>
                    <h6 class="node-title">${data.title}</h6>
                </div>
            </div>
            <div class="node-description">${description}</div>
            <div class="node-config">Click to configure</div>
            <div class="node-connector connector-input"></div>
            <div class="node-connector connector-output"></div>
            <button class="btn btn-sm btn-outline-danger position-absolute" style="top: 5px; right: 5px; padding: 0.1rem 0.3rem;" onclick="deleteNode('${nodeId}')">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        // Add click event for configuration
        nodeElement.addEventListener('click', function(e) {
            if (!e.target.closest('button')) {
                selectNode(this);
            }
        });
        
        // Make node draggable within canvas
        makeNodeDraggable(nodeElement);
        
        document.getElementById('workflow-canvas').appendChild(nodeElement);
        
        // Add to workflow data
        workflowData.nodes.push({
            id: nodeId,
            type: data.type,
            subtype: data.subtype,
            title: data.title,
            x: x - 100,
            y: y - 50,
            config: {}
        });
        
        updateDropZoneVisibility();
        updateScenarioPreview();
    }
    
    function getNodeIconClass(type) {
        const icons = {
            trigger: 'fab fa-whatsapp',
            ai: 'fas fa-brain',
            function: 'fas fa-code',
            datatable: 'fas fa-database',
            template: 'fas fa-file-alt',
            visualization: 'fas fa-chart-bar'
        };
        return icons[type] || 'fas fa-circle';
    }
    
    function getNodeDescription(type, subtype) {
        const descriptions = {
            'trigger-message': 'Triggered when user sends a message',
            'trigger-keyword': 'Activated by specific keywords',
            'ai-analysis': 'Analyzes message intent using AI',
            'function-custom': 'Executes custom business logic',
            'datatable-search': 'Searches database for information',
            'template-message': 'Sends formatted message template',
            'visualization-chart': 'Creates visual data representation'
        };
        return descriptions[`${type}-${subtype}`] || 'Workflow component';
    }
    
    function makeNodeDraggable(element) {
        let isDragging = false;
        let startX, startY, startLeft, startTop;
        
        element.addEventListener('mousedown', function(e) {
            if (e.target.closest('button')) return;
            
            isDragging = true;
            startX = e.clientX;
            startY = e.clientY;
            startLeft = parseInt(element.style.left);
            startTop = parseInt(element.style.top);
            
            element.classList.add('dragging');
            e.preventDefault();
        });
        
        document.addEventListener('mousemove', function(e) {
            if (!isDragging) return;
            
            const deltaX = e.clientX - startX;
            const deltaY = e.clientY - startY;
            
            element.style.left = `${startLeft + deltaX}px`;
            element.style.top = `${startTop + deltaY}px`;
        });
        
        document.addEventListener('mouseup', function() {
            if (isDragging) {
                isDragging = false;
                element.classList.remove('dragging');
                updateNodePosition(element);
            }
        });
    }
    
    function updateNodePosition(element) {
        const nodeId = element.id;
        const node = workflowData.nodes.find(n => n.id === nodeId);
        if (node) {
            node.x = parseInt(element.style.left);
            node.y = parseInt(element.style.top);
        }
    }
    
    function selectNode(element) {
        // Remove previous selection
        document.querySelectorAll('.workflow-node').forEach(node => {
            node.classList.remove('border-primary');
            node.style.borderWidth = '2px';
        });
        
        // Add selection to current node
        element.classList.add('border-primary');
        element.style.borderWidth = '3px';
        
        selectedNode = element;
        showNodeProperties(element);
    }
    
    function showNodeProperties(element) {
        const nodeData = workflowData.nodes.find(n => n.id === element.id);
        const propertiesContent = document.getElementById('properties-content');
        
        let configForm = '';
        
        switch (nodeData.type) {
            case 'trigger':
                configForm = `
                    <div class="form-group mb-3">
                        <label class="form-label small">Trigger Keywords</label>
                        <input type="text" class="form-control form-control-sm" placeholder="hello, hi, start" onchange="updateNodeConfig('${nodeData.id}', 'keywords', this.value)">
                    </div>
                    <div class="form-group mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="case-sensitive" onchange="updateNodeConfig('${nodeData.id}', 'caseSensitive', this.checked)">
                            <label class="form-check-label small" for="case-sensitive">
                                Case Sensitive
                            </label>
                        </div>
                    </div>
                `;
                break;
                
            case 'ai':
                configForm = `
                    <div class="form-group mb-3">
                        <label class="form-label small">AI Model</label>
                        <select class="form-select form-select-sm" onchange="updateNodeConfig('${nodeData.id}', 'model', this.value)">
                            <option value="gpt-3.5">GPT-3.5 Turbo</option>
                            <option value="gpt-4">GPT-4</option>
                            <option value="claude">Claude</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label small">System Prompt</label>
                        <textarea class="form-control form-control-sm" rows="3" placeholder="You are a helpful medical assistant..." onchange="updateNodeConfig('${nodeData.id}', 'prompt', this.value)"></textarea>
                    </div>
                `;
                break;
                
            case 'template':
                configForm = `
                    <div class="form-group mb-3">
                        <label class="form-label small">Template</label>
                        <select class="form-select form-select-sm" onchange="updateNodeConfig('${nodeData.id}', 'template', this.value)">
                            <option value="">Select Template</option>
                            <option value="welcome">Welcome Message</option>
                            <option value="appointment">Appointment Confirmation</option>
                            <option value="doctor-info">Doctor Information</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label small">Variables</label>
                        <input type="text" class="form-control form-control-sm" placeholder="name, date, doctor" onchange="updateNodeConfig('${nodeData.id}', 'variables', this.value)">
                    </div>
                `;
                break;
                
            default:
                configForm = `
                    <div class="form-group mb-3">
                        <label class="form-label small">Configuration</label>
                        <textarea class="form-control form-control-sm" rows="4" placeholder="Enter JSON configuration..." onchange="updateNodeConfig('${nodeData.id}', 'config', this.value)"></textarea>
                    </div>
                `;
        }
        
        propertiesContent.innerHTML = `
            <div class="border-bottom pb-2 mb-3">
                <h6 class="mb-0">${nodeData.title}</h6>
                <small class="text-muted">${nodeData.type} - ${nodeData.subtype}</small>
            </div>
            ${configForm}
            <div class="mt-3">
                <button class="btn btn-sm btn-outline-danger w-100" onclick="deleteNode('${nodeData.id}')">
                    <i class="fas fa-trash me-1"></i> Delete Node
                </button>
            </div>
        `;
    }
    
    function updateNodeConfig(nodeId, key, value) {
        const node = workflowData.nodes.find(n => n.id === nodeId);
        if (node) {
            if (!node.config) node.config = {};
            node.config[key] = value;
            updateScenarioPreview();
        }
    }
    
    function deleteNode(nodeId) {
        const element = document.getElementById(nodeId);
        if (element) {
            element.remove();
            workflowData.nodes = workflowData.nodes.filter(n => n.id !== nodeId);
            workflowData.connections = workflowData.connections.filter(c => c.source !== nodeId && c.target !== nodeId);
            
            if (selectedNode && selectedNode.id === nodeId) {
                selectedNode = null;
                document.getElementById('properties-content').innerHTML = `
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-hand-pointer fa-2x mb-2 opacity-50"></i>
                        <p>Select a component to view properties</p>
                    </div>
                `;
            }
            
            updateDropZoneVisibility();
            updateScenarioPreview();
        }
    }
    
    function updateDropZoneVisibility() {
        const dropZone = document.getElementById('canvas-drop-zone');
        const hasNodes = workflowData.nodes.length > 0;
        dropZone.style.display = hasNodes ? 'none' : 'block';
    }
    
    function updateScenarioPreview() {
        const steps = generateScenarioSteps();
        const container = document.getElementById('scenario-steps');
        
        container.innerHTML = steps.map((step, index) => `
            <div class="scenario-step d-flex align-items-center">
                <div class="step-number">${index + 1}</div>
                <div>
                    <strong>${step.title}</strong>
                    <div class="small mt-1 opacity-75">${step.description}</div>
                </div>
            </div>
        `).join('');
    }
    
    function generateScenarioSteps() {
        if (workflowData.nodes.length === 0) {
            return [
                { title: 'No workflow created yet', description: 'Add components to build your automation' }
            ];
        }
        
        const steps = [];
        workflowData.nodes.forEach(node => {
            switch (node.type) {
                case 'trigger':
                    steps.push({
                        title: `${node.title} Activated`,
                        description: `Workflow starts when ${node.subtype} condition is met`
                    });
                    break;
                case 'ai':
                    steps.push({
                        title: 'AI Processing',
                        description: 'Message analyzed for intent and context'
                    });
                    break;
                case 'template':
                    steps.push({
                        title: 'Template Response',
                        description: 'Pre-configured message template sent to user'
                    });
                    break;
                default:
                    steps.push({
                        title: `${node.title} Executed`,
                        description: `${node.type} component processes the request`
                    });
            }
        });
        
        return steps;
    }
    
    function loadSampleWorkflow() {
        // Add a sample workflow for demonstration
        setTimeout(() => {
            createWorkflowNode({
                type: 'trigger',
                subtype: 'message',
                title: 'Message Trigger'
            }, 150, 100);
            
            setTimeout(() => {
                createWorkflowNode({
                    type: 'ai',
                    subtype: 'analysis',
                    title: 'AI Analysis'
                }, 350, 100);
            }, 500);
            
            setTimeout(() => {
                createWorkflowNode({
                    type: 'template',
                    subtype: 'message',
                    title: 'Template Response'
                }, 550, 100);
            }, 1000);
        }, 1000);
    }
    
    // Canvas controls
    function zoomIn() {
        currentZoom = Math.min(currentZoom * 1.2, 3);
        applyZoom();
    }
    
    function zoomOut() {
        currentZoom = Math.max(currentZoom * 0.8, 0.3);
        applyZoom();
    }
    
    function resetZoom() {
        currentZoom = 1;
        applyZoom();
    }
    
    function applyZoom() {
        const canvas = document.getElementById('workflow-canvas');
        canvas.style.transform = `scale(${currentZoom})`;
    }
    
    // Workflow actions
    function saveWorkflow() {
        const workflowName = document.getElementById('workflow-name').value || 'Untitled Workflow';
        const workflowDescription = document.getElementById('workflow-description').value || '';
        
        workflowData.settings = {
            name: workflowName,
            description: workflowDescription,
            active: document.getElementById('workflow-active').checked,
            priority: document.getElementById('workflow-priority').value
        };
        
        Swal.fire({
            title: 'Saving Workflow...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });
        
        // Simulate save
        setTimeout(() => {
            Swal.fire('Saved!', 'Workflow saved successfully!', 'success');
        }, 1500);
    }
    
    function testWorkflow() {
        if (workflowData.nodes.length === 0) {
            Swal.fire('No Workflow', 'Please add some components first!', 'warning');
            return;
        }
        
        Swal.fire({
            title: 'Test Workflow',
            html: `
                <div class="text-start">
                    <div class="mb-3">
                        <label class="form-label">Test Message</label>
                        <input type="text" class="form-control" id="test-message" placeholder="hello doctor" value="hello doctor">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="test-phone" placeholder="+91 9876543210" value="+91 9876543210">
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Run Test',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                runWorkflowTest();
            }
        });
    }
    
    function runWorkflowTest() {
        Swal.fire({
            title: 'Testing Workflow...',
            html: '<div id="test-progress"></div>',
            allowOutsideClick: false,
            showConfirmButton: false
        });
        
        const progressDiv = document.getElementById('test-progress');
        let step = 0;
        const steps = generateScenarioSteps();
        
        const runStep = () => {
            if (step < steps.length) {
                progressDiv.innerHTML += `
                    <div class="d-flex align-items-center mb-2">
                        <div class="spinner-border spinner-border-sm text-primary me-2" style="width: 1rem; height: 1rem;"></div>
                        <span>${steps[step].title}</span>
                    </div>
                `;
                step++;
                setTimeout(runStep, 1000);
            } else {
                progressDiv.innerHTML += `
                    <div class="alert alert-success mt-3 mb-0">
                        <i class="fas fa-check-circle me-2"></i>
                        Workflow test completed successfully!
                    </div>
                `;
                setTimeout(() => {
                    Swal.close();
                }, 2000);
            }
        };
        
        runStep();
    }
    
    function publishWorkflow() {
        if (workflowData.nodes.length === 0) {
            Swal.fire('No Workflow', 'Please create a workflow first!', 'warning');
            return;
        }
        
        Swal.fire({
            title: 'Publish Workflow?',
            text: 'This will make the workflow live and active for all users.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Publish',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Publishing...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
                
                setTimeout(() => {
                    Swal.fire('Published!', 'Workflow is now live!', 'success');
                }, 2000);
            }
        });
    }
    
    function clearCanvas() {
        if (workflowData.nodes.length === 0) return;
        
        Swal.fire({
            title: 'Clear Canvas?',
            text: 'This will remove all workflow components.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Clear',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelectorAll('.workflow-node').forEach(node => node.remove());
                workflowData.nodes = [];
                workflowData.connections = [];
                selectedNode = null;
                
                document.getElementById('properties-content').innerHTML = `
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-hand-pointer fa-2x mb-2 opacity-50"></i>
                        <p>Select a component to view properties</p>
                    </div>
                `;
                
                updateDropZoneVisibility();
                updateScenarioPreview();
            }
        });
    }
    
    function addConnection() {
        Swal.fire('Feature Coming Soon', 'Visual connections between nodes will be available soon!', 'info');
    }
    
    // Roadmap Animation Functions
    function initializeRoadmapAnimations() {
        // Animate roadmap nodes on page load
        const roadmapNodes = document.querySelectorAll('.roadmap-node');
        const connections = document.querySelectorAll('.roadmap-connection');
        
        // Stagger animation for nodes
        roadmapNodes.forEach((node, index) => {
            node.style.opacity = '0';
            node.style.transform = 'translateY(50px)';
            
            setTimeout(() => {
                node.style.transition = 'all 0.6s ease';
                node.style.opacity = '1';
                node.style.transform = 'translateY(0)';
            }, index * 200);
        });
        
        // Animate connections after nodes
        connections.forEach((connection, index) => {
            connection.style.opacity = '0';
            connection.style.transform = 'scaleX(0)';
            connection.style.transformOrigin = 'left';
            
            setTimeout(() => {
                connection.style.transition = 'all 0.8s ease';
                connection.style.opacity = '1';
                connection.style.transform = 'scaleX(1)';
            }, (roadmapNodes.length * 200) + (index * 300));
        });
        
        // Add click handlers for roadmap nodes
        roadmapNodes.forEach(node => {
            node.addEventListener('click', function() {
                showNodeDetails(this);
            });
        });
        
        // Start flow animation
        setTimeout(() => {
            startFlowAnimation();
        }, 2000);
    }
    
    function showNodeDetails(node) {
        const step = node.getAttribute('data-step');
        const nodeType = node.classList.contains('start-node') ? 'WhatsApp Trigger' :
                        node.classList.contains('processing-node') ? 'Keyword Router' :
                        node.classList.contains('ai-node') ? 'AI Analysis' :
                        node.classList.contains('decision-node') ? 'Decision Engine' :
                        'Response Generator';
        
        let details = '';
        switch(step) {
            case '1':
                details = 'Receives incoming WhatsApp messages and initializes the workflow process.';
                break;
            case '2':
                details = 'Analyzes message content for predefined keywords and routing rules.';
                break;
            case '3':
                details = 'Uses AI to understand message intent when no keywords match.';
                break;
            case '4':
                details = 'Makes decisions on response type based on analysis results.';
                break;
            case '5':
                details = 'Generates and sends appropriate responses to users.';
                break;
        }
        
        Swal.fire({
            title: nodeType,
            html: `
                <div class="text-start">
                    <p><strong>Step ${step}:</strong> ${details}</p>
                    <hr>
                    <div class="small text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Click on any node to view detailed information about that workflow step.
                    </div>
                </div>
            `,
            icon: 'info',
            confirmButtonText: 'Got it'
        });
    }
    
    function startFlowAnimation() {
        const nodes = document.querySelectorAll('.roadmap-node');
        let currentIndex = 0;
        
        function animateNode() {
            // Remove previous highlight
            nodes.forEach(node => {
                node.style.boxShadow = '0 8px 25px rgba(0, 0, 0, 0.1)';
                node.style.transform = 'translateY(0)';
            });
            
            // Highlight current node
            if (nodes[currentIndex]) {
                nodes[currentIndex].style.boxShadow = '0 15px 40px rgba(102, 126, 234, 0.4)';
                nodes[currentIndex].style.transform = 'translateY(-8px)';
            }
            
            currentIndex = (currentIndex + 1) % nodes.length;
            
            // Continue animation
            setTimeout(animateNode, 1500);
        }
        
        animateNode();
    }
    
    function updateFlowStats() {
        // Simulate real-time updates to flow statistics
        const statNumbers = document.querySelectorAll('.stat-number');
        
        statNumbers.forEach((stat, index) => {
            const currentValue = parseInt(stat.textContent.replace(/[^\d]/g, ''));
            let newValue;
            
            switch(index) {
                case 0: // Flows Started
                    newValue = currentValue + Math.floor(Math.random() * 3);
                    break;
                case 1: // Completed
                    newValue = currentValue + Math.floor(Math.random() * 2);
                    break;
                case 2: // Avg Response (in seconds)
                    const randomTime = (Math.random() * 0.5 + 0.5).toFixed(1);
                    stat.textContent = randomTime + 's';
                    return;
                case 3: // Success Rate
                    const randomRate = (85 + Math.random() * 10).toFixed(1);
                    stat.textContent = randomRate + '%';
                    return;
            }
            
            // Animate number change
            stat.style.transform = 'scale(1.1)';
            stat.style.color = '#007bff';
            stat.textContent = newValue.toLocaleString();
            
            setTimeout(() => {
                stat.style.transform = 'scale(1)';
                stat.style.color = '#2c3e50';
            }, 300);
        });
    }
    
    // Initialize roadmap on page load
    document.addEventListener('DOMContentLoaded', function() {
        initializeRoadmapAnimations();
        
        // Update stats every 10 seconds
        setInterval(updateFlowStats, 10000);
    });
    
    function previewWorkflow() {
        const steps = generateScenarioSteps();
        const stepsHtml = steps.map((step, index) => `
            <div class="d-flex align-items-center mb-3">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; font-size: 0.8rem;">
                    ${index + 1}
                </div>
                <div>
                    <strong>${step.title}</strong>
                    <div class="small text-muted">${step.description}</div>
                </div>
            </div>
        `).join('');
        
        Swal.fire({
            title: 'Workflow Preview',
            html: `
                <div class="text-start">
                    <h6 class="mb-3">Execution Flow:</h6>
                    ${stepsHtml}
                </div>
            `,
            width: 600
        });
    }
</script>
@endpush
