<?php
// Set page title and active menu for layout
$page_title = 'Feedback Details';
$active_menu = 'avis';

// In a real MVC setup, this data would come from the controller
// For now, we'll create sample data for display
$feedbacks = $feedbacks ?? [
    'id' => 124,
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'subject' => 'Product Review',
    'message' => 'Great products and excellent service! I ordered the plant starter kit and it arrived in perfect condition. The plants are thriving and your guide was very helpful. Will definitely order again.',
    'rating' => 5,
    'product_id' => 12,
    'product_name' => 'Indoor Plant Starter Kit',
    'user_id' => null,
    'status' => 'approved',
    'is_public' => true,
    'featured' => false,
    'created_at' => '2025-04-12 15:30:00',
    'admin_response' => 'Thank you for your positive feedback! We\'re glad you enjoyed your purchase.',
    'admin_response_by' => 1,
    'admin_response_date' => '2025-04-12 17:45:00'
];

// Get status badge class
$statusClass = [
    'pending' => 'badge-warning',
    'approved' => 'badge-success',
    'rejected' => 'badge-danger'
][$feedbacks['status']] ?? 'badge-secondary';

// History timeline (would come from controller in real app)
$history = [
    [
        'action' => 'Feedback submitted',
        'date' => $feedbacks['created_at'],
        'user' => $feedbacks['name'],
        'details' => 'Initial submission with rating: ' . $feedbacks['rating'] . '/5'
    ]
];

// Add admin response to timeline if exists
if (!empty($feedbacks['admin_response'])) {
    $history[] = [
        'action' => 'Admin response added',
        'date' => $feedbacks['admin_response_date'],
        'user' => 'Administrator',
        'details' => 'Response: ' . substr($feedbacks['admin_response'], 0, 50) . (strlen($feedbacks['admin_response']) > 50 ? '...' : '')
    ];
}

// Add status changes to timeline (in a real app, these would come from a status history table)
if ($feedbacks['status'] !== 'pending') {
    $history[] = [
        'action' => 'Status changed to ' . ucfirst($feedbacks['status']),
        'date' => date('Y-m-d H:i:s', strtotime('-1 day')), // Example date
        'user' => 'Administrator',
        'details' => 'Status updated from pending to ' . $feedbacks['status']
    ];
}

// Sort history by date
usort($history, function($a, $b) {
    return strtotime($a['date']) - strtotime($b['date']);
});

// Start buffering for content
ob_start();
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/GreenMind/public/dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/GreenMind/public/dashboard.php?route=feedback/admin">Feedback</a></li>
        <li class="breadcrumb-item active">Feedback #<?php echo $feedbacks['id']; ?></li>
    </ol>
</nav>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        Feedback Details 
        <span class="badge <?php echo $statusClass; ?>"><?php echo ucfirst($feedbacks['status']); ?></span>
    </h1>
    <div>
        <a href="/GreenMind/public/dashboard.php?route=feedback/admin" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm mr-1"></i> Back to List
        </a>
    </div>
</div>

<div class="row">
    <!-- Feedback Details -->
    <div class="col-lg-8">
        <!-- Main Feedback Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold">
                    <?php echo htmlspecialchars($feedbacks['subject']); ?>
                </h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="feedbackDropdown" data-toggle="dropdown">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteModal">
                            <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i>
                            Delete Feedback
                        </a>
                        <?php if (!$feedbacks['featured']): ?>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-star fa-sm fa-fw mr-2 text-gray-400"></i>
                                Mark as Featured
                            </a>
                        <?php else: ?>
                            <a class="dropdown-item" href="#">
                                <i class="far fa-star fa-sm fa-fw mr-2 text-gray-400"></i>
                                Remove from Featured
                            </a>
                        <?php endif; ?>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#historySection">
                            <i class="fas fa-history fa-sm fa-fw mr-2 text-gray-400"></i>
                            View History
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Customer:</strong> <?php echo htmlspecialchars($feedbacks['name']); ?></p>
                            <p class="mb-1"><strong>Email:</strong> <?php echo htmlspecialchars($feedbacks['email']); ?></p>
                            <p class="mb-1"><strong>Date:</strong> <?php echo date('F j, Y, g:i a', strtotime($feedbacks['created_at'])); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1">
                                <strong>Rating:</strong> 
                                <?php 
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $feedbacks['rating']) {
                                        echo '<i class="fas fa-star text-warning"></i>';
                                    } else {
                                        echo '<i class="far fa-star text-warning"></i>';
                                    }
                                }
                                echo " ({$feedbacks['rating']}/5)";
                                ?>
                            </p>
                            <p class="mb-1">
                                <strong>Product:</strong> 
                                <?php if (!empty($feedbacks['product_name'])): ?>
                                    <a href="/GreenMind/public/dashboard.php?route=products/admin/edit&id=<?php echo $feedbacks['product_id']; ?>">
                                        <?php echo htmlspecialchars($feedbacks['product_name']); ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">N/A</span>
                                <?php endif; ?>
                            </p>
                            <p class="mb-1">
                                <strong>Visibility:</strong> 
                                <?php if ($feedbacks['is_public']): ?>
                                    <span class="badge badge-info">Public</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Private</span>
                                <?php endif; ?>
                                
                                <?php if ($feedbacks['featured']): ?>
                                    <span class="badge badge-warning">Featured</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h6 class="font-weight-bold">Feedback Message:</h6>
                    <div class="border rounded p-3 bg-light">
                        <?php echo nl2br(htmlspecialchars($feedbacks['message'])); ?>
                    </div>
                </div>
                
                <?php if (!empty($feedbacks['admin_response'])): ?>
                <div class="mb-4">
                    <h6 class="font-weight-bold">Admin Response:</h6>
                    <div class="border rounded p-3 bg-white">
                        <p class="mb-1 text-muted small">
                            Responded on <?php echo date('F j, Y, g:i a', strtotime($feedbacks['admin_response_date'])); ?>
                        </p>
                        <?php echo nl2br(htmlspecialchars($feedbacks['admin_response'])); ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Action Buttons -->
                <div class="mt-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <?php if ($feedbacks['status'] === 'pending'): ?>
                                <form method="POST" action="/GreenMind/public/dashboard.php?route=feedback/admin/status" class="d-inline-block">
                                    <input type="hidden" name="id" value="<?php echo $feedbacks['id']; ?>">
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check mr-1"></i> Approve
                                    </button>
                                </form>
                                
                                <form method="POST" action="/GreenMind/public/dashboard.php?route=feedback/admin/status" class="d-inline-block ml-2">
                                    <input type="hidden" name="id" value="<?php echo $feedbacks['id']; ?>">
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-times mr-1"></i> Reject
                                    </button>
                                </form>
                            <?php else: ?>
                                <form method="POST" action="/GreenMind/public/dashboard.php?route=feedback/admin/status" class="d-inline-block">
                                    <input type="hidden" name="id" value="<?php echo $feedbacks['id']; ?>">
                                    <input type="hidden" name="status" value="pending">
                                    <button type="submit" class="btn btn-secondary">
                                        <i class="fas fa-undo mr-1"></i> Reset to Pending
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <form method="POST" action="/GreenMind/public/dashboard.php?route=feedback/admin/visibility" class="d-inline-block">
                                <input type="hidden" name="id" value="<?php echo $feedbacks['id']; ?>">
                                <input type="hidden" name="isPublic" value="<?php echo $feedbacks['is_public'] ? '0' : '1'; ?>">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas <?php echo $feedbacks['is_public'] ? 'fa-eye-slash' : 'fa-eye'; ?> mr-1"></i>
                                    <?php echo $feedbacks['is_public'] ? 'Make Private' : 'Make Public'; ?>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Admin Response Form -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Add or Update Response</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="/GreenMind/public/dashboard.php?route=feedback/admin/response">
                    <input type="hidden" name="id" value="<?php echo $feedbacks['id']; ?>">
                    
                    <div class="form-group">
                        <label for="responseMessage">Response Message</label>
                        <textarea class="form-control" id="responseMessage" name="responseMessage" rows="5"><?php echo htmlspecialchars($feedback['admin_response'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="makePublic" name="makePublic" value="1" <?php echo $feedbacks['is_public'] ? 'checked' : ''; ?>>
                            <label class="custom-control-label" for="makePublic">Make feedback public when adding response</label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane mr-1"></i> 
                        <?php echo empty($feedbacks['admin_response']) ? 'Send Response' : 'Update Response'; ?>
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Feedback History Timeline -->
        <div class="card shadow mb-4" id="historySection">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Feedback History</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <?php foreach ($history as $index => $event): ?>
                    <div class="timeline-item">
                        <div class="timeline-item-marker">
                            <div class="timeline-item-marker-text">
                                <?php echo date('M d', strtotime($event['date'])); ?>
                            </div>
                            <div class="timeline-item-marker-indicator bg-primary"></div>
                        </div>
                        <div class="timeline-item-content">
                            <div class="font-weight-bold"><?php echo htmlspecialchars($event['action']); ?></div>
                            <p class="mb-0 text-muted small">
                                By <?php echo htmlspecialchars($event['admin'] ?? 'Inconnu'); ?>

<?php require_once __DIR__ . '/../../admin/partials/sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mb-4">
                <!-- Action buttons -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="index.php?route=feedback/admin" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                    <div>
                        <?php if ($feedbacks['status'] === 'pending'): ?>
                            <form action="index.php?route=feedback/admin/<?= $feedbacks['_id'] ?>/status" method="POST" class="d-inline">
                                <input type="hidden" name="_method" value="PUT">
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="btn btn-success me-2" onclick="return confirm('Approve this feedback?')">
                                    <i class="fas fa-check me-2"></i>Approve
                                </button>
                            </form>
                            <form action="index.php?route=feedback/admin/<?= $feedbacks['_id'] ?>/status" method="POST" class="d-inline">
                                <input type="hidden" name="_method" value="PUT">
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="btn btn-warning me-2" onclick="return confirm('Reject this feedback?')">
                                    <i class="fas fa-times me-2"></i>Reject
                                </button>
                            </form>
                        <?php endif; ?>
                        <form action="index.php?route=feedback/admin/<?= $feedbacks['_id'] ?>" method="POST" class="d-inline">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this feedback? This action cannot be undone.')">
                                <i class="fas fa-trash me-2"></i>Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Left column - Feedback information -->
            <div class="col-lg-4">
                <!-- Status card -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Status Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Current Status</label>
                            <?php
                            $statusClass = [
                                'pending' => 'bg-warning',
                                'approved' => 'bg-success',
                                'rejected' => 'bg-danger'
                            ];
                            $statusClass = $statusClass[$feedbacks['status']] ?? 'bg-secondary';
                            ?>
                            <div>
                                <span class="badge <?= $statusClass ?> fs-6"><?= ucfirst($feedbacks['status']) ?></span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Visibility</label>
                            <div>
                                <span class="badge <?= $feedbacks['isPublic'] ? 'bg-success' : 'bg-secondary' ?> fs-6">
                                    <?= $feedbacks['isPublic'] ? 'Public' : 'Private' ?>
                                </span>
                                <span class="badge <?= $feedbacks['featured'] ? 'bg-primary' : 'bg-secondary' ?> fs-6 ms-2">
                                    <?= $feedbacks['featured'] ? 'Featured' : 'Not Featured' ?>
                                </span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Date Submitted</label>
                            <div>
                                <span class="fw-bold"><?= date('F j, Y, g:i a', strtotime($feedbacks['createdAt'])) ?></span>
                            </div>
                        </div>

                        <!-- Update Status Form -->
                        <form action="index.php?route=feedback/admin/<?= $feedbacks['_id'] ?>/status" method="POST">
                            <input type="hidden" name="_method" value="PUT">
                            <div class="mb-3">
                                <label for="status" class="form-label">Change Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="pending" <?= $feedbacks['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="approved" <?= $feedbacks['status'] === 'approved' ? 'selected' : '' ?>>Approved</option>
                                    <option value="rejected" <?= $feedbacks['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                </select>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="isPublic" name="isPublic" value="true" <?= $feedbacks['isPublic'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="isPublic">Make public</label>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="featured" name="featured" value="true" <?= $feedbacks['featured'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="featured">Feature this feedback</label>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Update Status</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Customer Information -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Customer Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <div>
                                <span class="fw-bold"><?= htmlspecialchars($feedbacks['name']) ?></span>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <div>
                                <a href="mailto:<?= htmlspecialchars($feedbacks['email']) ?>" class="text-primary">
                                    <?= htmlspecialchars($feedbacks['email']) ?>
                                </a>
                            </div>
                        </div>
                        
                        <?php if (isset($feedbacks['user']) && $feedbacks['user']): ?>
                            <div class="mb-3">
                                <label class="form-label">User Account</label>
                                <div>
                                    <span class="badge bg-info">Registered User</span>
                                </div>
                                <?php if (isset($feedbacks['user']['_id'])): ?>
                                    <div class="mt-2">
                                        <a href="index.php?route=users/admin/<?= $feedbacks['user']['_id'] ?>" class="btn btn-sm btn-outline-primary">
                                            View User Profile
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($feedbacks['product']) && $feedbacks['product']): ?>
                            <div class="mb-3">
                                <label class="form-label">Product</label>
                                <div>
                                    <span class="fw-bold"><?= htmlspecialchars($feedbacks['productName'] ?? 'Unknown Product') ?></span>
                                </div>
                                <div class="mt-2">
                                    <a href="index.php?route=products/admin/<?= $feedbacks['product'] ?>" class="btn btn-sm btn-outline-primary">
                                        View Product
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Right column - Feedback content and response -->
            <div class="col-lg-8">
                <!-- Feedback content -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Feedback Details</h5>
                        <div class="d-flex">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?= $i <= $feedbacks['rating'] ? 'text-warning' : 'text-white' ?> ms-1"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <h4 class="card-title"><?= htmlspecialchars($feedbacks['subject']) ?></h4>
                        <div class="card-text mt-4 mb-4">
                            <?= nl2br(htmlspecialchars($feedbacks['message'])) ?>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Submitted on <?= date('F j, Y, g:i a', strtotime($feedbacks['createdAt'])) ?></span>
                            <span class="fw-bold">Rating: <?= $feedbacks['rating'] ?>/5</span>
                        </div>
                    </div>
                </div>
                
                <!-- Admin Response -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Admin Response</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($feedbacks['adminResponse']) && !empty($feedbacks['adminResponse']['message'])): ?>
                            <!-- Existing response -->
                            <div class="alert alert-light mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <strong>Response by:</strong> 
                                        <?= htmlspecialchars($feedbacks['adminResponse']['respondentName'] ?? 'Admin') ?>
                                    </div>
                                    <small class="text-muted">
                                        <?= date('F j, Y, g:i a', strtotime($feedbacks['adminResponse']['responseDate'])) ?>
                                    </small>
                                </div>
                                <hr>
                                <div class="mt-3">
                                    <?= nl2br(htmlspecialchars($feedbacks['adminResponse']['message'])) ?>
                                </div>
                            </div>
                            
                            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="collapse" data-bs-target="#editResponseForm">
                                <i class="fas fa-edit me-2"></i>Edit Response
                            </button>
                            
                            <div class="collapse" id="editResponseForm">
                                <div class="card card-body bg-light">
                                    <form action="index.php?route=feedback/admin/<?= $feedbacks['_id'] ?>/respond" method="POST">
                                        <div class="mb-3">
                                            <label for="responseMessage" class="form-label">Update Response</label>
                                            <textarea class="form-control" id="responseMessage" name="responseMessage" rows="5" required><?= htmlspecialchars($feedbacks['adminResponse']['message']) ?></textarea>
                                        </div>
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary">Update Response</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php else: ?>
                            <!-- New response form -->
                            <form action="index.php?route=feedback/admin/<?= $feedbacks['_id'] ?>/respond" method="POST">
                                <div class="mb-3">
                                    <label for="responseMessage" class="form-label">Respond to this feedback</label>
                                    <textarea class="form-control" id="responseMessage" name="responseMessage" rows="5" placeholder="Type your response here..." required></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="notifyCustomer" name="notifyCustomer" value="1" checked>
                                        <label class="form-check-label" for="notifyCustomer">
                                            Notify customer by email
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="makePublic" name="makePublic" value="1" <?= $feedbacks['isPublic'] ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="makePublic">
                                            Make feedback public after responding
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane me-2"></i>Send Response
                                    </button>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for form handling -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle form validation
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
        
        // Confirm deletion
        const deleteForm = document.querySelector('form[method="DELETE"]');
        if (deleteForm) {
            deleteForm.addEventListener('submit', event => {
                if (!confirm('Are you sure you want to delete this feedback? This action cannot be undone.')) {
                    event.preventDefault();
                }
            });
        }
    });
</script>

<?php require_once __DIR__ . '/../../admin/partials/sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Feedback Details Card -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Feedback Details</h4>
                        <div>
                            <a href="index.php?route=feedback/admin" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Status and Actions -->
                        <div class="mb-4 p-3 bg-light rounded d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">Status</h5>
                                <?php
                                $statusClass = [
                                    'pending' => 'bg-warning',
                                    'approved' => 'bg-success',
                                    'rejected' => 'bg-danger'
                                ];
                                $statusClass = $statusClass[$feedbacks['status']] ?? 'bg-secondary';
                                ?>
                                <span class="badge <?= $statusClass ?> fs-6"><?= ucfirst($feedbacks['status']) ?></span>
                            </div>
                            <div>
                                <!-- Status Management Buttons -->
                                <?php if ($feedbacks['status'] === 'pending'): ?>
                                    <form action="index.php?route=feedback/admin/<?= $feedbacks['_id'] ?>/status" method="POST" class="d-inline">
                                        <input type="hidden" name="_method" value="PUT">
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="btn btn-success" onclick="return confirm('Approve this feedback?')">
                                            <i class="fas fa-check me-1"></i> Approve
                                        </button>
                                    </form>
                                    <form action="index.php?route=feedback/admin/<?= $feedbacks['_id'] ?>/status" method="POST" class="d-inline">
                                        <input type="hidden" name="_method" value="PUT">
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Reject this feedback?')">
                                            <i class="fas fa-times me-1"></i> Reject
                                        </button>
                                    </form>
                                <?php endif; ?>
                                
                                <!-- Featured & Public Controls -->
                                <form action="index.php?route=feedback/admin/<?= $feedbacks['_id'] ?>/status" method="POST" class="d-inline mt-2 mt-md-0">
                                    <input type="hidden" name="_method" value="PUT">
                                    <input type="hidden" name="isPublic" value="<?= $feedbacks['isPublic'] ? 'false' : 'true' ?>">
                                    <button type="submit" class="btn btn-<?= $feedbacks['isPublic'] ? 'outline-info' : 'info' ?>">
                                        <i class="fas fa-<?= $feedbacks['isPublic'] ? 'eye-slash' : 'eye' ?> me-1"></i> <?= $feedback['isPublic'] ? 'Make Private' : 'Make Public' ?>
                                    </button>
                                </form>
                                
                                <form action="index.php?route=feedback/admin/<?= $feedbacks['_id'] ?>/status" method="POST" class="d-inline mt-2 mt-md-0">
                                    <input type="hidden" name="_method" value="PUT">
                                    <input type="hidden" name="featured" value="<?= $feedbacks['featured'] ? 'false' : 'true' ?>">
                                    <button type="submit" class="btn btn-<?= $feedbacks['featured'] ? 'outline-warning' : 'warning' ?>">
                                        <i class="fas fa-star me-1"></i> <?= $feedbacks['featured'] ? 'Unfeature' : 'Feature' ?>
                                    </button>
                                </form>
                                
                                <form action="index.php?route=feedback/admin/<?= $feedbacks['_id'] ?>" method="POST" class="d-inline mt-2 mt-md-0">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this feedback? This action cannot be undone.')">
                                        <i class="fas fa-trash me-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Customer Information -->
                            <div class="col-md-4">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h5 class="mb-0"><i class="fas fa-user me-2"></i>Customer Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-1"><strong>Name:</strong> <?= htmlspecialchars($feedbacks['name']) ?></p>
                                        <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($feedbacks['email']) ?></p>
                                        <p class="mb-0"><strong>Submitted On:</strong> <?= date('F j, Y, g:i a', strtotime($feedbacks['createdAt'])) ?></p>
                                        
                                        <?php if (!empty($feedbacks['user'])): ?>
                                            <hr>
                                            <p class="mb-0">
                                                <span class="badge bg-info">
                                                    <i class="fas fa-user-check me-1"></i> Registered User
                                                </span>
                                                <?php if (isset($feedbacks['user']['_id'])): ?>
                                                    <a href="index.php?route=users/show/<?= $feedbacks['user']['_id'] ?>" class="btn btn-sm btn-outline-primary mt-2">
                                                        View User Profile
                                                    </a>
                                                <?php endif; ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <!-- Product Information (if applicable) -->
                                <?php if (!empty($feedbacks['product'])): ?>
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h5 class="mb-0"><i class="fas fa-tag me-2"></i>Product Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-0"><strong>Product:</strong> <?= htmlspecialchars($feedbacks['productName']) ?></p>
                                        <a href="index.php?route=products/show/<?= $feedbacks['product'] ?>" class="btn btn-sm btn-outline-primary mt-2">
                                            View Product
                                        </a>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <!-- Rating -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0"><i class="fas fa-star me-2"></i>Rating</h5>
                                    </div>
                                    <div class="card-body text-center">
                                        <div class="display-6 mb-2">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-star <?= $i <= $feedbacks['rating'] ? 'text-warning' : 'text-muted' ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <p class="lead mb-0"><?= $feedbacks['rating'] ?> out of 5</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Feedback Content -->
                            <div class="col-md-8">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="mb-0"><i class="fas fa-comment-alt me-2"></i>Feedback Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <h4 class="card-title"><?= htmlspecialchars($feedbacks['subject']) ?></h4>
                                        <div class="card-text mb-4">
                                            <?= nl2br(htmlspecialchars($feedbacks['message'])) ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Admin Response -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="mb-0"><i class="fas fa-reply me-2"></i>Admin Response</h5>
                                    </div>
                                    <div class="card-body">
                                        <?php if (!empty($feedbacks['adminResponse']) && !empty($feedbacks['adminResponse']['message'])): ?>
                                            <div class="mb-3 p-3 bg-light rounded">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <strong>
                                                        <?= htmlspecialchars($feedbacks['adminResponse']['respondentName'] ?? 'Admin') ?>
                                                    </strong>
                                                    <small class="text-muted">
                                                        <?= date('F j, Y, g:i a', strtotime($feedbacks['adminResponse']['responseDate'])) ?>
                                                    </small>
                                                </div>
                                                <p class="mb-0"><?= nl2br(htmlspecialchars($feedbacks['adminResponse']['message'])) ?></p>
                                            </div>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#editResponseForm">
                                                <i class="fas fa-edit me-1"></i> Edit Response
                                            </button>
                                            <div class="collapse mt-3" id="editResponseForm">
                                                <form action="index.php?route=feedback/admin/<?= $feedbacks['_id'] ?>/respond" method="POST">
                                                    <div class="form-group mb-3">
                                                        <label for="responseMessage">Update Response</label>
                                                        <textarea class="form-control" id="responseMessage" name="responseMessage" rows="4" required><?= htmlspecialchars($feedbacks['adminResponse']['message']) ?></textarea>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Update Response</button>
                                                </form>
                                            </div>
                                        <?php else: ?>
                                            <form action="index.php?route=feedback/admin/<?= $feedbacks['_id'] ?>/respond" method="POST">
                                                <div class="form-group mb-3">
                                                    <label for="responseMessage">Add Response</label>
                                                    <textarea class="form-control" id="responseMessage" name="responseMessage" rows="4" placeholder="Enter your response to this feedback..." required></textarea>
                                                </div>
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" id="makePublic" name="makePublic" value="1" <?= $feedbacks['isPublic'] ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="makePublic">
                                                        Make feedback public after responding
                                                    </label>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Submit Response</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

