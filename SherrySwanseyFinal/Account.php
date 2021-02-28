<?php 
    include (__DIR__.'/NavBar.php');
    include (__DIR__.'/model/ModelReview.php');

    $loop2=0; 
    $resInfoArray=[];


    $uID=getUserID($_SESSION['email']);
    $user=getUserByID($uID);
    $resReviewArray=getAllResReviewsByUserChronologicalNoLimit($uID, TRUE);

    if (!isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn']) {
        header('Location: Login.php');
        exit;
    }

    if ($user['ResOwner']==True){
        $owner=True;
        $resInfoArray=findOwnedRes($uID);
    }
    else{
        $owner=False;
    }
    


    if(isset($_GET['Totalsearch'])){
        $src=filter_input(INPUT_GET,'Totalsearch');
        $src= $_GET['Totalsearch'];
        header("Location: SearchResults.php?Totalsearch=".$src."");
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gourmandize | Account</title>
</head>
<body>
    <div class="container gz-div-glow">
        <div class="container gz-div-inner mx-auto text-left py-5 text-white" style="font-family: textFont;">
            <div class="container mr-auto mb-5">
                <h1 class="display-4"style="font-family: textFont; color:#999;">
                    <?=$user['Username'];?>
                    <a href="Inbox.php">
                        <img src="misc/images/Mail_Icon.png" style="width:64px;height:64px;margin-top:-10px;">
                    </a>
                </h1>
                <h3 class="display-5"><?=$user['FName'];?></h3>
            <?php
                if($owner!=True){
                    echo '<h3 class="display-5">Number of Reviews: '.count($resReviewArray).'</h3>';
                    echo '<h3 class="display-5">Average Star Reviews:</h3>';
                    echo '<div class="star-ratings-sprite" style="float:left;">';
                        echo '<span style="width:' . number_format(calculateAvgStarRatingFromUser($uID), 2, ".", "") * 20 . '%' . ';" class="star-ratings-sprite-rating">';
                    echo '</span></div>';
                    echo '<br>';
                    echo '</div>';
                    foreach($resReviewArray as $resReview)
                    {
                        echo '<div class="row border border-white rounded m-2" style="background-image: radial-gradient(ellipse at center, #e75480,#f71a08)">';
                            echo '<div class="media mx-3" style="padding-top: 15px; padding-bottom: 15px;">';
                                echo '<div class="media-body">';
                                    echo '<div>';
                                        echo '<h3>Restaurant Name: ' . getRestaurantName($resReview['Restaurant_ID']) . '</h3>';
                                        echo '<div class="star-ratings-sprite" style="float:left;">';
                                        echo '<span style="width:' . number_format($resReview['Star_lvl'], 2, '.', '') * 20 . '%' . ';" class="star-ratings-sprite-rating">';
                                        echo '</span></div>';
                                        echo '<br>';
                                        echo '<p style="min-height: 110px">' . $resReview['Review'] . '</p>';
                                        echo '<p>Date: ' . $resReview['ReviewDate'] .'</p>'; 
                                    echo '</div>';
                                    $reviewArray = getItemsInRestaurantReview($resReview['ResReview_ID']);
                                    foreach($reviewArray as $review)
                                    {
                                        echo '<hr style="width:100%!important; border-top:2px solid white;"/>';
                                        echo '<div class="media my-3">';
                                            echo'<!-- Adjust image source-->';
                                                echo'<div class="media-body">';
                                                    echo '<div class="mx-5">';
                                                    echo '<!-- Adjust data-->';
                                                    echo'<h3>Food Name: '. getItemName($review['Item_ID']) .'</h3>'; 
                                                    echo '<div class="star-ratings-sprite" style="float:left;">';
                                                    echo '<span style="width:' . number_format($review['Star_lvl'], 2, '.', '')* 20 . '%' . ';" class="star-ratings-sprite-rating">';
                                                    echo '</span></div>';
                                                    echo '<br>';
                                                    echo '<p>'.$review['Review'].' </p>';
                                                echo '</div>';
                                            echo '</div>';
                                        echo '</div>';
                                    }
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    }
                }
                elseif($owner==True){echo '</div>';
                    foreach($resInfoArray as $resInfo)
                    {
                        echo "<a href=ViewOwnedRestaurant.php?id=".$resInfo['Restaurant_ID']." style='color:white;'>";
                        echo '<div class="row border border-white rounded m-2" style="background-image: radial-gradient(ellipse at center, #e75480,#f71a08)">';
                            echo "<div style='margin-left:30%;'>"; //this is where a picture would go
                                echo '<div style="width:180%;margin:15%;">';
                                    echo '<h3 width:100%;>' . $resInfo['Restaurant_Name'] . '</h3>';
                                    echo '<div class="star-ratings-sprite" style="float:left;">';
                                    echo '<span style="width:' . round(calculateRestaurantStarRating($resInfo['Restaurant_ID']),2)* 20 . '%' . ';" class="star-ratings-sprite-rating">';
                                    echo '</span></div>';
                                    echo '<br>';
                                    echo '<p>'.$resInfo['ResAddress'] . '</p>';
                                    echo "</div>";
                                echo '</div>';
                            echo '</a>';
                            echo "</div>";
                    }
                    echo "<br/><br/><br/>";
                    echo '<h4>My Reviews </h4>';
                    foreach($resReviewArray as $resReview)
                    {
                        echo '<div class="row border border-white rounded m-2" style="background-image: radial-gradient(ellipse at center, #e75480,#f71a08)">';
                            echo '<div class="media mx-3" style="padding-top: 15px; padding-bottom: 15px;">';
                                echo '<div class="media-body">';
                                    echo '<div>';
                                        echo '<h3>Restaurant Name: ' . getRestaurantName($resReview['Restaurant_ID']) . '</h3>';
                                        echo '<h3>Stars: '. number_format($resReview['Star_lvl'], 2, '.', '') . '</h3>';
                                        echo '<p style="min-height: 110px">' . $resReview['Review'] . '</p>';
                                        echo '<p>Date: ' . $resReview['ReviewDate'] .'</p>'; 
                                    echo '</div>';
                                    $reviewArray = getItemsInRestaurantReview($resReview['ResReview_ID']);
                                    foreach($reviewArray as $review)
                                    {
                                        echo '<hr style="width:100%!important; border-top:2px solid white;"/>';
                                        echo '<div class="media my-3">';
                                            echo'<!-- Adjust image source-->';
                                                echo'<div class="media-body">';
                                                    echo '<div class="mx-5">';
                                                    echo '<!-- Adjust data-->';
                                                    echo'<h3>Food Name: '. getItemName($review['Item_ID']) .'</h3>'; 
                                                    echo '<h3>Stars '.number_format($review['Star_lvl'], 2, '.', '').'</h3>';
                                                    echo '<p>'.$review['Review'].' </p>';
                                                echo '</div>';
                                            echo '</div>';
                                        echo '</div>';
                                    }
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    }
                }     

                

            ?>
        </div>
            </div>
    </div>
</body>
<footer>
  <br/>
  <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.9); color:#ff3300; font-size:16px">
    © 2021 Copyright:
    <a class="text-blue" href="AboutUs.php">About Us</a>
  </div>
  </footer>
</html>