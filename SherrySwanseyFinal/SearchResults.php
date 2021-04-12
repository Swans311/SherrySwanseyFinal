<?php 
    include(__DIR__.'/NavBar.php');
    
    include(__DIR__.'/model/ModelReview.php');
    $resultsByResName=[];
    $resultsByItemName=[];
    $resultsByItemCats=[];
    $resultsByResCats=[];


    if(isset($_GET))
    {
        $searchResults = [];
        if(isset($_GET['type']))
        {
            if($_GET['type'] ==  "food")
            {
                $minStars = $_GET['minRating'] == "" ? -1 : number_format($_GET['minRating']);
                $searchResults = searchByItem($_GET['name'], $_GET['categories'], $minStars);
            }
            elseif($_GET['type'] == "restaurant")
            {
                $minStars = $_GET['minRating'] == "" ? -1 : number_format($_GET['minRating']);
                $searchResults = searchByRestaurant($_GET['name'], $_GET['categories'], $minStars);
            }
        }
        
    }

    if(isset($_GET['categories'])){
        $homepageRedirectCategory=filter_input(INPUT_GET,'categories');
        $homepageRedirectCategory = $_GET['categories'];
    } else {
        $homepageRedirectCategory = '';
        $_GET['type']='';
    }

    if(isset($_GET['Totalsearch'])){
        $src=filter_input(INPUT_GET,'Totalsearch');
        $src= $_GET['Totalsearch'];

        $resultsByResCats=searchByRestaurant("", $src, -1);
        $resultsByItemCats=SearchByItem("", $src, -1);
        if($resultsByResCats == array() && $resultsByItemCats == array())
        {
            $resultsByItemName=SearchMenuItems($src);
            $resultsByResName=SearchResName($src);
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
                <form method="GET">
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
                        <input size="30"type="text" name="categories" value="<?=$homepageRedirectCategory;?>" />
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
            if($resultsByResName!=''){
                foreach($resultsByResName as $resultByResName)
                {
                    echo '<a href = "ViewRestaurant.php?id='.$resultByResName['Restaurant_ID'].'" style="text-decoration: inherit;text-shadow: -1px 1px #000; color: white; cursor: pointer;">';
                    echo '<div class="row border border-white rounded m-2" style="background-image: radial-gradient(ellipse at center, #e75480,#f71a08);">';
                    echo '<div class="media mx-3 " style="padding-top: 15px; padding-bottom: 15px;">';
                        $pic=getRecentResReviewPictures($resultByResName['Restaurant_ID']);
                            echo '<div class="media-body">';
                            echo '<div class="row">';
                                echo '<div class="col">';
                                    echo '<h3>'.$resultByResName['Restaurant_Name'].'</h3>';
                                    echo '<div class="star-ratings-sprite" style="float:left;">';
                                    echo '<span style="width:' . round(calculateRestaurantStarRating($resultByResName['Restaurant_ID']),2 ) * 20 . '%' . ';" class="star-ratings-sprite-rating">';
                                    echo '</span></div>';
                                    echo '<br>';
                                    echo '<h5>'.$resultByResName['ResAddress'].'</h5>';
                                    echo '<h5>'.$resultByResName['Phone'].'</h5>';
                                echo '</div></div></div></div></div></a>';
                }
            }
            if($resultsByItemName!=''){
                foreach($resultsByItemName as $resultByItemName){
                    echo '<a href = "ViewItem.php?id='.$resultByItemName['Item_ID'].'" style="text-decoration: inherit;text-shadow: -1px 1px white; color: #0cf; cursor: pointer;">';
                    echo '<div class="row border border-white rounded m-2" style="background-image: radial-gradient(ellipse at center, #e75480,#f71a08);">';
                    echo '<div class="media mx-3 " style="padding-top: 15px; padding-bottom: 15px;">';
                        /*Adjust image source*/
                        $pic=getRecentReviewPictures($resultByItemName['Item_ID']);
                            echo '<div class="media-body">';
                            echo '<div class="row">';
                                echo '<div class="col">';
                                    echo '<h3>'.$resultByItemName['ItemName'].'</h3>';
                                    echo '<h3>'.getRestaurantName($resultByItemName['Restaurant_ID']).'</h3>';
                                    echo '<div class="star-ratings-sprite" style="float:left;">';
                                    echo '<span style="width:' . round(calculateItemStarRating($resultByItemName['Item_ID']),2 ) * 20 . '%' . ';" class="star-ratings-sprite-rating">';
                                    echo '</span></div>';
                                    echo '<br>';
                                    echo '<h5>'.implode(', ', extractNames(getCommonItemCategories($resultByItemName['Item_ID'], 3))).'</h5>';
                                echo '</div></div></div></div></div></a>';
            }
            }
            if(isset($resultsByItemCats)){
                foreach($resultsByItemCats as $resultByItemCats){
                    echo '<a href = "ViewItem.php?id='.$resultByItemCats['Item_ID'].'" style="text-decoration: inherit;text-shadow: -1px 1px #000; color: white; cursor: pointer;">';
                    echo '<div class="row border border-white rounded m-2" style="background-image: radial-gradient(ellipse at center, #e75480,#f71a08);">';
                    echo '<div class="media mx-3 " style="padding-top: 15px; padding-bottom: 15px;">';
                        /*Adjust image source*/
                        $pic = getRecentReviewPictures($resultByItemCats['Item_ID']);
                        echo '<div class="media-body">';
                            echo '<div class="row">';
                                echo '<div class="col">';
                                    echo '<h3>'.getItemName($resultByItemCats['Item_ID']).'</h3>';
                                    echo '<h3>'.getRestaurantName($resultByItemCats['Restaurant_ID']).'</h3>';
                                    echo '<div class="star-ratings-sprite" style="float:left;">';
                                    echo '<span style="width:' . round(calculateItemStarRating($resultByItemCats['Item_ID']),2 ) * 20 . '%' . ';" class="star-ratings-sprite-rating">';
                                    echo '</span></div>';
                                    echo '<br>';
                                    echo '<h5>'.implode(', ', extractNames(getCommonItemCategories($resultByItemCats['Item_ID'], 3))).'</h5>';
                                echo '</div></div></div></div></div></a>';
                }
            }

            if(isset($resultsByResCats)){
                foreach($resultsByResCats as $resultByResCats)
                {
                    $res = getRestaurantById($resultByResCats['Restaurant_ID']);
                    echo '<a href = "ViewRestaurant.php?id='.$res['Restaurant_ID'].'" style="text-decoration: inherit;text-shadow: -1px 1px #000; color: white; cursor: pointer;">';
                    echo '<div class="row border border-white rounded m-2" style="background-image: radial-gradient(ellipse at center, #e75480,#f71a08);">';
                    echo '<div class="media mx-3 " style="padding-top: 15px; padding-bottom: 15px;">';
                        $pic=getRecentResReviewPictures($res['Restaurant_ID']);
                        echo '<div class="media-body">';
                            echo '<div class="row">';
                                echo '<div class="col">';
                                    echo '<h3>'.$res['Restaurant_Name'].'</h3>';
                                    echo '<div class="star-ratings-sprite" style="float:left;">';
                                    echo '<span style="width:' . round(calculateRestaurantStarRating($resultByResCats['Restaurant_ID']),2 ) * 20 . '%' . ';" class="star-ratings-sprite-rating">';
                                    echo '</span></div>';
                                    echo '<br>';
                                    echo '<h5>'.$res['ResAddress'].'</h5>';
                                    echo '<h5>'.$res['Phone'].'</h5>';
                                echo '</div></div></div></div></div></a>';
                }
            }

                if(isset($_GET['type']))
                {
                    if($_GET['type'] == 'restaurant')
                        foreach($searchResults as $searchResult)
                        {
                            echo '<a href = "ViewRestaurant.php?id='.$searchResult['Restaurant_ID'].'" style="text-decoration: inherit;text-shadow: -1px 1px #000; color: white; cursor: auto;">';
                            echo '<div class="row border border-white rounded m-2" style="background-image: radial-gradient(ellipse at center, #e75480,#f71a08);">';
                                echo '<div class="media mx-3 " style="padding-top: 15px; padding-bottom: 15px;">';
                                    /*Adjust image source*/
                                    $pic=getRecentResReviewPictures($searchResult['Restaurant_ID']);
                                    echo '<div class="media-body">';
                                        echo '<div class="row">';
                                            echo '<div class="col">';
                                                echo '<h3>'.$searchResult['Restaurant_Name'].'</h3>';
                                                echo '<div class="star-ratings-sprite" style="float:left;">';
                                                echo '<span style="width:' . round(calculateRestaurantStarRating($searchResult['Restaurant_ID']),2 ) * 20 . '%' . ';" class="star-ratings-sprite-rating">';
                                                echo '</span></div>';
                                                echo '<br>';
                                                echo '<h5>'.$searchResult['ResAddress'].'</h5>';
                                                echo '<h5>'.$searchResult['Phone'].'</h5>';
                                            echo '</div></div></div></div></div></a>';

                        }
                    else if($_GET['type'] == 'food')
                    {
                        foreach($searchResults as $searchResult)
                        {
                            echo '<a href = "ViewItem.php?id='.$searchResult['Item_ID'].'" style="text-decoration: inherit; text-shadow: -1px 1px #000; color: white; cursor: auto;">';
                            echo '<div class="row border border-white rounded m-2" style="background-image: radial-gradient(ellipse at center, #e75480,#f71a08);">';
                            echo '<div class="media mx-3 " style="padding-top: 15px; padding-bottom: 15px;">';
                                /*Adjust image source*/
                                $pic=getRecentReviewPictures($searchResult['Item_ID']);
                                echo '<div class="media-body">';
                                    echo '<div class="row">';
                                        echo '<div class="col">';
                                            echo '<h3>'.$searchResult['ItemName'].'</h3>';
                                            echo '<h3>'.getRestaurantName($searchResult['Restaurant_ID']).'</h3>';
                                            echo '<div class="star-ratings-sprite" style="float:left;">';
                                            echo '<span style="width:' . round(calculateItemStarRating($searchResult['Item_ID']),2 ) * 20 . '%' . ';" class="star-ratings-sprite-rating">';
                                            echo '</span></div>';
                                            echo '<br>';
                                            echo '<h5>'.implode(', ', extractNames(getCommonItemCategories($searchResult['Item_ID'], 3))).'</h5>';
                                        echo '</div></div></div></div></div></a>';
                        }
                    }
                }
            ?>
            <div class="text-center" style="margin-top:15px;">
                Didn't find what you're looking for? 
                <a href = "AddRestaurantReview.php" style="color:#ff3300">
                    Leave the first review
                </a>
            </div>
        </div>
    </div>
</body>
<footer style="bottom:0;  width:100%;">
  <br/>
  <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.9); color:#ff3300; font-size:16px">
    © 2021 Copyright:
    <a class="text-blue" href="AboutUs.php">About Us</a>
  </div>
  </footer>
</html>