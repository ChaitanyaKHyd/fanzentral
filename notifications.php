<?php   
include("includes/header.php");

$notifications_obj = new Message($con, $userLoggedIn);

?>

<div class="notifications_main_column column" id="main_column">
	<div class="posts_area"></div>
	<img id="loading" src="assets/images/icons/loading.gif" style="display: block; margin: auto;">
</div>


<script>
	$(document).ready(function() {
            $('#loading').show();

            //Original ajax request for loading first posts

            $.ajax({
                url:"includes/handlers/ajax_load_notifications.php",
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
                var page = $('.posts_area').find('.nextPageDropDownData').val();
                var noMorePosts = $('.posts_area').find('.noMoreDropDownData').val();

                if ((document.body.scrollHeight == document.body.scrollTop + window.innerHeight) && noMorePosts == 'false'){
                    $('#loading').show();

                var ajaxReq = $.ajax({
                url:"includes/handlers/ajax_load_posts.php",
                type:"POST",
                data:"page="+page+"&userLoggedIn="+userLoggedIn,
                cache:false,

                success: function(response){
                    $('.posts_area').find('.nextPageDropDownData').remove();
                    $('.posts_area').find('.noMoreDropDownData').remove();

                    $('#loading').hide();
                    $('.posts_area').append(response);
                }
            });


            }
            return false;
            });
        });
</script>