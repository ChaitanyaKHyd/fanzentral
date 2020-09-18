<?php 
include("includes/header.php");
include("includes/classes/Trivia.php");

if(isset($_GET['id'])){
	$id = $_GET['id'];
}
else{
	$id = 0;
}

$id = $_GET['id'];
$query = mysqli_query($con, "SELECT * FROM trivia WHERE id='$id'");
$row = mysqli_fetch_array($query);
$trivia_topic = $row['trivia_topic'];
$desc_query = mysqli_query($con, "SELECT * FROM trivia_topics WHERE topic='$trivia_topic'");
$desc_row = mysqli_fetch_array($desc_query);
$trivia_topic_description = $desc_row['description'];
$trivia_topic_id = $desc_row['id'];

 ?>
<div class="single_trivia_topic">
    <a href="<?php echo 'trivia_page.php?id='.$trivia_topic_id;?>"><h1><?php echo $trivia_topic; ?></h1></a>
    <h5><?php echo $trivia_topic_description; ?></h5>
</div>

<div class="trivia_main_column trivia_column" id="main_column">
	<div class="posts_area">
		<?php 
			$post = new Trivia($con, $userLoggedIn);
			$post->getSingleTrivia($id);
		 ?>
	</div>
</div>
</div>