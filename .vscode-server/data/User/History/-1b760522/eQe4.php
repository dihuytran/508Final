<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guest') {
    header("Location: login.php");
    exit;
}

date_default_timezone_set('America/New_York'); // Set the timezone to Eastern Standard Time

$userId = $_SESSION['user_id'];
$userInfo = $conn->prepare("SELECT member_name FROM member WHERE member_id = ?");
$userInfo->execute([$userId]);
$userInfo = $userInfo->fetch(PDO::FETCH_ASSOC);
?>

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
            margin: 0;
            padding: 0;
            background: #f4f4f4;
        }

        .header {
            background: #333;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
            padding-top: 30px;
            padding-bottom: 30px;
        }

        .welcome-banner {
            text-align: center;
            opacity: 0;
            transition: opacity 2s;
            font-size: 32px;
            /* Increased font size */
            padding: 20px;
            margin-top: 50px;
            font-weight: bold;
            /* Make text bold */
        }

        .content {
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
            text-align: left;
            font-size: 18px;
            /* Regular content font size */
        }

        .service-description {
            font-size: 24px;
            /* Larger font size for important service description */
        }

        .visible {
            opacity: 1;
        }

        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }

        /* Custom styles for columns */
        .column {
            padding: 10px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            /* Make columns occupy full height */
        }
        .btn-wide {
            padding: 10px 50px; /* Adjust the padding to make the button wider */
        }

        .text-center {
            text-align: center;
        }

        .logout-button {
            margin-top: 30px; /* Adjust the margin value to increase or decrease the space */
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Fade in the welcome message
            const welcomeBanner = document.getElementById('welcome-banner');
            setTimeout(() => {
                welcomeBanner.style.opacity = 1;
            }, 500);

            // Fade in the content half a second after the banner is visible
            setTimeout(() => {
                document.querySelectorAll('.content').forEach(element => {
                    element.classList.add('visible');
                });
            }, 1000); // Half a second after the banner
        });
    </script>
</head>

<body>
    <div class="header">
        <h1>Welcome, <?php echo htmlspecialchars($userInfo['member_name']); ?>!</h1>
    </div>
    <div id="welcome-banner" class="welcome-banner">
        The current date and time is <em><?php echo date('F j, g:i A'); ?> EST</em>, what would you like to do today?
    </div>
    <div class="container">
        <div class="content mt-4 service-description">
            <p>This service allows guests to enroll in our various classes, reserve, rent, and return equipment from our
                gear inventory,
                as well as view your previous gym transactions and any other relevant information. If you have any
                questions or concerns,
                do reach out to any of our underage, underpaid, and overworked staff!</p>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="content mt-4 column">
                    <p>Explore our offered classes! We have trainers specializing in everything from weight loss to
                        olympic lifting. Simply apply for the
                        class of your choice and enter the time that you desire!
                    </p>
                    <button onclick="location.href='enroll.php'"
                        class="btn btn-primary align-self-start">Classes</button>
                </div>
            </div>
            <div class="col-md-4">
                <div class="content mt-4 column">
                    <p>We carry many types of equipment in our facility; need boxing gloves for use on our heavy bags,
                        or maybe
                        lifting belts and straps? We have it, and it probably isn't broken!.</p>
                    <button onclick="location.href='equipment.php'"
                        class="btn btn-primary align-self-start">Equipment</button>
                </div>
            </div>
            <div class="col-md-4">
                <div class="content mt-4 column">
                    <p>Manage your personal account settings, view your payment history, and update your profile without
                        having
                        to speak to anyone at our front desk!.</p>
                    <button onclick="location.href='guest_dashboard.php'"
                        class="btn btn-primary align-self-start">Account</button>
                </div>
            </div>
        </div>

        <div class="content mt-4 text-center">
            <a href="logout.php" class="btn btn-danger btn-wide logout-button">Log Out</a>
        </div>
    </div>
</body>

</html>