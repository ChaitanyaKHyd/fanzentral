<?php 

include("../../config/config.php");

include("../classes/Trivia_topic.php");



$limit = 10;	//Number of posts to be loaded per call



$posts = new Trivia_topic($con);

$posts->loadTriviaTopics($_REQUEST, $limit);





 ?>