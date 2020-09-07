<?php  
require 'config/config.php';
include("includes/classes/User.php");
include("includes/classes/Feed.php");
include("includes/classes/Notification.php");   

if(isset($_SESSION['username'])){
    $userLoggedIn = $_SESSION['username'];
    $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
    $user = mysqli_fetch_array($user_details_query);
}
else{
    header("Location: register.php");
}

?>

<html>
<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu&display=swap" rel="stylesheet">
</head>
<body>
<style type="text/css">
    * {
        font-size: 14px;
        font-family: 'Ubuntu', Sans-serif;
    }

    </style>

<script>
        var userLoggedIn = '<?php echo $userLoggedIn; ?>';
        var profileUsername = '<?php echo $username;  ?>';
        $(document).ready(function() {
            $('#loading').show();

            //Original ajax request for loading first posts

            $.ajax({
                url:"includes/handlers/ajax_load_profile_posts.php",
                type:"POST",
                data:"page=1&userLoggedIn="+userLoggedIn+"&profileUsername="+profileUsername,
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
                url:"includes/handlers/ajax_load_profile_posts.php",
                type:"POST",
                data:"page="+page+"&userLoggedIn="+userLoggedIn+"&profileUsername="+profileUsername,
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