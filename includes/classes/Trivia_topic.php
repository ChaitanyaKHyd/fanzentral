<?php 
class Trivia_topic{
	private $con;

	public function __construct($con){
		$this->con = $con;
	}

	public function submitTriviaTopic($trivia_topic, $topic_description){
		$topic = $trivia_topic;
		$topic = strip_tags($topic); //remove html tags
		$topic = mysqli_real_escape_string($this->con, $topic);
		$check_empty = preg_replace('/\s+/', '', $topic); //Deletes all spaces

		if($check_empty != ""){

			$description = $topic_description;
			$description = strip_tags($description); //remove html tags
			$description = mysqli_real_escape_string($this->con, $description);

			//insert trivia topic
			$query = mysqli_query($this->con, "INSERT INTO trivia_topics VALUES('','$topic', '$description', 'no')");
		}
	}

	public function loadTriviaTopics($data, $limit){ 

		$page = $data['page'];

		if($page == 1)
			$start = 0;
		else
			$start = ($page - 1)*$limit;

		$str = ""; //String to return
		$data_query = mysqli_query($this->con,"SELECT * FROM trivia_topics WHERE deleted='no' ORDER BY topic");

		if(mysqli_num_rows($data_query)>0){

			$num_iterations = 0;//Number of results checked (not necessarily posted)
			$count = 1;

			while($row = mysqli_fetch_array($data_query)){
				$id = $row['id'];
				$topic = $row['topic'];
				$description = $row['description'];

				if($num_iterations++<$start)
					continue;

				//once ten topics have been loaded , break
				if($count>$limit){
					break;
				}
				else{
					$count++;
				}

				$str .= "<div class='col-md-6 col-lg-3'>
							<div class='card my-3'>
								<div class='card-body'>
									<h5 class='card-title' id=''>$topic</h5>
									<p class='card-text'>$description</p>
									<a href='trivia_page.php?topic=$topic' class='btn btn-dark'>Go to topic</a>
								</div>
							</div>
						</div>";
			}

			if($count>$limit)
				$str .="<input type='hidden' class='nextPage' value='".($page+1)."'><input type='hidden' class='noMorePosts' value='false'>";
			else
				$str .="<input type='hidden' class='noMorePosts' value='true'><p style='text-align:center;'>No more topics to show!</p>";
		

			echo $str;
		}
	}
}
	
	?>