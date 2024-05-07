<?php
require_once 'connection.php';  // Ensure the database connection is included

session_start();  // Start the session at the top of the script

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check if the input fields are not empty
    if (!empty($email) && !empty($password)) {
        // Prepare a select statement to fetch user data based on the email
        $stmt = $conn->prepare("SELECT member_id, member_name, password, role FROM member WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the user and the password
        if ($user && password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['member_id'];
            $_SESSION['user_name'] = $user['member_name'];
            $_SESSION['role'] = $user['role'];

            // Redirect user based on their role
            switch ($_SESSION['role']) {
                case 'guest':
                    header('Location: guest_dashboard.php');
                    break;
                case 'staff':
                    header('Location: staff_dashboard.php');
                    break;
                case 'admin':
                    header('Location: admin_dashboard.php');
                    break;
                default:
                    header('Location: login.php'); // Redirect to login for undefined roles
                    break;
            }
            exit;
        } else {
            // If user is not found or password does not match
            $login_error = "Invalid email or password.";
        }
    } else {
        $login_error = "Please fill in both email and password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Gym Database</title>
</head>
<body>
    <div class="container">
        <h2>Login to Your Account</h2>
        <form method="post">
            <div>
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <div>
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            <div>
                <button type="submit">Log in</button>
                <a href="signup.php">Sign Up</a>
            </div>
            <?php if (isset($login_error)): ?>
                <p style="color: red;"><?php echo $login_error; ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>