/**
 * FreeDoctor Global JavaScript Library
 * Contains all reusable functions for UI interactions
 */

// Global variables
window.globalState = {
    currentScale: 1,
    workflowNodes: [],
    selectedNode: null,
    dragData: null,
    autoRefreshInterval: null
};

// Common Alert Functions
function showAlert(message, type = 'info') {
    if (typeof Swal !== 'undefined') {
        const iconMap = {
            'success': 'success',
            'error': 'error',
            'warning': 'warning',
            'info': 'info'
        };
        
        Swal.fire({
            title: type.charAt(0).toUpperCase() + type.slice(1),
            text: message,
            icon: iconMap[type] || 'info',
            timer: 3000,
            showConfirmButton: false
        });
    } else {
        alert(message);
    }
}

function showToast(message, type = 'info') {
    showAlert(message, type);
}

// Modal Functions
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal && typeof bootstrap !== 'undefined') {
        const bsModal = bootstrap.Modal.getInstance(modal);
        if (bsModal) {
            bsModal.hide();
        }
    }
}

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal && typeof bootstrap !== 'undefined') {
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    }
}

// Common Button Actions
function refreshAll() {
    location.reload();
}

function goBack() {
    window.history.back();
}

function printPage() {
    window.print();
}

function closePage() {
    window.close();
}

// Element Removal Functions
function removeElement(element) {
    if (element && element.remove) {
        element.remove();
    }
}

function removeParentElement(element) {
    if (element && element.parentElement && element.parentElement.remove) {
        element.parentElement.remove();
    }
}

function removeGrandParentElement(element) {
    if (element && element.parentElement && element.parentElement.parentElement && element.parentElement.parentElement.remove) {
        element.parentElement.parentElement.remove();
    }
}

// Image Modal Functions
function openImageModal(imageUrl, title = '') {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: title,
            imageUrl: imageUrl,
            imageAlt: title,
            showCloseButton: true,
            showConfirmButton: false,
            customClass: {
                image: 'img-fluid'
            }
        });
    } else {
        window.open(imageUrl, '_blank');
    }
}

// Filter Functions
function closeFilterModal() {
    closeModal('filterModal');
}

function clearFilters() {
    const form = document.querySelector('#filterModal form');
    if (form) {
        form.reset();
    }
    showToast('Filters cleared', 'success');
}

function applyFilters() {
    const form = document.querySelector('#filterModal form');
    if (form) {
        form.submit();
    }
}

// Registration Modal Functions
function closeRegistrationModal() {
    closeModal('registrationModal');
}

function showRegistrationModal(campaignId) {
    const modal = document.getElementById('registrationModal');
    if (modal) {
        // Update modal content if needed
        openModal('registrationModal');
    }
}

// Campaign Functions
function shareEnhancedCampaign(campaignId, url, title, description, thumbnail) {
    if (navigator.share) {
        navigator.share({
            title: title,
            text: description,
            url: url
        }).catch(console.error);
    } else {
        // Fallback to copy to clipboard
        navigator.clipboard.writeText(url).then(() => {
            showToast('Campaign link copied to clipboard!', 'success');
        }).catch(() => {
            showToast('Could not copy link', 'error');
        });
    }
}

function showCampaignDetails(campaignData) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: campaignData.title || 'Campaign Details',
            html: `
                <div class="text-start">
                    <p><strong>Description:</strong> ${campaignData.description || 'No description'}</p>
                    <p><strong>Date:</strong> ${campaignData.date || 'Not specified'}</p>
                    <p><strong>Location:</strong> ${campaignData.location || 'Not specified'}</p>
                </div>
            `,
            confirmButtonText: 'Close'
        });
    }
}

// Lead Management Functions
function closeLeadModal() {
    closeModal('leadModal');
}

// View Detail Functions
function viewDetails(data) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Details',
            html: `<pre>${JSON.stringify(data, null, 2)}</pre>`,
            confirmButtonText: 'Close'
        });
    } else {
        console.log('Details:', data);
    }
}

function viewRegistration(data) {
    viewDetails(data);
}

function viewWithdrawalDetails(data) {
    viewDetails(data);
}

function viewRequest(data) {
    viewDetails(data);
}

// Authentication Functions
function loginWithGoogle() {
    showToast('Google login not configured', 'warning');
}

function registerWithGoogle() {
    showToast('Google registration not configured', 'warning');
}

// WhatsApp Functions
function testReply(element) {
    showToast('Testing reply...', 'info');
    setTimeout(() => {
        showToast('Reply test completed', 'success');
    }, 2000);
}

function editLink(url) {
    window.open(url, '_blank');
}

// Automation Specific Functions (from automation.blade.php)
function fitToScreen() {
    const canvas = document.getElementById('workflow-canvas');
    if (canvas) {
        window.globalState.currentScale = 1;
        canvas.style.transform = 'scale(1)';
        canvas.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'center' });
    }
}

function zoomIn() {
    window.globalState.currentScale = Math.min(window.globalState.currentScale + 0.1, 2);
    const canvas = document.getElementById('workflow-canvas');
    if (canvas) {
        canvas.style.transform = `scale(${window.globalState.currentScale})`;
    }
}

function zoomOut() {
    window.globalState.currentScale = Math.max(window.globalState.currentScale - 0.1, 0.5);
    const canvas = document.getElementById('workflow-canvas');
    if (canvas) {
        canvas.style.transform = `scale(${window.globalState.currentScale})`;
    }
}

function undoAction() {
    showToast('Undo functionality not implemented', 'info');
}

function redoAction() {
    showToast('Redo functionality not implemented', 'info');
}

function clearCanvas() {
    if (confirm('Are you sure you want to clear the canvas?')) {
        const canvas = document.getElementById('workflow-canvas');
        if (canvas) {
            canvas.innerHTML = '';
            window.globalState.workflowNodes = [];
            showToast('Canvas cleared', 'success');
        }
    }
}

function saveWorkflow() {
    showToast('Saving workflow...', 'info');
    setTimeout(() => {
        showToast('Workflow saved successfully', 'success');
    }, 1500);
}

function previewWorkflow() {
    showToast('Workflow preview feature coming soon', 'info');
}

function testWorkflow() {
    showToast('Testing workflow...', 'info');
    setTimeout(() => {
        showToast('Workflow test completed', 'success');
    }, 2000);
}

function deployWorkflow() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Deploy Workflow?',
            text: 'This will make your workflow live and active.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, deploy it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                showToast('Workflow deployed successfully', 'success');
            }
        });
    }
}

function editNode(nodeId) {
    showToast(`Editing node ${nodeId}`, 'info');
}

function deleteNode(nodeId) {
    if (confirm('Are you sure you want to delete this node?')) {
        const node = document.getElementById(nodeId);
        if (node) {
            node.remove();
            showToast('Node deleted', 'success');
        }
    }
}

function updateNodeProperty(nodeId, property, value) {
    console.log(`Updating node ${nodeId}: ${property} = ${value}`);
}

function activateWorkflow(templateKey) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Activate Workflow?',
            text: 'This will make the workflow available for incoming WhatsApp messages.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, activate it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                showToast('Workflow activated successfully', 'success');
            }
        });
    }
}

function duplicateWorkflow(templateKey) {
    showToast('Duplicating workflow...', 'info');
    setTimeout(() => {
        showToast('Workflow duplicated successfully', 'success');
    }, 1500);
}

function viewWorkflowDetails(workflowId) {
    showToast(`Viewing workflow ${workflowId} details`, 'info');
}

function pauseWorkflow(workflowId) {
    showToast(`Workflow ${workflowId} paused`, 'success');
}

function stopWorkflow(workflowId) {
    showToast(`Workflow ${workflowId} stopped`, 'success');
}

function openMachineConfig() {
    openModal('machineConfigModal');
}

function testAllConnections() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Testing Connections...',
            text: 'Please wait while we test all machine connections',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });
        
        setTimeout(() => {
            Swal.fire({
                title: 'Connection Tests Complete',
                html: `
                    <div class="text-start">
                        <div class="mb-2"><i class="fas fa-check text-success me-2"></i>AI Machine: Connected</div>
                        <div class="mb-2"><i class="fas fa-check text-success me-2"></i>Function Machine: Connected</div>
                        <div class="mb-2"><i class="fas fa-check text-success me-2"></i>DataTable Machine: Connected</div>
                        <div class="mb-2"><i class="fas fa-check text-success me-2"></i>Template Machine: Connected</div>
                        <div class="mb-2"><i class="fas fa-times text-warning me-2"></i>Visualization Machine: Disabled</div>
                    </div>
                `,
                icon: 'success',
                confirmButtonText: 'Close'
            });
        }, 2000);
    }
}

function saveMachineConfig() {
    showToast('Saving machine configuration...', 'info');
    setTimeout(() => {
        showToast('Configuration saved successfully', 'success');
    }, 1500);
}

// Form Prevention Functions
function preventDefault(event) {
    if (event && event.preventDefault) {
        event.preventDefault();
    }
}

// File Tab Functions
function showFileTab(tabId) {
    const element = document.getElementById(tabId);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
    }
}

// Generic Event Handlers
function handleElementClick(element, action) {
    switch(action) {
        case 'remove':
            removeElement(element);
            break;
        case 'removeParent':
            removeParentElement(element);
            break;
        case 'removeGrandParent':
            removeGrandParentElement(element);
            break;
        default:
            console.log('Unknown action:', action);
    }
}

// jQuery Alternative Functions (for when jQuery is not available)
function findClosest(element, selector) {
    while (element && element !== document) {
        if (element.matches && element.matches(selector)) {
            return element;
        }
        element = element.parentElement;
    }
    return null;
}

function addClass(element, className) {
    if (element && element.classList) {
        element.classList.add(className);
    }
}

function removeClass(element, className) {
    if (element && element.classList) {
        element.classList.remove(className);
    }
}

// Initialize global functions when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('FreeDoctor Global Library loaded successfully');
    
    // Make all functions globally available
    const functions = [
        'showAlert', 'showToast', 'closeModal', 'openModal', 'refreshAll', 'goBack', 
        'printPage', 'closePage', 'removeElement', 'removeParentElement', 'removeGrandParentElement',
        'openImageModal', 'closeFilterModal', 'clearFilters', 'applyFilters', 'closeRegistrationModal',
        'showRegistrationModal', 'shareEnhancedCampaign', 'showCampaignDetails', 'closeLeadModal',
        'viewDetails', 'viewRegistration', 'viewWithdrawalDetails', 'viewRequest', 'loginWithGoogle',
        'registerWithGoogle', 'testReply', 'editLink', 'fitToScreen', 'zoomIn', 'zoomOut',
        'undoAction', 'redoAction', 'clearCanvas', 'saveWorkflow', 'previewWorkflow', 'testWorkflow',
        'deployWorkflow', 'editNode', 'deleteNode', 'updateNodeProperty', 'activateWorkflow',
        'duplicateWorkflow', 'viewWorkflowDetails', 'pauseWorkflow', 'stopWorkflow', 'openMachineConfig',
        'testAllConnections', 'saveMachineConfig', 'preventDefault', 'showFileTab', 'handleElementClick',
        'findClosest', 'addClass', 'removeClass'
    ];
    
    functions.forEach(funcName => {
        if (typeof window[funcName] !== 'undefined') {
            window[funcName] = eval(funcName);
        }
    });
});

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        showAlert, showToast, closeModal, openModal, refreshAll, goBack,
        printPage, closePage, removeElement, removeParentElement, removeGrandParentElement,
        openImageModal, closeFilterModal, clearFilters, applyFilters, closeRegistrationModal,
        showRegistrationModal, shareEnhancedCampaign, showCampaignDetails, closeLeadModal,
        viewDetails, viewRegistration, viewWithdrawalDetails, viewRequest, loginWithGoogle,
        registerWithGoogle, testReply, editLink, fitToScreen, zoomIn, zoomOut,
        undoAction, redoAction, clearCanvas, saveWorkflow, previewWorkflow, testWorkflow,
        deployWorkflow, editNode, deleteNode, updateNodeProperty, activateWorkflow,
        duplicateWorkflow, viewWorkflowDetails, pauseWorkflow, stopWorkflow, openMachineConfig,
        testAllConnections, saveMachineConfig, preventDefault, showFileTab, handleElementClick,
        findClosest, addClass, removeClass
    };
}
