<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: login.php");
    exit;
}

// Add member logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_member'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    $sql = "INSERT INTO member (member_name, email, phone_number, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$name, $email, $phone, $role]);
}

// Delete member logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_member'])) {
    $memberId = $_POST['member_id'];

    // Check if the member is a staff
    $sql = "SELECT role FROM member WHERE member_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$memberId]);
    $role = $stmt->fetchColumn();

    if ($role !== 'staff') {
        $sql = "DELETE FROM member WHERE member_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$memberId]);
        $deleteSuccess = true;
    } else {
        $deleteError = "Cannot delete staff members.";
    }
}

// Example fetch for display
$stmt = $conn->prepare("SELECT member_id, member_name, email, phone_number, role FROM member");
$stmt->execute();
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Members</title>
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
    </style>
</head>
<body>
    <div class="container">
        <div class="banner">
            <h1>Manage Members</h1>
        </div>

        <nav class="navbar">
            <ul>
                <li><a href="staff_dashboard.php">Dashboard</a></li>
                <li><a href="manage_members.php">Manage Members</a></li>
                <li><a href="manage_classes.php">Manage Classes</a></li>
            </ul>
        </nav>

        <?php if (isset($deleteSuccess)): ?>
            <div class="alert alert-success">Member deleted successfully!</div>
        <?php elseif (isset($deleteError)): ?>
            <div class="alert alert-danger"><?php echo $deleteError; ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <select name="role" required>
                <option value="guest">Guest</option>
                <option value="staff">Staff</option>
            </select>
            <button type="submit" name="add_member">Add Member</button>
        </form>
        <form method="POST">
            <input type="number" name="member_id" placeholder="Member ID" required>
            <button type="submit" name="delete_member">Delete Member</button>
        </form>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($members as $member) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($member['member_id']); ?></td>
                    <td><?php echo htmlspecialchars($member['member_name']); ?></td>
                    <td><?php echo htmlspecialchars($member['email']); ?></td>
                    <td><?php echo htmlspecialchars($member['phone_number']); ?></td>
                    <td><?php echo htmlspecialchars($member['role']); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>