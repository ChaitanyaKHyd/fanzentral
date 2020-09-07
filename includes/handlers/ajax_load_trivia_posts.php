<?php 
include("../../config/config.php");
include("../classes/User.php");
include("../classes/Trivia.php");

$limit = 10;	//Number of posts to be loaded per call

$posts = new Trivia($con, $_REQUEST['trivia_topic']);
$posts->loadTriviaPosts($_REQUEST, $limit);


 ?>