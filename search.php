<?php 
include("includes/header.php");

if(isset($_GET['q'])){
	$query = $_GET['q'];
}
else{
	$query = ""; 
}

if(isset($_GET['type'])){
	$type = $_GET['type'];
}
else{
	$type = "name"; 
}

 ?>

 <div class="main_column column" id="main_column">

 	<?php 
 	if($query=="")
 		echo "You must enter something in the search box.";
 	else{
		
		$search = explode(" ", $query);

		//If type is trivia topic, assume trivia topics being searched
		if($type == "topic"){

		$returnedQuery = mysqli_query($con, "SELECT * FROM trivia_topics WHERE topic LIKE '$search[0]%'");

		//Check if results were found 
		if(mysqli_num_rows($returnedQuery) == 0)
			echo "We can't find anything with a " . $type . " like: " .$query;
		else 
			echo mysqli_num_rows($returnedQuery) . " results found: <br> <br>";

		echo "<p id='grey'>Try searching for:</p>";
		echo "<a href='search.php?q=" . $query ."&type=name'>Names</a>, <a href='search.php?q=" . $query ."&type=topic'>Trivia Topics</a><br><br><hr id='search_hr'>";

		while($row = mysqli_fetch_array($returnedQuery))
	{
		echo "<div class='resultDisplay'>
				<a href='trivia_page.php?id=".$row['id']."'style='color: #007BFF;'>".$row['topic']."</a>
				<p>".$row['description']."</p>
				</div>";
	}
		}
		
		else{
		//If there are two words, assume they are first and last names respectively
		if(count($search) == 3)
			$returnedQuery = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$search[0]%' AND last_name LIKE '$search[2]%') AND user_closed='no'");
		//If query has one word only, search first names or last names 
		else if(count($search) == 2)
			$returnedQuery = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$search[0]%' AND last_name LIKE '$search[1]%') AND user_closed='no'");
		else 
			$returnedQuery = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$search[0]%' OR last_name LIKE '$search[0]%') AND user_closed='no'");

		//Check if results were found 
		if(mysqli_num_rows($returnedQuery) == 0)
			echo "We can't find anything with a " . $type . " like: " .$query;
		else 
			echo mysqli_num_rows($returnedQuery) . " results found: <br> <br>";

		echo "<p id='grey'>Try searching for:</p>";
		echo "<a href='search.php?q=" . $query ."&type=name'>Names</a>, <a href='search.php?q=" . $query ."&type=topic'>Trivia Topics</a><br><br><hr id='search_hr'>";

		while($row = mysqli_fetch_array($returnedQuery)) {
			$user_obj = new User($con, $user['username']);

			$button = "";
			$mutual_friends = "";

			if($user['username'] != $row['username']) {

				//Generate button depending on friendship status 
				if($user_obj->isFriend($row['username']))
					$button = "<input type='submit' name='" . $row['username'] . "' class='danger' value='Remove Friend'>";
				else if($user_obj->didReceiveRequest($row['username']))
					$button = "<input type='submit' name='" . $row['username'] . "' class='warning' value='Respond to request'>";
				else if($user_obj->didSendRequest($row['username']))
					$button = "<input type='submit' class='default' value='Request Sent'>";
				else 
					$button = "<input type='submit' name='" . $row['username'] . "' class='success' value='Add Friend'>";

				$mutual_friends = $user_obj->getMutualFriends($row['username']) . " friends in common";


				//Button forms
				if(isset($_POST[$row['username']])) {

					if($user_obj->isFriend($row['username'])) {
						$user_obj->removeFriend($row['username']);
						header("Location: http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
					}
					else if($user_obj->didReceiveRequest($row['username'])) {
						header("Location: requests.php");
					}
					else if($user_obj->didSendRequest($row['username'])) {

					}
					else {
						$user_obj->sendRequest($row['username']);
						header("Location: http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
					}

				}



			}

			echo "<div class='search_result'>
					<div class='searchPageFriendButtons'>
						<form action='' method='POST'>
							" . $button . "
							<br>
						</form>
					</div>


					<div class='result_profile_pic'>
						<a href='" . $row['username'] ."'><img src='". $row['profile_pic'] ."' style='height: 100px;'></a>
					</div>

						<a href='" . $row['username'] ."'> " . $row['first_name'] . " " . $row['last_name'] . "
						</a>
						<br>
						" . $mutual_friends ."<br>
				</div>
				<hr id='search_hr'>";

		} //End while


	    }

	}


	?>
</div>