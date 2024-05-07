<?php
session_start();

// Ensure the user is logged in and is an administrator
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: login.php");
    exit;
}

require_once 'connection.php'; // Database connection script

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container mt-4">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
        <h2>Admin Dashboard</h2>
        <p>Manage all aspects of the database and user interactions.</p>

        <div class="list-group">
            <a href="manage_members.php" class="list-group-item list-group-item-action">Manage Members</a>
            <a href="manage_staff.php" class="list-group-item list-group-item-action">Manage Staff</a>
            <a href="manage_classes.php" class="list-group-item list-group-item-action">Manage Classes</a>
            <a href="manage_payments.php" class="list-group-item list-group-item-action">Manage Payments</a>
            <a href="manage_inventory.php" class="list-group-item list-group-item-action">Manage Inventory</a>
            <a href="manage_equipment.php" class="list-group-item list-group-item-action">Manage Equipment</a>
            <a href="logout.php" class="btn btn-danger mt-4">Log Out</a>
        </div>
    </div>
</body>
</html>