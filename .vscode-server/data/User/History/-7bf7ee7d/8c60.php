<?php
require_once 'connection.php';

$email = $password = $confirm_password = $name = $phone = $role = "";
$email_err = $password_err = $confirm_password_err = $name_err = $phone_err = $staffCodeError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter your name.";
    } else {
        $name = trim($_POST["name"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } else {
        $sql = "SELECT member_id FROM member WHERE email = :email";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $param_email = trim($_POST["email"]);
            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    $email_err = "This email is already taken.";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            unset($stmt);
        }
    }

    // Validate phone number
    if (empty(trim($_POST["phone_number"]))) {
        $phone_err = "Please enter a phone number.";
    } elseif (!preg_match("/^\(\d{3}\) \d{3}-\d{4}$/", trim($_POST["phone_number"]))) {
        $phone_err = "Invalid phone number format. Please use (xxx) xxx-xxxx.";
    } else {
        $phone = trim($_POST["phone_number"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

// Validate role and staff code
$role = trim($_POST["role"]);

if ($role === 'staff') {
    if (isset($_POST["staffCode"]) && !empty(trim($_POST["staffCode"]))) {
        $staffCode = trim($_POST["staffCode"]);
        if ($staffCode !== 'helloCano') {
            $staffCodeError = "Invalid staff code. Please try again.";
        }
    } else {
        $staffCodeError = "Please enter the staff code.";
    }
}

    // Check input errors before inserting in database
    if (empty($name_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($phone_err) && empty($staffCodeError)) {
        $sql = "INSERT INTO member (member_name, email, password, phone_number, role) VALUES (:name, :email, :password, :phone, :role)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bindParam(":name", $param_name, PDO::PARAM_STR);
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
            $stmt->bindParam(":phone", $phone, PDO::PARAM_STR);
            $stmt->bindParam(":role", $role, PDO::PARAM_STR);

            $param_name = $name;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            if ($stmt->execute()) {
                header("Location: login.php");
            } else {
                echo "Something went wrong. Please try again later.";
            }
            unset($stmt);
        }
    }
    unset($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font: 14px sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-image: url('assets/back.jpg');
            background-size: cover;
            background-position: center;
        }

        .wrapper {
            width: 360px;
            padding: 20px;
            background: rgba(128, 128, 128, 0);
            color: #FFFFFF;
        }

        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="wrapper">
            <h2>Sign Up</h2>
            <p>Please fill this form to create an account.</p>
            <div class="form-group">
        <label>Role</label>
        <select name="role" id="role" class="form-control" required>
            <option value="guest">Guest</option>
            <option value="staff">Staff</option>
        </select>
        <?php if (!empty($staffCodeError)): ?>
            <span class="text-danger"><?php echo $staffCodeError; ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group" id="staffCodeGroup" style="display: none;">
        <label for="staffCode">Staff Code</label>
        <input type="text" class="form-control" id="staffCode" name="staffCode">
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="login.php" class="btn btn-success ml-2">Login Instead</a>
    </div>
</form>
        </div>
    </div>

    <!-- Staff Code Modal -->
    <div class="modal fade" id="staffCodeModal" tabindex="-1" role="dialog" aria-labelledby="staffCodeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staffCodeModalLabel">Staff Code</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="staffCode">Please enter the staff code:</label>
                        <input type="text" class="form-control" id="staffCode" name="staffCode" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmStaffCode">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#role').change(function () {
                if ($(this).val() === 'staff') {
                    $('#staffCodeModal').modal('show');
                }
            });

            $('#confirmStaffCode').click(function () {
                var staffCode = $('#staffCode').val();
                if (staffCode === 'helloCano') {
                    $('#staffCodeModal').modal('hide');
                } else {
                    alert('Invalid staff code. Please try again.');
                }
            });
        });
    </script>
</body>

</html>