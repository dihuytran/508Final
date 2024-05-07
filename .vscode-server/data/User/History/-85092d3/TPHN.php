<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'staff') {
    header("Location: login.php");
    exit;
}

// Add inventory logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_inventory'])) {
    $quantity = $_POST['quantity'];

    $sql = "INSERT INTO inventory (quantity_available) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$quantity]);
}

// Fetch inventory for display
$stmt = $conn->prepare("SELECT inventory_id, quantity_available FROM inventory");
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
        <form method="POST">
            <input type="number" name="quantity" placeholder="Quantity Available" required>
            <button type="submit" name="add_inventory">Add Inventory</button>
        </form>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Quantity Available</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($inventory as $item) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['inventory_id']); ?></td>
                        <td><?php echo htmlspecialchars($item['quantity_available']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
