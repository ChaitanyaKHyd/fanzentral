<?php 
include("includes/header.php");
include("includes/form_handlers/settings_handler.php");
 ?>

 <div class="main_settings_column column">
 	<h4>Account Settings</h4>
 	<?php 
 	echo "<img src='".$user['profile_pic']."' class='small_profile_pic'>";
 	 ?>
 	 <br>
 	 <a href="upload.php" class="pc">Upload new profile picture</a><br><br><br>
 	 <a href="upload_2.php" class="mob">Upload new profile picture</a><br><br><br>
 	 <h4>Close Account</h4>
 	 <form action="settings.php" method="POST">
 	 	<input type="submit" name="close_account" id="close_account" value="Close Account" class="warning settings_submit">
	  </form>
	  <div>
 	 <div class="name_column">
 	 <h4>Change Name and Email</h4>
 	 <?php 
 	 $user_data_query = mysqli_query($con, "SELECT first_name, last_name, email FROM users WHERE username='$userLoggedIn'");
 	 $row = mysqli_fetch_array($user_data_query);

 	 $first_name = $row['first_name'];
 	 $last_name = $row['last_name'];
 	 $email = $row['email'];
 	  ?>
 	 <form action="settings.php" method="POST">
 	 	<label>First Name: </label><input type="text" name="first_name" value="<?php echo $first_name; ?>"id="settings_input"><br>
 	 	<label>Last Name: </label><input type="text" name="last_name" value="<?php echo $last_name; ?>"id="settings_input"><br>
 	 	<label>Email: </label><input type="text" name="email" value="<?php echo $email; ?>"id="settings_input"><br>
 	 	<?php echo $message; ?>
 	 	<input type="submit" name="update_details" id="save_details" value="Update Details" class="default settings_submit"><br>
 	 </form>
 	 </div>
 	 <div class="password_column">
 	 <h4>Change Password</h4>
 	 <form action="settings.php" method="POST">
 	 	<label>Old Password: </label><input type="password" name="old_password" id="settings_input"><br>
 	 	<label>New Password: </label><input type="password" name="new_password_1" id="settings_input"><br>
 	 	<label>New Password Again: </label><input type="password" name="new_password_2" id="settings_input"><br>
 	 	<?php echo $password_message; ?>
 	 	<input type="submit" name="update_password" id="save_password" value="Update Password" class="default settings_submit"><br>
 	 </form>
	  </div>
	  <div class="about_us">
		  <a href="privacy_policy.php">Privacy policy</a>
		  <a href="contact_us.php">Contact us</a>
		  <a href="about_us.php">About us</a>
		  <a href="dmca.php">DMCA</a>
	  </div>
 </div>
</div>