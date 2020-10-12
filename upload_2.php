<?php 
include("includes/header.php");

if(isset($_POST['image'])){
    $uploadOk = 1;
    $imageName = $_FILES['upload']['name'];
    $errorMessage = "";

    if($imageName != ""){
        $targetDir = "assets/images/profile_pics/";
        $imageName = $targetDir.uniqid().basename($imageName);
        $imageFileType = pathinfo($imageName, PATHINFO_EXTENSION);

        if($_FILES['upload']['size']>10000000){
            $errorMessage = "Sorry, your file is too large!";
            $uploadOk = 0;
        }

        if(strtolower($imageFileType) != "jpeg" && strtolower($imageFileType) != "png" && strtolower($imageFileType) != "jpg" && strtolower($imageFileType) != "gif"){
            $errorMessage = "Sorry, invalid file format!";
            $uploadOk = 0;
        }

        if($uploadOk){
        	function resize_image($file, $w, $h, $crop=FALSE) {
			    list($width, $height) = getimagesize($file);
			    $r = $width / $height;
			    if ($crop) {
			        if ($width > $height) {
			            $width = ceil($width-($width*abs($r-$w/$h)));
			        } else {
			            $height = ceil($height-($height*abs($r-$w/$h)));
			        }
			        $newwidth = $w;
			        $newheight = $h;
			    } else {
			        if ($w/$h > $r) {
			            $newwidth = $h*$r;
			            $newheight = $h;
			        } else {
			            $newheight = $w/$r;
			            $newwidth = $w;
			        }
			    }
			    $src = imagecreatefromjpeg($file);
			    $dst = imagecreatetruecolor($newwidth, $newheight);
			    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

			    return $dst;
			}
            if(move_uploaded_file($_FILES['upload']['tmp_name'], $imageName)){
                //Image uploaded
            }
            else{
                $uploadOk = 0;
            }
        }
        $img = resize_image($imageName, 150, 150);
    }

    if($uploadOk){
    	$insert_pic_query = mysqli_query($con, "UPDATE users SET profile_pic='$imageName' WHERE username='$userLoggedIn'");
		header("Location: ".$userLoggedIn);
	}
}

?>

<div class="main_column column">


	<div id="formExample">
	    
	    <form action="upload_2.php" method="post"  enctype="multipart/form-data">
	        Upload something<br /><br />
	        <input type="file" id="image" name="upload" style="width:200px; height:30px; " />
	        <input type="hidden" value="jpeg" name="type" /> <?php // $type ?><br/><br/>
	        <input type="submit" name="image" value="Submit" style="width:85px; height:25px;" />
	    </form><br/><br/>
	    
		</div>
	</div> 
</div>
</body>
</html>