<?php
$page_title = 'Marketing';
$active_menu = 'marketing';

ob_start();
?>
<link rel="stylesheet" href="/2A27/view/assets/css/marketing.css">
<!-- Main Content Area -->
<main class="content-area">
    <!-- Top Header Bar -->
    <header class="top-header">
        <div class="header-left">
            <h1><?php echo htmlspecialchars($page_title); ?></h1>
        </div>
        <div class="header-right">
            <div class="user-menu">
                <span class="user-avatar">👤</span>
                <span class="user-name">Admin</span>
            </div>
        </div>
    </header>

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

        <!-- Campaigns Grid -->
        <?php if (!empty($campaigns)): ?>
        <div class="campaigns-grid">
            <?php foreach ($campaigns as $c): ?>
                <div class="campaign-card" onclick="showCampaignDetails(<?= htmlspecialchars(json_encode($c)) ?>)">
                    <div class="campaign-status-bar">
                        <?php 
                        $today = new DateTime();
                        $start = new DateTime($c['date_debut']);
                        $end = new DateTime($c['date_fin']);
                        $status = ($today < $start) ? 'upcoming' : ($today > $end ? 'ended' : 'active');
                        ?>
                        <span class="status-badge <?= $status ?>"><?= ucfirst($status) ?></span>
                        <span class="campaign-id">#<?= htmlspecialchars($c['id']) ?></span>
                    </div>
                    <div class="campaign-header">
                        <h3><?= htmlspecialchars($c['nom_compagne']) ?></h3>
                    </div>
                    <div class="campaign-progress">
                        <?php
                        $totalDays = $start->diff($end)->days;
                        $elapsedDays = $today->diff($start)->days;
                        $progress = min(100, max(0, ($elapsedDays / $totalDays) * 100));
                        ?>
                        <div class="progress-bar">
                            <div class="progress" style="width: <?= $progress ?>%"></div>
                        </div>
                        <span class="progress-text"><?= round($progress) ?>% Complete</span>
                    </div>
                    <div class="campaign-dates">
                        <div class="date-item">
                            <i class="far fa-calendar-start"></i>
                            <div class="date-info">
                                <span class="label">Starts</span>
                                <span class="value"><?= date('M d, Y', strtotime($c['date_debut'])) ?></span>
                            </div>
                        </div>
                        <div class="date-item">
                            <i class="far fa-calendar-end"></i>
                            <div class="date-info">
                                <span class="label">Ends</span>
                                <span class="value"><?= date('M d, Y', strtotime($c['date_fin'])) ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="campaign-budget">
                        <div class="budget-info">
                            <span class="budget-label">Budget Allocated</span>
                            <span class="budget-value">$<?= number_format($c['budget']) ?></span>
                        </div>
                        <div class="budget-icon">💰</div>
                    </div>
                    <div class="campaign-description">
                        <p><?= substr(htmlspecialchars($c['description']), 0, 100) ?>...</p>
                    </div>
                    <div class="campaign-footer">
                        <div class="card-actions">
                            <button class="view-details">View Details</button>
                            <button class="btn-edit" onclick="event.stopPropagation(); editCampaign(<?= $c['id'] ?>)">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                                Edit
                            </button>
                            <button class="btn-delete" onclick="event.stopPropagation(); deleteCampaign(<?= $c['id'] ?>)">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 6h18"/>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/>
                                    <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                </svg>
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?> 
        </div>
        <?php else: ?>
        <div class="no-campaigns">
            <p>No campaigns found. Create your first campaign!</p>
        </div>
        <?php endif; ?>
    </div>
</main>

<!-- Update Campaign Details Box -->
<div id="campaignDetailsBox" class="campaign-details-box">
    <div class="details-backdrop"></div>
    <div class="details-content">
        <button type="button" class="close-details" onclick="closeCampaignDetails()" title="Close">
            <svg width="14" height="14" viewBox="0 0 14 14">
                <path d="M13 1L1 13M1 1L13 13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </button>
        <div class="campaign-info">
            <!-- Will be populated dynamically -->
        </div>
        <div class="campaign-partners">
            <h3>Partners</h3>
            <div class="partners-list">
                <!-- Will be populated dynamically -->
            </div>
            <button onclick="showAddPartnerModal()" class="add-partner-btn">
                <span>➕</span> Add Partner
            </button>
        </div>
    </div>
</div>

<!-- Add/Edit Partner Modal -->
<div id="partnerModal" class="modal glassmorphism">
    <div class="modal-content partner-modal">
        <button type="button" class="modal-close" onclick="closePartnerModal()" title="Close">
            <svg width="14" height="14" viewBox="0 0 14 14">
                <path d="M13 1L1 13M1 1L13 13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </button>
        <h2>Add New Partner</h2>
        <form method="post" action="/2A27/view/admin/pages/add_partner.php" id="partnerForm">
            <input type="hidden" name="id">
            <input type="hidden" name="campaign_id" id="campaign_id">
            <div class="form-group">
                <input type="text" name="nom_entreprise" placeholder=" " >
                <label>Company Name</label>
            </div>
            <div class="form-group">
                <input type="email" name="email" placeholder=" " >
                <label>Email</label>
            </div>
            <div class="form-group">
                <input type="tel" name="telephone" placeholder=" " >
                <label>Phone Number</label>
            </div>
            <div class="form-group">
                <input type="text" name="adresse" placeholder=" " >
                <label>Address</label>
            </div>
            <div class="form-group full-width">
                <textarea name="description" placeholder=" " rows="3"></textarea>
                <label>Description</label>
            </div>
            <div class="form-group">
                <select name="statut" >
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <label>Status</label>
            </div>
            <div class="form-actions">
                <button type="button" class="btn-cancel" onclick="closePartnerModal()">Cancel</button>
                <button type="submit" class="btn-submit">Add Partner</button>
            </div>
        </form>
    </div>
</div>

<div id="errorBox" class="error-box" style="display: none;"></div>

<!-- Create Campaign Modal -->
<div id="campaignModal" class="modal glassmorphism">
    <div class="modal-content">
        <button type="button" class="modal-close" onclick="closeModal()" title="Close">
            <svg width="14" height="14" viewBox="0 0 14 14">
                <path d="M13 1L1 13M1 1L13 13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </button>
        <h2>Create Campaign</h2>
        <form method="post" action="/2A27/view/admin/pages/add_campaign.php" id="campaignForm">
            <input type="hidden" name="id">
            <div class="form-row">
                <div class="form-group">
                    <input type="text" name="nom_compagne" placeholder=" " >
                    <label>Campaign Name</label>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <input type="date" name="date_debut" placeholder=" " >
                    <label>Start Date</label>
                </div>
                <div class="form-group">
                    <input type="date" name="date_fin" placeholder=" " >
                    <label>End Date</label>
                </div>
            </div>
            <div class="form-group">
                <input type="number" name="budget" placeholder=" "  onkeyup="validateBudget(this)">
                <label>Budget</label>
            </div>
            <div class="form-group full-width">
                <textarea name="description" placeholder=" " rows="3"></textarea>
                <label>Description</label>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn-submit">Create Campaign</button>
            </div>
        </form>
    </div>
</div>

<script src="/2A27/view/assets/js/marketing.js"></script>

<?php
$content = ob_get_clean();
include_once __DIR__ . '/../layout.php';
?>
