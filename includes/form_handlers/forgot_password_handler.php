<?php 
//Declaring variables to prevent errors
$email = "";
$token = "";
$date = "";
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
		$url = "fanzentral/reset_password.php?token=".$token;

		$date_expires = date("Y-m-d H:i:s", strtotime('+1 hours')); 

		$password_email_query = mysqli_query($con, "INSERT INTO password_reset VALUES('', '$email', '$token', '$date_expires')" );
		array_push($error_array, "<span style='color: #14C800;'>Check your email for reset link.</span><br>");

		$to = $email;
		$subject = "Reset your password for FanZentral.net";

		$message = "<p>We received a password reset request. If you did not make this request, you can ignore this email</p>";

		$message .= "<p>Here is your password reset link: </br>";
		$message .= "<a href='".$url."'><button value='Reset Password'></a></p>";

		$headers = "From: Fanzentral <chaitanyak.hyd@gmail.com>"."\r\n";
		$headers .= "Reply to: chaitanyak.hyd@gmail.com"."\r\n";
		$headers .= "Content-type: text/html"."\r\n";

		mail($to, $subject, $message, $headers);
	}
}
else {
	array_push($error_array, "Invalid email format<br>");
}

}
 ?>