<?php   
include("includes/header.php"); 
include("includes/classes/Trivia.php");

if(isset($_POST['trivia_post'])){
    $trivia = new Trivia($con, $userLoggedIn);
    $trivia->submitTrivia($_POST['trivia_text'], '');
}

 ?>
    <div class="main_trivia_column column">
        <form class="post_form" action="trivia_page.php" method="POST">
            <textarea name="trivia_text" id="post_text" placeholder="Post something"></textarea>
            <input type="submit" name="trivia_post" id="post_button" value="Post"></input>
            <hr>
            
        </form>

        <div class="trivia_posts_area"></div>
        <img id="loading" src="assets/images/icons/loading.gif" style="display: block; margin: auto;">

    </div>

    <script>
        var trivia_topic = '<?php echo $trivia_topic; ?>';

        $(document).ready(function() {
            $('#loading').show();

            //Original ajax request for loading first posts

            $.ajax({
                url:"includes/handlers/ajax_load_trivia_posts.php",
                type:"POST",
                data:"page=1&trivia_topic="+trivia_topic,
                cache:false,

                success: function(data){
                    $('#loading').hide();
                    $('.posts_area').html(data);
                }
            });

            $(window).scroll(function(){
                var height = $('.posts_area').height();
                var scroll_top = $(this).scrollTop();
                var page = $('.posts_area').find('.nextPage').val();
                var noMorePosts = $('.posts_area').find('.noMorePosts').val();

                if ((document.body.scrollHeight == document.body.scrollTop + window.innerHeight) && noMorePosts == 'false'){
                    $('#loading').show();

                var ajaxReq = $.ajax({
                url:"includes/handlers/ajax_load_trivia_posts.php",
                type:"POST",
                data:"page="+page+"&userLoggedIn="+userLoggedIn,
                cache:false,

                success: function(response){
                    $('.posts_area').find('.nextPage').remove();
                    $('.posts_area').find('.noMorePosts').remove();

                    $('#loading').hide();
                    $('.posts_area').append(response);
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