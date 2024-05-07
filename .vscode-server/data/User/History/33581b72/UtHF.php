<?php
require_once 'connection.php';
session_start();

$email = $password = "";
$login_error = "";

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
            $_SESSION['role'] = $user['role'];

            switch ($_SESSION['role']) {
                case 'guest':
                    header('Location: guest_dashboardNew.php');
                    exit;
                case 'staff':
                    header('Location: staff_dashboard.php');
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Gym Database</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font: 14px sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-image: url('assets/huge.jpg'); /* Path to the background image */
            background-size: cover; /* Cover the entire page */
            background-position: center; /* Center the background image */
        }
        .wrapper {
            width: 360px;
            padding: 20px;
            background: rgba(128, 128, 128, 0); /* Optional: adds a white, semi-transparent layer to enhance text visibility */
            color: #FFFFFF;
        }
        .form-group {
            margin-bottom: 20px; /* Add some space between form elements */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="wrapper">
            <h2>Login</h2>
            <p>Please fill in your credentials to login.</p>
            <?php if (!empty($login_error)): ?>
                <div class="alert alert-danger"><?php echo $login_error; ?></div>
            <?php endif; ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo $email; ?>" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Login</button>
                    <a href="signup.php" class="btn btn-success ml-2">Sign Up Instead</a> <!-- Sign Up button -->
                </div>
            </form>
        </div>
    </div>
</body>
</html>
