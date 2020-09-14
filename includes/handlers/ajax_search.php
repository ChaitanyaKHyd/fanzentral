<?php 
include("../../config/config.php");
include("../../includes/classes/User.php");

$query = $_POST['query'];
$userLoggedIn = $_POST['userLoggedIn'];

$search = explode(" ", $query);


 $topicreturnedQuery = mysqli_query($con, "SELECT * FROM trivia_topics WHERE topic LIKE '$search[0]%'");
//If there are two words, assume they are first and last name respectively
 if(count($search) == 2)
	$returnedQuery = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$search[0]%' AND last_name LIKE '$search[1]%') AND user_closed='no' LIMIT 8");
//If query has one word only, search first names or last names
else 
	$returnedQuery = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$search[0]%' OR last_name LIKE '$search[0]%') AND user_closed='no' LIMIT 8");

if($query != ""){

	while($row = mysqli_fetch_array($returnedQuery)) {
		$user = new User($con, $userLoggedIn);

		echo "<div class='resultDisplay'>
				<a href='" . $row['username'] . "' style='color: #1485BD;'>
					<div class='liveSearchProfilePic'>
						<img src='" . $row['profile_pic'] ."'>
					</div>

					<div class='liveSearchText'>
						" . $row['first_name'] . " " . $row['last_name'] . "
					</div>
				</a>
				</div>";
			}
			
	while($searchrow = mysqli_fetch_array($topicreturnedQuery))
	{
		echo "<div class='resultDisplay'>
				<a href='" . $searchrow['topic'] . "'style='color: #1485BD;'>".$searchrow['topic']."</a>
				</div>";
	}

}

 ?>