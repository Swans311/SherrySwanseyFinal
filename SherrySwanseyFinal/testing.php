<?php

    include ("model/db.php");
    include ("model/ModelReview.php");

    function addfakeRestaurantReview($restaurantID, $userID, $restaurantReview, $rating, $imageFilePath)
    {
        global $db;
        $results = 'Data NOT Added';
        $stmt = $db->prepare("INSERT INTO fakerestaurantreview SET Restaurant_ID = :restaurantID, User_ID = :userID, Review = :review, Star_lvl = :rating, UserName = :username, ReviewDate = :revDate");
        $stmt->bindValue(':restaurantID', $restaurantID);
        $stmt->bindValue(':userID', $userID);
        $stmt->bindValue(':review', $restaurantReview);
        $stmt->bindValue(':rating', $rating);
        $stmt->bindValue(':username', getUsername($userID));

        //var_dump($restaurantID);
        
        $time = date('Y-m-d H:i:s');


        $stmt->bindValue(':revDate', $time);

        //$stmt->bindValue(':visible', $anonymous);
        //$stmt->bindValue(':imageFilePath', $imageFilePath);
        //$stmt->debugDumpParams();

        $stmt->execute();


        $success = $stmt->rowCount();

        //get resReviewID by searching table for match on restaurantID, userID, date
        $stmt2 = $db->prepare("SELECT ResReview_ID FROM fakerestaurantreview WHERE User_ID = :userID ORDER BY ResReview_ID  DESC LIMIT 1");
        $stmt2 ->bindValue(":userID", $userID);
        $stmt2->execute();

        $results = $stmt2->fetch(PDO::FETCH_ASSOC);
        $resRevID = $results['ResReview_ID'];
        //var_dump ($results);
        //var_dump($resRevID);
        //echo ("my favorite number is ".$resRevID);
        echo ($resRevID);
        

        $stmt1 = $db->prepare("UPDATE fakerestaurantreview SET ResImage = :Img WHERE (ResReview_ID = :resRevID)");
        
        var_dump($imageFilePath);
        $stmt1->bindValue(":Img", $imageFilePath);
        $stmt1->bindValue(':resRevID', $resRevID);

        $stmt1->execute();





    }







    if(isset($_POST['submit']))
    {
        $images=$_FILES['profile']['name'];
        $tmp_dir=$_FILES['profile']['tmp_name'];
        $imageSize=$_FILES['profile']['size'];
        $upload_dir='uploads';
        $imgExt=strtolower(pathinfo($images, PATHINFO_EXTENSION));
        $valid_extensions=array('jpeg', 'jpg', 'png', 'gif', 'pdf');
        $picProfile=rand(1000,1000000). ".".$imgExt;
        move_uploaded_file($tmp_dir, $upload_dir.$picProfile);

        addfakeRestaurantReview(3, 8, "mmm this is so good 100/100", 5, $picProfile);

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
                                <input type="file" name="profile" class="form-control-file"  accept="*/image" >
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

                    <button id="submitButton" class="btn btn-outline-light mx-3" name="submit" type="submit">Submit</button>
                </div>
            </form>
<!--<form method="post" enctype="multipart/form-data">
    <input type="file" name="profile" class="form-control" required="" accept="*/image" >
    <button type="submit" name="btn-add">Add Picture </button>
</form>-->



</body>
</html>