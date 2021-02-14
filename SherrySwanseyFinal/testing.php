<?php

    include ("model/db.php");
    include ("model/ModelReview.php");

    if(isset($_POST['submit']))
    {
        if(isset($_POST['restaurantName']) && isset($_POST['restaurantAddress']) && isset($_POST['restaurantPhone']) && isset($_POST['restaurantURL']))
            {
                if($resID == false)//if restaurantID wasnt found add restaurant
                {
                    addRestaurant($_POST['restaurantName'], $_POST['restaurantAddress'], $_POST['restaurantPhone'], $_POST['restaurantURL']);
                    //Should be guaranteed to exist now
                    $resID = searchOneRestaurantID($_POST['restaurantName'], $_POST['restaurantAddress'], $_POST['restaurantPhone'], $_POST['restaurantURL']);
                }
                //put together restaurantreview except 2d array portion
                $resReviewParams = array();
                $resReviewParams['resID'] = $resID;
                //waiting on dave to push his code for this to work
                $uID = getUserID($_SESSION['email']);
                $resReviewParams['UserID'] = $uID;
                if(isset($_POST['restaurantReview']) && $_POST['restaurantReview'] != "")
                    $resReviewParams['resReview'] = $_POST['restaurantReview'];
                else     
                    $flag = false;
                if(isset($_POST['restaurantCategories']) && $_POST['restaurantCategories'] != "")
                    $resReviewParams['categories'] = $_POST['restaurantCategories'];
                else     
                    $flag = false;
                if(isset($_POST['restaurantRating']) && $_POST['restaurantRating'] != "")
                    $resReviewParams['resRating'] = $_POST['restaurantRating'];
                else     
                    $flag = false;
                if(isset($_POST['reviewAnonymous']))
                    $resReviewParams['resVisible'] = false;
                else     
                    $resReviewParams['resVisible'] = true;
                
                //TODO:: code for sending image location too

                //if all those vars were present continue
                
                    if($flag){
                        addRestaurantReview($resID, $uID, $resReviewParams['resReview'],  $resReviewParams['resRating'], $resReviewParams['resVisible'], $restaurantPic, $twoDimArray, $resReviewParams['categories']);
                        var_dump($resID, $uID, $resReviewParams['resReview'],  $resReviewParams['resRating'], $resReviewParams['resVisible'], $restaurantPic, $twoDimArray, $resReviewParams['categories']);
                        //header('Location: homepage.php');
                    }
                } 






    if(isset($_POST['btn-add']))
    {
        $images=$_FILES['profile']['name'];
        $tmp_dir=$_FILES['profile']['tmp_name'];
        $imageSize=$_FILES['profile']['size'];
        $upload_dir='uploads';
        $imgExt=strtolower(pathinfo($images, PATHINFO_EXTENSION));
        $valid_extensions=array('jpeg', 'jpg', 'png', 'gif', 'pdf');
        $picProfile=rand(1000,1000000). ".".$imgExt;
        move_uploaded_file($tmp_dir, $upload_dir.$picProfile);
        $stmt=$db->prepare("INSERT INTO rimages SET Picture = :Img");
        $stmt->bindParam(":Img", $picProfile);

        if($stmt->execute())
        {
            ?>
            <script>
                alert("new record added");
            </script>
            <?php
        }else{
            ?>
            <script>
                alert("record not added");
            </script>
            <?php
        }
    }



    $pic=array();
    $pic2=array();
    $pic=findpicture(4);



?>



<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">



</head>
<body>
<form method="post" enctype="multipart/form-data">
                <div id="inputs">
                    <div class="row border border-outline-white rounded mx-2 p-2">
                        <div class="row mx-2 p-1">
                            <div class="col form-group">
                                <h2 class="display-5 mb-5">Restaurant Review</h2>
                                <label for="exampleFormControlFile1">Upload Image Here:</label>
                                <!--TODO:: Make this stick around after a POST-->
                                <input type="file" name="restaurant" class="form-control-file"  accept="*/image" >
                                <div class="form-check mt-5">
                                    <input class="form-check-input" name="reviewAnonymous" type="checkbox" value="" id="reviewAnonymous" <?=isset($_POST['reviewAnonymous'])? "checked" : "" ?>>
                                    <label class="form-check-label" for="reviewAnonymous"> Review Anonymously</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input size="25"type="text" name="restaurantName" id="restaurantName" placeholder="Restaurant Name" value="McDonald's" readonly/>
                                </div>
                                <div class="form-group">
                                    <input size="25"type="text" name="restaurantAddress" id="restaurantAddress" placeholder="Address" value="1 McAve" readonly/>
                                </div>
                                <div class="form-group">
                                    <input size="25"type="text" name="restaurantPhone" id="restaurantPhone" placeholder="Phone" value="4011111111" readonly/>
                                </div>
                                <div class="form-group">
                                    <input size="25"type="text" name="restaurantURL" placeholder="URL" value="McDonalds.com" readonly/>
                                </div>
                                <div class="form-group">
                                    <input size="25"type="text" name="restaurantCategories" id="restaurantCategories" placeholder="EX: Fast Food, Burger, Fried"  value="Fast Food"/>
                                </div>
                                <div class="form-group">
                                    <label for="restaurantRating">Star Rating:</label>
                                    <input type="number" name="restaurantRating" id="restaurantRating" min="0" max="5" step="1" value="5"/>
                                </div>
                            </div>
                            <div class="col form-group">
                                <h2 class="text-white" style="font-family: textFont">Review</h2>
                                <textarea name="restaurantReview" class="form-control" rows="6"><?= isset($_POST['restaurantReview'])? $_POST['restaurantReview']: ''?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row border border-outline-white rounded m-2 p-2">
                        <div class="row mx-2 p-1">
                            <div class="col form-group">
                                <h2 class="display-5 mb-5">Food Item Review</h2>
                                <label for="exampleFormControlFile1">Upload Image Here: </label>
                                <input type="file" name="foodPic1" id="foodPic1" class="form-control-file"  accept="*/image" >
                                
                            </div>
                        </div>
                    <button id="submitButton" class="btn btn-outline-light mx-3" name="submit" type="submit">Submit</button>
                </div>
            </form>
<!--<form method="post" enctype="multipart/form-data">
    <input type="file" name="profile" class="form-control" required="" accept="*/image" >
    <button type="submit" name="btn-add">Add Picture </button>
</form>-->

<?php foreach ($pic as $p){
    //echo $p['Picture'];
    echo "<img src='uploads$p[Picture]' style='width:100px; height:100px;'/>";
}
?>

</body>
</html>