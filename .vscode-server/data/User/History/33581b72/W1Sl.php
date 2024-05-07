<?php
require_once 'connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        // Prepare and execute the SQL statement
        $stmt = $conn->prepare("SELECT member_id, member_name, password, role FROM member WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['member_id'];
            $_SESSION['user_name'] = $user['member_name'];
            $_SESSION['role'] = $user['role'];

            // Debugging output
            echo 'User ID: ' . $_SESSION['user_id'] . '<br>';
            echo 'User Name: ' . $_SESSION['user_name'] . '<br>';
            echo 'Role: ' . $_SESSION['role'] . '<br>';
            // Temporarily stop the script to see the debug output
            exit();

            // Redirect based on role
            switch ($_SESSION['role']) {
                case 'guest':
                    header('Location: guest_dashboard.php');
                    exit;
                case 'staff':
                    header('Location: staff_dashboard.php');
                    exit;
                case 'admin':
                    header('Location: admin_dashboard.php');
                    exit;
                default:
                    header('Location: login.php'); // Redirect to login page for undefined roles
                    exit;
            }
        } else {
            echo "<p class='alert alert-danger' role='alert'>Invalid email or password.</p>";
        }
    } else {
        echo "<p class='alert alert-warning' role='alert'>Please fill in all fields.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Gym Database</title>
</head>
<body>
    <form method="post">
        Email: <input type="email" name="email" required><br>
        Password: <input type="password" name="password" required><br>
        <button type="submit">Log In</button>
    </form>
</body>
</html>