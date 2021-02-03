<?php 
    include (__DIR__.'/NavBar.php');
    include (__DIR__.'/model/ModelReview.php');

    $loop2=0; 

    $uID=getUserID($_SESSION['email']);
    $user=getUserByID($uID);
    $resReviewArray=getAllResReviewsByUserChronologicalNoLimit($uID, TRUE);

    if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
        header('Location: Login.php');
        exit;
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
                <h1 class="display-4"style="font-family: titleFont;"><?=$user['Username'];?></h1>
                <h1 class="display-4"><?=$user['FName'];?></h1>
                <h1 class="display-5">Number of Reviews: <?=count($resReviewArray);?></h1>
                <h1 class="display-5">Average Star Reviews: <?php echo number_format(calculateAvgStarRatingFromUser($uID), 2, '.', '');?></h1>
            </div>
            <?php 
                foreach($resReviewArray as $resReview)
                {
                    echo '<div class="row border border-white rounded m-2" style="background-image: radial-gradient(ellipse at center, #448a9a,#e1b10f66)">';
                        echo '<div class="media mx-3" style="padding-top: 15px; padding-bottom: 15px;">';
                            echo '<div class="media-body">';
                                echo '<div>';
                                    echo `<h3>Restaurant's Name: ` . getRestaurantName($resReview['Restaurant_ID']) . `</h3>`;
                                    echo `<h3>Stars: `. number_format($resReview['Star_lvl'], 2, '.', '') . `</h3>`;
                                    echo `<p style="min-height: 110px">` . $resReview['Review'] . `?></p>`;
                                    echo `<p>Date: ` . $resReview['ReviewDate'] .`</p>`;
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
