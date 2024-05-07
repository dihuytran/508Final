<?php
require_once 'connection.php';
session_start();  // Ensure session starts at the top before any outputs

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT member_id, member_name, password, role FROM member WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['member_id'];
            $_SESSION['user_name'] = $user['member_name'];
            $_SESSION['role'] = $user['role'];

            echo "Debug - Role: ".$_SESSION['role']; // Debug statement
            exit; // Stop execution to see debug output

            // Assuming the redirection logic follows
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
    <title>Login</title>
</head>
<body>
    <form method="post">
        Email: <input type="email" name="email" required><br>
        Password: <input type="password" name="password" required><br>
        <button type="submit">Log In</button>
    </form>
    <?php if (!empty($login_error)): ?>
        <p style="color: red;"><?php echo $login_error; ?></p>
    <?php endif; ?>
</body>
</html>