<?php
$current_page = 'warehouse';
$page_title = 'Warehouse Inventory';
require_once '../includes/header.php';
require_once '../includes/auth.php';

// Check if user is logged in
$is_admin = isset($_SESSION['user_id']) && isAdmin();

// Handle form submissions
$success_message = '';
$error_message = '';

// Add new item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_item']) && $is_admin) {
    $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    
    if (empty($item_name) || empty($quantity)) {
        $error_message = "All fields are required!";
    } else {
        $check_query = "SELECT * FROM WAREHOUSE WHERE Item_Name = '$item_name'";
        $check_result = mysqli_query($conn, $check_query);
        
        if (mysqli_num_rows($check_result) > 0) {
            $error_message = "Item already exists! Use edit to update quantity.";
        } else {
            $insert_query = "INSERT INTO WAREHOUSE (Item_Name, quantity) VALUES ('$item_name', '$quantity')";
            if (mysqli_query($conn, $insert_query)) {
                $success_message = "Item added successfully!";
            } else {
                $error_message = "Error: " . mysqli_error($conn);
            }
        }
    }
}

// Update item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_item']) && $is_admin) {
    $item_id = mysqli_real_escape_string($conn, $_POST['item_id']);
    $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    
    $update_query = "UPDATE WAREHOUSE SET Item_Name = '$item_name', quantity = '$quantity' WHERE Item_ID = '$item_id'";
    if (mysqli_query($conn, $update_query)) {
        $success_message = "Item updated successfully!";
    } else {
        $error_message = "Error: " . mysqli_error($conn);
    }
}

// Delete item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_item']) && $is_admin) {
    $item_id = mysqli_real_escape_string($conn, $_POST['item_id']);
    
    $delete_query = "DELETE FROM WAREHOUSE WHERE Item_ID = '$item_id'";
    if (mysqli_query($conn, $delete_query)) {
        $success_message = "Item deleted successfully!";
    } else {
        $error_message = "Error: " . mysqli_error($conn);
    }
}

// Fetch all warehouse items
$warehouse_query = "SELECT * FROM WAREHOUSE ORDER BY Item_Name";
$warehouse_result = mysqli_query($conn, $warehouse_query);

// Get statistics
$stats_query = "SELECT COUNT(*) as total_items, SUM(quantity) as total_quantity FROM WAREHOUSE";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);
?>

<style>
.edit-modal, .delete-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    justify-content: center;
    align-items: center;
}

.modal-content {
    background: white;
    padding: 30px;
    border-radius: 15px;
    max-width: 500px;
    width: 90%;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
}

.modal-content h3 {
    color: #6b4a8e;
    margin-bottom: 20px;
    font-size: 24px;
}

.modal-buttons {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    margin-top: 20px;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-primary {
    background: linear-gradient(135deg, #6b4a8e, #8b5db4);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(107, 74, 142, 0.3);
}

.btn-danger {
    background: #dc3545;
    color: white;
}

.btn-danger:hover {
    background: #c82333;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
}

.warehouse-table {
    width: 100%;
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.warehouse-table th, .warehouse-table td {
    padding: 15px;
    text-align: left;
}

.warehouse-table th {
    background: linear-gradient(135deg, #6b4a8e, #8b5db4);
    color: white;
    font-weight: 600;
}

.warehouse-table tr:nth-child(even) {
    background: #f8f9fa;
}

.warehouse-table tr:hover {
    background: #f0f0f0;
}

.action-buttons {
    display: flex;
    gap: 5px;
}

.btn-sm {
    padding: 5px 12px;
    font-size: 12px;
}
</style>

<div class="hero" style="background: linear-gradient(rgba(107, 74, 142, 0.85), rgba(139, 93, 180, 0.85)), url('<?php echo SITE_URL; ?>/assets/images/warehouse.jpg'); background-size: cover; background-position: center;">
    <h1>Warehouse Inventory</h1>
    <div class="breadcrumb">
        <a href="<?php echo SITE_URL; ?>/index.php"><i class="fas fa-home"></i> Home</a>
        <span>></span>
        <span>Warehouse</span>
    </div>
</div>

<div class="container">
    <?php if ($success_message): ?>
    <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 30px; border: 1px solid #c3e6cb;">
        <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
    </div>
    <?php endif; ?>
    
    <?php if ($error_message): ?>
    <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 30px; border: 1px solid #f5c6cb;">
        <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
    </div>
    <?php endif; ?>
    
    <!-- Statistics -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px;">
        <div style="background: linear-gradient(135deg, #6b4a8e, #8b5db4); color: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.15);">
            <div style="display: flex; align-items: center; gap: 20px;">
                <i class="fas fa-boxes" style="font-size: 48px; opacity: 0.8;"></i>
                <div>
                    <h3 style="font-size: 36px; margin-bottom: 5px;"><?php echo $stats['total_items']; ?></h3>
                    <p style="opacity: 0.9;">Total Item Types</p>
                </div>
            </div>
        </div>
        
        <div style="background: linear-gradient(135deg, #28a745, #20c997); color: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.15);">
            <div style="display: flex; align-items: center; gap: 20px;">
                <i class="fas fa-cubes" style="font-size: 48px; opacity: 0.8;"></i>
                <div>
                    <h3 style="font-size: 36px; margin-bottom: 5px;"><?php echo number_format($stats['total_quantity']); ?></h3>
                    <p style="opacity: 0.9;">Total Units in Stock</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Visual Cards View -->
    <div style="text-align: center; margin-bottom: 30px;">
        <h2 style="font-size: 36px; color: #6b4a8e; margin-bottom: 15px;">Available Items</h2>
        <p style="font-size: 18px; color: #666;">Current inventory of donation items</p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; margin-bottom: 50px;">
        <?php 
        mysqli_data_seek($warehouse_result, 0); // Reset pointer
        while ($item = mysqli_fetch_assoc($warehouse_result)): 
        ?>
        <div style="background: white; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); padding: 30px; text-align: center;">
            <div style="width: 80px; height: 80px; margin: 0 auto 20px; background: linear-gradient(135deg, #6b4a8e, #8b5db4); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 36px;">
                <i class="fas fa-box"></i>
            </div>
            <h3 style="color: #333; margin-bottom: 15px; font-size: 20px;"><?php echo htmlspecialchars($item['Item_Name']); ?></h3>
            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                <p style="color: #666; font-size: 14px; margin-bottom: 5px;">Available Quantity</p>
                <p style="color: #6b4a8e; font-size: 32px; font-weight: 700;"><?php echo $item['quantity']; ?></p>
                <p style="color: #888; font-size: 12px;">units</p>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    
    <!-- Admin Management Section -->
    <?php if ($is_admin): ?>
    <div style="background: white; border-radius: 15px; box-shadow: 0 5px 25px rgba(0,0,0,0.1); padding: 40px; margin-bottom: 50px;">
        <h2 style="font-size: 28px; color: #6b4a8e; margin-bottom: 10px; text-align: center;">
            <i class="fas fa-plus-circle"></i> Add New Item
        </h2>
        <p style="text-align: center; color: #666; margin-bottom: 30px;">Add a new item to warehouse inventory</p>
        
        <form method="POST" action="">
            <div style="display: grid; grid-template-columns: 2fr 1fr auto; gap: 15px; align-items: end;">
                <div>
                    <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">
                        <i class="fas fa-box"></i> Item Name *
                    </label>
                    <input type="text" name="item_name" required 
                           style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;"
                           placeholder="Enter item name">
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">
                        <i class="fas fa-sort-numeric-up"></i> Quantity *
                    </label>
                    <input type="number" name="quantity" required min="0" 
                           style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;"
                           placeholder="0">
                </div>
                
                <button type="submit" name="add_item" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Item
                </button>
            </div>
        </form>
    </div>
    <?php endif; ?>
    
    <!-- Detailed List View -->
    <div style="margin-bottom: 50px;">
        <h2 style="font-size: 28px; color: #6b4a8e; margin-bottom: 20px; text-align: center;">
            <i class="fas fa-list"></i> Inventory Details
        </h2>
        
        <table class="warehouse-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <?php if ($is_admin): ?>
                    <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php 
                mysqli_data_seek($warehouse_result, 0); // Reset pointer
                while ($item = mysqli_fetch_assoc($warehouse_result)): 
                    $status_color = $item['quantity'] > 100 ? '#28a745' : ($item['quantity'] > 50 ? '#ffc107' : '#dc3545');
                    $status_text = $item['quantity'] > 100 ? 'In Stock' : ($item['quantity'] > 50 ? 'Low Stock' : 'Critical');
                ?>
                <tr>
                    <td><?php echo $item['Item_ID']; ?></td>
                    <td><strong><?php echo htmlspecialchars($item['Item_Name']); ?></strong></td>
                    <td><span style="font-size: 18px; font-weight: 600; color: #6b4a8e;"><?php echo number_format($item['quantity']); ?></span> units</td>
                    <td>
                        <span style="background: <?php echo $status_color; ?>; color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                            <?php echo $status_text; ?>
                        </span>
                    </td>
                    <?php if ($is_admin): ?>
                    <td>
                        <div class="action-buttons">
                            <button onclick="openEditModal(<?php echo $item['Item_ID']; ?>, '<?php echo addslashes($item['Item_Name']); ?>', <?php echo $item['quantity']; ?>)" 
                                    class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button onclick="openDeleteModal(<?php echo $item['Item_ID']; ?>, '<?php echo addslashes($item['Item_Name']); ?>')" 
                                    class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($is_admin): ?>
<!-- Edit Modal -->
<div id="editModal" class="edit-modal">
    <div class="modal-content">
        <h3><i class="fas fa-edit"></i> Edit Item</h3>
        <form method="POST" action="">
            <input type="hidden" name="item_id" id="edit_item_id">
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">
                    <i class="fas fa-box"></i> Item Name
                </label>
                <input type="text" name="item_name" id="edit_item_name" required 
                       style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">
                    <i class="fas fa-sort-numeric-up"></i> Stock Quantity
                </label>
                <input type="number" name="quantity" id="edit_quantity" required min="0" 
                       style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;">
            </div>
            
            <div class="modal-buttons">
                <button type="button" onclick="closeEditModal()" class="btn btn-secondary">Cancel</button>
                <button type="submit" name="update_item" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="delete-modal">
    <div class="modal-content">
        <h3><i class="fas fa-exclamation-triangle" style="color: #dc3545;"></i> Confirm Delete</h3>
        <p style="color: #666; margin-bottom: 20px;">Are you sure you want to delete <strong id="delete_item_name"></strong>? This action cannot be undone.</p>
        <form method="POST" action="">
            <input type="hidden" name="item_id" id="delete_item_id">
            <div class="modal-buttons">
                <button type="button" onclick="closeDeleteModal()" class="btn btn-secondary">Cancel</button>
                <button type="submit" name="delete_item" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Delete Item
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(id, name, quantity) {
    document.getElementById('edit_item_id').value = id;
    document.getElementById('edit_item_name').value = name;
    document.getElementById('edit_quantity').value = quantity;
    document.getElementById('editModal').style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

function openDeleteModal(id, name) {
    document.getElementById('delete_item_id').value = id;
    document.getElementById('delete_item_name').textContent = name;
    document.getElementById('deleteModal').style.display = 'flex';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}

// Close modals when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('edit-modal') || event.target.classList.contains('delete-modal')) {
        closeEditModal();
        closeDeleteModal();
    }
}
</script>
<?php endif; ?>

<?php require_once '../includes/footer.php'; ?>
