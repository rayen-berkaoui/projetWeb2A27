<!-- view/admin/products/index.php -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2>Products Management</h2>
        <a href="<?= base_url('admin/products/add') ?>" class="btn btn-primary">
            Add New Product
        </a>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= $product->id ?></td>
                    <td><?= htmlspecialchars($product->nom) ?></td>
                    <td><?= number_format($product->prix, 2) ?></td>
                    <td><?= $product->categorical ?></td>
                    <td>
                        <a href="<?= base_url('admin/products/edit/' . $product->id) ?>" class="btn btn-sm btn-warning">Edit</a>
                        <form action="<?= base_url('admin/products/delete') ?>" method="POST" class="d-inline">
                            <input type="hidden" name="id" value="<?= $product->id ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>