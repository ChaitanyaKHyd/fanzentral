<?php 
require '../../config/config.php';
include("../classes/User.php");

if(isset($_GET['post_id'])){
	$post_id = $_GET['post_id'];
}

if(isset($_POST['result'])){
	if($_POST['result']=='true')
		$query=mysqli_query($con, "UPDATE trivia SET deleted='yes' WHERE id='$post_id'");

		//Update post count for user 
		$delete_query = mysqli_query($con, "SELECT * FROM trivia WHERE id='$post_id'");
		$row = mysqli_fetch_array($delete_query);
		$added_by = $row['added_by'];
		$user_obj_1 = new User($con, $added_by);
		$num_posts = $user_obj_1->getNumPosts();
		$num_posts--;
		$update_query = mysqli_query($con, "UPDATE users SET num_posts='$num_posts' WHERE username='$added_by'");
		$remove_upvotes_query = mysqli_query($con, "SELECT * FROM upvotes WHERE post_id='$post_id'");
		$remove_upvotes = mysqli_num_rows($remove_upvotes_query);
		$total_user_upvotes_query = mysqli_query($con, "SELECT num_upvotes FROM users WHERE username='$added_by'");
		$row = mysqli_fetch_array($total_user_upvotes_query);
		$total_user_upvotes = $row['num_upvotes'];

		$total_user_upvotes = $total_user_upvotes-$remove_upvotes;

		$user_upvotes_updated = mysqli_query($con, "UPDATE users SET num_upvotes='$total_user_upvotes' WHERE username='$added_by'");
}

 ?>