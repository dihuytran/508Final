<html>
<head>
<title>Gym database</title>
<?php require_once('header.php'); ?>
</head>

<?php require_once('connection.php'); ?>

<body>

	<div class="container mt-3 mb-3">
		<form method="post">
			<div class="row justify-content-center">
				<div class="col-4">
					<div class="form-group">
						<label>Email:</label>
						<input type="email" class="form-control" id="email" placeholder="Enter email" name="email" required>
					</div>
					<div class="form-group">
						<label>Password:</label>
						<input type="password" class="form-control" id="password" placeholder="Enter password" name="password" required>
					</div>
					<button type="submit" class="btn btn-primary">Log in</button>
				</div>
			</div>
		</form>
	</div>
	
	<div class="container">
	
	<p>GYM To use this page, you need to first create a table `user` on your database as:</p>
	
	<p>
	<code>
    CREATE TABLE `user` (
      `ID` int(11)  PRIMARY KEY AUTO_INCREMENT,
      `email` varchar(255) NOT NULL,
      `password` char(60) NOT NULL,
      `name` varchar(255) NOT NULL
    );
    </code>
    </p>
    
    <p>Then add a new user (e.g. user/password both <b>test@vcu.edu</b>). Use <a href='https://onlinephp.io/password-hash'>https://onlinephp.io/password-hash</a> to generate the hash for the password</p>
    
    <p>
    <code>INSERT INTO `user` (`ID`, `email`, `password`, `name`) VALUES (1, 'test@vcu.edu', '$2y$10$m2VseJAjEXWM5MTYb8dfaehQGNzok5eT4GtNFu4nFw5hp6iLZ.yAK', 'Test User');</code>
    </p>
    
    <p>Recommended practice: Create the sign up option to add new users to the database</p>
     
	</div>

</body>
</html>