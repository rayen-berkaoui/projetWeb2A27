/**
 * 2A27 Dashboard - Feedback Management
 * Handles admin feedback interactions and management
 */

(function() {
    'use strict';

    // Initialize when DOM is fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        initBulkActions();
        initQuickActions();
        initStatusToggle();
        initResponseForm();
        initFeedbackFilters();
    });

    /**
     * Initialize bulk action functionality
     */
    function initBulkActions() {
        const selectAllCheckbox = document.getElementById('select-all');
        const feedbackCheckboxes = document.querySelectorAll('.feedback-checkbox');
        const bulkActionSelect = document.getElementById('bulk-action');
        const applyBulkButton = document.getElementById('apply-bulk');
        const bulkActionForm = document.getElementById('bulk-action-form');
        
        if (!selectAllCheckbox || !bulkActionForm) return;

        // Handle "Select All" checkbox
        selectAllCheckbox.addEventListener('change', function() {
            feedbackCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            updateBulkActionButton();
        });

        // Handle individual checkboxes
        feedbackCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const allChecked = Array.from(feedbackCheckboxes).every(cb => cb.checked);
                const anyChecked = Array.from(feedbackCheckboxes).some(cb => cb.checked);
                
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = anyChecked && !allChecked;
                
                updateBulkActionButton();
            });
        });

        // Handle bulk action select
        bulkActionSelect.addEventListener('change', updateBulkActionButton);

        // Handle bulk action form submission
        bulkActionForm.addEventListener('submit', function(event) {
            const selectedAction = bulkActionSelect.value;
            const checkedItems = document.querySelectorAll('.feedback-checkbox:checked');
            
            if (!selectedAction || checkedItems.length === 0) {
                event.preventDefault();
                return;
            }
            
            // Confirm destructive actions
            if (selectedAction === 'delete') {
                const confirmed = confirm(`Are you sure you want to delete ${checkedItems.length} selected feedback item(s)? This action cannot be undone.`);
                if (!confirmed) {
                    event.preventDefault();
                }
            }
        });

        // Helper to update the apply button state
        function updateBulkActionButton() {
            const anyChecked = Array.from(feedbackCheckboxes).some(cb => cb.checked);
            const actionSelected = bulkActionSelect.value !== '';
            
            applyBulkButton.disabled = !(anyChecked && actionSelected);
        }
    }

    /**
     * Initialize quick action buttons (approve, reject, delete)
     */
    function initQuickActions() {
        // Quick approve buttons
        document.querySelectorAll('.quick-approve').forEach(button => {
            button.addEventListener('click', function() {
                const feedbackId = this.dataset.id;
                if (confirm('Approve this feedback?')) {
                    submitStatusChange(feedbackId, 'approved');
                }
            });
        });

        // Quick reject buttons
        document.querySelectorAll('.quick-reject').forEach(button => {
            button.addEventListener('click', function() {
                const feedbackId = this.dataset.id;
                if (confirm('Reject this feedback?')) {
                    submitStatusChange(feedbackId, 'rejected');
                }
            });
        });

        // Quick delete buttons
        document.querySelectorAll('.quick-delete').forEach(button => {
            button.addEventListener('click', function() {
                const feedbackId = this.dataset.id;
                if (confirm('Are you sure you want to delete this feedback? This action cannot be undone.')) {
                    submitDelete(feedbackId);
                }
            });
        });

        // Helper function to submit status changes
        function submitStatusChange(id, status) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `index.php?route=feedback/admin/${id}/status`;
            form.style.display = 'none';
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            
            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = status;
            
            form.appendChild(methodInput);
            form.appendChild(statusInput);
            document.body.appendChild(form);
            form.submit();
        }

        // Helper function to submit deletion
        function submitDelete(id) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `index.php?route=feedback/admin/${id}`;
            form.style.display = 'none';
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        }
    }

    /**
     * Initialize status toggle functionality on feedback detail page
     */
    function initStatusToggle() {
        // Public/private toggle
        const publicToggleForm = document.querySelector('form[action*="status"] input[name="isPublic"]');
        if (publicToggleForm) {
            const parentForm = publicToggleForm.closest('form');
            parentForm.addEventListener('change', function(e) {
                if (e.target.name === 'isPublic') {
                    this.submit();
                }
            });
        }

        // Featured toggle
        const featuredToggleForm = document.querySelector('form[action*="status"] input[name="featured"]');
        if (featuredToggleForm) {
            const parentForm = featuredToggleForm.closest('form');
            parentForm.addEventListener('change', function(e) {
                if (e.target.name === 'featured') {
                    this.submit();
                }
            });
        }
    }

    /**
     * Initialize response form handling
     */
    function initResponseForm() {
        const responseForm = document.querySelector('form[action*="respond"]');
        if (!responseForm) return;

        // Add character counter to textarea
        const textarea = responseForm.querySelector('textarea');
        if (textarea) {
            const counterDiv = document.createElement('div');
            counterDiv.className = 'text-muted small mt-1 text-end';
            counterDiv.innerHTML = `<span id="char-count">0</span>/<span id="char-max">1000</span> characters`;
            
            textarea.parentNode.appendChild(counterDiv);
            
            const charCount = document.getElementById('char-count');
            const maxLength = 1000; // Set maximum length
            
            // Update character count on input
            textarea.addEventListener('input', function() {
                const currentLength = this.value.length;
                charCount.textContent = currentLength;
                
                if (currentLength > maxLength) {
                    charCount.classList.add('text-danger');
                    charCount.classList.remove('text-muted');
                } else {
                    charCount.classList.remove('text-danger');
                    charCount.classList.add('text-muted');
                }
                
                // Auto-save draft after 1 second of inactivity
                if (window.responseDraftTimeout) {
                    clearTimeout(window.responseDraftTimeout);
                }
                
                window.responseDraftTimeout = setTimeout(function() {
                    saveResponseDraft(textarea.value);
                }, 1000);
            });
            
            // Set initial character count
            textarea.dispatchEvent(new Event('input'));
            
            // Try to load draft
            loadResponseDraft(textarea);
        }
        
        // Form validation and submission
        responseForm.addEventListener('submit', function(event) {
            const textarea = this.querySelector('textarea');
            
            if (!textarea.value.trim()) {
                event.preventDefault();
                showError('Please enter a response message');
                textarea.focus();
                return;
            }
            
            if (textarea.value.length > 1000) {
                event.preventDefault();
                showError('Response message is too long (maximum 1000 characters)');
                textarea.focus();
                return;
            }
            
            // Show loading state
            const submitButton = this.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
            }
            
            // Clear draft after successful submission
            localStorage.removeItem('feedbackResponseDraft');
        });
        
        // Response preview functionality
        const previewButton = document.getElementById('preview-response');
        if (previewButton && textarea) {
            const previewContainer = document.createElement('div');
            previewContainer.className = 'response-preview mt-3 p-3 bg-light rounded d-none';
            previewContainer.innerHTML = '<h6>Preview:</h6><div id="response-preview-content"></div>';
            
            textarea.parentNode.after(previewContainer);
            
            previewButton.addEventListener('click', function(e) {
                e.preventDefault();
                
                const previewContent = document.getElementById('response-preview-content');
                const content = textarea.value.trim();
                
                if (content) {
                    previewContent.innerHTML = content.replace(/\n/g, '<br>');
                    previewContainer.classList.remove('d-none');
                } else {
                    previewContainer.classList.add('d-none');
                }
            });
        }
    }
    
    /**
     * Save response draft to localStorage
     * @param {string} text - The draft response text
     */
    function saveResponseDraft(text) {
        if (text.trim()) {
            localStorage.setItem('feedbackResponseDraft', text);
        } else {
            localStorage.removeItem('feedbackResponseDraft');
        }
    }
    
    /**
     * Load saved response draft from localStorage
     * @param {HTMLTextAreaElement} textarea - The textarea element to populate
     */
    function loadResponseDraft(textarea) {
        const savedDraft = localStorage.getItem('feedbackResponseDraft');
        
        if (savedDraft && !textarea.value) {
            // Only load if the textarea is empty (to avoid overwriting user input)
            textarea.value = savedDraft;
            textarea.dispatchEvent(new Event('input'));
            
            // Show notification
            const notification = document.createElement('div');
            notification.className = 'alert alert-info alert-dismissible fade show mt-2';
            notification.innerHTML = 'A draft response has been loaded. <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            
            textarea.parentNode.appendChild(notification);
            
            // Automatically remove after 5 seconds
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => notification.remove(), 150);
            }, 5000);
        }
    }

    /**
     * Initialize feedback filter functionality
     */
    function initFeedbackFilters() {
        const filterForm = document.querySelector('form[action*="feedback/admin"]');
        if (!filterForm) return;
        
        // Quick filter selects
        const filterSelects = filterForm.querySelectorAll('select');
        filterSelects.forEach(select => {
            select.addEventListener('change', function() {
                // If using auto-submit, uncomment this line
                // filterForm.submit();
            });
        });
        
        // Reset button
        const resetButton = filterForm.querySelector('a[href*="feedback/admin"]');
        if (resetButton) {
            resetButton.addEventListener('click', function(e) {
                // Clear filters before navigating to reset URL
                filterSelects.forEach(select => select.selectedIndex = 0);
                
                // Clear any text inputs
                const textInputs = filterForm.querySelectorAll('input[type="text"]');
                textInputs.forEach(input => input.value = '');
            });
        }
        
        // Date range filters
        const dateInputs = filterForm.querySelectorAll('input[type="date"]');
        dateInputs.forEach(input => {
            input.addEventListener('change', function() {
                validateDateRange(dateInputs[0], dateInputs[1]);
            });
        });
    }
    
    /**
     * Validate date range to ensure start date is before end date
     * @param {HTMLInputElement} startInput - Start date input
     * @param {HTMLInputElement} endInput - End date input
     */
    function validateDateRange(startInput, endInput) {
        if (!startInput || !endInput) return;
        
        const startDate = startInput.value ? new Date(startInput.value) : null;
        const endDate = endInput.value ? new Date(endInput.value) : null;
        
        if (startDate && endDate && startDate > endDate) {
            showError('Start date must be before end date');
            endInput.value = '';
        }
    }
    
    /**
     * Show an error message
     * @param {string} message - The error message
     */
    function showError(message) {
        const alertContainer = document.getElementById('alert-container');
        if (!alertContainer) {
            // Create alert container if it doesn't exist
            const container = document.createElement('div');
            container.id = 'alert-container';
            container.className = 'alert-container position-fixed top-0 start-50 translate-middle-x mt-3 z-index-10';
            document.body.appendChild(container);
        }
        
        const alert = document.createElement('div');
        alert.className = 'alert alert-danger alert-dismissible fade show';
        alert.innerHTML = `
            <i class="fas fa-exclamation-circle me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        document.getElementById('alert-container').appendChild(alert);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 150);
        }, 5000);
    }
    
    /**
     * Show a success message
     * @param {string} message - The success message
     */
    function showSuccess(message) {
        const alertContainer = document.getElementById('alert-container');
        if (!alertContainer) {
            // Create alert container if it doesn't exist
            const container = document.createElement('div');
            container.id = 'alert-container';
            container.className = 'alert-container position-fixed top-0 start-50 translate-middle-x mt-3 z-index-10';
            document.body.appendChild(container);
        }
        
        const alert = document.createElement('div');
        alert.className = 'alert alert-success alert-dismissible fade show';
        alert.innerHTML = `
            <i class="fas fa-check-circle me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        document.getElementById('alert-container').appendChild(alert);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 150);
        }, 5000);
    }
    
    /**
     * Add loading state to a button
     * @param {HTMLButtonElement} button - The button element
     * @param {string} loadingText - Text to display during loading
     */
    function setButtonLoading(button, loadingText = 'Loading...') {
        button.disabled = true;
        button.dataset.originalHtml = button.innerHTML;
        button.innerHTML = `<i class="fas fa-spinner fa-spin me-2"></i>${loadingText}`;
    }
    
    /**
     * Restore button from loading state
     * @param {HTMLButtonElement} button - The button element
     */
    function resetButton(button) {
        button.disabled = false;
        button.innerHTML = button.dataset.originalHtml;
    }
})();
