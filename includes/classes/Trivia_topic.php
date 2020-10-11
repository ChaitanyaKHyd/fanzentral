<?php 

class Trivia_topic{

	private $con;



	public function __construct($con){

		$this->con = $con;

	}



	public function submitTriviaTopic($trivia_topic, $topic_description, $topic_category){

		$topic = $trivia_topic;

		$topic = strip_tags($topic); //remove html tags

		$topic = mysqli_real_escape_string($this->con, $topic);

		$check_empty = preg_replace('/\s+/', '', $topic); //Deletes all spaces



		if($check_empty != ""){



			$description = $topic_description;

			$description = strip_tags($description); //remove html tags

			$description = mysqli_real_escape_string($this->con, $description);

			$category = $topic_category;

			$date = date("Y-m-d H:i:s");



			//insert trivia topic

			$query = mysqli_query($this->con, "INSERT INTO trivia_topics VALUES('','$topic', '$category', '$description', '$date', 'no')"); 

		}

	}



	public function loadTriviaTopics($data, $limit){ 



		$page = $data['page'];

		$cat = $data['category'];



		if($page == 1){

			$start = 0;
		}
		else{

			$start = ($page - 1)*$limit;
		}


		$str = ""; //String to return

		$data_query = mysqli_query($this->con,"SELECT * FROM trivia_topics WHERE deleted='no' AND category='$cat' ORDER BY topic");



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



				$str .= "<div class='col-md-4 col-lg-3'>

							<div class='card my-3'>

								<div id='accordion'>

								  <div class='card'>

								    <div class='card-header' id='heading$id'>

								      <h5 class='mb-0'>

								        <button class='btn btn-link' data-toggle='collapse' data-target='#collapse$id' aria-expanded='true' aria-controls='collapse$id'>

								          $topic

								        </button>

								      </h5>

								    </div>



								    <div id='collapse$id' class='collapse show' aria-labelledby='heading$id' data-parent='#accordion'>

								      <div class='card-body'>

								        $description

								      </div>

								    </div>

								  </div>

								<a href='trivia_page.php?id=$id' class='btn btn-dark'>Go to topic</a>

							</div>

							</div>

							</div>

						</div>";

			}



			if($count>$limit)

				$str .="<input type='hidden' class='nextPage' value='".($page+1)."'><input type='hidden' class='noMorePosts' value='false'>";

			else

				$str .="<input type='hidden' class='noMorePosts' value='true'>";

		



			echo $str;

		}

	}

}

	

	?>