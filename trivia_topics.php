<?php   

include("includes/header.php");

include("includes/classes/Trivia_topic.php"); 



if(isset($_POST['post'])){

    $topic = new Trivia_topic($con);

    $topic->submitTriviaTopic($_POST['trivia_topic_create'], $_POST['trivia_topic_description'], $_POST['category']);

    echo '<h3 style="text-align:center;" class="alert alert-success alert-dismissible fade show">Topic created!</h3>';

}

?>

	<div class="container">

		<div class="main_trivia_column column">

        <form class="post_form_trivia" action="trivia_topics.php" method="POST">

        	<input type="text" name="trivia_topic_create" placeholder="Trivia Topic" required>

            <select name="category">

                <option selected="selected">Choose one</option>

                <?php

                // A sample categories array

                $categories = array("Films", "Sports", "Celebrity", "Organisation", "Lifestyle", "Automobiles", "Culture", "Business", "Politics", "Science", "Books");

                

                // Iterating through the categories array

                foreach($categories as $item){

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

        <br>

         <select name="category" class="custom-select mb-3 topic_select">

                <option class="selected" selected="selected">Choose one</option>

                <?php

                // A sample categories array

                $categories = array("Films", "Sports", "Celebrity", "Organisation", "Lifestyle", "Automobiles", "Culture", "Business", "Politics", "Science", "Books");

                

                // Iterating through the categories array

                foreach($categories as $item){

                ?>

                <option value="<?php echo strtolower($item); ?>"><?php echo $item; ?></option>

                <?php

                }

                ?>

            </select>

    <div class ="trivia_topics_area row"></div>

    <p style='text-align:center;'>No more topics to show!</p>

    	<img id="loading" src="assets/images/icons/loading.gif" style="display: block; margin: auto;">

    </div>

	<script>

        $(document).ready(function() {

            $('#loading').hide();

            $(".topic_select").change(function(){

                var cat = $('.topic_select').val();    

            

            //Original ajax request for loading first posts

            $.ajax({

                url:"includes/handlers/ajax_load_trivia_topics.php",

                type:"POST",

                data:"page=1&category="+cat,

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

                data:"page="+page+"&category="+cat,

                cache:false,



                success: function(response){

                    $('.trivia_topics_area').find('.nextPage').remove();



                    $('#loading').hide();

                    $('.trivia_topics_area').append(response);

                }

            });





            }

            return false;

            });

            });

            });

    </script>

    </div>

	</body>

</html>



