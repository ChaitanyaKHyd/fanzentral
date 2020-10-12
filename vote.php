<?php  

require 'config/config.php';

include("includes/classes/User.php");

include("includes/classes/Notification.php");	



if(isset($_SESSION['username'])){

	$userLoggedIn = $_SESSION['username'];

	$user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");

	$user = mysqli_fetch_array($user_details_query);

}

else{

	$userLoggedIn = 'mickey_mouse';

	$user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
	
	$user = mysqli_fetch_array($user_details_query);
}



?>



<!DOCTYPE html>

<html>

<head>

	<title></title>

	<link rel="stylesheet" type="text/css" href="assets/css/style.css">

	<link href="https://fonts.googleapis.com/css2?family=Ubuntu&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Hind:wght@300&display=swap" rel="stylesheet">	

</head>

<body>

<style type="text/css">

	*{

		font-family: 'Hind', 'Ubuntu', sans-serif;

	}



	body{

		background-color: #fff;

	}



	form{

		position: absolute;

		top: 0;

	}

</style>

<?php 

//Get id of post

if(isset($_GET['id'])){

	$id = $_GET['id'];

}



$get_votes = mysqli_query($con,"SELECT upvotes, added_by FROM trivia WHERE id='$id'");

$votes_row = mysqli_fetch_array($get_votes);

$total_upvotes = $votes_row['upvotes'];

$user_voted_on = $votes_row['added_by'];



$user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$user_voted_on'");

$row = mysqli_fetch_array($user_details_query);

$total_user_upvotes = $row['num_upvotes'];



//Upvote Button

if(isset($_POST['upvote_button'])){

	$total_upvotes++;

	$query = mysqli_query($con, "UPDATE trivia SET upvotes='$total_upvotes' WHERE id='$id'");

	$total_user_upvotes++;

	$user_upvotes = mysqli_query($con, "UPDATE users SET num_upvotes='$total_user_upvotes' WHERE username='$user_voted_on'");

	$insert_user = mysqli_query($con, "INSERT INTO upvotes VALUES('','$userLoggedIn', '$id')");



	//Insert notification

	if($user_voted_on!=$userLoggedIn){

		$notification = new Notification($con, $userLoggedIn);

		$notification->insertNotification($id, $user_voted_on, 'upvote');

	}

}



//Downvote Button

if(isset($_POST['downvote_button'])){

	$total_upvotes--;

	$query = mysqli_query($con, "UPDATE trivia SET upvotes='$total_upvotes' WHERE id='$id'");

	$total_user_upvotes--;

	$user_upvotes = mysqli_query($con, "UPDATE users SET num_upvotes='$total_user_upvotes' WHERE username='$user_voted_on'");

	$insert_user = mysqli_query($con, "DELETE FROM upvotes WHERE username='$userLoggedIn' AND post_id='$id'");

}



//Check for previous votes

$check_query_upvoted = mysqli_query($con, "SELECT * FROM upvotes WHERE username='$userLoggedIn' AND post_id='$id'");

$num_rows = mysqli_num_rows($check_query_upvoted);



if($num_rows > 0){

	echo($total_upvotes==1)? '<form action="vote.php?id='.$id.'"method="POST">

								<input type="submit" class="comment_vote" name="downvote_button" value="Remove Upvote">

								<div class="upvote_value">

								'.$total_upvotes.' Upvote

								</div>

								</form>':'
								<form action="vote.php?id='.$id.'"method="POST">

								<input type="submit" class="comment_vote" name="downvote_button" value="Remove Upvote">

								<div class="upvote_value">

								'.$total_upvotes.' Upvotes

								</div>

								</form>';

}

else{

	echo($total_upvotes==1)? '<form action="vote.php?id='.$id.'"method="POST">

								<input type="submit" class="comment_vote" name="upvote_button" value="Upvote">

								<div class="upvote_value">

								'.$total_upvotes.' Upvote

								</div>

								</form>':'
								<form action="vote.php?id='.$id.'"method="POST">

								<input type="submit" class="comment_vote" name="upvote_button" value="Upvote">

								<div class="upvote_value">

								'.$total_upvotes.' Upvotes

								</div>

								</form>';

}



?>



</body>

</html>