<?php  
require 'config/config.php';
require 'includes/form_handlers/forgot_password_handler.php';
?>

<html>
<head>
	<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0"/>
	<title>Welcome to FanZentral!</title>
	<link rel="stylesheet" type="text/css" href="assets/css/register_style.css">
	<link href="https://fonts.googleapis.com/css2?family=Ubuntu&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Fjalla+One&display=swap" rel="stylesheet">
	<link rel="icon" href="assets/images/icons/minilogo200x200.png">
</head>
<body>

	<div class="wrapper">

		<div class="login_box">

			<div class="login_header">
				<h1>FanZentral.net</h1>
				Forgot Password
			</div>
			<br>
				<form action="forgot_password.php" method="POST">
					<input type="email" name="forgot_email" placeholder="Email Address" required>
					<br>
					<?php if(in_array("No account with email<br>", $error_array)) echo "No account with email<br>"; 
					else if(in_array("Invalid email format<br>", $error_array)) echo "Invalid email format<br>"; ?>
					<input type="submit" name="forgot_password_button" value="Send reset link">
					<br>
					<?php if(in_array("<span style='color: #14C800;'>Check your email for reset link.</span><br>", $error_array)) echo "<span style='color: #14C800;'>Check your email for reset link.</span><br>"; ?>
					<a href="register.php" id="signup" class="signup">Already have an account? Sign in here!</a>
					

				</form>

		</div>

	</div>


</body>
</html>