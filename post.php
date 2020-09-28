<?php 
include("includes/header.php");
include("includes/classes/Feed.php");

if(isset($_GET['id'])){
	$id = $_GET['id'];
}
else{
	$id = 0;
}

 ?>

<div class="user_details column">
    <a href="<?php echo $userLoggedIn; ?>"><img src="<?php echo $user['profile_pic']; ?>"></a>
    <div class="user_details_left_right">
        <a href="<?php echo $userLoggedIn; ?>">
        <?php 
            echo $user['first_name']." ".$user['last_name'];
         ?>
         </a>
         <br>
         <?php echo "Trivia Posts: ".$user['num_posts']."<br>";
         echo "Upvotes: ".$user['num_upvotes']."<br>";?>
    </div>
</div>

<div class="main_column column" id="main_column">
	<div class="posts_area">
		<?php 
			$post = new Feed($con, $userLoggedIn);
			$post->getSinglePost($id);
		 ?>
	</div>
</div>
<script>
    $('#more<?php echo $id;?>').find('#more-btn<?php echo $id;?>').on('click', showMenu);

    function showMenu() {
            document.querySelector('#more<?php echo $id;?>').classList.add('show-more-menu');
            document.querySelector('#more<?php echo $id;?>').querySelector('.more-menu').setAttribute('aria-hidden', false);
            document.addEventListener('mousedown', function(e) {
                if ($(e.target).is("#delete") === false && $(e.target).is("#edit") === false) {
                  $("#more<?php echo $id;?>").removeClass("show-more-menu");
                }
              });
            }
    $('#more-menu<?php echo $id;?>').find('#delete').on('click', function(){
        bootbox.confirm("Are you sure you want to delete this post?", function(result){
            $.post("includes/form_handlers/delete_post.php?post_id=<?php echo $id;?>", {result:result});
            if(result)
                location.reload();
        });
    });

</script>
</div>
</body>
</html>