<?php
require_once 'connection.php';

$name = $email = $phone = $password = "";
$role = "staff"; // Default to 'staff', can be changed to 'admin' via dropdown
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone_number"]);
    $password = trim($_POST["password"]);
    $role = trim($_POST["role"]);

    if (empty($name) || empty($email) || empty($phone) || empty($password)) {
        $errors[] = "All fields are required.";
    } elseif (!preg_match("/^\(\d{3}\) \d{3}-\d{4}$/", $phone)) {
        $errors[] = "Invalid phone number format. Please use (xxx) xxx-xxxx.";
    } else {
        // Check if email is already taken in the staff table
        $stmt = $conn->prepare("SELECT staff_id FROM staff WHERE email = :email");
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $errors[] = "This email is already taken.";
        } else {
            // Insert the new user into the staff table
            $sql = "INSERT INTO staff (staff_name, email, password, phone_number, staff_role) VALUES (:name, :email, :password, :phone, :role)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password", password_hash($password, PASSWORD_DEFAULT));
            $stmt->bindParam(":phone", $phone);
            $stmt->bindParam(":role", $role);

            if ($stmt->execute()) {
                $success = "Staff/Admin user successfully added.";
            } else {
                $errors[] = "Something went wrong. Please try again later.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Admin/Staff User</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>Add Admin/Staff User</h2>
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger"><?php echo implode('<br>', $errors); ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    <form action="" method="post">
        <div class="form-group">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
        </div>
        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
        </div>
        <div class="form-group">
            <label>Phone Number:</label>
            <input type="text" name="phone_number" class="form-control" value="<?php echo $phone; ?>">
        </div>
        <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="form-group">
            <label>Role:</label>
            <select name="role" class="form-control">
                <option value="staff" <?php echo $role === 'staff' ? 'selected' : ''; ?>>Staff</option>
                <option value="admin" <?php echo $role === 'admin' ? 'selected' : ''; ?>>Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Add User</button>
    </form>
</div>
</body>
</html>