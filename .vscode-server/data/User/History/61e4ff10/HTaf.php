<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: login.php");
    exit;
}

// Add payment logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_payment'])) {
    $amount = $_POST['amount'];
    $paymentMethod = $_POST['paymentMethod'];
    $memberId = $_POST['memberId'];

    $sql = "INSERT INTO payment (amount, payment_method, member_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$amount, $paymentMethod, $memberId]);
}

// Fetch payments for display
$stmt = $conn->prepare("SELECT payment_id, amount, payment_date, payment_method, member_id FROM payment");
$stmt->execute();
$payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Payments</title>
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
        <h1>Manage Payments</h1>
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

    <form method="POST" class="form-column">
        <div class="form-group">
            <label>Amount</label>
            <input type="text" name="amount" class="form-control" placeholder="Amount" required>
        </div>
        <div class="form-group">
            <label>Payment Method</label>
            <input type="text" name="paymentMethod" class="form-control" placeholder="Payment Method" required>
        </div>
        <div class="form-group">
            <label>Member ID</label>
            <input type="number" name="memberId" class="form-control" placeholder="Member ID" required>
        </div>
        <button type="submit" name="add_payment" class="btn btn-primary">Add Payment</button>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Method</th>
                <th>Member ID</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($payments as $payment) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($payment['payment_id']); ?></td>
                    <td><?php echo htmlspecialchars($payment['amount']); ?></td>
                    <td><?php echo htmlspecialchars($payment['payment_date']); ?></td>
                    <td><?php echo htmlspecialchars($payment['payment_method']); ?></td>
                    <td><?php echo htmlspecialchars($payment['member_id']); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
