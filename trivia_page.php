<?php   
include("includes/header.php"); 
include("includes/classes/Trivia.php");


$id = $_GET['id'];
$query = mysqli_query($con, "SELECT * FROM trivia_topics WHERE id=$id");
$row = mysqli_fetch_array($query);
$trivia_topic = $row['topic'];
$trivia_topic_description = $row['description'];

if(isset($_POST['trivia_post'])){
    $trivia = new Trivia($con, $userLoggedIn);
    $trivia->submitTrivia($id, $_POST['trivia_text']);
}
 ?>
    <div class="main_trivia_column column">
        <h1><?php echo $trivia_topic; ?></h1>
        <h5><?php echo $trivia_topic_description; ?></h5>
        <br>
        <form class="post_form_trivia" action="trivia_page.php?id=<?php echo $id ?>" method="POST">
          <textarea name="trivia_text" id="post_text" maxlength="280" data-counter_id="length"></textarea>
          <input type="submit" name="trivia_post" id="post_button" value="Post">
          <input style="color:black;font-size:12pt;font-style:italic;display:block;border:none;" readonly type="text" id='length' name="length" size="3" maxlength="3" value="280">
          <hr>
        </form>

        <div class="trivia_posts_area"></div>
        <img id="loading" src="assets/images/icons/loading.gif" style="display: block; margin: auto;">

    </div>

    <script>

        document.getElementById('post_text').addEventListener('input', (e) => {
          let el = e.currentTarget;
          let counterId = el.dataset.counter_id;
          let maxLength = el.maxLength
          let currentLength = el.value.length
          document.getElementById(counterId).value = maxLength - currentLength;
        });

        var userLoggedIn = '<?php echo $userLoggedIn; ?>';
        var id = '<?php echo $id; ?>';

        $(document).ready(function() {
            $('#loading').show();

            //Original ajax request for loading first trivia posts

            $.ajax({
                url:"includes/handlers/ajax_load_trivia_posts.php",
                type:"POST",
                data:"page=1&userLoggedIn="+userLoggedIn+"&id="+id,
                cache:false,

                success: function(data){
                    $('#loading').hide();
                    $('.trivia_posts_area').html(data);
                }
            });

            $(window).scroll(function(){
                var height = $('.trivia_posts_area').height();
                var scroll_top = $(this).scrollTop();
                var page = $('.trivia_posts_area').find('.nextPage').val();
                var noMorePosts = $('.trivia_posts_area').find('.noMorePosts').val();

                if ((document.body.scrollHeight == document.body.scrollTop + window.innerHeight) && noMorePosts == 'false'){
                    $('#loading').show();

                var ajaxReq = $.ajax({
                url:"includes/handlers/ajax_load_trivia_posts.php",
                type:"POST",
                data:"page="+page+"&userLoggedIn="+userLoggedIn+"&id="+id,
                cache:false,

                success: function(response){
                    $('.trivia_posts_area').find('.nextPage').remove();
                    $('.trivia_posts_area').find('.noMorePosts').remove();

                    $('#loading').hide();
                    $('.trivia_posts_area').append(response);
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