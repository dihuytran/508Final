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
        .form-column {
            padding: 20px;
            background-color: #f8f9fa; /* Light gray background for better contrast */
            border-radius: 5px; /* Optional: rounds the corners of the form background */
        }
        .form-group {
            margin-bottom: 15px;
        }
        .table {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="banner">
        <h1>Manage Classes</h1>
    </div>

    <nav class="navbar">
        <ul>
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="manage_members.php">Manage Members</a></li>
            <li><a href="manage_classes.php">Manage Classes</a></li>
            <li><a href="manage_equipment.php">Manage Equipment</a></li>
            <li><a href="manage_inventory.php">Manage Inventory</a></li>
            <li><a href="manage_payments.php">Manage Payments</a></li>
        </ul>
    </nav>

    <div class="row">
        <div class="col-md-12 form-column">
            <h2>Add or Update Class</h2>
            <form method="POST">
                <div class="form-group">
                    <input type="hidden" name="class_id" class="form-control">
                    <label>Class Name</label>
                    <input type="text" name="className" class="form-control" placeholder="Class Name" required>
                </div>
                <div class="form-group">
                    <label>Capacity</label>
                    <input type="number" name="capacity" class="form-control" placeholder="Capacity" required>
                </div>
                <div class="form-group">
                    <label>Class Date</label>
                    <input type="date" name="classDate" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Class Time</label>
                    <input type="time" name="classTime" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Class Type</label>
                    <input type="text" name="classType" class="form-control" placeholder="Class Type">
                </div>
                <div class="form-group">
                    <button type="submit" name="add_class" class="btn btn-primary">Add Class</button>
                    <button type="submit" name="update_class" class="btn btn-info">Update Class</button>
                </div>
            </form>
        </div>
    </div>

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
                    <form method="POST" style="display: inline-block;">
                        <input type="hidden" name="class_id" value="<?php echo $class['class_id']; ?>">
                        <button type="submit" name="delete_class" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>