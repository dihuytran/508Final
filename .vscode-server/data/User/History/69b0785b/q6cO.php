<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Our Gym Management System</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            position: relative;
            overflow: hidden;
        }
        .container {
            text-align: center;
            color: #fff;
            z-index: 1;
        }
        video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }
        .welcome-text {
            opacity: 0;
            transform: translateY(-50px);
            animation: fadeInSlideDown 1s ease-in-out forwards;
        }
        .sub-text, .buttons {
            opacity: 0;
            animation: fadeIn 1s ease-in-out 0.5s forwards;
        }
        @keyframes fadeInSlideDown {
            0% {
                opacity: 0;
                transform: translateY(-50px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes fadeIn {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <video autoplay loop muted>
        <source src="assets0/gym508.mp4" type="video/mp4">
    </video>
    <div class="container">
        <h1 class="welcome-text">Welcome to The Gym Kiosk</h1>
        <p class="sub-text">Enrolling for classes, reserving equipment, and changing your account information has never been easier.</p>
        <div class="buttons">
            <a href="login.php" class="btn btn-primary">Login</a>
            <a href="signup.php" class="btn btn-secondary">Sign Up</a>
        </div>
    </div>
</body>
</html>