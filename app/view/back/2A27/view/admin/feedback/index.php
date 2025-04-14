<?php
// Set page title and active menu for layout
$page_title = 'Feedback Management';
$active_menu = 'avis';

// In a real MVC setup, this data would come from the controller
// For now, we'll create some sample data
$feedbacks = $feedbackList ?? [
    [
        'id' => 124,
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'subject' => 'Product Review',
        'rating' => 5,
        'message' => 'Great products and excellent service!',
        'status' => 'approved',
        'created_at' => '2025-04-12 15:30:00'
    ],
    [
        'id' => 123,
        'name' => 'Jane Smith',
        'email' => 'jane@example.com',
        'subject' => 'Shipping Feedback',
        'rating' => 4,
        'message' => 'Very good quality, but shipping took longer than expected.',
        'status' => 'approved',
        'created_at' => '2025-04-11 10:15:00'
    ],
    [
        'id' => 122,
        'name' => 'Alex Johnson',
        'email' => 'alex@example.com',
        'subject' => 'Product Quality',
        'rating' => 3,
        'message' => 'Product is okay, but could be better.',
        'status' => 'pending',
        'created_at' => '2025-04-10 09:45:00'
    ]
];

// Start buffering for content
ob_start();
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Feedback Management</h1>
    <div>
        <a href="#" class="btn btn-sm btn-success shadow-sm">
            <i class="fas fa-download fa-sm text-white-50 mr-1"></i> Export CSV
        </a>
    </div>
</div>

<!-- Feedback Filters -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold">Filters</h6>
        <div class="dropdown no-arrow">
            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                <a class="dropdown-item" href="#">Reset Filters</a>
                <a class="dropdown-item" href="#">Save Filter</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="">
            <input type="hidden" name="route" value="feedback/admin">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="rating">Rating</label>
                    <select class="form-control" id="rating" name="rating">
                        <option value="">All Ratings</option>
                        <option value="5">5 Stars</option>
                        <option value="4">4 Stars</option>
                        <option value="3">3 Stars</option>
                        <option value="2">2 Stars</option>
                        <option value="1">1 Star</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="search">Search</label>
                    <input type="text" class="form-control" id="search" name="search" placeholder="Search by name, email, or content">
                </div>
                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-search fa-sm mr-1"></i> Search
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Feedback Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">All Feedback</h6>
    </div>
    <div class="card-body">
        <?php if (empty($feedbacks)): ?>
            <div class="text-center py-4">
                <i class="fas fa-comment-slash fa-3x text-gray-300 mb-3"></i>
                <p>No feedback available.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="selectAll">
                                    <label class="custom-control-label" for="selectAll"></label>
                                </div>
                            </th>
                            <th width="5%">ID</th>
                            <th width="15%">Name</th>
                            <th width="10%">Rating</th>
                            <th width="25%">Message</th>
                            <th width="10%">Status</th>
                            <th width="15%">Date</th>
                            <th width="15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($feedbacks as $feedback): ?>
                            <tr>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="select<?php echo $feedback['id']; ?>">
                                        <label class="custom-control-label" for="select<?php echo $feedback['id']; ?>"></label>
                                    </div>
                                </td>
                                <td><?php echo $feedback['id']; ?></td>
                                <td><?php echo htmlspecialchars($feedback['name']); ?></td>
                                <td>
                                    <?php 
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $feedback['rating']) {
                                            echo '<i class="fas fa-star text-warning"></i>';
                                        } else {
                                            echo '<i class="far fa-star text-warning"></i>';
                                        }
                                    }
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars(substr($feedback['message'], 0, 50) . (strlen($feedback['message']) > 50 ? '...' : '')); ?></td>
                                <td>
                                    <?php if ($feedback['status'] === 'approved'): ?>
                                        <span class="badge badge-success">Approved</span>
                                    <?php elseif ($feedback['status'] === 'pending'): ?>
                                        <span class="badge badge-warning">Pending</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Rejected</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('M d, Y H:i', strtotime($feedback['created_at'])); ?></td>
                                <td>
                                    <a href="/GreenMind/public/dashboard.php?route=feedback/admin/show&id=<?php echo $feedback['id']; ?>" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#" class="btn btn-success btn-sm">
                                        <i class="fas fa-check"></i>
                                    </a>
                                    <a href="#" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Bulk Actions -->
            <div class="row mt-3">
                <div class="col-md-3">
                    <form method="POST" action="/GreenMind/public/dashboard.php?route=feedback/admin/bulk" id="bulkForm">
                        <div class="input-group">
                            <select class="form-control" name="action" id="bulkAction">
                                <option value="">Bulk Actions</option>
                                <option value="approve">Approve</option>
                                <option value="reject">Reject</option>
                                <option value="delete">Delete</option>
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit" id="bulkSubmit">Apply</button>
                            </div>
                        </div>
                        <!-- Hidden inputs for selected IDs will be added by JavaScript -->
                        <div id="selectedIds"></div>
                    </form>
                </div>
                <div class="col-md-9">
                    <?php if (isset($pagination) && $pagination['total'] > $pagination['perPage']): ?>
                        <nav aria-label="Feedback pagination">
                            <ul class="pagination justify-content-end">
                                <?php if (isset($pagination['current']) && $pagination['current'] > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="/GreenMind/public/dashboard.php?route=feedback/admin&page=<?php echo $pagination['current'] - 1; ?>" tabindex="-1">Previous</a>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                                    </li>
                                <?php endif; ?>
                                
                                <?php 
                                    $start = max(1, isset($pagination['current']) ? $pagination['current'] - 2 : 1);
                                    $end = min(isset($pagination['total']) ? $pagination['total'] : 1, $start + 4);
                                    
                                    for ($i = $start; $i <= $end; $i++): 
                                ?>
                                    <li class="page-item <?php echo (isset($pagination['current']) && $pagination['current'] == $i) ? 'active' : ''; ?>">
                                        <a class="page-link" href="/GreenMind/public/dashboard.php?route=feedback/admin&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>
                                
                                <?php if (isset($pagination['current']) && $pagination['current'] < $pagination['total']): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="/GreenMind/public/dashboard.php?route=feedback/admin&page=<?php echo $pagination['current'] + 1; ?>">Next</a>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#">Next</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- JavaScript for Bulk Actions -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Checkbox select all functionality
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.custom-control-input:not(#selectAll)');
    
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
            updateBulkButton();
        });
    }
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateBulkButton();
        });
    });
    
    // Bulk action form submission
    const bulkForm = document.getElementById('bulkForm');
    const bulkSubmit = document.getElementById('bulkSubmit');
    
    function updateBulkButton() {
        const checkedBoxes = document.querySelectorAll('.custom-control-input:checked:not(#selectAll)');
        bulkSubmit.disabled = checkedBoxes.length === 0;
    }
    
    if (bulkForm) {
        bulkForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const checkedBoxes = document.querySelectorAll('.custom-control-input:checked:not(#selectAll)');
            const selectedIds = document.getElementById('selectedIds');
            selectedIds.innerHTML = '';
            
            checkedBoxes.forEach(checkbox => {
                const feedbackId = checkbox.id.replace('select', '');
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'ids[]';
                hiddenInput.value = feedbackId;
                selectedIds.appendChild(hiddenInput);
            });
            
            if (checkedBoxes.length > 0) {
                bulkForm.submit();
            }
        });
    }
    
    // Initialize
    updateBulkButton();
});
</script>

<?php
// Get the buffer and assign it to content
$content = ob_get_clean();

// Include layout
require_once dirname(__DIR__) . '/layout.php';
