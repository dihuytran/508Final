<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Add staff logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_staff'])) {
    $name = $_POST['name'];
    $role = $_POST['role'];

    $sql = "INSERT INTO staff (staff_name, staff_role) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$name, $role]);
}

// Fetch staff for display
$stmt = $conn->prepare("SELECT staff_id, staff_name, staff_role FROM staff");
$stmt->execute();
$staffs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Staff</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Manage Staff</h1>
        <form method="POST">
            <input type="text" name="name" placeholder="Name" required>
            <input type="text" name="role" placeholder="Role" required>
            <button type="submit" name="add_staff">Add Staff</button>
        </form>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($staffs as $staff) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($staff['staff_id']); ?></td>
                        <td><?php echo htmlspecialchars($staff['staff_name']); ?></td>
                        <td><?php echo htmlspecialchars($staff['staff_role']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>