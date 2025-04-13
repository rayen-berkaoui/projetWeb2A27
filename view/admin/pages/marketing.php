    <?php
    $page_title = 'Marketing';
    $active_menu = 'marketing';

    // Initialize campaigns array


    // Get the last ID for auto-increment
    $lastId = !empty($campaigns) ? max(array_column($campaigns, 'id')) : 0;

    ob_start();
    ?>

    <div class="content">
        <div class="header-actions">
            <h1>
                <span class="icon">📣</span>
                <span>Marketing Campaigns</span>

            </h1>
            <button onclick="openModal()" class="open-modal-btn">
                <span>➕</span> New Campaign
            </button>
        </div>

    </div>

    <!-- Modal -->
    <div id="campaignModal">
        <div class="modal-content">
            <button type="button" class="modal-close" onclick="closeModal()" title="Close">
                <svg width="14" height="14" viewBox="0 0 14 14">
                    <path d="M13 1L1 13M1 1L13 13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
            <h2>Create Campaign</h2>
            <form method="post" action="add_campaign.php" id="campaignForm">
                <div class="form-row">
                    <div class="form-group">
                        <input type="number" name="id" class="id-field" value="<?= $lastId + 1 ?>" readonly placeholder=" ">
                        <label>Campaign ID</label>
                    </div>
                    <div class="form-group">
                        <input type="text" name="nom_compagne" placeholder=" ">
                        <label>Campaign Name</label>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <input type="date" name="date_debut" placeholder=" ">
                        <label>Start Date</label>
                    </div>
                    <div class="form-group">
                        <input type="date" name="date_fin" placeholder=" ">
                        <label>End Date</label>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <input type="text" name="budget" placeholder=" " oninput="validateBudget(this)">
                        <label>Budget ($)</label>
                    </div>
                </div>

                <div class="form-group full-width">
                    <textarea name="description" rows="4" placeholder=" "></textarea>
                    <label>Campaign Description</label>
                </div>

                <div class="form-actions">
                    <button type="button" onclick="closeModal()" class="btn-cancel">Cancel</button>
                    <button type="submit" class="open-modal-btn">Save Campaign</button>
                </div>
            </form>
        </div>
    </div>

    <div id="errorBox" class="error-box" style="display: none;"></div> <!-- Dynamic error message box -->

    <script>
    function editCampaign(id) {
        openModal(true);
        // Add logic to populate form with campaign data
    }

    function deleteCampaign(id) {
        if (confirm('Are you sure you want to delete this campaign?')) {
            // Add delete logic here
        }
    }

    function openModal(editMode = false) {
        const modal = document.getElementById("campaignModal");
        const form = document.getElementById("campaignForm");
        
        if (!editMode) {
            form.reset();
            document.querySelector('input[name="id"]').value = getNextId();
            setMinDates(); // Set minimum dates when opening the modal
            resetInputStyles(); // Reset input styles and messages when opening the modal
        }
        
        modal.style.display = "flex";
    }

    function closeModal() {
        document.getElementById("campaignModal").style.display = "none";
        document.getElementById("campaignForm").reset();
        resetInputStyles(); // Reset input styles and messages when closing the modal
    }

    // Auto-increment function
    function getNextId() {
        const lastId = <?= $lastId ?>;
        return lastId + 1;
    }

    // Set minimum dates for start and end date inputs
    function setMinDates() {
        const today = new Date().toISOString().split('T')[0];
        const startDateInput = document.querySelector('input[name="date_debut"]');
        const endDateInput = document.querySelector('input[name="date_fin"]');
        
        startDateInput.min = today; // Start date must be today or later
        startDateInput.addEventListener('change', () => {
            endDateInput.min = startDateInput.value; // End date must be after start date
        });
    }

    // Validate budget input to allow only numeric values
    function validateBudget(input) {
        input.value = input.value.replace(/[^0-9]/g, ''); // Remove non-numeric characters
    }

    // Validate form inputs and highlight invalid fields with messages
    function validateForm(event) {
        event.preventDefault(); // Prevent form submission for validation
        const campaignName = document.querySelector('input[name="nom_compagne"]');
        const startDate = document.querySelector('input[name="date_debut"]');
        const endDate = document.querySelector('input[name="date_fin"]');
        const budget = document.querySelector('input[name="budget"]');
        let isValid = true;

        // Reset input styles and messages
        resetInputStyles();

        // Validate campaign name
        if (!campaignName.value.trim()) {
            showErrorMessage(campaignName, "This input is required.");
            isValid = false;
        }

        // Validate start date
        if (!startDate.value || new Date(startDate.value) < new Date()) {
            showErrorMessage(startDate, "Start date must be today or later.");
            isValid = false;
        }

        // Validate end date
        if (!endDate.value || new Date(endDate.value) <= new Date(startDate.value)) {
            showErrorMessage(endDate, "End date must be after the start date.");
            isValid = false;
        }

        // Validate budget
        if (!budget.value.trim() || isNaN(budget.value) || budget.value <= 0) {
            showErrorMessage(budget, "Budget must be a positive number.");
            isValid = false;
        }

        return isValid; // Allow form submission if all validations pass
    }

    // Show error message under the input field
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

    // Reset input styles and remove error messages
    function resetInputStyles() {
        const inputs = document.querySelectorAll('#campaignForm input, #campaignForm textarea');
        inputs.forEach(input => {
            input.style.border = "2px solid #e2e8f0"; // Reset to default border style
            const errorMessage = input.parentElement.querySelector(".error-message");
            if (errorMessage) {
                errorMessage.remove(); // Remove error message if it exists
            }
        });
    }

    // Attach form validation to the submit button
    document.getElementById("campaignForm").addEventListener("submit", validateForm);

    // Add responsive handling for sidebar
    function toggleSidebar() {
        document.querySelector('.sidebar').classList.toggle('active');
    }
    </script>

    <?php
    $content = ob_get_clean();
    include_once __DIR__ . '/../layout.php';
    ?>
