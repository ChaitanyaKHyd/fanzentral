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

        if($upvotes>0 && $upvotes<=5){
            $badges_str = "<div class='badges_div tier-1'>
                                <img src='assets/images/icons/bronze-medal-96.png'>
                           </div>";
        }elseif($upvotes>6 && $upvotes<=10){
            $badges_str = "<div class='badges_div tier-1'>
                                <img src='assets/images/icons/silver-medal-96.png'><img src='assets/images/icons/bronze-medal-96.png'>
                           </div>";
        }elseif($upvotes>10 && $upvotes<20){
            $badges_str = "<div class='badges_div tier-1'>
                                <img src='assets/images/icons/gold-medal-96.png'><img src='assets/images/icons/silver-medal-96.png'><img src='assets/images/icons/bronze-medal-96.png'>
                           </div>";
        }elseif($upvotes>=20 && $upvotes<30){
            $badges_str = "<div class='badges_div tier-2'>
                                <img src='assets/images/icons/emerald-96.png'>
                                <img src='assets/images/icons/gold-medal-96.png'><img src='assets/images/icons/silver-medal-96.png'><img src='assets/images/icons/bronze-medal-96.png'>
                           </div>";
        }elseif($upvotes>=30 && $upvotes<40){
            $badges_str = "<div class='badges_div tier-2'>
                                <img src='assets/images/icons/sapphire-96.png'><img src='assets/images/icons/emerald-96.png'>
                                <img src='assets/images/icons/gold-medal-96.png'><img src='assets/images/icons/silver-medal-96.png'><img src='assets/images/icons/bronze-medal-96.png'>
                           </div>";
        }elseif($upvotes>=40 && $upvotes<50){
            $badges_str = "<div class='badges_div tier-2'>
                                <img src='assets/images/icons/ruby-96.png'><img src='assets/images/icons/sapphire-96.png'><img src='assets/images/icons/emerald-96.png'>
                                <img src='assets/images/icons/gold-medal-96.png'><img src='assets/images/icons/silver-medal-96.png'><img src='assets/images/icons/bronze-medal-96.png'>
                           </div>";
        }elseif($upvotes>=50){
            $badges_str = "<div class='badges_div tier-2'>
                                <img src='assets/images/icons/diamond-96.png'><img src='assets/images/icons/ruby-96.png'><img src='assets/images/icons/sapphire-96.png'><img src='assets/images/icons/emerald-96.png'>
                                <img src='assets/images/icons/gold-medal-96.png'><img src='assets/images/icons/silver-medal-96.png'><img src='assets/images/icons/bronze-medal-96.png'>
                           </div>";
        }
    return $badges_str;
}
}
?>