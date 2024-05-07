<?php
require_once 'connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        // Check if the user is a member
        $stmt = $conn->prepare("SELECT member_id, member_name, role, password FROM member WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['member_id'];
            $_SESSION['user_name'] = $user['member_name'];
            $_SESSION['user_role'] = $user['role']; // Store user's role in session

            // Redirect based on user's role
            switch ($_SESSION['user_role']) {
                case 'guest':
                    header('Location: guest_dashboard.php');
                    exit;
                case 'staff':
                case 'admin':
                    // Fetch staff role from staff table
                    $stmt = $conn->prepare("SELECT staff_role FROM staff WHERE email = :email");
                    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                    $stmt->execute();
                    $staff_details = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($staff_details) {
                        $_SESSION['staff_role'] = $staff_details['staff_role']; // Store staff role in session
                        if ($_SESSION['staff_role'] === 'admin') {
                            header('Location: admin_dashboard.php');
                        } else {
                            header('Location: staff_dashboard.php');
                        }
                        exit;
                    }
                    break;
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
