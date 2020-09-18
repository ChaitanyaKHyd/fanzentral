<?php  
require 'config/config.php';
include("includes/classes/User.php");
include("includes/classes/Message.php");
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
	<title>Fanzentral</title>
	<!--Javascript-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="assets/js/bootbox.min.js"></script>
	<script src="assets/js/jcrop_bits.js"></script>
	<script src="assets/js/jquery.Jcrop.js"></script>
	<script src="assets/js/fanzentral.js"></script>
  <script src="assets/js/bootstrap.js"></script>

	<!--bootstrap & CSS-->
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<link rel="stylesheet" type="text/css" href="assets/css/jquery.Jcrop.css">

	<!--font-->
	<link href="https://fonts.googleapis.com/css2?family=Fjalla+One&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Ubuntu&display=swap" rel="stylesheet">


	<!--fontawesome icons-->
	<script src="https://kit.fontawesome.com/024419fd11.js" crossorigin="anonymous"></script>

</head>
<body>
	<div class="top_bar">
		<div class="logo">
			<a href="index.php">FanZentral.com</a>
		</div>
    <div class="search">
      <form action="search.php" method="GET" name="search_form">
        <input type="text" onkeyup="getLiveSearch(this.value, '<?php echo $userLoggedIn; ?>')" name="q" placeholder="Search..." autocomplete="off" id="search_text_input">
        <div class="button_holder">
          <img src="assets/images/icons/magnifying_glass.png">
        </div>
      </form>
      <div class="search_results">
        
      </div>
      <div class="search_results_footer_empty">
        
      </div>
    </div>
		<nav class="header">
			<?php 
				//Unread messages
				$messages = new Message($con, $userLoggedIn);
				$num_messages = $messages->getUnreadNumber();

        //Unread notifications
        $notifications = new Notification($con, $userLoggedIn);
        $num_notifications = $notifications->getUnreadNumber();

        //Unread friend requests
        $user_obj = new User($con, $userLoggedIn);
        $num_requests = $user_obj->getNumberofFriendRequests();

			 ?>
  			<a href="<?php echo $userLoggedIn; ?>"><?php echo $user['first_name']; ?></a>
  			<a href="javascript:void(0)" onclick="getDropdownData('<?php echo $userLoggedIn;?>', 'message')"><i class="fas fa-inbox"></i>
  				<?php 
  				if($num_messages>0)
  					echo'<span class="notification_badge" id="unread_message">'.$num_messages.'</span>'
          ?>
  			</a>
  			<a href="javascript:void(0)" onclick="getDropdownData('<?php echo $userLoggedIn;?>', 'notification')"><i class="fas fa-bell"></i>
        <?php 
          if($num_notifications>0)
            echo'<span class="notification_badge" id="unread_notification">'.$num_notifications.'</span>'
          ?>
        </a>
  			<a href="requests.php"><i class="fas fa-user-friends"></i>
        <?php 
          if($num_requests>0)
            echo'<span class="notification_badge" id="unread_requests">'.$num_requests.'</span>'
          ?>
        </a>
  			<a href="settings.php"><i class="fas fa-cog"></i></a>
  			<a href="includes/handlers/logout.php"><i class="fas fa-sign-out-alt"></i></a>
		</nav>
		<div class="dropdown_data_window" style="height:0px; border:none;" ></div>
		<input type="hidden"  id="dropdown_data_type" value ="">
	</div>

	<script>
        var userLoggedIn = '<?php echo $userLoggedIn; ?>';

        $(document).ready(function() {

            $('.dropdown_data_window').scroll(function(){
                var inner_height = $('.dropdown_data_window').innerHeight();
                var scroll_top = $('.dropdown_data_window').scrollTop();
                var page = $('.dropdown_data_window').find('.nextPageDropDownData').val();
                var noMoreData = $('.dropdown_data_window').find('.noMoreDropDownData').val();

                if ((scroll_top+inner_height>=$('.dropdown_data_window')[0].scrollHeight) && noMoreData == 'false'){

                	var pageName;//Holds page name to send ajax request to
                	var type= $('#dropdown_data_type').val();

                	if(type == 'notification')
                		pageName  = "ajax_load_notifications.php";
                	else if(type = 'message')
                		pageName = "ajax_load_messages.php";

                var ajaxReq = $.ajax({
                url:"includes/handlers/"+pageName,
                type:"POST",
                data:"page="+page+"&userLoggedIn="+userLoggedIn,
                cache:false,

                success: function(response){
                    $('.dropdown_data_window').find('.nextPageDropDownData').remove();
                    $('.dropdown_data_window').find('.noMoreDropDownData').remove();

                    $('.dropdown_data_window').append(response);
                }
            });


            }
            return false;
            });
        });
    </script>

	<div class="wrapper">