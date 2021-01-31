<?php 
    include(__DIR__.'/NavBar.php');
    include(__DIR__.'/model/ModelReview.php');
    $Results0=[];
    $resname=array();
    $Results1=[];
    $foodname=array();
    $Results2=[];
    $FoodCat=array();
    $Results2_2=array();
    $Results3=[];
    $ResCat=array();
    $Results3_2=array();


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

    if(isset($_GET['Totalsearch'])){
      $Src=filter_input(INPUT_GET,'Totalsearch');
      $Src= $_GET['Totalsearch'];

      $Results3=SearchResCategory($Src);
      $Results2=SearchFoodCategory($Src);
      $Results1=SearchMenuItems($Src);
      $Results0=SearchResName($Src);
      
        if($Results0!=''){
            array_push($resname, $Results0['Restaurant_Name']);
        }

        if($Results1!=''){      
            array_push($foodname, $Results1['ItemName']);
        }

        if($Results2!=''){      
            array_push($FoodCat, $Results2['Review_ID']);
            $Results2_2=FindMenuItemName($Results2['Item_ID']);
        }

        if($Results3!=''){
            array_push($ResCat, $Results3['ResReview_ID']);
            $Results3_2=getRestaurantByID($Results3['Restaurant_ID']);
        }
      
    }





?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gourmandize | Search</title>
    <link rel="stylesheet" type="text/css" href="misc/css/Style.css">
    <style>
        .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
        }

        .switch input { 
        opacity: 0;
        width: 0;
        height: 0;
        }

        .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
        }

        .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
        }

        input:checked + .slider {
        background-color: cyan;
        }

        input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
        border-radius: 34px;
        }

        .slider.round:before {
        border-radius: 50%;
        }
    </style>
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
                        <label class="switch">
                            <input type="radio" id="restaurantRadio" name="type" value="restaurant"/>
                            <span class="slider round"></span>
                        </label>
                        <label for="restaurantRadio" >Restaurant</label>
                        <label class="switch">
                            <input type="radio" id="foodRadio" name="type" value="food" <?= $_GET['type'] == 'food'? "checked" : "" ?>/>
                            <span class="slider round"></span>
                        </label>
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
            if($Results0!=''){
                foreach($resname as $Result0)
                {
                    echo '<div class="row border border-white rounded m-2" style="background-image: radial-gradient(ellipse at center, #448a9a,#e1b10f66)">';
                    echo '<div class="media mx-3 " style="padding-top: 15px; padding-bottom: 15px;">';
                        echo '<img class="mr-3 align-self-top" style="height: auto; width: 25%;" src="misc\images\Restaurant_Test.jpg" alt="img">';
                        echo '<div class="media-body">';
                            echo '<div class="row">';
                                echo '<div class="col">';
                                    echo '<h3>'.$Results0['Restaurant_Name'].'</h3>';
                                    echo '<h3>'.round(calculateRestaurantStarRating($Results0['Restaurant_ID']),2 ).' Stars</h3>';
                                    echo '<h5>'.$Results0['ResAddress'].'</h5>';
                                    echo '<h5>'.$Results0['Phone'].'</h5>';
                                    echo '<h5><a href = "'.$Results0['Restaurant_URL'].'" target="_blank">'.$Results0['Restaurant_URL'].'</a></h5>';
                                echo '</div>';
                                echo '<div class="col d-flex align-content-center flex-wrap">';
                                    echo '<button class="btn btn-outline-light m-3" onclick="window.location.href=`ViewRestaurant.php?id='.$Results0['Restaurant_ID'].'`">View Reviews</button>';
                                    echo '<button class="btn btn-outline-light m-3" onclick="window.location.href=`AddRestaurantReview.php?RestaurantID='.$Results0['Restaurant_ID'].'`">Add Review</button>';
                                echo '</div></div></div></div></div>';
                }
            }
            if($Results1!=''){
                foreach($foodname as $Result1){
                    echo '<div class="row border border-white rounded m-2" style="background-image: radial-gradient(ellipse at center, #448a9a,#e1b10f66)">';
                    echo '<div class="media mx-3 " style="padding-top: 15px; padding-bottom: 15px;">';
                        /*Adjust image source*/
                        echo '<img class="mr-3 align-self-top" style="height: auto; width: 25%;" src="misc\images\Fries_Test.jpeg" alt="img">';
                        echo '<div class="media-body">';
                            echo '<div class="row">';
                                echo '<div class="col">';
                                    echo '<h3>'.$Results1['ItemName'].'</h3>';
                                    echo '<h3>'.getRestaurantName($Results1['Restaurant_ID']).'</h3>';
                                    echo '<h3>'.round(calculateItemStarRating($Results1['Item_ID']),2 ).' Stars</h3>';
                                    echo '<h5>'.implode(', ', getCommonItemCategories($Results1['Item_ID'], 3)).'</h5>';
                                echo '</div>';
                                echo '<div class="col d-flex align-content-center flex-wrap">';
                                    echo '<button class="btn btn-outline-light m-3" onclick="window.location.href=`ViewItem.php?id='.$Results1['Item_ID'].'`">View Reviews</button>';
                                    echo '<button class="btn btn-outline-light m-3" onclick="window.location.href=`AddRestaurantReview.php?itemID='.$Results1['Item_ID'].'`">Add Review</button>';
                                echo '</div></div></div></div></div>';
            }
            }
            if($Results2!=''){
                foreach($FoodCat as $Result2){
                    echo '<div class="row border border-white rounded m-2" style="background-image: radial-gradient(ellipse at center, #448a9a,#e1b10f66)">';
                    echo '<div class="media mx-3 " style="padding-top: 15px; padding-bottom: 15px;">';
                        /*Adjust image source*/
                        echo '<img class="mr-3 align-self-top" style="height: auto; width: 25%;" src="misc\images\Fries_Test.jpeg" alt="img">';
                        echo '<div class="media-body">';
                            echo '<div class="row">';
                                echo '<div class="col">';
                                    echo '<h3>'.$Results2_2['ItemName'].'</h3>';
                                    echo '<h3>'.getRestaurantName($Results2['Restaurant_ID']).'</h3>';
                                    echo '<h3>'.round(calculateItemStarRating($Results2['Item_ID']),2 ).' Stars</h3>';
                                    echo '<h5>'.implode(', ', getCommonItemCategories($Results2['Item_ID'], 3)).'</h5>';
                                echo '</div>';
                                echo '<div class="col d-flex align-content-center flex-wrap">';
                                    echo '<button class="btn btn-outline-light m-3" onclick="window.location.href=`ViewItem.php?id='.$Results2['Item_ID'].'`">View Reviews</button>';
                                    echo '<button class="btn btn-outline-light m-3" onclick="window.location.href=`AddRestaurantReview.php?itemID='.$Results2['Item_ID'].'`">Add Review</button>';
                                echo '</div></div></div></div></div>';
                }
            }

            if($Results3!=''){
                foreach($ResCat as $Result3)
                {
                    echo '<div class="row border border-white rounded m-2" style="background-image: radial-gradient(ellipse at center, #448a9a,#e1b10f66)">';
                    echo '<div class="media mx-3 " style="padding-top: 15px; padding-bottom: 15px;">';
                        echo '<img class="mr-3 align-self-top" style="height: auto; width: 25%;" src="misc\images\Restaurant_Test.jpg" alt="img">';
                        echo '<div class="media-body">';
                            echo '<div class="row">';
                                echo '<div class="col">';
                                    echo '<h3>'.getRestaurantName($Results3['Restaurant_ID']).'</h3>';
                                    echo '<h3>'.round(calculateRestaurantStarRating($Results3['Restaurant_ID']),2 ).' Stars</h3>';
                                    echo '<h5>'.$Results3_2['ResAddress'].'</h5>';
                                    echo '<h5>'.$Results3_2['Phone'].'</h5>';
                                    echo '<h5><a href = "'.$Results3_2['Restaurant_URL'].'" target="_blank">'.$Results3_2['Restaurant_URL'].'</a></h5>';
                                echo '</div>';
                                echo '<div class="col d-flex align-content-center flex-wrap">';
                                    echo '<button class="btn btn-outline-light m-3" onclick="window.location.href=`ViewRestaurant.php?id='.$Results3_2['Restaurant_ID'].'`">View Reviews</button>';
                                    echo '<button class="btn btn-outline-light m-3" onclick="window.location.href=`AddRestaurantReview.php?RestaurantID='.$Results3_2['Restaurant_ID'].'`">Add Review</button>';
                                echo '</div></div></div></div></div>';
                }
            }

            if($Results0='' && $Results1='' && $Results2='' && $Results3=''){
                echo "No Search results found";
            }

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
<footer>
  <hr style="color:red;"/>
  <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2)">
    © 2021 Copyright:
    <a class="text-dark" href="AboutUs.php">About Us</a>
  </div>
  </footer>
</html>