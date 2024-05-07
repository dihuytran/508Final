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
            background-image: url('assets0/huge.jpg'); /* Path to the background image */
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
                    <a href="signup.php" class="btn btn-success ml-2">Sign Up</a> <!-- Sign Up button -->
                </div>
            </form>
        </div>
    </div>
</body>
</html>
