<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guest') {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

// Handle equipment reservation
// ...

// Handle equipment return
// ...

// Fetch available equipment from the inventory table
$stmt = $conn->query("SELECT * FROM inventory");
$inventory = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipment Reservation</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .banner {
            background-color: #007bff;
            color: #fff;
            padding: 20px;
            text-align: center;
            margin-bottom: 30px;
        }
        .banner h1 {
            margin: 0;
            font-size: 36px;
        }
        .navbar {
            background-color: #f8f9fa;
            padding: 10px;
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
        }
        .navbar ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
        }
        .navbar li {
            margin: 0 10px;
        }
        .navbar a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }
        .navbar a:hover {
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="banner">
            <h1>Equipment Reservation</h1>
        </div>

        <nav class="navbar">
            <ul>
                <li><a href="guest_dashboard.php">Dashboard</a></li>
                <li><a href="enroll.php">Classes</a></li>
                <li><a href="account.php">Account</a></li>
            </ul>
        </nav>

        <?php if (isset($reservationSuccess)): ?>
            <div class="alert alert-success">Equipment reserved successfully!</div>
        <?php elseif (isset($reservationError)): ?>
            <div class="alert alert-danger"><?php echo $reservationError; ?></div>
        <?php endif; ?>

        <?php if (isset($returnSuccess)): ?>
            <div class="alert alert-success">Equipment returned successfully!</div>
        <?php elseif (isset($returnError)): ?>
            <div class="alert alert-danger"><?php echo $returnError; ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label for="inventory_id">Select Equipment:</label>
                <select name="inventory_id" id="inventory_id" class="form-control" required>
                    <option value="">Select Equipment</option>
                    <?php foreach ($inventory as $item): ?>
                        <option value="<?php echo $item['inventory_id']; ?>">
                            <?php echo $item['type']; ?> - Quantity Available: <?php echo $item['quantity_available']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" name="reserve" class="btn btn-primary">Reserve</button>
            <button type="submit" name="return" class="btn btn-secondary">Return</button>
        </form>

        <h3 class="mt-4">Available Equipment</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Equipment</th>
                    <th>Quantity Available</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($inventory as $item): ?>
                    <tr>
                        <td><?php echo $item['type']; ?></td>
                        <td><?php echo $item['quantity_available']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>