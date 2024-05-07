<?php
session_start();
require_once 'connection.php';

// Check if the user is signed in as staff
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: login.php");
    exit;
}

// Add class
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_class'])) {
    $className = filter_input(INPUT_POST, 'className', FILTER_SANITIZE_STRING);
    $capacity = filter_input(INPUT_POST, 'capacity', FILTER_SANITIZE_NUMBER_INT);
    $classDate = $_POST['classDate'];
    $classTime = $_POST['classTime'];
    $classType = filter_input(INPUT_POST, 'classType', FILTER_SANITIZE_STRING);

    $sql = "INSERT INTO class (class_name, capacity, class_date, class_time, class_type) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$className, $capacity, $classDate, $classTime, $classType]);
}

// Update class
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_class'])) {
    $classId = filter_input(INPUT_POST, 'class_id', FILTER_SANITIZE_NUMBER_INT);
    $className = filter_input(INPUT_POST, 'className', FILTER_SANITIZE_STRING);
    $capacity = filter_input(INPUT_POST, 'capacity', FILTER_SANITIZE_NUMBER_INT);
    $classDate = $_POST['classDate'];
    $classTime = $_POST['classTime'];
    $classType = filter_input(INPUT_POST, 'classType', FILTER_SANITIZE_STRING);

    $sql = "UPDATE class SET class_name = ?, capacity = ?, class_date = ?, class_time = ?, class_type = ? WHERE class_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$className, $capacity, $classDate, $classTime, $classType, $classId]);
}

// Delete class
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_class'])) {
    $classId = filter_input(INPUT_POST, 'class_id', FILTER_SANITIZE_NUMBER_INT);
    $sql = "DELETE FROM class WHERE class_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$classId]);
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
    <style>
        .navbar {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Navigation Menu -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Gym Management</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="manage_inventory.php">Manage Inventory</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage_classes.php">Manage Classes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage_equipment.php">Manage Equipment</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage_members.php">Manage Members</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage_payments.php">Manage Payments</a>
                </li>
            </ul>
        </div>
    </nav>

    <h1>Manage Classes</h1>

    <!-- Add Class Form -->
    <form method="POST">
        <input type="text" name="className" placeholder="Class Name" required>
        <input type="number" name="capacity" placeholder="Capacity" required>
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
            <th>Actions</th>
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
                <td>
                    <!-- Update Class Form -->
                    <form method="POST" style="display: inline-block;">
                        <input type="hidden" name="class_id" value="<?php echo $class['class_id']; ?>">
                        <input type="text" name="className" placeholder="Class Name" value="<?php echo $class['class_name']; ?>" required>
                        <input type="number" name="capacity" placeholder="Capacity" value="<?php echo $class['capacity']; ?>" required>
                        <input type="date" name="classDate" placeholder="Class Date" value="<?php echo $class['class_date']; ?>" required>
                        <input type="time" name="classTime" placeholder="Class Time" value="<?php echo $class['class_time']; ?>" required>
                        <input type="text" name="classType" placeholder="Class Type" value="<?php echo $class['class_type']; ?>">
                        <button type="submit" name="update_class">Update</button>
                    </form>

                    <!-- Delete Class Form -->
                    <form method="POST" style="display: inline-block;">
                        <input type="hidden" name="class_id" value="<?php echo $class['class_id']; ?>">
                        <button type="submit" name="delete_class">Delete</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>