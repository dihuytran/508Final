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

// Handle class enrollment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['enroll_class'])) {
    $classId = filter_input(INPUT_POST, 'class_id', FILTER_VALIDATE_INT);
    $fullName = trim(filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_STRING));
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));

    if ($classId && $fullName && $email) {
        // Check if already enrolled to prevent double enrollment
        $stmt = $conn->prepare("SELECT * FROM class_member WHERE member_id = ? AND class_id = ?");
        $stmt->execute([$userId, $classId]);
        if ($stmt->rowCount() == 0) {
            $stmt = $conn->prepare("INSERT INTO class_member (member_id, class_id) VALUES (?, ?)");
            $stmt->execute([$userId, $classId]);
            $enrollmentSuccess = true;
        } else {
            $enrollmentError = "You are already enrolled in this class.";
        }
    } else {
        $enrollmentError = "Please provide valid enrollment details.";
    }
}

// Fetch available classes
$stmt = $conn->query("SELECT class_id, class_name, capacity, class_date, class_time, class_type FROM class");
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Enrollment</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Class Enrollment</h2>
        <p>We offer a variety of classes for our members. From high-intensity cardio to relaxing yoga, there's something for everyone. Our experienced instructors will guide you through each session, helping you achieve your fitness goals. Enroll in a class today and take the first step towards a healthier you!</p>

        <?php if (isset($enrollmentSuccess)): ?>
            <div class="alert alert-success">Enrollment successful!</div>
        <?php elseif (isset($enrollmentError)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($enrollmentError); ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label for="full_name">Full Name:</label>
                <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($userInfo['member_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($userInfo['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="class_id">Class ID:</label>
                <input type="number" class="form-control" id="class_id" name="class_id" required>
            </div>
            <button type="submit" class="btn btn-primary" name="enroll_class">Enroll</button>
        </form>

        <h3 class="mt-4">Available Classes</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Class ID</th>
                    <th>Class Name</th>
                    <th>Capacity</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Class Type</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($classes as $class): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($class['class_id']); ?></td>
                        <td><?php echo htmlspecialchars($class['class_name']); ?></td>
                        <td><?php echo htmlspecialchars($class['capacity']); ?></td>
                        <td><?php echo htmlspecialchars($class['class_date']); ?></td>
                        <td><?php echo htmlspecialchars($class['class_time']); ?></td>
                        <td><?php echo htmlspecialchars($class['class_type']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>