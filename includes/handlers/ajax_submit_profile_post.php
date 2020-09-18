<?php 
require '../../config/config.php';
include("../classes/User.php");
include("../classes/Feed.php");

if(isset($_POST['post_body'])){
	$imageName = "";
	$post = new Feed($con,$_POST['user_from']);
	$post->submitFeed($_POST['post_body'], $_POST['user_to'],$imageName);
}

 ?>