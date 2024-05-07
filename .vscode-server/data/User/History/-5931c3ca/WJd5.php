<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            background: #333;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
        }
        .nav {
            display: flex;
            justify-content: center;
            padding-bottom: 20px;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
        }
        .nav a {
            color: #333;
            padding: 10px 15px;
            text-decoration: none;
            font-weight: bold;
            margin: 0 10px;
        }
        .nav a:hover {
            text-decoration: underline;
        }
        .container {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome, <?php echo htmlspecialchars($userInfo['member_name']); ?>!</h1>
    </div>
    <div class="nav">
        <a href="home.php">Home</a>
        <a href="about.php">About</a>
        <a href="services.php">Services</a>
        <a href="account.php">Account</a>
    </div>
    <div class="container mt-4">
        <h2>Dashboard</h2>
        
        <h3>Personal Information</h3>
        <p>Email: <?php echo htmlspecialchars($userInfo['email']); ?></p>
        <p>Phone Number: <?php echo htmlspecialchars($userInfo['phone_number']); ?></p>

        <h3>Financial Information</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Payment Method</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $payment) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($payment['amount']); ?></td>
                        <td><?php echo htmlspecialchars($payment['payment_date']); ?></td>
                        <td><?php echo htmlspecialchars($payment['payment_method']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <h3>Equipment Reservations</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Equipment ID</th>
                    <th>Inventory ID</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($equipmentReservations as $reservation) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($reservation['equipment_id']); ?></td>
                        <td><?php echo htmlspecialchars($reservation['inventory_id']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <h3>Enroll in a Class</h3>
        <form method="POST">
            <select name="class_id" required>
                <option value="">Select a Class</option>
                <?php foreach ($classes as $class) { ?>
                    <option value="<?php echo $class['class_id']; ?>"><?php echo htmlspecialchars($class['class_name']); ?></option>
                <?php } ?>
            </select>
            <button type="submit" name="enroll_class">Enroll</button>
        </form>
        
        <a href="logout.php" class="btn btn-danger">Log Out</a>
    </div>
</body>
</html>
