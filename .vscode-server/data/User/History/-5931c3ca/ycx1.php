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

// Handle phone number update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_phone'])) {
    $newPhone = $_POST['phone'];
    $stmt = $conn->prepare("UPDATE member SET phone_number = ? WHERE member_id = ?");
    $stmt->execute([$newPhone, $userId]);
    // Refresh the user info
    $stmt = $conn->prepare("SELECT member_name, email, phone_number FROM member WHERE member_id = ?");
    $stmt->execute([$userId]);
    $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Handle class enrollment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['enroll_class'])) {
    $classId = $_POST['class_id'];
    $stmt = $conn->prepare("SELECT * FROM class_member WHERE member_id = ? AND class_id = ?");
    $stmt->execute([$userId, $classId]);
    if ($stmt->rowCount() == 0) {
        $stmt = $conn->prepare("INSERT INTO class_member (member_id, class_id) VALUES (?, ?)");
        $stmt->execute([$userId, $classId]);
    }
}

// Fetch user classes
$stmt = $conn->prepare("SELECT class.class_id, class_name, class_date, class_time FROM class_member JOIN class ON class_member.class_id = class.class_id WHERE member_id = ?");
$stmt->execute([$userId]);
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all classes for enrollment purposes
$stmt = $conn->prepare("SELECT class_id, class_name FROM class");
$stmt->execute();
$allClasses = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
        <p>Here you can manage your information, classes, and equipment rentals.</p>

        <h3>Update Your Information</h3>
        <form method="POST">
            <label>Update Phone Number:</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($userInfo['phone_number']); ?>" required>
            <button type="submit" name="update_phone">Update Phone</button>
        </form>

        <h3>Your Classes</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Class ID</th>
                    <th>Class Name</th>
                    <th>Date</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($classes as $class) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($class['class_id']); ?></td>
                        <td><?php echo htmlspecialchars($class['class_name']); ?></td>
                        <td><?php echo htmlspecialchars($class['class_date']); ?></td>
                        <td><?php echo htmlspecialchars($class['class_time']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <h3>Enroll in a Class</h3>
        <form method="POST">
            <select name="class_id" required>
                <option value="">Select a Class</option>
                <?php foreach ($allClasses as $class) { ?>
                    <option value="<?php echo $class['class_id']; ?>"><?php echo htmlspecialchars($class['class_name']); ?></option>
                <?php }
