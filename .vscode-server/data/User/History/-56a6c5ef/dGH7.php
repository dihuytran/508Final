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

    if ($classId) {
        // Check if the class exists and get its capacity
        $stmt = $conn->prepare("SELECT capacity FROM class WHERE class_id = ?");
        $stmt->execute([$classId]);
        $classCapacity = $stmt->fetchColumn();

        if ($classCapacity !== false) {
            // Check the current enrollment count for the class
            $stmt = $conn->prepare("SELECT COUNT(*) FROM class_member WHERE class_id = ?");
            $stmt->execute([$classId]);
            $enrollmentCount = $stmt->fetchColumn();

            if ($enrollmentCount < $classCapacity) {
                // Check if the user is already enrolled to prevent double enrollment
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
                $enrollmentError = "The class is already full. Enrollment is not available.";
            }
        } else {
            $enrollmentError = "Invalid class ID.";
        }
    } else {
        $enrollmentError = "Please select a class.";
    }
}

// Handle class unenrollment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['unenroll_class'])) {
    $classId = filter_input(INPUT_POST, 'class_id', FILTER_VALIDATE_INT);

    if ($classId) {
        // Check if the user is enrolled in the class
        $stmt = $conn->prepare("SELECT * FROM class_member WHERE member_id = ? AND class_id = ?");
        $stmt->execute([$userId, $classId]);
        if ($stmt->rowCount() > 0) {
            // Remove the user's enrollment from the class_member table
            $stmt = $conn->prepare("DELETE FROM class_member WHERE member_id = ? AND class_id = ?");
            $stmt->execute([$userId, $classId]);
            $unenrollmentSuccess = true;
        } else {
            $unenrollmentError = "You are not enrolled in this class.";
        }
    } else {
        $unenrollmentError = "Please select a class.";
    }
}

// Fetch available classes
$stmt = $conn->query("
    SELECT c.class_id, c.class_name, c.capacity, c.class_date, c.class_time, c.class_type,
           (SELECT COUNT(*) FROM class_member cm WHERE cm.class_id = c.class_id) AS enrollment_count
    FROM class c
");
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Enrollment</title>
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
        .class-description {
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .form-section {
            margin-bottom: 30px;
        }
        .table-section {
            margin-top: 50px;
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
            <h1>CLASSES</h1>
        </div>

        <nav class="navbar">
            <ul>
                <li><a href="guest_dashboardNew.php">Dashboard</a></li>
                <li><a href="equipment.php">Equipment</a></li>
                <li><a href="account.php">Account</a></li>
            </ul>
        </nav>

        <div class="class-description">
            <p>We offer a variety of classes for our members. From grueling bodybuilding style training, to stuff for middle-aged moms whose joints
                can't handle jogging over 5mph, and even a class to teach you all you need to know about dieting and weight loss! Simply fill in the form
                below with your desired class, with the ID given by the table under it, and you're ready to go!
            </p>
        </div>

        <div class="container">
        <!-- ... (navigation and class description remain the same) ... -->

        <div class="form-section">
            <?php if (isset($enrollmentSuccess)): ?>
                <div class="alert alert-success">Enrollment successful!</div>
            <?php elseif (isset($enrollmentError)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($enrollmentError); ?></div>
            <?php endif; ?>

            <?php if (isset($unenrollmentSuccess)): ?>
                <div class="alert alert-success">Unenrollment successful!</div>
            <?php elseif (isset($unenrollmentError)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($unenrollmentError); ?></div>
            <?php endif; ?>

<form method="post">
                <div class="form-group">
                    <label for="class_id">Select Class:</label>
                    <select name="class_id" id="class_id" class="form-control" required>
                        <option value="">Select a class</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?php echo $class['class_id']; ?>">
                                <?php echo $class['class_id']; ?> - <?php echo $class['class_name']; ?>
                                (Capacity: <?php echo $class['enrollment_count']; ?>/<?php echo $class['capacity']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" name="enroll_class">Enroll</button>
                <button type="submit" class="btn btn-danger" name="unenroll_class">Unenroll</button>
            </form>
        </div>

        <div class="table-section">
            <h3>Available Classes</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Class ID</th>
                        <th>Class Name</th>
                        <th>Capacity </th>
                        <th>Date and Time</th>
                        <th>Class Type</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($classes as $class): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($class['class_id']); ?></td>
                            <td><?php echo htmlspecialchars($class['class_name']); ?></td>
                            <td><?php echo htmlspecialchars($class['capacity']); ?></td>
                            <td><?php echo htmlspecialchars($class['class_time']); ?></td>
                            <td><?php echo htmlspecialchars($class['class_type']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>