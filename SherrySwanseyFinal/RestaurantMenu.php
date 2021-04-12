<?php 
    include(__DIR__.'/NavBar.php');
    
    include(__DIR__.'/model/ModelReview.php');
    

    if(isset($_GET['id'])){
        $restaurantInfo = getRestaurantByID($_GET['id']);
        $menuInfo=getItemsByResID($_GET['id']);
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
    <title>Gourmandize | Restaurant</title>
</head>
<body>
    <div class="container gz-div-glow">
        <div class="container gz-div-inner mx-auto text-left py-5 text-white" style="font-family: textFont;background-color:#41aade;">
            <div class="media mr-auto mb-5">
            <?php $pic=getRecentResReviewPictures($restaurantInfo['Restaurant_ID']); ?>
                <div class="media-body ms-auto">
                    <?php
                        echo '<h1 class="display-4"style="font-family: textFont;">'.$restaurantInfo['Restaurant_Name'].'</h1>';
                        echo '<div class="star-ratings-sprite" style="float:left;">';
                        echo '<span style="width:' . round(calculateRestaurantStarRating($restaurantInfo['Restaurant_ID']),2) * 20 . '%' . ';" class="star-ratings-sprite-rating">';
                        echo '</span></div>';
                        echo '<br>';
                        echo '<h3 class="display-5" style="width:50%">'.$restaurantInfo['ResAddress'].'</h3>';
                        echo '<h3 class="display-5" style="width:50%">'.$restaurantInfo['Phone'].'</h3>';
                        echo '<h3 class="display-5" style="width:50%"><a href = "'.$restaurantInfo['Restaurant_URL'].'" style="color:red;" target="_blank">Website</a></31>';
                        echo '<button class="btn btn-outline-light m-3" onclick="window.location.href=`AddRestaurantReview.php?RestaurantID='.$restaurantInfo['Restaurant_ID'].'`">Add Review</button>';

                    ?>
                </div>
            </div>
            <div class="container gz-div-inner mx-auto text-center py-5 text-white" style="font-family: textFont;marign-bot:-30px;margin-top:-30px;">
                <a class="btn btn-outline-light" href="ViewRestaurant.php?id=<?php echo $restaurantInfo['Restaurant_ID'];?>" style="width:160px;">Reviews</a>
                <a class="btn btn-dark" href="" style="margin-left:-5px;width:160px;" disabled>Menu</a>
            </div>
            <div style="width:100%; margin:auto; margin-left:10%;">
                <table class="table-borderless " style="table-layout:fixed; width:80%;">
                    <?php 
                        $rowCount = 0;
                        $i=0;
                        foreach($menuInfo as $mInfo):
                            if($rowCount == 0)
                                echo '<tr>';
                            $rowCount ++;
                            $i++;
                            echo '<td style="padding:20px; text-align:center; text-height:50%;  " class="col-md-4"><a href="ViewItem.php?id='.$mInfo['Item_ID'].'" class="btn btn-outline-light" style="min-height:75px; min-width:100%;">'. $mInfo['ItemName'] . '</a></td>'; 
                            if($rowCount == 4)
                            {
                                echo "</tr>";
                                $rowCount = 0;
                            }
                        endforeach;
                        if($rowCount != 0)
                            echo "</tr>";
                    ?>
                </table>
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