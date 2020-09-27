<?php 
include("../../config/config.php");
include("../classes/User.php");
include("../classes/Trivia.php");

$limit = 5;	//Number of posts to be loaded per call

$posts = new Trivia($con, $_REQUEST['userLoggedIn']);
$posts->loadTriviaPosts($_REQUEST, $limit);


 ?>