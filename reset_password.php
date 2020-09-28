<?php  
require 'config/config.php';
$error_array = array();

if(isset($_GET['token'])){
	$token = $_GET['token'];
}

if(isset($_POST['reset_password_button'])){

	$expiry_date = date("Y-m-d H:i:s", strtotime('now'));
	$password = $_POST['new_password_1'];
	$password2 = $_POST['new_password_2'];
	$token=$_POST['token'];

	$email_query = mysqli_query($con, "SELECT * FROM password_reset WHERE password_reset_token='$token'");

	$row = mysqli_fetch_array($email_query);

	$date_created_token = $row['password_reset_expires'];
	$email = $row['password_reset_email'];

	if($date_created_token<$expiry_date){
		array_push($error_array, "Sorry, request expired. You need to re-submit a forgot password request.<br>");
	}else{
		if($password!=$password2){
			array_push($error_array, "Your passwords do not match<br>");
		}elseif(preg_match('/[^A-Za-z0-9]/', $password)) {
			array_push($error_array, "Your password can only contain english characters or numbers<br>");
		}
		elseif (strlen($password > 30 || strlen($password) < 5)) {
			array_push($error_array, "Your password must be betwen 5 and 30 characters<br>");
		}
		elseif(empty($error_array)) {
		$password = md5($password); //Encrypt password before sending to database
	
		$update_query = mysqli_query($con, "UPDATE users SET password='$password' WHERE email='$email'");
		array_push($error_array, "<span style='color: #14C800;'>Password updated, You can login now</span><br>");


	}

} 
}
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
			<form action="reset_password.php" method="POST">
				<input type="password" name="new_password_1" placeholder="New Password" required><br>
				<input type="password" name="new_password_2" placeholder="New Password again" required><br>
				<?php if(in_array("Your passwords do not match<br>", $error_array)) echo "Your passwords do not match<br>"; 
				else if(in_array("Your password can only contain english characters or numbers<br>", $error_array)) echo "Your password can only contain english characters or numbers<br>";
				else if(in_array("Your password must be betwen 5 and 30 characters<br>", $error_array)) echo "Your password must be betwen 5 and 30 characters<br>"; 
				else if(in_array("Sorry, request expired. You need to re-submit a forgot password request.<br>", $error_array)) echo "Sorry, request expired. You need to re-submit a forgot password request.<br>";?>
				<input type="submit" name="reset_password_button" value="Update password">
				<input type="hidden" name="token" value="<?php echo $token;?>">
				<br>
				<?php if(in_array("<span style='color: #14C800;'>Password updated, You can login now</span><br>", $error_array)) echo "<span style='color: #14C800;'>Password updated, You can login now</span><br>"; ?>
				<a href="register.php" id="signup" class="signup">Already have an account? Sign in here!</a>
				
			</form>
		</div>

	</div>


</body>
</html>