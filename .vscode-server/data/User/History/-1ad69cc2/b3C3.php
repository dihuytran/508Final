<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'staff') {
    header("Location: login.php");
    exit;
}

require_once 'connection.php';

// Fetch staff information
$stmt = $conn->prepare("SELECT staff_name, staff_role FROM staff WHERE staff_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$staffInfo = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle class creation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_class'])) {
    $className = $_POST['class_name'];
    $classCapacity = $_POST['class_capacity'];
    $classDate = $_POST['class_date'];
    $classTime = $_POST['class_time'];
    $classType = $_POST['class_type'];

    $stmt = $conn->prepare("INSERT INTO class (class_name, capacity, class_date, class_time, class_type) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$className, $classCapacity, $classDate, $classTime, $classType]);
}

// Handle class cancellation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel_class'])) {
    $classId = $_POST['class_id'];

    $stmt = $conn->prepare("DELETE FROM class WHERE class_id = ?");
    $stmt->execute([$classId]);
}

// Fetch all members
$stmt = $conn->prepare("SELECT * FROM member");
$stmt->execute();
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all payments
$stmt = $conn->prepare("SELECT * FROM payment");
$stmt->execute();
$payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all classes
$stmt = $conn->prepare("SELECT * FROM class");
$stmt->execute();
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Welcome, <?php echo htmlspecialchars($staffInfo['staff_name']); ?>!</h1>
        <h2>Staff Dashboard</h2>

        <h3>Members</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Member ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($members as $member) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($member['member_id']); ?></td>
                        <td><?php echo htmlspecialchars($member['member_name']); ?></td>
                        <td><?php echo htmlspecialchars($member['email']); ?></td>
                        <td><?php echo htmlspecialchars($member['phone_number']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <h3>Payments</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Member ID</th>
                    <th>Amount</th>
                    <th>Payment Date</th>
                    <th>Payment Method</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $payment) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($payment['payment_id']); ?></td>
                        <td><?php echo htmlspecialchars($payment['member_id']); ?></td>
                        <td><?php echo htmlspecialchars($payment['amount']); ?></td>
                        <td><?php echo htmlspecialchars($payment['payment_date']); ?></td>
                        <td><?php echo htmlspecialchars($payment['payment_method']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <h3>Create Class</h3>
        <form method="POST">
            <input type="text" name="class_name" placeholder="Class Name" required>
            <input type="text" name="class_capacity" placeholder="Capacity" required>
            <input type="date" name="class_date" placeholder="Date" required>
            <input type="time" name="class_time" placeholder="Time" required>
            <input type="text" name="class_type" placeholder="Class Type" required>
            <button type="submit" name="create_class">Create Class</button>
        </form>

        <h3>Cancel Class</h3>
        <form method="POST">
            <select name="class_id" required>
                <option value="">Select Class</option>
                <?php foreach ($classes as $class) { ?>
                    <option value="<?php echo $class['class_id']; ?>"><?php echo htmlspecialchars($class['class_name']); ?></option>
                <?php } ?>
            </select>
            <button type="submit" name="cancel_class">Cancel Class</button>
        </form>

        <h3>Classes</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Class ID</th>
                    <th>Class Name</th>
                    <th>Capacity</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($classes as $class) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($class['class_id']); ?></td>
                        <td><?php echo htmlspecialchars($class['class_name']); ?></td>
                        <td><?php echo htmlspecialchars($class['capacity']); ?></td>
                        <td><?php echo htmlspecialchars($class['class_date']); ?></td>
                        <td><?php echo htmlspecialchars($class['class_time']); ?></td>
                        <td><?php echo htmlspecialchars($class['class_type']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <a href="logout.php" class="btn btn-danger">Log Out</a>
    </div>
</body>
</html>
