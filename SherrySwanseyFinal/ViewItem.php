<?php 
    include(__DIR__.'/NavBar.php');
    include(__DIR__.'/model/ModelReview.php');
    
    if(isset($_GET['id'])){
        $itemInfo = getItemByID($_GET['id']);
        $itemReviews = getMostRecentReviewsByItem($_GET['id'], 3);
    }
    else{
        header("Location:SearchResults.php");
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
    <title>Gourmandize | Food Item</title>
</head>
<body>
<div class="container gz-div-glow">
        <div class="container gz-div-inner mx-auto text-left py-5 text-white" style="font-family: textFont;">
            <div class="media mr-auto mb-5">
                <img class="mr-3 align-self-center" style="height: 300px; width: auto;" src="misc\images\Fries_Test.jpg" alt="img">
                <div class="media-body">
                    <?php
                        echo '<h1 class="display-4"style="font-family: textFont;">'.$itemInfo['ItemName'].'</h1>';
                        echo '<h3 class="display-4"style="font-family: textFont;">'.getRestaurantName($itemInfo['Restaurant_ID']).'</h3>';
                        echo '<h3 class="display-4">'.implode(', ', extractNames(getCommonItemCategories($itemInfo['Item_ID'], 3))).'</h3>';
                        echo '<div class="star-ratings-sprite" style="float:left;">';
                        echo '<span style="width:' .round(calculateItemStarRating($itemInfo['Item_ID']),2 ) * 20 . '%' . ';" class="star-ratings-sprite-rating">';
                        echo '</span></div>';
                        echo '<br>';
                        echo '<button class="btn btn-outline-light m-3" onclick="window.location.href=`AddRestaurantReview.php?itemID='.$itemInfo['Restaurant_ID'].'`">Add Review</button>';

                    ?>
                </div>
            </div>
            <?php
                if(isset($_GET['id']))
                {
                    foreach($itemReviews as $itemRev)
                    {
                        
                        
                        echo '<div class="row border border-white rounded m-2" style="background-image: radial-gradient(ellipse at center, #e75480,#f71a08)">';
                        echo '<div class="media mx-3" style="padding-top: 15px; padding-bottom: 15px;">';
                        $pic = getReviewPictures($itemRev['Review_ID']);
                        //image goes here***********************
                                echo '<div class="media-body">';
                                    echo '<div>';
                                        echo '<h3>'.getUsername($itemRev['User_ID']).'</h3>';
                                        echo '<div class="star-ratings-sprite" style="float:left;">';
                                        echo '<span style="width:' . $itemRev['Star_lvl'] * 20 . '%' . ';" class="star-ratings-sprite-rating">';
                                        echo '</span></div>';
                                        echo '<br>';
                                        echo '<p>'.$itemRev['Review'].'</p>';
                                        echo '<p>';
                                        $tags = array();
                                        foreach(explode(',', $itemRev['Category']) as $tagID)
                                            array_push($tags, getTagByID($tagID)['Name'] . '   ');
                                        echo implode(', ', $tags);
                                        echo '</p>';

                                    echo '</div></div></div></div>';
                                    
                    }
                }
            ?>
            </div>
        </div>
    </div>
</body>
<footer style="bottom:0; width:100%;">
  <br/>
  <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.9); color:#ff3300; font-size:16px">
    © 2021 Copyright:
    <a class="text-blue" href="AboutUs.php">About Us</a>
  </div>
  </footer>
</html>