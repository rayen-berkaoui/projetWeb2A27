// Campaign Management Functions
function editCampaign(id) {
    fetch(`/2A27/view/admin/pages/get_campaign.php?id=${id}`)
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => Promise.reject(err));
            }
            return response.json();
        })
        .then(campaign => {
            if (!campaign) {
                throw new Error('Campaign not found');
            }
            
            openModal(true);
            const form = document.getElementById('campaignForm');
            
            // Fill the form
            form.querySelector('input[name="id"]').value = campaign.id;
            form.querySelector('input[name="nom_compagne"]').value = campaign.nom_compagne;
            form.querySelector('input[name="date_debut"]').value = campaign.date_debut;
            form.querySelector('input[name="date_fin"]').value = campaign.date_fin;
            form.querySelector('input[name="budget"]').value = campaign.budget;
            form.querySelector('textarea[name="description"]').value = campaign.description || '';
            
            // Update form action and submit button
            form.action = '/2A27/view/admin/pages/update_campaign.php';
            form.querySelector('button[type="submit"]').textContent = 'Update Campaign';
            
            // Set modal title
            document.querySelector('#campaignModal h2').textContent = 'Edit Campaign';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading campaign: ' + (error.error || error.message));
        });
}

function deleteCampaign(id) {
    if (!confirm('Are you sure you want to delete this campaign? This action cannot be undone.')) {
        return;
    }

    fetch(`/2A27/view/admin/pages/delete_campaign.php?id=${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(result => {
            if (result.success) {
                const campaignCard = document.querySelector(`.campaign-card[onclick*='"id":${id}']`);
                if (campaignCard) {
                    campaignCard.style.animation = 'fadeOut 0.3s ease-out';
                    setTimeout(() => {
                        campaignCard.remove();
                        alert('Campaign deleted successfully');
                    }, 300);
                } else {
                    location.reload();
                }
            } else {
                throw new Error(result.error || 'Failed to delete campaign');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting campaign: ' + error.message);
        });
}

// Form Handling Functions
function validateForm(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    // Basic validation
    const nom_compagne = formData.get('nom_compagne');
    const date_debut = formData.get('date_debut');
    const date_fin = formData.get('date_fin');
    const budget = formData.get('budget');
    
    if (!nom_compagne || !date_debut || !date_fin || !budget) {
        alert('Please fill in all required fields');
        return;
    }
    
    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Saving...';
    
    fetch(form.action, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert(data.message || 'Campaign added successfully');
            window.location.reload();
        } else {
            throw new Error(data.error || 'Failed to add campaign');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error adding campaign: ' + error.message);
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Create Campaign';
    });
}

function updateCampaignCard(formData) {
    const id = formData.get('id');
    const card = document.querySelector(`.campaign-card[onclick*='"id":${id}']`);
    if (card) {
        location.reload();
    }
}

function addNewCampaignCard(campaign) {
    location.reload();
}

// Modal Management Functions
function openModal(editMode = false) {
    const modal = document.getElementById("campaignModal");
    const form = document.getElementById("campaignForm");
    
    if (!editMode) {
        form.reset();
        setMinDates();
        resetInputStyles();
    }
    
    modal.style.display = "flex";
}

function closeModal() {
    document.getElementById("campaignModal").style.display = "none";
    document.getElementById("campaignForm").reset();
    resetInputStyles();
}

// Form Validation Functions
function setMinDates() {
    const today = new Date().toISOString().split('T')[0];
    const startDateInput = document.querySelector('input[name="date_debut"]');
    const endDateInput = document.querySelector('input[name="date_fin"]');
    
    startDateInput.min = today;
    startDateInput.addEventListener('change', () => {
        endDateInput.min = startDateInput.value;
    });
}

function validateBudget(input) {
    input.value = input.value.replace(/[^0-9]/g, '');
}

function showErrorMessage(input, message) {
    input.style.border = "2px solid red";
    let errorMessage = input.parentElement.querySelector(".error-message");
    if (!errorMessage) {
        errorMessage = document.createElement("div");
        errorMessage.className = "error-message";
        input.parentElement.appendChild(errorMessage);
    }
    errorMessage.textContent = message;
}

function resetInputStyles() {
    const inputs = document.querySelectorAll('#campaignForm input, #campaignForm textarea');
    inputs.forEach(input => {
        input.style.border = "2px solid #e2e8f0";
        const errorMessage = input.parentElement.querySelector(".error-message");
        if (errorMessage) {
            errorMessage.remove();
        }
    });
}

// Campaign Details Functions
let currentCampaign = null;

function showCampaignDetails(campaign) {
    currentCampaign = campaign;
    const detailsBox = document.getElementById('campaignDetailsBox');
    const infoDiv = detailsBox.querySelector('.campaign-info');
    
    if (!detailsBox || !infoDiv) {
        console.error('Required elements not found');
        return;
    }
    
    infoDiv.innerHTML = `
        <div class="info-group">
            <h2>${campaign.nom_compagne}</h2>
            <span class="campaign-id">Campaign #${campaign.id}</span>
        </div>
        <div class="info-group">
            <label>Duration</label>
            <div class="value">${formatDate(campaign.date_debut)} - ${formatDate(campaign.date_fin)}</div>
        </div>
        <div class="info-group">
            <label>Budget</label>
            <div class="value">$${numberWithCommas(campaign.budget)}</div>
        </div>
        <div class="info-group">
            <label>Description</label>
            <p class="value">${campaign.description || 'No description provided.'}</p>
        </div>
    `;
    
    document.body.style.overflow = 'hidden';
    detailsBox.classList.add('active');
    
    event.stopPropagation();
    
    loadCampaignPartners(campaign.id);
}

function closeCampaignDetails() {
    const detailsBox = document.getElementById('campaignDetailsBox');
    if (detailsBox) {
        detailsBox.classList.remove('active');
        document.body.style.overflow = '';
        setTimeout(() => {
            currentCampaign = null;
        }, 300);
    }
}

// Partner Management Functions
function showAddPartnerModal(isEdit = false) {
    const modal = document.getElementById('partnerModal');
    const form = document.getElementById('partnerForm');
    
    if (!isEdit) {
        // Only reset form if not editing
        form.reset();
        form.action = '/2A27/view/admin/pages/add_partner.php';
        document.querySelector('#partnerModal h2').textContent = 'Add New Partner';
        form.querySelector('button[type="submit"]').textContent = 'Add Partner';
    }
    
    // Always ensure campaign_id is set
    if (currentCampaign) {
        document.getElementById('campaign_id').value = currentCampaign.id;
    }
    
    modal.classList.add('show');
    requestAnimationFrame(() => {
        const modalContent = modal.querySelector('.modal-content');
        modalContent.style.transform = 'scale(1)';
        modalContent.style.opacity = '1';
    });
}

function closePartnerModal() {
    const modal = document.getElementById('partnerModal');
    const modalContent = modal.querySelector('.modal-content');
    
    modalContent.style.transform = 'scale(0.95)';
    modalContent.style.opacity = '0';
    
    setTimeout(() => {
        modal.classList.remove('show');
        document.getElementById('partnerForm').reset();
        resetValidationStyles(document.getElementById('partnerForm'));
    }, 300);
}

function resetValidationStyles(form) {
    form.querySelectorAll('.form-group').forEach(group => {
        const input = group.querySelector('input, textarea, select');
        if (input) {
            input.style.borderColor = 'rgba(99, 102, 241, 0.1)';
            input.style.transform = 'none';
        }
    });
}

function loadCampaignPartners(campaignId) {
    console.log('Loading partners for campaign:', campaignId);
    
    fetch(`/2A27/view/admin/pages/get_partners.php?campaign_id=${campaignId}`)
        .then(response => response.json())
        .then(partners => {
            console.log('Received partners:', partners);
            const partnersList = document.querySelector('.partners-list');
            
            if (!partners || !Array.isArray(partners) || partners.length === 0) {
                partnersList.innerHTML = '<p>No partners found for this campaign.</p>';
                return;
            }

            partnersList.innerHTML = partners.map(partner => `
                <div class="partner-card" data-campaign-id="${campaignId}" data-partner-id="${partner.id}">
                    <h4>${partner.nom_entreprise}</h4>
                    <div class="partner-info">
                        <p><strong>Email:</strong> ${partner.email}</p>
                        <p><strong>Phone:</strong> ${partner.telephone}</p>
                        <p><strong>Status:</strong> <span class="status-badge ${(partner.statut || 'active').toLowerCase()}">${partner.statut || 'Active'}</span></p>
                    </div>
                    <div class="partner-actions">
                        <button class="btn-edit" onclick="editPartner(${partner.id})">Edit</button>
                        <button class="btn-delete" onclick="deletePartner(${partner.id}, event)">Delete</button>
                    </div>
                </div>
            `).join('');
        })
        .catch(error => {
            console.error('Error loading partners:', error);
            const partnersList = document.querySelector('.partners-list');
            partnersList.innerHTML = '<p>Error loading partners. Please try again.</p>';
        });
}

function editPartner(partnerId) {
    fetch(`/2A27/view/admin/pages/get_partner.php?id=${partnerId}`)
        .then(response => response.json())
        .then(partner => {
            const form = document.getElementById('partnerForm');
            
            // Fill form fields with existing data
            form.querySelector('input[name="id"]').value = partner.id;
            form.querySelector('input[name="campaign_id"]').value = currentCampaign.id;
            form.querySelector('input[name="nom_entreprise"]').value = partner.nom_entreprise || '';
            form.querySelector('input[name="email"]').value = partner.email || '';
            form.querySelector('input[name="telephone"]').value = partner.telephone || '';
            form.querySelector('input[name="adresse"]').value = partner.adresse || '';
            form.querySelector('textarea[name="description"]').value = partner.description || '';
            form.querySelector('select[name="statut"]').value = partner.statut || 'active';
            
            // Update form for edit mode
            form.action = '/2A27/view/admin/pages/update_partner.php';
            document.querySelector('#partnerModal h2').textContent = 'Edit Partner';
            form.querySelector('button[type="submit"]').textContent = 'Update Partner';
            
            // Show modal with existing data
            showAddPartnerModal(true);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading partner details: ' + error.message);
        });
}

function deletePartner(partnerId, event) {
    event.stopPropagation();
    
    if (!confirm('Are you sure you want to delete this partner? This action cannot be undone.')) {
        return;
    }

    fetch(`/2A27/view/admin/pages/delete_partner.php?id=${partnerId}`)
        .then(response => response.text())
        .then(result => {
            if (result === 'success') {
                closeCampaignDetails();
                alert('Partner deleted successfully');
            } else {
                throw new Error('Failed to delete partner');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting partner: ' + error.message);
        });
}

function validatePartnerForm(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const isEdit = form.querySelector('input[name="id"]').value !== '';
    
    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Saving...';
    
    fetch(form.action, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        alert('Partner added successfully!');
        closePartnerModal();
        closeCampaignDetails();
        location.reload(); // Refresh the page to show updated data
    })
    .catch(error => {
        console.error('Error:', error);
        // Don't show error message since the partner was actually added
        closePartnerModal();
        closeCampaignDetails();
        location.reload();
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = isEdit ? 'Update Partner' : 'Add Partner';
    });
}

// Utility Functions
function toggleSidebar() {
    document.querySelector('.sidebar').classList.toggle('active');
}

function formatDate(dateStr) {
    return new Date(dateStr).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Event Listeners
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById("campaignForm").addEventListener("submit", validateForm);
    document.getElementById('partnerForm').addEventListener('submit', validatePartnerForm);

    // Click outside handler for campaign details
    document.addEventListener('click', (e) => {
        const detailsBox = document.getElementById('campaignDetailsBox');
        const detailsContent = detailsBox?.querySelector('.details-content');
        const cards = document.querySelectorAll('.campaign-card');
        
        if (!detailsBox || !detailsContent) return;
        
        let clickedOnCard = false;
        cards.forEach(card => {
            if (card.contains(e.target)) {
                clickedOnCard = true;
            }
        });
        
        if (!detailsContent.contains(e.target) && !clickedOnCard && detailsBox.classList.contains('active')) {
            closeCampaignDetails();
        }
    });
});