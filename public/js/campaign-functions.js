/**
 * Campaign-specific JavaScript functions
 * Handles campaign filtering, registration, and UI interactions
 */

// Campaign Filter Functions with Unique IDs
function openFilterModal_campaigns() {
    openModal('filterModal');
}

function closeFilterModal_campaigns() {
    closeModal('filterModal');
}

function clearFilters_campaigns() {
    const form = document.querySelector('#filterModal form');
    if (form) {
        form.reset();
        // Clear URL parameters
        const url = new URL(window.location);
        url.search = '';
        window.history.replaceState({}, document.title, url);
    }
    showToast('Filters cleared', 'success');
}

function applyFilters_campaigns() {
    const form = document.querySelector('#filterModal form');
    if (form) {
        form.submit();
    }
}

function removeFilter_campaigns(filterKey) {
    const url = new URL(window.location);
    url.searchParams.delete(filterKey);
    window.location.href = url.toString();
}

// Campaign Registration Functions
function showRegistrationModal(campaignId) {
    const modal = document.getElementById('registrationModal');
    if (modal) {
        // Set campaign ID if needed
        const input = modal.querySelector('input[name="campaign_id"]');
        if (input) {
            input.value = campaignId;
        }
        openModal('registrationModal');
    }
}

function closeRegistrationModal() {
    closeModal('registrationModal');
}

// Campaign Details Functions
function showCampaignDetails(campaignData) {
    if (typeof campaignData === 'string') {
        try {
            campaignData = JSON.parse(campaignData);
        } catch (e) {
            console.error('Invalid campaign data:', e);
            return;
        }
    }
    
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: campaignData.title || 'Campaign Details',
            html: `
                <div class="text-start">
                    <p><strong>Description:</strong> ${campaignData.description || 'No description'}</p>
                    <p><strong>Date:</strong> ${campaignData.date || 'Not specified'}</p>
                    <p><strong>Location:</strong> ${campaignData.location || 'Not specified'}</p>
                    <p><strong>Fee:</strong> ${campaignData.fee ? '₹' + campaignData.fee : 'Free'}</p>
                    ${campaignData.thumbnail ? `<img src="${campaignData.thumbnail}" class="img-fluid mt-2" alt="Campaign thumbnail">` : ''}
                </div>
            `,
            confirmButtonText: 'Close',
            width: '600px'
        });
    } else {
        console.log('Campaign Details:', campaignData);
    }
}

// Campaign Sharing Functions
function shareEnhancedCampaign(campaignId, url, title, description, thumbnail) {
    if (navigator.share) {
        navigator.share({
            title: title,
            text: description,
            url: url
        }).then(() => {
            showToast('Campaign shared successfully!', 'success');
        }).catch(() => {
            fallbackShare(url, title);
        });
    } else {
        fallbackShare(url, title);
    }
}

function fallbackShare(url, title) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(url).then(() => {
            showToast('Campaign link copied to clipboard!', 'success');
        }).catch(() => {
            promptCopyLink(url);
        });
    } else {
        promptCopyLink(url);
    }
}

function promptCopyLink(url) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Share Campaign',
            html: `
                <div class="text-start">
                    <p>Copy this link to share the campaign:</p>
                    <input type="text" class="form-control" value="${url}" id="shareUrl" readonly>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Copy Link',
            cancelButtonText: 'Close',
            preConfirm: () => {
                const input = document.getElementById('shareUrl');
                input.select();
                document.execCommand('copy');
                return true;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                showToast('Link copied to clipboard!', 'success');
            }
        });
    } else {
        prompt('Copy this link to share the campaign:', url);
    }
}

// Enhanced Filter Functions for Campaign Lists
function openFilterModal(uniqueId) {
    const modalId = uniqueId ? `filterModal_${uniqueId}` : 'filterModal';
    openModal(modalId);
}

function closeFilterModal(uniqueId) {
    const modalId = uniqueId ? `filterModal_${uniqueId}` : 'filterModal';
    closeModal(modalId);
}

function clearFilters(uniqueId) {
    const formSelector = uniqueId ? `#filterModal_${uniqueId} form` : '#filterModal form';
    const form = document.querySelector(formSelector);
    if (form) {
        form.reset();
    }
    showToast('Filters cleared', 'success');
}

function applyFilters(uniqueId) {
    const formSelector = uniqueId ? `#filterModal_${uniqueId} form` : '#filterModal form';
    const form = document.querySelector(formSelector);
    if (form) {
        form.submit();
    }
}

function removeFilter(filterKey, uniqueId) {
    const url = new URL(window.location);
    url.searchParams.delete(filterKey);
    window.location.href = url.toString();
}

// Campaign Image Functions
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
            },
            width: '80%',
            padding: '1rem'
        });
    } else {
        window.open(imageUrl, '_blank');
    }
}

// Registration Management Functions
function viewRegistration(data) {
    if (typeof data === 'string') {
        try {
            data = JSON.parse(data);
        } catch (e) {
            console.error('Invalid registration data:', e);
            return;
        }
    }
    
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Registration Details',
            html: `
                <div class="text-start">
                    <p><strong>Name:</strong> ${data.name || 'N/A'}</p>
                    <p><strong>Email:</strong> ${data.email || 'N/A'}</p>
                    <p><strong>Phone:</strong> ${data.phone || 'N/A'}</p>
                    <p><strong>Campaign:</strong> ${data.campaign_title || 'N/A'}</p>
                    <p><strong>Status:</strong> ${data.status || 'N/A'}</p>
                    <p><strong>Date:</strong> ${data.created_at || 'N/A'}</p>
                </div>
            `,
            confirmButtonText: 'Close',
            width: '500px'
        });
    } else {
        console.log('Registration Details:', data);
    }
}

// Initialize campaign functions
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            if (alert.classList.contains('alert-dismissible')) {
                const closeBtn = alert.querySelector('.btn-close');
                if (closeBtn) {
                    closeBtn.click();
                }
            }
        });
    }, 5000);
    
    // Initialize tooltips if Bootstrap is available
    if (typeof bootstrap !== 'undefined') {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
    
    console.log('Campaign functions initialized');
});

// Make functions globally available
window.openFilterModal_campaigns = openFilterModal_campaigns;
window.closeFilterModal_campaigns = closeFilterModal_campaigns;
window.clearFilters_campaigns = clearFilters_campaigns;
window.applyFilters_campaigns = applyFilters_campaigns;
window.removeFilter_campaigns = removeFilter_campaigns;
window.showRegistrationModal = showRegistrationModal;
window.closeRegistrationModal = closeRegistrationModal;
window.showCampaignDetails = showCampaignDetails;
window.shareEnhancedCampaign = shareEnhancedCampaign;
window.openImageModal = openImageModal;
window.viewRegistration = viewRegistration;
window.openFilterModal = openFilterModal;
window.closeFilterModal = closeFilterModal;
window.clearFilters = clearFilters;
window.applyFilters = applyFilters;
window.removeFilter = removeFilter;
