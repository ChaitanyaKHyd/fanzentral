<?php   
include("includes/header.php"); 
include("includes/classes/Feed.php");

if(isset($_POST['post'])){
    
    $post = $_POST['post'];
    $uploadOk = 1;
    $imageName = $_FILES['fileToUpload']['name'];
    $errorMessage = "";

    if($imageName != ""){
        $targetDir = "assets/images/posts/";
        $imageName = $targetDir.uniqid().basename($imageName);
        $imageFileType = pathinfo($imageName, PATHINFO_EXTENSION);

        if($_FILES['fileToUpload']['size']>10000000){
            $errorMessage = "Sorry, your file is too large!";
            $uploadOk = 0;
        }

        if(strtolower($imageFileType) != "jpeg" && strtolower($imageFileType) != "png" && strtolower($imageFileType) != "jpg" && strtolower($imageFileType) != "gif"){
            $errorMessage = "Sorry, invalid file format!";
            $uploadOk = 0;
        }

        if($uploadOk){
            if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $imageName)){
                //Image uploaded
            }
            else{
                $uploadOk = 0;
            }
        }
    }

    if($uploadOk){
        $post = new Feed($con, $userLoggedIn);
        $post->submitFeed($_POST['post_text'], 'none', $imageName);
    }
    else{
        echo "<div style='text-align:center;' class='alert alert-danger alert-dismissible fade show'>
                $errorMessage
              </div>";
    }
}

 ?>
    <div class="trivia_topics column">
        <a href="trivia_topics.php">Trivia topics</a>
        <hr>  
        <div>
             <?php 
             $query = mysqli_query($con, "SELECT * FROM trivia WHERE deleted ='no' ORDER BY upvotes  DESC LIMIT 10");

             foreach($query as $row){
                $trivia = $row['body'];
                $id = $row['id'];
                $trivia_dot = strlen($trivia) >= 70 ? "..." : "";

                $trimmed_trivia =  str_split($trivia, 70);
                $trimmed_trivia = $trimmed_trivia[0];

                echo "<a href='trivia.php?id=$id'>$trimmed_trivia".$trivia_dot."</a><hr>";
             }
             ?>
         </div>
    </div>
    <div class="main_column column">
        <form class="post_form" action="index.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="fileToUpload" id="fileToUpload">
            <textarea name="post_text" id="post_text" placeholder="Upload an image and post something"></textarea>
            <input type="submit" name="post" id="post_button" value="Post"></input>
            <hr>
            
        </form>

        <div class="posts_area"></div>
        <img id="loading" src="assets/images/icons/loading.gif" style="display: block; margin: auto;">
    </div>

    <script>

        var userLoggedIn = '<?php echo $userLoggedIn; ?>';

        $(document).ready(function() {
            $('#loading').show();

            //Original ajax request for loading first posts

            $.ajax({
                url:"includes/handlers/ajax_load_posts.php",
                type:"POST",
                data:"page=1&userLoggedIn="+userLoggedIn,
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
                url:"includes/handlers/ajax_load_posts.php",
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