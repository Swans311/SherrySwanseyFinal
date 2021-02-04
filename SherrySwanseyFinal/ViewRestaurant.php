<?php 
    include(__DIR__.'/NavBar.php');
    include(__DIR__.'/model/ModelReview.php');
    
    $restaurantInfo = getRestaurantByID($_GET['id']);
    $restaurantReviews = getMostRecentReviewsByRestaurant($_GET['id'], 3);

    
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
    <title>Gourmandize | Restaurant</title>
</head>
<body>
    <div class="container gz-div-glow">
        <div class="container gz-div-inner mx-auto text-left py-5 text-white" style="font-family: textFont;">
            <div class="media mr-auto mb-5">
                <img class="mr-3 align-self-center" style="height: 300px; width: 40%;" src="misc\images\Restaurant_Test.jpg" alt="img"/>
                <div class="media-body ms-auto">
                    <?php
                        echo '<h1 class="display-4"style="font-family: titleFont; width:50%">'.$restaurantInfo['Restaurant_Name'].'</h1>';
                        echo '<h1 class="display-4" style="width:50%">'.round(calculateRestaurantStarRating($restaurantInfo['Restaurant_ID']),2).' Stars</h1>';
                        echo '<h1 class="display-5" style="width:50%">'.$restaurantInfo['ResAddress'].'</h1>';
                        echo '<h1 class="display-5" style="width:50%">'.$restaurantInfo['Phone'].'</h1>';
                        echo '<h1 class="display-5" style="width:50%"><a href = "'.$restaurantInfo['Restaurant_URL'].'" target="_blank">Restaurant Site</a></h1>';
                        echo '<button class="btn btn-outline-light m-3" onclick="window.location.href=`AddRestaurantReview.php?itemID='.$restaurantInfo['Restaurant_ID'].'`">Add Review</button>';

                    ?>
                </div>
            </div>
            <?php
                if(isset($_GET['id']))
                    foreach($restaurantReviews as $resRev)
                    {
                        echo '<div class="row border border-white rounded m-2" style="background-image: radial-gradient(ellipse at center, #448a9a,#e1b10f66)">';
                            echo '<div class="media mx-3" style="padding-top: 15px; padding-bottom: 15px;">';
                                echo '<img class="mr-3 align-self-top" style="height: auto; width: 25%;" src="misc\images\Restaurant_Test.jpg" alt="img">';
                                    echo '<div class="media-body">';
                                        echo '<div>';
                                            echo '<h3>'.$resRev['UserName'].'</h3>';
                                            echo '<h3>'.$resRev['Star_lvl'].' Stars</h3>';
                                            echo '<p style="min-height: 110px">'.$resRev['Review'].'</p>';
                                        echo '</div>';
                                        foreach(getItemsInRestaurantReview($resRev['ResReview_ID']) as $itemRev)
                                        {
                                            echo '<hr style="width:100%!important; border-top:2px solid white;"/>';
                                            echo '<div class="media my-3">';
                                                echo '<img class="mr-3 align-self-center" style="height: auto; width: 25%;" src="misc\images\Fries_Test.jpeg" alt="img">';
                                                echo '<div class="media-body">';
                                                    echo '<div>';
                                                        echo '<h3>'.getItemName($itemRev['Item_ID']).'</h3>';
                                                        echo '<h3>'.$itemRev['Star_lvl'].' Stars</h3>';
                                                        echo '<p>'.$itemRev['Review'].'</p>';
                                                    echo '</div></div></div>';
                                        }
                                        echo '</div></div></div>';

                    }
            ?>
        </div>
    </div>
</body>
<footer>
  <hr style="color:red;"/>
  <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2)">
    © 2021 Copyright:
    <a class="text-dark" href="AboutUs.php">About Us</a>
  </div>
  </footer>
</html>