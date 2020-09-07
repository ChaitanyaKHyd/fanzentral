<?php 
class Trivia_topic{
	private $user_obj;
	private $con;
	private $topic

	public function __construct($con,$user,$topic){
		$this->con = $con;
		$this->user_obj = new User($con, $user);
		$this->topic = new Trivia($con,$topic);
	}

	public function submitTriviaTopic($trivia_topic, $topic_description){
		$topic = $trivia_topic;
		$topic = strip_tags($topic); //remove html tags
		$topic = mysqli_real_escape_string($this->con, $topic);
		$check_empty = preg_replace('/\s+/', '', $topic); //Deletes all spaces

		if($check_empty != ""){

			$description = $topic_description;

			//insert trivia
			$query = mysqli_query($this->con, "INSERT INTO trivia_topic VALUES('','$topic', '$description', 'no')");
			
		}
	}