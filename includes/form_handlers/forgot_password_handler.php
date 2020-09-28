<?php 

//Declaring variables to prevent errors
$email = "";
$token = "";
$date_expires = "";
$error_array = array();

if(isset($_POST['forgot_password_button'])){
 
$email = strip_tags($_POST['forgot_email']);
$email = str_replace(' ', '', $email);

if(filter_var($email, FILTER_VALIDATE_EMAIL)) {

	$email = filter_var($email, FILTER_VALIDATE_EMAIL);

	//Check if email already exists 
	$e_check = mysqli_query($con, "SELECT email FROM users WHERE email='$email'");

	//Count the number of rows returned
	$num_rows = mysqli_num_rows($e_check);

	if($num_rows < 1) {
		array_push($error_array, "No account with email<br>");
	}
	else{
		$token = md5(uniqid(rand(), true));
		$url = "fanzentral.net/reset_password.php?token=".$token;

		$date_expires = date("Y-m-d H:i:s", strtotime('+5 minutes')); 

		$password_email_query = mysqli_query($con, "INSERT INTO password_reset VALUES('', '$email', '$token', '$date_expires')" );
		array_push($error_array, "<span style='color: #14C800;'>Check your email for reset link.</span><br>");

		$to = $email;
		$subject = "Reset your password for FanZentral.net";

		$message = '<p>We received a password reset request. If you did not make this request, you can ignore this email</p><p>Here is your password reset link: </br><a href='.$url.'><button>Reset Password</button></a></p>';

		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		// More headers
		$headers .= 'From: <admin@fanzentral.net>' . "\r\n";

		mail($to,$subject,$message,$headers);
	}
}
else {
	array_push($error_array, "Invalid email format<br>");
}

}
 ?>