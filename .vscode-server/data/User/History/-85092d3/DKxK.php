<?php
session_start();
require_once 'connection.php';

// Check if the user is signed in as staff
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: login.php");
    exit;
}

// Add inventory
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_inventory'])) {
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT);
    $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);

    if ($quantity >= 0) {
        $sql = "INSERT INTO inventory (quantity_available, type) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$quantity, $type]);
    } else {
        echo "Quantity must be greater than or equal to 0.";
    }
}

// Update inventory
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_inventory'])) {
    $inventoryId = filter_input(INPUT_POST, 'inventory_id', FILTER_SANITIZE_NUMBER_INT);
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT);

    // Check if the updated quantity is valid
    $sql = "SELECT quantity_available FROM inventory WHERE inventory_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$inventoryId]);
    $currentQuantity = $stmt->fetchColumn();

    if ($quantity >= 0 && $quantity <= $currentQuantity) {
        $sql = "UPDATE inventory SET quantity_available = ? WHERE inventory_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$quantity, $inventoryId]);
    } else {
        echo "Invalid quantity. Quantity must be between 0 and the current quantity.";
    }
}

// Delete inventory
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_inventory'])) {
    $inventoryId = filter_input(INPUT_POST, 'inventory_id', FILTER_SANITIZE_NUMBER_INT);
    $sql = "DELETE FROM inventory WHERE inventory_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$inventoryId]);
}

// Fetch inventory for display
$stmt = $conn->prepare("SELECT inventory_id, quantity_available, type FROM inventory");
$stmt->execute();
$inventory = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Inventory</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .banner {
            background-color: #007bff;
            color: #fff;
            padding: 20px;
            text-align: center;
            margin-bottom: 30px;
        }
        .banner h1 {
            margin: 0;
            font-size: 36px;
        }
        .navbar {
            background-color: #f8f9fa;
            padding: 10px;
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
        }
        .navbar ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
        }
        .navbar li {
            margin: 0 10px;
        }
        .navbar a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }
        .navbar a:hover {
            color: #007bff;
        }
        .form-column {
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .table {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="banner">
        <h1>Manage Inventory</h1>
    </div>

    <nav class="navbar">
        <ul>
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="manage_members.php">Manage Members</a></li>
            <li><a href="manage_classes.php">Manage Classes</a></li>
            <li><a href="manage_equipment.php">Manage Equipment</a></li>
            <li><a href="manage_inventory.php">Manage Inventory</a></li>
            <li><a href="manage_payments.php">Manage Payments</a></li>
        </ul>
    </nav>

    <div class="row">
        <div class="col-md-6 form-column">
            <h2>Add Inventory</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Quantity Available</label>
                    <input type="number" name="quantity" class="form-control" placeholder="Quantity Available" required>
                </div>
                <div class="form-group">
                    <label>Type</label>
                    <input type="text" name="type" class="form-control" placeholder="Type" required>
                </div>
                <div class="form-group">
                    <button type="submit" name="add_inventory" class="btn btn-primary">Add Inventory</button>
                </div>
            </form>
        </div>
        <div class="col-md-6 form-column">
            <h2>Update Inventory</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Inventory ID</label>
                    <input type="number" name="inventory_id" class="form-control" placeholder="Inventory ID" required>
                </div>
                <div class="form-group">
                    <label>Quantity Available</label>
                    <input type="number" name="quantity" class="form-control" placeholder="Quantity Available" required>
                </div>
                <div class="form-group">
                    <button type="submit" name="update_inventory" class="btn btn-info">Update Inventory</button>
                </div>
            </form>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Quantity Available</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($inventory as $item) { ?>
            <tr>
                <td><?php echo htmlspecialchars($item['inventory_id']); ?></td>
                <td><?php echo htmlspecialchars($item['quantity_available']); ?></td>
                <td><?php echo htmlspecialchars($item['type']); ?></td>
                <td>
                    <form method="POST" style="display: inline-block;">
                        <input type="hidden" name="inventory_id" value="<?php echo $item['inventory_id']; ?>">
                        <button type="submit" name="delete_inventory" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>