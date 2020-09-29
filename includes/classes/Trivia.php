<?php 

class Trivia{

	private $user_obj;

	private $con;



	public function __construct($con,$user){

		$this->con = $con;

		$this->user_obj = new User($con, $user);

	}



	public function submitTrivia($id, $body){

		$body = strip_tags($body); //remove html tags

		$body = mysqli_real_escape_string($this->con, $body);

		$check_empty = preg_replace('/\s+/', '', $body); //Deletes all spaces



		if($check_empty != ""){



			//Current date and time

			$date_added = date("Y-m-d H:i:s");

			//Get username

			$added_by = $this->user_obj->getUsername();



			//Initializing upvotes

			$upvotes = 0;



			$trivia_topic_id = $id;



			$topic_query = mysqli_query($this->con, "SELECT * FROM trivia_topics WHERE id='$trivia_topic_id'");



			$trivia_topic_row = mysqli_fetch_array($topic_query);



			$trivia_topic = $trivia_topic_row['topic'];

			

			//insert trivia

			$query = mysqli_query($this->con, "INSERT INTO trivia VALUES('', '$trivia_topic', '$trivia_topic_id', '$body', '$added_by', '$upvotes', '$date_added', 'no')");



			//Update post count for user 

			$num_posts = $this->user_obj->getNumPosts();

			$num_posts++;

			$update_query = mysqli_query($this->con, "UPDATE users SET num_posts='$num_posts' WHERE username='$added_by'");

		}

	}



	public function loadTriviaPosts($data, $limit){ 



		$page = $data['page'];

		$userLoggedIn = $data['userLoggedIn'];



		if($page == 1)

			$start = 0;

		else

			$start = ($page - 1)*$limit;



		$trivia_id = $data['id'];



		$str = ""; //String to return

		$data_query = mysqli_query($this->con,"SELECT * FROM trivia WHERE deleted='no' AND trivia_topic_id='$trivia_id'  ORDER BY id DESC");



		if(mysqli_num_rows($data_query)>0){



			$num_iterations = 0;//Number of results checked (not necessarily posted)

			$count = 1;



			while($row = mysqli_fetch_array($data_query)){

				$id = $row['id'];

				$trivia_topic = $row['trivia_topic'];

				$body = $row['body'];

				$added_by = $row['added_by'];

				$date_time = $row['date'];

				$upvotes = $row['upvotes'];



				//Check if user who posted, has the account closed

				$added_by_obj = new User($this->con, $added_by);

				if($added_by_obj->isClosed()){

					continue;

				}



				$user_logged_obj = new User($this->con, $userLoggedIn);

				if(!$user_logged_obj->isClosed()){



				if($num_iterations++<$start)

					continue;



				//once ten posts have been loaded , break

				if($count>$limit){

					break;

				}

				else{

					$count++;

				}



				if($userLoggedIn==$added_by)

					$more_button = "<div class='more_button'>

									<div class='more_container'>

									    <div class='more' id='more$id'>

									        <button class='more-btn' id='more-btn$id'>

									            <span class='more-dot'></span>

									            <span class='more-dot'></span>

									            <span class='more-dot'></span>

									        </button>

									        <div class='more-menu' id='more-menu$id'>

									            <div class='more-menu-caret'>

									                <div class='more-menu-caret-outer'></div>

									                <div class='more-menu-caret-inner'></div>

									            </div>

									            <ul class='more-menu-items' tabindex='-1' role='menu' aria-labelledby='more-btn' aria-hidden='true' id='post$id'>

									                <li class='more-menu-item' role='presentation'>

									                    <button type='button' class='more-menu-btn' role='menuitem' id='delete'>Delete</button>

									                </li>	

									            </ul>

									        </div>

									    </div>

									</div>

									</div>";

				else

					$more_button = "";

				

				$user_details_query = mysqli_query($this->con, "SELECT first_name, last_name, profile_pic FROM users WHERE username='$added_by'");

				$user_row = mysqli_fetch_array($user_details_query);

				$first_name = $user_row['first_name'];

				$last_name = $user_row['last_name'];

				$profile_pic = $user_row['profile_pic'];



				//Timeframe

				$date_time_now = date("Y-m-d H:i:s");

				$start_date =  new DateTime($date_time);//Time of post

				$end_date = new DateTime($date_time_now);//Current time

				$interval = $start_date->diff($end_date);//Difference between dates

				if($interval->y >= 1){

					if($interval == 1)

						$time_message = $interval->y." year ago";

					else

						$time_message = $interval->y." years ago";

				}

				else if ($interval->m>=1){

					if($interval->d=0){

						$days = " ago";

					}

					else if ($interval->d == 1){

						$days =  $interval->d." day ago";

					}

					else {

						$days =  $interval->d." days ago";

					}



					if($interval->m == 1){

						$time_message = $interval->m." month".$days;

					}

					else{

						$time_message = $interval->m." months".$days;	

					}

				}

				else if($interval->d >= 1){

					if($interval->d == 1){

						$time_message = "Yesterday";

					}

					else {

						$time_message =  $interval->d." days ago";

					}

				}

				else if($interval->h >= 1){

					if($interval->h == 1){

						$time_message = $interval->h." hour ago";

					}

					else {

						$time_message =  $interval->h." hours ago";

					}

				}

				else if($interval->i >= 1){

					if($interval->i == 1){

						$time_message = $interval->i." minute ago";

					}

					else {

						$time_message =  $interval->i." minutes ago";

					}

				}

				else {

					if($interval->s < 30){

						$time_message = "Just now";

					}

					else {

						$time_message =  $interval->s." seconds ago";

					}

				}



				$str .= "<div class='trivia_post'>

							<div class='post_profile_pic'>

								<img src='$profile_pic' width='50'>

							</div>

							<div class='posted_by' style='color:#000000 ;'>

								<a href='$added_by'>$first_name $last_name</a>&nbsp;&nbsp;&nbsp;&nbsp;$time_message$more_button

							</div>

							<div id='post_body'>

								$body

								<br>

								<br>

								<br>

							</div>

							<div class='vote'>

								<iframe src='vote.php?id=$id' scrolling='no'></iframe>

							</div>

						</div>

						<hr>";

					}



					?>

					<script>

						$('#more<?php echo $id;?>').find('#more-btn<?php echo $id;?>').on('click', showMenu);



						function showMenu() {

						        document.querySelector('#more<?php echo $id;?>').classList.add('show-more-menu');

						        document.querySelector('#more<?php echo $id;?>').querySelector('.more-menu').setAttribute('aria-hidden', false);

								document.addEventListener('mousedown', function(e) {

								    if ($(e.target).is("#delete") === false && $(e.target).is("#edit") === false) {

								      $("#more<?php echo $id;?>").removeClass("show-more-menu");

								    }

								  });

								}

						$('#more-menu<?php echo $id;?>').find('#delete').on('click', function(){

							bootbox.confirm("Are you sure you want to delete this post?", function(result){

								$.post("includes/form_handlers/delete_post.php?post_id=<?php echo $id;?>", {result:result});

								if(result)

									location.reload();

							});

						});



					</script>

					<?php

			}



			if($count>$limit)

				$str .="<input type='hidden' class='nextPage' value='".($page+1)."'><input type='hidden' class='noMorePosts' value='false'>";

			else

				$str .="<input type='hidden' class='noMorePosts' value='true'><p style='text-align:center;'>No more posts to show!</p>";

		}



			echo $str;



	}



	public function getSingleTrivia($post_id){



		$userLoggedIn = $this->user_obj->getUsername();



		$opened_query = mysqli_query($this->con, "UPDATE notifications SET opened = 'yes' WHERE user_to='$userLoggedIn' AND link LIKE '%=$post_id'");



		$str = ""; //String to return

		$data_query = mysqli_query($this->con,"SELECT * FROM trivia WHERE deleted='no' AND id='$post_id'");



		if(mysqli_num_rows($data_query)>0){





			$row = mysqli_fetch_array($data_query); 

				$id = $row['id'];

				$body = $row['body'];

				$added_by = $row['added_by'];

				$date_time = $row['date'];



				//Check if user who posted, has the account closed

				$added_by_obj = new User($this->con, $added_by);

				if($added_by_obj->isClosed()){

					return;

				}



				if($userLoggedIn==$added_by)

					$more_button = "<div class='more_button'>

									<div class='more_container'>

									    <div class='more' id='more$id'>

									        <button class='more-btn' id='more-btn$id'>

									            <span class='more-dot'></span>

									            <span class='more-dot'></span>

									            <span class='more-dot'></span>

									        </button>

									        <div class='more-menu' id='more-menu$id'>

									            <div class='more-menu-caret'>

									                <div class='more-menu-caret-outer'></div>

									                <div class='more-menu-caret-inner'></div>

									            </div>

									            <ul class='more-menu-items' tabindex='-1' role='menu' aria-labelledby='more-btn' aria-hidden='true' id='post$id'>

									                <li class='more-menu-item' role='presentation'>

									                    <button type='button' class='more-menu-btn' role='menuitem' id='delete'>Delete</button>

									                </li>	

									            </ul>

									        </div>

									    </div>

									</div>

									</div>";

				else

					$more_button = "";

				$user_details_query = mysqli_query($this->con, "SELECT first_name, last_name, profile_pic FROM users WHERE username='$added_by'");

				$user_row = mysqli_fetch_array($user_details_query);

				$first_name = $user_row['first_name'];

				$last_name = $user_row['last_name'];

				$profile_pic = $user_row['profile_pic'];



				//Timeframe

				$date_time_now = date("Y-m-d H:i:s");

				$start_date =  new DateTime($date_time);//Time of post

				$end_date = new DateTime($date_time_now);//Current time

				$interval = $start_date->diff($end_date);//Difference between dates

				if($interval->y >= 1){

					if($interval == 1)

						$time_message = $interval->y." year ago";

					else

						$time_message = $interval->y." years ago";

				}

				else if ($interval->m>=1){

					if($interval->d=0){

						$days = " ago";

					}

					else if ($interval->d == 1){

						$days =  $interval->d." day ago";

					}

					else {

						$days =  $interval->d." days ago";

					}



					if($interval->m == 1){

						$time_message = $interval->m." month".$days;

					}

					else{

						$time_message = $interval->m." months".$days;	

					}

				}

				else if($interval->d >= 1){

					if($interval->d == 1){

						$time_message = "Yesterday";

					}

					else {

						$time_message =  $interval->d." days ago";

					}

				}

				else if($interval->h >= 1){

					if($interval->h == 1){

						$time_message = $interval->h." hour ago";

					}

					else {

						$time_message =  $interval->h." hours ago";

					}

				}

				else if($interval->i >= 1){

					if($interval->i == 1){

						$time_message = $interval->i." minute ago";

					}

					else {

						$time_message =  $interval->i." minutes ago";

					}

				}

				else {

					if($interval->s < 30){

						$time_message = "Just now";

					}

					else {

						$time_message =  $interval->s." seconds ago";

					}

				}



				$str .= "<div class='trivia_post'>

							<div class='post_profile_pic'>

								<img src='$profile_pic' width='50'>

							</div>

							<div class='posted_by' style='color:#000000 ;'>

								<a href='$added_by'>$first_name $last_name</a>&nbsp;&nbsp;&nbsp;&nbsp;$time_message$more_button

							</div>

							<div id='post_body'>

								$body

								<br>

								<br>

								<br>

							</div>

							<div class='vote'>

								<iframe src='vote.php?id=$id' scrolling='no'></iframe>

							</div>

						</div>

						<hr>";



					?>

					<script>

						$('#more<?php echo $id;?>').find('#more-btn<?php echo $id;?>').on('click', showMenu);



						function showMenu() {

						        document.querySelector('#more<?php echo $id;?>').classList.add('show-more-menu');

						        document.querySelector('#more<?php echo $id;?>').querySelector('.more-menu').setAttribute('aria-hidden', false);

								document.addEventListener('mousedown', function(e) {

								    if ($(e.target).is("#delete") === false && $(e.target).is("#edit") === false) {

								      $("#more<?php echo $id;?>").removeClass("show-more-menu");

								    }

								  });

								}

						$('#more-menu<?php echo $id;?>').find('#delete').on('click', function(){

							bootbox.confirm("Are you sure you want to delete this post?", function(result){

								$.post("includes/form_handlers/delete_trivia.php?post_id=<?php echo $id;?>", {result:result});

								if(result)

									location.reload();

							});

						});

					</script>

					<?php

			}



			echo $str;



	}

	}

 ?>

