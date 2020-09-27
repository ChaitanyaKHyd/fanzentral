<?php  
require 'config/config.php';
require 'includes/form_handlers/reset_password_handler.php';

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
				Reset Password
				<br>
			</div>
			<br>
			<?php 
			$token = $_GET['token'];

			if(!empty($token)){
			 ?>
				<form action="includes/form_handlers/reset_password_handler.php" method="POST">
					<input type="password" name="new_password_1" placeholder="New Password" required><br>
 	 				<input type="password" name="new_password_2" placeholder="New Password again" required><br>
					<?php if(in_array("Your passwords do not match<br>", $error_array)) echo "Your passwords do not match<br>"; 
					else if(in_array("Your password can only contain english characters or numbers<br>", $error_array)) echo "Your password can only contain english characters or numbers<br>";
					else if(in_array("Your password must be betwen 5 and 30 characters<br>", $error_array)) echo "Your password must be betwen 5 and 30 characters<br>"; 
					else if(in_array("Sorry, request expired. You need to re-submit a forgot password request.<br>", $error_array)) echo "Sorry, request expired. You need to re-submit a forgot password request.<br>";?>
					<input type="submit" name="reset_password_button" value="Update password">
					<br>
					<?php if(in_array("<span style='color: #14C800;'>Password updated, You can login now</span><br>", $error_array)) echo "<span style='color: #14C800;'>Password updated, You can login now</span><br>"; ?>
					<a href="register.php" id="signup" class="signup">Already have an account? Sign in here!</a>
					
				</form>
				<?php
			}
			?>
		</div>

	</div>


</body>
</html>