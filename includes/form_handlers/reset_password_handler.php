<?php 

$error_array = array();

if(isset($_POST['reset_password_button'])){

	$expiry_date = date(strtotime('now'));
	$token = $_POST['token'];
	$password = $_POST['new_password_1'];
	$password2 = $_POST['new_password_2'];

	$email_query = mysqli_query($con, "SELECT * FROM password_reset WHERE password_reset_token='$token'");

	$row = mysqli_fetch_array($email_query);

	$date_created_token = $row['password_reset_expires'];
	$email = $row['email'];

	if($date_created_token>$expiry_date){
		array_push($error_array, "Sorry, request expired. You need to re-submit a forgot password request.<br>");
	}else{
		if($password!=$password2){
			array_push($error_array, "Your passwords do not match<br>");
			header('Location: ../../reset_password.php?token=".$token."');
			exit();
		}elseif(preg_match('/[^A-Za-z0-9]/', $password)) {
			array_push($error_array, "Your password can only contain english characters or numbers<br>");
			header('Location: ../../reset_password.php?token=".$token."');
			exit();
		}
		elseif (strlen($password > 30 || strlen($password) < 5)) {
			array_push($error_array, "Your password must be betwen 5 and 30 characters<br>");
			header('Location: ../../reset_password.php?token=".$token."');
			exit();
		}elseif(empty($error_array)) {
		$password = md5($password); //Encrypt password before sending to database
	
		$update_query = mysqli_query($con, "UPDATE users SET password='$password' WHERE email='$email'");


	}

} 
}
?>