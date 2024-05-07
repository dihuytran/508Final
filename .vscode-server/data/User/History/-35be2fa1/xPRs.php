<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: login.php");
    exit;
}

// Add equipment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_equipment'])) {
    $inventoryId = $_POST['inventoryId'];
    $renterId = $_POST['renterId'];

    $sql = "INSERT INTO equipment (inventory_id, renter_name) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$inventoryId, $renterId]);
}

// Fetch equipment for display, including member names
$stmt = $conn->prepare("SELECT e.equipment_id, e.inventory_id, m.member_name
                        FROM equipment e
                        INNER JOIN member m ON e.renter_name = m.member_id");
$stmt->execute();
$equipment = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Equipment</title>
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
            <h1>Manage Equipment</h1>
        </div>

        <nav class="navbar">
            <ul>
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="manage_equipment.php">Manage Equipment</a></li>
                <li><a href="manage_classes.php">Manage Classes</a></li>
            </ul>
        </nav>

        <div class="row">
            <div class="col-md-6 form-column">
                <h2>Add Equipment</h2>
                <form method="POST">
                    <div class="form-group">
                        <label>Inventory ID</label>
                        <input type="number" name="inventoryId" class="form-control" placeholder="Inventory ID" required>
                    </div>
                    <div class="form-group">
                        <label>Renter ID</label>
                        <input type="number" name="renterId" class="form-control" placeholder="Renter ID" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="add_equipment" class="btn btn-primary">Add Equipment</button>
                    </div>
                </form>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Equipment ID</th>
                    <th>Inventory ID</th>
                    <th>Renter Name</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($equipment as $item) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['equipment_id']); ?></td>
                    <td><?php echo htmlspecialchars($item['inventory_id']); ?></td>
                    <td><?php echo htmlspecialchars($item['member_name']); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>