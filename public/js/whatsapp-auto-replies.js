// WhatsApp Auto-replies JavaScript

let templates = [];
let currentAutoReply = null;

// Fetch templates from WhatsApp Cloud API
async function fetchWhatsAppTemplates() {
    try {
        const response = await fetch('/admin/whatsapp/templates/sync');
        const data = await response.json();
        if (data.success) {
            templates = data.templates;
            updateTemplateSelectors();
        }
    } catch (error) {
        console.error('Error fetching templates:', error);
        showError('Failed to fetch WhatsApp templates');
    }
}

// Update all template selectors with fresh data
function updateTemplateSelectors() {
    const selectors = ['template_id', 'follow_up_template_id'].map(id => 
        document.getElementsByName(id)[0]
    ).filter(Boolean);

    selectors.forEach(selector => {
        const currentValue = selector.value;
        selector.innerHTML = '<option value="">Select template...</option>';
        
        templates.forEach(template => {
            const option = document.createElement('option');
            option.value = template.id;
            option.textContent = template.name;
            selector.appendChild(option);
        });

        if (currentValue) {
            selector.value = currentValue;
        }
    });
}

// Test the auto-reply configuration
async function testReply() {
    const testMessage = document.getElementById('testMessage').value;
    if (!testMessage) {
        showError('Please enter a test message');
        return;
    }

    const form = document.getElementById('autoReplyForm');
    const formData = new FormData(form);
    const previewContent = document.getElementById('previewContent');

    try {
        const response = await fetch('/admin/whatsapp/auto-replies/test', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                message: testMessage,
                pattern: formData.get('pattern'),
                template_id: formData.get('template_id'),
                sentiment_type: formData.get('sentiment_type'),
                smart_selection: formData.get('smart_selection') === 'on'
            })
        });

        const data = await response.json();
        if (data.success) {
            previewContent.textContent = data.preview;
            showSuccess('Test successful!');
        } else {
            throw new Error(data.message || 'Test failed');
        }
    } catch (error) {
        console.error('Test error:', error);
        showError(error.message || 'Failed to test auto-reply');
    }
}

// Open the auto-reply modal
function openAutoReplyModal(autoReply = null) {
    currentAutoReply = autoReply;
    const modal = document.getElementById('autoReplyModal');
    const form = document.getElementById('autoReplyForm');

    // Reset form
    form.reset();
    document.getElementById('previewContent').textContent = 'Your response will appear here...';

    // If editing existing auto-reply
    if (autoReply) {
        Object.keys(autoReply).forEach(key => {
            const input = form.elements[key];
            if (input) {
                if (input.type === 'checkbox') {
                    input.checked = autoReply[key];
                } else {
                    input.value = autoReply[key];
                }
            }
        });
    }

    // Show modal
    modal.classList.remove('hidden');
}

// Close the auto-reply modal
function closeAutoReplyModal() {
    const modal = document.getElementById('autoReplyModal');
    modal.classList.add('hidden');
    currentAutoReply = null;
}

// Show success message
function showSuccess(message) {
    // Implement your success notification here
    console.log('Success:', message);
}

// Show error message
function showError(message) {
    // Implement your error notification here
    console.error('Error:', message);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    fetchWhatsAppTemplates();

    // Set up form submission
    const form = document.getElementById('autoReplyForm');
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(form);
        const endpoint = currentAutoReply 
            ? `/admin/whatsapp/auto-replies/${currentAutoReply.id}`
            : '/admin/whatsapp/auto-replies';

        try {
            const response = await fetch(endpoint, {
                method: currentAutoReply ? 'PUT' : 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(Object.fromEntries(formData))
            });

            const data = await response.json();
            if (data.success) {
                showSuccess('Auto-reply saved successfully!');
                closeAutoReplyModal();
                // Refresh the auto-replies list
                location.reload();
            } else {
                throw new Error(data.message || 'Failed to save auto-reply');
            }
        } catch (error) {
            console.error('Save error:', error);
            showError(error.message || 'Failed to save auto-reply');
        }
    });

    // Live preview updates
    const patternInput = document.getElementsByName('pattern')[0];
    const templateSelect = document.getElementsByName('template_id')[0];

    [patternInput, templateSelect].forEach(element => {
        element?.addEventListener('change', () => {
            const preview = document.getElementById('previewContent');
            const pattern = patternInput.value;
            const templateId = templateSelect.value;
            
            if (pattern && templateId) {
                preview.textContent = 'Updating preview...';
                testReply();
            }
        });
    });
});
