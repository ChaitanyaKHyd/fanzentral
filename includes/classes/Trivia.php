<?php 
class Trivia{
	private $user_obj;
	private $con;

	public function __construct($con,$user){
		$this->con = $con;
		$this->user_obj = new User($con, $user);
	}

	public function submitTrivia($body){
		$body = strip_tags($body); //remove html tags
		$body = mysqli_real_escape_string($this->con, $body);
		$check_empty = preg_replace('/\s+/', '', $body); //Deletes all spaces

		if($check_empty != ""){

			//Current date and time
			$date_added = date("Y-m-d H:i:s");
			//Get username
			$added_by = $this->user_obj->getUsername();

			//Initializing upvotes and downvotes
			$upvotes = 0;
			$downvotes = 0;
			
			//insert trivia
			$query = mysqli_query($this->con, "INSERT INTO trivia VALUES('', '$body', '$added_by', '$upvotes', '$downvotes', '$date_added', 'no')");

			//Update post count for user 
			$num_posts = $this->user_obj->getNumPosts();
			$num_posts++;
			$update_query = mysqli_query($this->con, "UPDATE users SET num_posts='$num_posts' WHERE username='$added_by'");

			
		}
	}

	public function loadTriviaPosts($data, $limit){ 

		$page = $data['page'];
		$userLoggedIn = $this->user_obj->getUsername();

		if($page == 1)
			$start = 0;
		else
			$start = ($page - 1)*$limit;

		$str = ""; //String to return
		$data_query = mysqli_query($this->con,"SELECT * FROM trivia WHERE deleted='no' ORDER BY id DESC");

		if(mysqli_num_rows($data_query)>0){

			$num_iterations = 0;//Number of results checked (not necessarily posted)
			$count = 1;

			while($row = mysqli_fetch_array($data_query)){
				$id = $row['id'];
				$trivia_topic = $row['trivia_topic'];
				$body = $row['body'];
				$added_by = $row['added_by'];
				$date_time = $row['date'];

				//Check if user who posted, has the account closed
				$added_by_obj = new User($this->con, $added_by);
				if($added_by_obj->isClosed()){
					continue;
				}

				$user_logged_obj = new User($this->con, $userLoggedIn);
				if($user_logged_obj->isFriend($added_by)){

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
					$delete_button = "<button class='delete_button btn-danger' id='post$id'>X</button>";
				else
					$delete_button = "";
				$user_details_query = mysqli_query($this->con, "SELECT first_name, last_name, profile_pic FROM users WHERE username='$added_by'");
				$user_row = mysqli_fetch_array($user_details_query);
				$first_name = $user_row['first_name'];
				$last_name = $user_row['last_name'];
				$profile_pic = $user_row['profile_pic'];

				$comments_check = mysqli_query($this->con, "SELECT * FROM comments WHERE post_id='$id'");
				$comments_check_num = mysqli_num_rows($comments_check);

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

				$str .= "<div class='status_post' onClick='javascript:toggle$id()'>
							<div class='post_profile_pic'>
								<img src='$profile_pic' width='50'>
							</div>
							<div class='posted_by' style='color:#000000 ;'>
								<a href='$added_by'>$first_name $last_name</a>&nbsp;&nbsp;&nbsp;&nbsp;$time_message
								$delete_button
							</div>
							<div id='post_body'>
								$body
								<br>
								<br>
								<br>
							</div>
						</div>
						<hr>";
					}

					?>
					<script>
						$(document).ready(function(){
							$('#post<?php echo $id;?>').on('click', function(){
								bootbox.confirm("Are you sure you want to delete this post?", function(result){
									$.post("includes/form_handlers/delete_post.php?post_id=<?php echo $id;?>", {result:result});
									if(result)
										location.reload();
								});
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
	}
 ?>
