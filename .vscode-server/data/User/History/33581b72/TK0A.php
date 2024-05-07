<?php
require_once 'connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT member_id, member_name, password, role FROM member WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['member_id'];
            $_SESSION['user_name'] = $user['member_name'];
            $_SESSION['user_role'] = $user['role'];

            switch ($_SESSION['user_role']) {
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
                    header('Location: login.php');
                    exit;
            }
        } else {
            $login_error = "Invalid email or password.";
        }
    } else {
        $login_error = "Please fill in all fields.";
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
    <?php if (isset($login_error)): ?>
        <p style="color: red;"><?php echo $login_error; ?></p>
    <?php endif; ?>
</body>
</html>