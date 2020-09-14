<?php   
include("includes/header.php");
include("includes/classes/Trivia_topic.php"); 

if(isset($_POST['post'])){
    $topic = new Trivia_topic($con);
    $topic->submitTriviaTopic($_POST['trivia_topic_create'], $_POST['trivia_topic_description'], $_POST['category']);
}
?>
	<div class="container">
		<div class="main_trivia_column column">
        <form class="post_form" action="trivia_topics.php" method="POST">
        	<input type="text" name="trivia_topic_create" placeholder="Trivia Topic" required>
            <select name="category">
                <option selected="selected">Choose one</option>
                <?php
                // A sample product array
                $products = array("Films", "Sports", "Lifestyle", "Automobiles", "Culture", "Business", "Politics", "Science", "Books");
                
                // Iterating through the product array
                foreach($products as $item){
                ?>
                <option value="<?php echo strtolower($item); ?>"><?php echo $item; ?></option>
                <?php
                }
                ?>
            </select>
            <br><br>
            <textarea name="trivia_topic_description"  placeholder="Give a description of trivia topic"></textarea>
            <input type="submit" name="post" id="post_button" value="Create"></input>
            <hr>
        </form>
    	</div>
    <div class ="trivia_topics_area row"></div>
    	<img id="loading" src="assets/images/icons/loading.gif" style="display: block; margin: auto;">

	<script>
        $(document).ready(function() {
            $('#loading').show();

            //Original ajax request for loading first posts

            $.ajax({
                url:"includes/handlers/ajax_load_trivia_topics.php",
                type:"POST",
                data:"page=1&userLoggedIn="+userLoggedIn,
                cache:false,

                success: function(data){
                    $('#loading').hide();
                    $('.trivia_topics_area').html(data);
                }
            });

            $(window).scroll(function(){
                var height = $('.trivia_topics_area').height();
                var scroll_top = $(this).scrollTop();
                var page = $('.trivia_topics_area').find('.nextPage').val();
                var noMorePosts = $('.trivia_topics_area').find('.noMorePosts').val();

                if ((document.body.scrollHeight == document.body.scrollTop + window.innerHeight) && noMorePosts == 'false'){
                    $('#loading').show();

                var ajaxReq = $.ajax({
                url:"includes/handlers/ajax_load_trivia_topics.php",
                type:"POST",
                data:"page="+page+"&userLoggedIn="+userLoggedIn,
                cache:false,

                success: function(response){
                    $('.trivia_topics_area').find('.nextPage').remove();
                    $('.trivia_topics_area').find('.noMorePosts').remove();

                    $('#loading').hide();
                    $('.trivia_topics_area').append(response);
                }
            });


            }
            return false;
            });
        });
    </script>
</div>
	</body>
</html>

