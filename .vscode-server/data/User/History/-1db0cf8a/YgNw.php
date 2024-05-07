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
    (SELECT 'Enrolled' AS action, c.class_name AS item
    FROM class_member cm
    JOIN class c ON cm.class_id = c.class_id
    WHERE cm.member_id = ?)
    UNION ALL
    (SELECT 'Reserved' AS action, i.type AS item
    FROM equipment e
    JOIN inventory i ON e.inventory_id = i.inventory_id
    WHERE e.renter_name = ?)
    ORDER BY action
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

// Handle password change request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["currentPassword"]) && isset($_POST["newPassword"]) && isset($_POST["confirmPassword"])) {
    $currentPassword = $_POST["currentPassword"];
    $newPassword = $_POST["newPassword"];
    $confirmPassword = $_POST["confirmPassword"];

    // Retrieve the current password hash from the database
    $stmt = $conn->prepare("SELECT password FROM member WHERE member_id = ?");
    $stmt->execute([$userId]);
    $passwordHash = $stmt->fetchColumn();

    // Verify the current password
    if (password_verify($currentPassword, $passwordHash)) {
        // Validate the new password
        if (strlen($newPassword) >= 6 && $newPassword === $confirmPassword) {
            // Update the password in the database
            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE member SET password = ? WHERE member_id = ?");
            $stmt->execute([$newPasswordHash, $userId]);

            // Password changed successfully
            $passwordChangeSuccess = true;
        } else {
            // New password validation failed
            $passwordChangeError = "New password must be at least 6 characters long and match the confirm password.";
        }
    } else {
        // Current password is incorrect, log out the user
        session_destroy();
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Information</title>
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
            <h1>Account Information</h1>
        </div>

        <nav class="navbar">
            <ul>
                <li><a href="guest_dashboardNew.php">Dashboard</a></li>
                <li><a href="enroll.php">Classes</a></li>
                <li><a href="equipment.php">Equipment</a></li>
            </ul>
        </nav>

        <div class="card mb-4">
            <div class="card-body">
                <h3>Personal Details</h3>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($userInfo['member_name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($userInfo['email']); ?></p>
                <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($userInfo['phone_number']); ?></p>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#changePasswordModal">Change Password</button>
            </div>
        </div>

        <?php if (isset($passwordChangeSuccess)): ?>
            <div class="alert alert-success">Password changed successfully.</div>
        <?php elseif (isset($passwordChangeError)): ?>
            <div class="alert alert-danger"><?php echo $passwordChangeError; ?></div>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-body">
                <h3>Service History</h3>
                <?php if (!empty($serviceHistory)): ?>
                    <ol>
                        <?php foreach ($serviceHistory as $history): ?>
                            <li><?php echo htmlspecialchars($history['action']); ?>: <?php echo htmlspecialchars($history['item']); ?></li>
                        <?php endforeach; ?>
                    </ol>
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

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="changePasswordForm" method="post">
                        <div class="form-group">
                            <label for="currentPassword">Current Password (If Incorrect, You Will Be Logged Out)</label>
                            <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
                        </div>
                        <div class="form-group">
                            <label for="newPassword">New Password</label>
                            <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                        </div>
                        <div class="form-group">
                            <label for="confirmPassword">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" form="changePasswordForm" class="btn btn-primary">Change Password</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>