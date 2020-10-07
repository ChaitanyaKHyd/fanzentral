<?php   

include("includes/header.php"); 

include("includes/classes/Trivia.php");



if(isset($_GET['order'])){
    $order = $_GET['order'];
}else{
    $order = "new";
}

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

    <div style="height: 0;width: auto;">

    <div class="trivia_topics column">
        <a href="trivia_topics.php">Trivia topics</a>
        <hr>  
        <div>
             <?php 
             $query = mysqli_query($con, "SELECT * FROM trivia WHERE deleted ='no' ORDER BY upvotes DESC");
             $unique_topic = array();
             foreach($query as $row){
                $topic_id = $row['trivia_topic_id'];
                if(!in_array($topic_id, $unique_topic)){
                $trivia_topic_2 = $row['trivia_topic'];
                $topic_query = mysqli_query($con, "SELECT description FROM trivia_topics WHERE id='$topic_id' LIMIT 10");
                $description_row = mysqli_fetch_array($topic_query);
                $description = $description_row['description'];
                array_push($unique_topic, $topic_id);

                echo "<a href='trivia_page.php?id=$topic_id'>".$trivia_topic_2."</a>
                      <p>".$description."</p><hr>";
             }else{
                continue;
             }
             }

             ?>
         </div>
    </div>

    <div class="main_trivia_column column">

        <h2><?php echo $trivia_topic; ?></h2>

        <h5><?php echo $trivia_topic_description; ?></h5>

        <div class="dropdown">
              <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Select order
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="trivia_page.php?id=<?php echo $id ?>&order=new">Newest First</a>
                <a class="dropdown-item" href="trivia_page.php?id=<?php echo $id ?>&order=top">Top Trivia First</a>
              </div>
          </div>

        <form class="post_form_trivia" action="trivia_page.php?id=<?php echo $id ?>" method="POST">

          <textarea name="trivia_text" id="post_text" maxlength="280" data-counter_id="length"></textarea>

          <input type="submit" name="trivia_post" id="post_button" value="Post">

          <input style="color:black;font-size:12pt;font-style:italic;display:none;border:none;" readonly type="text" id='length' name="length" size="3" maxlength="3" value="280">
          
          <hr>

        </form>



        <div class="trivia_posts_area"></div>

        <img id="loading" src="assets/images/icons/loading.gif" style="display: block; margin: auto;">



    </div>

    </div>



    <script>

        document.getElementById('post_text').addEventListener('input', (e) => {

          let el = e.currentTarget;

          let counterId = el.dataset.counter_id;

          let maxLength = el.maxLength

          let currentLength = el.value.length

          document.getElementById('length').style.display = "block";

          document.getElementById(counterId).value = maxLength - currentLength;

        });



        var userLoggedIn = '<?php echo $userLoggedIn; ?>';

        var id = '<?php echo $id; ?>';

        var order = '<?php echo $order; ?>';



        $(document).ready(function() {

            $('#loading').show();
            console.log(order);


            //Original ajax request for loading first trivia posts



            $.ajax({

                url:"includes/handlers/ajax_load_trivia_posts.php",

                type:"POST",

                data:"page=1&userLoggedIn="+userLoggedIn+"&id="+id+"&order="+order,

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

                data:"page="+page+"&userLoggedIn="+userLoggedIn+"&id="+id+"&order="+order,

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