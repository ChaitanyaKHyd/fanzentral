<?php 
include("../../config/config.php");
include("../classes/User.php");
include("../classes/Feed.php");

$limit = 5;	//Number of posts to be loaded per call

$posts = new Feed($con, $_REQUEST['userLoggedIn']);
$posts->loadProfilePosts($_REQUEST, $limit);


 ?>