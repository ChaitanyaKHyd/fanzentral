<?php

class Badge{

    private $con;

    private $user_obj;

    public function __construct($con, $user){

        $this->con = $con;

        $this->user_obj = new User($con, $user);
    }

    public function calculateAndDisplayBadges($user){

        $badges_str = "";

        $check_query = mysqli_query($this->con, "SELECT num_upvotes FROM users WHERE username='$user'");

        $row = mysqli_fetch_array($check_query);

        $upvotes = $row['num_upvotes'];
        ?>
        <script>
            $(document).ready(function(){
              $('[data-toggle="popover"]').popover();
            });
        </script>
        <?php
        if($upvotes>0 && $upvotes<=5){
            $badges_str = "<div class='badges_div tier-1'>
                                <img data-toggle='popover' title='Bronze Medal Fan' data-content='Has atleast 5 upvotes' data-placement='bottom' src='assets/images/icons/bronze-medal-96.png'>
                           </div>";
        }elseif($upvotes>5 && $upvotes<=10){
            $badges_str = "<div class='badges_div tier-1'>
                                <img data-toggle='popover' title='Silver Medal Fan' data-content='Has between 6 and 10 upvotes' data-placement='bottom' src='assets/images/icons/silver-medal-96.png'><img data-toggle='popover' title='Bronze Medal Fan' data-content='Has atleast 5 upvotes' data-placement='bottom' src='assets/images/icons/bronze-medal-96.png'>
                           </div>";
        }elseif($upvotes>10 && $upvotes<20){
            $badges_str = "<div class='badges_div tier-1'>
                                <img data-toggle='popover' title='Gold Medal Fan' data-content='Has between 11 and 19 upvotes' data-placement='bottom' src='assets/images/icons/gold-medal-96.png'><img data-toggle='popover' title='Silver Medal Fan' data-content='Has between 6 and 10 upvotes' data-placement='bottom' src='assets/images/icons/silver-medal-96.png'><img data-toggle='popover' title='Bronze Medal Fan' data-content='Has atleast 5 upvotes' data-placement='bottom' src='assets/images/icons/bronze-medal-96.png'>
                           </div>";
        }elseif($upvotes>=20 && $upvotes<30){
            $badges_str = "<div class='badges_div tier-2'>
                                <img data-toggle='popover' title='Emerald Fan' data-content='Has between 20 and 29 upvotes' data-placement='bottom' src='assets/images/icons/emerald-96.png'>
                                <img data-toggle='popover' title='Gold Medal Fan' data-content='Has between 11 and 19 upvotes' data-placement='bottom' src='assets/images/icons/gold-medal-96.png'><img data-toggle='popover' title='Silver Medal Fan' data-content='Has between 6 and 10 upvotes' data-placement='bottom' src='assets/images/icons/silver-medal-96.png'><img data-toggle='popover' title='Bronze Medal Fan' data-content='Has atleast 5 upvotes' data-placement='bottom' src='assets/images/icons/bronze-medal-96.png'>
                           </div>";
        }elseif($upvotes>=30 && $upvotes<40){
            $badges_str = "<div class='badges_div tier-2'>
                                <img data-toggle='popover' title='Sapphire Fan' data-content='Has between 30 and 39 upvotes' data-placement='bottom' src='assets/images/icons/sapphire-96.png'><img data-toggle='popover' title='Emerald Fan' data-content='Has between 20 and 29 upvotes' data-placement='bottom' src='assets/images/icons/emerald-96.png'>
                                <img data-toggle='popover' title='Gold Medal Fan' data-content='Has between 11 and 19 upvotes' data-placement='bottom' src='assets/images/icons/gold-medal-96.png'><img data-toggle='popover' title='Silver Medal Fan' data-content='Has between 6 and 10 upvotes' data-placement='bottom' src='assets/images/icons/silver-medal-96.png'><img data-toggle='popover' title='Bronze Medal Fan' data-content='Has atleast 5 upvotes' data-placement='bottom' src='assets/images/icons/bronze-medal-96.png'>
                           </div>";
        }elseif($upvotes>=40 && $upvotes<50){
            $badges_str = "<div class='badges_div tier-2'>
                                <img data-toggle='popover' title='Ruby Fan' data-content='Has between 40 and 49 upvotes' data-placement='bottom' src='assets/images/icons/ruby-96.png'><img data-toggle='popover' title='Sapphire Fan' data-content='Has between 30 and 39 upvotes' data-placement='bottom' src='assets/images/icons/sapphire-96.png'><img data-toggle='popover' title='Emerald Fan' data-content='Has between 20 and 29 upvotes' data-placement='bottom' src='assets/images/icons/emerald-96.png'>
                                <img data-toggle='popover' title='Gold Medal Fan' data-content='Has between 11 and 19 upvotes' data-placement='bottom' src='assets/images/icons/gold-medal-96.png'><img data-toggle='popover' title='Silver Medal Fan' data-content='Has between 6 and 10 upvotes' data-placement='bottom' src='assets/images/icons/silver-medal-96.png'><img data-toggle='popover' title='Bronze Medal Fan' data-content='Has atleast 5 upvotes' data-placement='bottom' src='assets/images/icons/bronze-medal-96.png'>
                           </div>";
        }elseif($upvotes>=50){
            $badges_str = "<div class='badges_div tier-2'>
                                <img data-toggle='popover' title='Diamond Fan' data-content='Has more than 50 upvotes' data-placement='bottom' src='assets/images/icons/diamond-96.png'><img data-toggle='popover' title='Ruby Fan' data-content='Has between 40 and 49 upvotes' data-placement='bottom' src='assets/images/icons/ruby-96.png'><img data-toggle='popover' title='Sapphire Fan' data-content='Has between 30 and 39 upvotes' data-placement='bottom' src='assets/images/icons/sapphire-96.png'><img data-toggle='popover' title='Emerald Fan' data-content='Has between 20 and 29 upvotes' data-placement='bottom' src='assets/images/icons/emerald-96.png'>
                                <img data-toggle='popover' title='Gold Medal Fan' data-content='Has between 11 and 19 upvotes' data-placement='bottom' src='assets/images/icons/gold-medal-96.png'><img data-toggle='popover' title='Silver Medal Fan' data-content='Has between 6 and 10 upvotes' data-placement='bottom' src='assets/images/icons/silver-medal-96.png'><img data-toggle='popover' title='Bronze Medal Fan' data-content='Has atleast 5 upvotes' data-placement='bottom'src='assets/images/icons/bronze-medal-96.png'>
                           </div>";
        }
    return $badges_str;
}
}
?>