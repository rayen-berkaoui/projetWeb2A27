<?php $page_title = 'Marketing'; ?>

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
            <?php foreach ($campaigns as $campaign): ?>
                <?php include 'partials/campaign-card.php'; ?>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="no-campaigns">
            <p>No campaigns found. Create your first campaign!</p>
        </div>
    <?php endif; ?>
</div>

<?php 
include 'partials/campaign-modal.php';
include 'partials/partner-modal.php';
?>
