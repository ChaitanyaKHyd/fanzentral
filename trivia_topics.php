<?php   
include("includes/header.php"); 

if(isset($_POST['post'])){
    $topic = new Trivia_topic($con, $userLoggedIn);
    $post->submitFeed($_POST['post_text'], 'none');
}
?>
		<div class="container">
			<div class="main_trivia_column column">
	        <form class="post_form" action="trivia_topics.php" method="POST">
	        	<input type="text" class="trivia_topic_create" name="trivia_topic_create" placeholder="Trivia Topic" required>
	            <textarea name="post_text" id="post_text" placeholder="Give a description of trivia topic" required></textarea>
	            <input type="submit" name="post" id="post_button" value="Create"></input>
	            <hr>
	        </form>
        <div class ="trivia_topics_area"></div>
        	<img id="loading" src="assets/images/icons/loading.gif" style="display: block; margin: auto;">
		</div>
		</div>
		<div class="container">                  
			<ul class="pagination justify-content-center">
				<li class="page-item disabled"><a class="page-link" href="#" tabindex="-1">Previous</a></li>
				<li class="page-item"><a class="page-link" href="#">1</a></li>
				<li class="page-item"><a class="page-link" href="#">2</a></li>
				<li class="page-item"><a class="page-link" href="#">3</a></li>
				<li class="page-item"><a class="page-link" href="#">Next</a></li>
			</ul>
		</div>
	</div>	
	</body>
</html>