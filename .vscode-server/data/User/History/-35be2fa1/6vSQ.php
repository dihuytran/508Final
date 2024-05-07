<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: login.php");
    exit;
}

// Fetch all active reservations from the equipment table
$stmt = $conn->query("
    SELECT e.equipment_id, e.inventory_id, e.renter_name, m.member_name, i.type
    FROM equipment e
    JOIN member m ON e.renter_name = m.member_id
    JOIN inventory i ON e.inventory_id = i.inventory_id
");
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipment Reservations</title>
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
    </style>
</head>
<body>
    <div class="container">
        <div class="banner">
            <h1>Equipment Reservations</h1>
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

        <h3 class="mt-4">Active Reservations</h3>
        <?php if (!empty($reservations)): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Reservation ID</th>
                        <th>Equipment Type</th>
                        <th>Renter Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $reservation): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($reservation['equipment_id']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['type']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['member_name']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No active reservations found.</p>
        <?php endif; ?>
    </div>
</body>
</html>