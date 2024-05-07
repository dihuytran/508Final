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

// Fetch available classes
$stmt = $conn->query("SELECT class_id, class_name FROM class");
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle class enrollment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['enroll_class'])) {
    $classId = $_POST['class_id'];
    // Check if already enrolled to prevent double enrollment
    $stmt = $conn->prepare("SELECT * FROM class_member WHERE member_id = ? AND class_id = ?");
    $stmt->execute([$userId, $classId]);
    if ($stmt->rowCount() == 0) {
        $stmt = $conn->prepare("INSERT INTO class_member (member_id, class_id) VALUES (?, ?)");
        $stmt->execute([$userId, $classId]);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
    background: #333;
    color: #fff;
    padding: 10px 20px;
    text-align: center;
    padding-bottom: 30px; /* Adjust this value as needed */
}

        .nav {
            display: flex;
            justify-content: center;
            padding-bottom: 20px;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
        }
        .nav a {
            color: #333;
            padding: 10px 15px;
            text-decoration: none;
            font-weight: bold;
            margin: 0 10px;
        }
        .nav a:hover {
            text-decoration: underline;
        }
        .container {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome, <?php echo htmlspecialchars($userInfo['member_name']); ?>!</h1>
    </div>
    <div class="nav">
        <a href="about.php">About</a>
        <a href="services.php">Services</a>
        <a href="account.php">Account</a>
    </div>
    <div class="container mt-4">
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

        <h3>Enroll in a Class</h3>
        <form method="POST">
            <select name="class_id" required>
                <option value="">Select a Class</option>
                <?php foreach ($classes as $class) { ?>
                    <option value="<?php echo $class['class_id']; ?>"><?php echo htmlspecialchars($class['class_name']); ?></option>
                <?php } ?>
            </select>
            <button type="submit" name="enroll_class">Enroll</button>
        </form>
        
        <a href="logout.php" class="btn btn-danger">Log Out</a>
    </div>
</body>
</html>
