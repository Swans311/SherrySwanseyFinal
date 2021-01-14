<?php 
    include(__DIR__.'/NavBar.php');
    include(__DIR__.'/model/ModelReview.php');
    if(isset($_POST))
    {
        $searchResults = [];
        if(isset($_POST['type']))
        {
            if($_POST['type'] ==  "food")
            {
                $minStars = $_POST['minRating'] == "" ? -1 : number_format($_POST['minRating']);
                $searchResults = searchByItem($_POST['name'], $_POST['categories'], $minStars);
            }
            elseif($_POST['type'] == "restaurant")
            {
                $minStars = $_POST['minRating'] == "" ? -1 : number_format($_POST['minRating']);
                $searchResults = searchByRestaurant($_POST['name'], $_POST['categories'], $minStars);
            }
        }
        
        // /var_dump($searchResults);
    }

    if(isset($_GET['categories'])){
        $CATTY=filter_input(INPUT_GET,'categories');
        $CATTY = $_GET['categories'];
        $TYPEY=filter_input(INPUT_GET,'type');
        $TYPEY=$_GET['type'];
    } else {
        $CATTY = '';
        $_GET['type']='';
    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gourmandize | Search</title>
</head>
<body>
    <div class="container gz-div-glow">
        <div class="container gz-div-inner mx-auto text-left py-5 text-white" style="font-family: textFont;">
            <div class="container d-flex justify-content-center mx-auto text-left">
                <form method="post">
                    <h2 class="text-white display-4" style="font-family: textFont">Filter</h2>
                    <hr style="width:100%!important; border-top:2px solid white;"/>
                    <div class="form-group m-3"style="float:left;">
                        <h2 class="text-white display-5" style="font-family: textFont">Name</h2>
                        <input size="30"type="text"name="name"/>
                    </div>
                    <div class="form-group m-3" style="float:left;">
                        <h2 class="text-white display-5" style="font-family: textFont">Minimum Stars</h2>
                        <input type="number" name="minRating" min="0" max="5" step="0.1"/>
                    </div>
                    <div style="clear: both;">
                    <hr style="width:100%!important; border-top:2px solid white;"/>
                    </div>
                    <div class="form-group m-3" style="float:left;">
                        <h2 class="text-white display-5" style="font-family: textFont">Categories</h2>
                        <input size="30"type="text" name="categories" value="<?=$CATTY;?>" />
                    </div>
                    <div class="form-group m-3" style="float:left;">
                        <h2 class="text-white display-5" style="font-family: textFont">Type</h2>
                        <input type="radio" id="restaurantRadio" name="type" value="restaurant"/>
                        <label for="restaurantRadio" >Restaurant</label>
                        <input type="radio" id="foodRadio" name="type" value="food" <?= $_GET['type'] == 'food'? "checked" : "" ?>/>
                        <label for="foodRadio">Food</label>
                    </div>
                    <div style="clear: both;">
                    <hr style="width:100%!important; border-top:2px solid white;"/>
                    </div>
                    <div class="form-group m-auto text-center">
                    <button id="submitButton" class="btn btn-outline-light"type="submit">Search</button>
                    </div>
                </form>
            </div>    
            <?php
                if(isset($_POST['type']))
                {
                    if($_POST['type'] == 'restaurant')
                        foreach($searchResults as $searchResult)
                        {
                            echo '<div class="row border border-white rounded m-2" style="background-image: radial-gradient(ellipse at center, #448a9a,#e1b10f66)">';
                                echo '<div class="media mx-3 " style="padding-top: 15px; padding-bottom: 15px;">';
                                    /*Adjust image source*/
                                    echo '<img class="mr-3 align-self-top" style="height: auto; width: 25%;" src="misc\images\Restaurant_Test.jpg" alt="img">';
                                    echo '<div class="media-body">';
                                        echo '<div class="row">';
                                            echo '<div class="col">';
                                                echo '<h3>'.$searchResult['Restaurant_Name'].'</h3>';
                                                echo '<h3>'.round(calculateRestaurantStarRating($searchResult['Restaurant_ID']),2 ).' Stars</h3>';
                                                echo '<h5>'.$searchResult['ResAddress'].'</h5>';
                                                echo '<h5>'.$searchResult['Phone'].'</h5>';
                                                echo '<h5><a href = "'.$searchResult['Restaurant_URL'].'" target="_blank">'.$searchResult['Restaurant_URL'].'</a></h5>';
                                            echo '</div>';
                                            echo '<div class="col d-flex align-content-center flex-wrap">';
                                                echo '<button class="btn btn-outline-light m-3" onclick="window.location.href=`ViewRestaurant.php?id='.$searchResult['Restaurant_ID'].'`">View Reviews</button>';
                                                echo '<button class="btn btn-outline-light m-3" onclick="window.location.href=`AddRestaurantReview.php?RestaurantID='.$searchResult['Restaurant_ID'].'`">Add Review</button>';
                                            echo '</div></div></div></div></div>';

                        }
                    else if($_POST['type'] == 'food')
                    {
                        foreach($searchResults as $searchResult)
                        {
                            echo '<div class="row border border-white rounded m-2" style="background-image: radial-gradient(ellipse at center, #448a9a,#e1b10f66)">';
                            echo '<div class="media mx-3 " style="padding-top: 15px; padding-bottom: 15px;">';
                                /*Adjust image source*/
                                echo '<img class="mr-3 align-self-top" style="height: auto; width: 25%;" src="misc\images\Fries_Test.jpeg" alt="img">';
                                echo '<div class="media-body">';
                                    echo '<div class="row">';
                                        echo '<div class="col">';
                                            echo '<h3>'.$searchResult['ItemName'].'</h3>';
                                            echo '<h3>'.getRestaurantName($searchResult['Restaurant_ID']).'</h3>';
                                            echo '<h3>'.round(calculateItemStarRating($searchResult['Item_ID']),2 ).' Stars</h3>';
                                            echo '<h5>'.implode(', ', getCommonItemCategories($searchResult['Item_ID'], 3)).'</h5>';
                                        echo '</div>';
                                        echo '<div class="col d-flex align-content-center flex-wrap">';
                                            echo '<button class="btn btn-outline-light m-3" onclick="window.location.href=`ViewItem.php?id='.$searchResult['Item_ID'].'`">View Reviews</button>';
                                            echo '<button class="btn btn-outline-light m-3" onclick="window.location.href=`AddRestaurantReview.php?itemID='.$searchResult['Item_ID'].'`">Add Review</button>';
                                        echo '</div></div></div></div></div>';
                        }
                    }
                }
            ?>
        </div>
    </div>
</body>
</html>