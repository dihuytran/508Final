<?php
require_once 'connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        // Fetch user from members, assuming all users including staff are here
        $stmt = $conn->prepare("SELECT member_id, member_name, password, role FROM member WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Debug: Print hashed password fetched from the database
            echo "<pre>";
            echo "Hashed Password from Database: " . $user['password'];
            echo "</pre>";

            if (password_verify($password, $user['password'])) {
                // Password verification successful
                // Proceed with login logic
                // ...
            } else {
                $login_error = "Invalid email or password.";
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

