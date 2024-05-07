<?php
session_start();
require_once 'connection.php';

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
    $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);

    if ($quantity >= 0) {
        $sql = "UPDATE inventory SET quantity_available = ?, type = ? WHERE inventory_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$quantity, $type, $inventoryId]);
    } else {
        echo "Quantity must be greater than or equal to 0.";
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
</head>
<body>
<div class="container">
    <h1>Manage Inventory</h1>

    <!-- Add Inventory Form -->
    <form method="POST">
        <input type="number" name="quantity" placeholder="Quantity Available" required>
        <input type="text" name="type" placeholder="Type" required>
        <button type="submit" name="add_inventory">Add Inventory</button>
    </form>

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
                    <!-- Update Inventory Form -->
                    <form method="POST" style="display: inline-block;">
                        <input type="hidden" name="inventory_id" value="<?php echo $item['inventory_id']; ?>">
                        <input type="number" name="quantity" placeholder="Quantity Available" value="<?php echo $item['quantity_available']; ?>" required>
                        <input type="text" name="type" placeholder="Type" value="<?php echo $item['type']; ?>" required>
                        <button type="submit" name="update_inventory">Update</button>
                    </form>

                    <!-- Delete Inventory Form -->
                    <form method="POST" style="display: inline-block;">
                        <input type="hidden" name="inventory_id" value="<?php echo $item['inventory_id']; ?>">
                        <button type="submit" name="delete_inventory">Delete</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>