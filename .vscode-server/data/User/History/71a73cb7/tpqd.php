<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'staff') {
    header("Location: login.php");
    exit;
}

// Add class logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_class'])) {
    $className = $_POST['className'];
    $capacity = $_POST['capacity'];
    $classDate = $_POST['classDate'];
    $classTime = $_POST['classTime'];
    $classType = $_POST['classType'];

    $sql = "INSERT INTO class (class_name, capacity, class_date, class_time, class_type) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$className, $capacity, $classDate, $classTime, $classType]);
}

// Fetch classes for display
$stmt = $conn->prepare("SELECT class_id, class_name, capacity, class_date, class_time, class_type FROM class");
$stmt->execute();
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Classes</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Manage Classes</h1>
        <form method="POST">
            <input type="text" name="className" placeholder="Class Name" required>
            <input type="text" name="capacity" placeholder="Capacity" required>
            <input type="date" name="classDate" placeholder="Class Date" required>
            <input type="time" name="classTime" placeholder="Class Time" required>
            <input type="text" name="classType" placeholder="Class Type">
            <button type="submit" name="add_class">Add Class</button>
        </form>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
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
    </div>
</body>
</html>