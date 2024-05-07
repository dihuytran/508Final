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

// Fetch service history
$stmt = $conn->prepare("
    (SELECT 'Enrolled' AS action, c.class_name AS item, cm.class_member_id AS id, c.class_time AS timestamp
    FROM class_member cm
    JOIN class c ON cm.class_id = c.class_id
    WHERE cm.member_id = ?)
    UNION ALL
    (SELECT 'Reserved' AS action, i.type AS item, e.equipment_id AS id, e.timestamp
    FROM equipment e
    JOIN inventory i ON e.inventory_id = i.inventory_id
    WHERE e.renter_name = ?)
    ORDER BY timestamp DESC
");
$stmt->execute([$userId, $userId]);
$serviceHistory = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch transaction history
$stmt = $conn->prepare("
    SELECT amount, payment_date, payment_method
    FROM payment
    WHERE member_id = ?
    ORDER BY payment_date DESC
");
$stmt->execute([$userId]);
$transactionHistory = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Information</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Account Information</h1>

        <div class="card mb-4">
            <div class="card-body">
                <h3>Personal Details</h3>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($userInfo['member_name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($userInfo['email']); ?></p>
                <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($userInfo['phone_number']); ?></p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h3>Service History</h3>
                <?php if (!empty($serviceHistory)): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Item</th>
                                <th>Timestamp</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($serviceHistory as $history): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($history['action']); ?></td>
                                    <td><?php echo htmlspecialchars($history['item']); ?></td>
                                    <td><?php echo htmlspecialchars($history['timestamp']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No service history found.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h3>Transaction History</h3>
                <?php if (!empty($transactionHistory)): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Amount</th>
                                <th>Payment Date</th>
                                <th>Payment Method</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactionHistory as $transaction): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($transaction['amount']); ?></td>
                                    <td><?php echo htmlspecialchars($transaction['payment_date']); ?></td>
                                    <td><?php echo htmlspecialchars($transaction['payment_method']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No transaction history found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>