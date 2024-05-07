<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'staff') {
    header("Location: login.php");
    exit;
}

// Add equipment logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_equipment'])) {
    $inventoryId = $_POST['inventoryId'];
    $renterId = $_POST['renterId'];

    // Correct the column name here to match your database schema
    $sql = "INSERT INTO equipment (inventory_id, renter_name) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$inventoryId, $renterId]);
}

// Fetch equipment for display, correcting column names
$stmt = $conn->prepare("SELECT equipment_id, inventory_id, renter_name FROM equipment");
$stmt->execute();
$equipment = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Equipment</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Manage Equipment</h1>
        <form method="POST">
            <input type="number" name="inventoryId" placeholder="Inventory ID" required>
            <input type="number" name="renterId" placeholder="Renter ID" required>  <!-- Consider renaming this field to match your schema, such as "Renter Name" -->
            <button type="submit" name="add_equipment">Add Equipment</button>
        </form>
        <table class="table">
            <thead>
                <tr>
                    <th>Equipment ID</th>
                    <th>Inventory ID</th>
                    <th>Renter Name</th> <!-- Updated column header -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($equipment as $item) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['equipment_id']); ?></td>
                        <td><?php echo htmlspecialchars($item['inventory_id']); ?></td>
                        <td><?php echo htmlspecialchars($item['renter_name']); ?></td> <!-- Updated to display the correct field -->
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
