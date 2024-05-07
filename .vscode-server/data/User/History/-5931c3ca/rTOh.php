<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guest') {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

// Fetch user information
$stmt = $conn->prepare("SELECT member_name, email, phone_number FROM member WHERE member_id = ?");
$stmt->execute([$userId]);
$userInfo = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch payment history
$stmt = $conn->prepare("SELECT amount, payment_date, payment_method FROM payment WHERE member_id = ?");
$stmt->execute([$userId]);
$payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch equipment reservations
$stmt = $conn->prepare("SELECT equipment_id, inventory_id FROM equipment WHERE renter_name = ?");
$stmt->execute([$userId]);
$equipmentReservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Guest Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Welcome, <?php echo htmlspecialchars($userInfo['member_name']); ?>!</h1>
        <h2>Dashboard</h2>
        
        <h3>Personal Information</h3>
        <p>Email: <?php echo htmlspecialchars($userInfo['email']); ?></p>
        <p>Phone Number: <?php echo htmlspecialchars($userInfo['phone_number']); ?></p>

        <h3>Financial Information</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Payment Method</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $payment) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($payment['amount']); ?></td>
                        <td><?php echo htmlspecialchars($payment['payment_date']); ?></td>
                        <td><?php echo htmlspecialchars($payment['payment_method']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <h3>Equipment Reservations</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Equipment ID</th>
                    <th>Inventory ID</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($equipmentReservations as $reservation) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($reservation['equipment_id']); ?></td>
                        <td><?php echo htmlspecialchars($reservation['inventory_id']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        
        <a href="logout.php" class="btn btn-danger">Log Out</a>
    </div>
</body>
</html>