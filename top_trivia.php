<?php   
include("includes/header.php");
?>

<div class="trivia_top_page column">
        <div class='top_trivia_heading'>
        <a href="#">Top Trivia</a>
        </div>
        <hr>  
        <div>
             <?php 
             $query = mysqli_query($con, "SELECT * FROM trivia WHERE deleted ='no' ORDER BY upvotes  DESC LIMIT 10");

             $alt_var = 1;
             foreach($query as $row){
                $trivia = $row['body'];
                $id = $row['id'];
                $trivia_dot = strlen($trivia) >= 90 ? "..." : "";

                $trimmed_trivia =  str_split($trivia, 90);
                $trimmed_trivia = $trimmed_trivia[0];

                if($alt_var==1){
                    $alt_var=2;
                }else{
                    $alt_var=1;
                }


                echo($alt_var==1)?"<div class='trivia_div' id='aliceBlue'>
                                    <a href='trivia.php?id=$id'>".$trimmed_trivia."".$trivia_dot."</a><hr>
                                  </div>":
                                  "<div class='trivia_div' id='cornSilk'>
                                    <a href='trivia.php?id=$id'>".$trimmed_trivia."".$trivia_dot."</a><hr>
                                  </div>";
             }
             ?>
         </div>
    </div>