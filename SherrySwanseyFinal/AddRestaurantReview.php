<?php 
    include (__DIR__.'/NavBar.php');
    include (__DIR__.'/model/ModelReview.php');

    if(isset($_GET['itemID']))
    {
        $item = getItemByID($_GET['itemID']);
        $restaurant = getRestaurantByID($item['Restaurant_ID']);
    }
    if(isset($_GET['RestaurantID']))
    {
        $restaurant = getRestaurantByID($_GET['RestaurantID']);
    }
    
    $numFoodReviews = isset($_POST['hidden']) ? $_POST['hidden'] : 1;
    if(!isset($_POST))
        $_SESSION['numFoodReviews'] = 1;
    else
        $_SESSION['numFoodReviews'] = $numFoodReviews;
    if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) 
    {
        header('Location: Login.php');
        exit;
    }

    //Submit the info and try to add the review
    if(isset($_POST['submit']))
    {
        $lastchars = [];
        foreach($_POST as $key => $value)
        {
            //filter out empty values
            if($value != '')
            {
                //Take last char of all post values
                $lastchar = substr($key, -1);
                //Only ones ending in a number
                if(is_numeric($lastchar))
                    array_push($lastchars, $lastchar);
            }
        }
        $numReviewAreas = max($lastchars);
        $flag = true;
        $twoDimArray = array();
        if(isset($_POST['restaurantName']) && isset($_POST['restaurantAddress']) && isset($_POST['restaurantPhone']) && isset($_POST['restaurantURL']))
        {
            $resID = searchOneRestaurantID($_POST['restaurantName'], $_POST['restaurantAddress'], $_POST['restaurantPhone'], $_POST['restaurantURL']);
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
            if($flag) 
            {
                for($i = 1; $i <= $numReviewAreas; $i++)
                {
                    $singleItemArray = array();

                    if(isset($_POST['food' . $i]) && $_POST['food' . $i] != "")
                    {
                        $itemID = searchOneItemId($resID, $_POST['food' . $i]);
                        if($itemID == false)//if restaurantID wasnt found add restaurant
                        {
                            addItem($resID, $_POST['food' . $i]);
                            //Should be gaurunteed to exist now
                            $itemID = searchOneItemID($resID, $_POST['food' . $i]);
                        }
                        $singleItemArray['itemID'] = $itemID;
                    }
                    else     
                        $flag = false;

                    if(isset($_POST['foodCategories'.$i]) && $_POST['foodCategories'.$i] != "")
                    {
                        $singleItemArray['category'] = $_POST['foodCategories'.$i];
                    }
                    else     
                        $flag = false;
                        
                    if(isset($_POST['foodRating'.$i]) && $_POST['foodRating'.$i] != "")
                        $singleItemArray['rating'] = $_POST['foodRating'.$i];
                    else     
                        $flag = false;
                                                
                    if(isset($_POST['foodReview'.$i]) && $_POST['foodReview'.$i] != "")
                        $singleItemArray['review'] = $_POST['foodReview'.$i];
                    else     
                        $flag = false;
                    //TODO:: Code for sending image location
                    array_push($twoDimArray, $singleItemArray);
                }
                if($flag){
                    addRestaurantReview($resID, $uID, $resReviewParams['resReview'],  $resReviewParams['resRating'], $resReviewParams['resVisible'], "", $twoDimArray, $resReviewParams['categories']);
                    header('Location: homepage.php');
                }
            } 
            //add item reviews to array

        }
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
    <title>Gourmandize | Add Review</title>
    <script>
    var hiddenInput = document.getElementById("hidden")
    function addFoodReviewArea()
    {
        document.getElementById("hidden").value++;
    }
    function removeFoodReviewArea()
    {
        document.getElementById("hidden").value--;
    }
    function regenerateFoodReviewAreas()
    {
        var div = document.getElementById("inputs");
        
        for(let x = 2; x <= <?=$_SESSION['numFoodReviews']?> ; x++)
        {
            console.log(x)
            div.innerHTML += `<div class="row border border-outline-white rounded m-2 p-2">`
            + `<div class="row mx-2 p-1">`
                + `<div class="col form-group">`
                    + `<h2 class="display-5 mb-5">Food Item Review</h2>`
                    + `<label for="exampleFormControlFile`+ x +`">Upload Image Here: (Non-Funtional)</label>`
                    + `<input type="file" class="form-control-file" id="exampleFormControlFile`+ x +`" disabled>`
                + `</div>`
                + `<div class="col">`
                    + `<div class="form-group mt-4">`
                        + `<input size="25"type="text" name="food`+x+`" id="food`+x+`" placeholder="Food" />`
                    + `</div>`
                    + `<div class="form-group">`
                        + `<input size="25"type="text" name="foodCategories`+x+`" id="foodCategories`+x+`" placeholder="Categories" />`
                    + `</div>`
                    + `<div class="form-group">`
                        + `<label for="foodRating`+x+`">Star Rating:</label>`
                        + `<input type="number" name="foodRating`+x+`" id="foodRating`+x+`" min="0" max="5" step="1" />`
                    + `</div>`
                + `</div>`
                + `<div class="col form-group">`
                    + `<div class="d-flex justify-content-end mb-2">`
                    + `<h2 class="text-white mr-auto" style="font-family: textFont">Review</h2>`
                + `</div>`
                + `<textarea class="form-control" name="foodReview`+x+`" rows="4"></textarea>`
                + `</div></div></div>`;
        }
    }
    window.addEventListener('load', regenerateFoodReviewAreas);
</script>
</head>
<body>
    <div class="container gz-div-glow">
        <div class="container gz-div-inner mx-auto text-left pt-4 pb-2 text-white" style="font-family: textFont;">
            <form method="post">
                <div id="inputs">
                    <div class="row border border-outline-white rounded mx-2 p-2">
                        <div class="row mx-2 p-1">
                            <div class="col form-group">
                                <h2 class="display-5 mb-5">Restaurant Review</h2>
                                <label for="exampleFormControlFile1">Upload Image Here: (Non-Funtional)</label>
                                <!--TODO:: Make this stick around after a POST-->
                                <input type="file" class="form-control-file" name="restaurantImage" id="exampleFormControlFile1" disabled>
                                <div class="form-check mt-5">
                                    <input class="form-check-input" name="reviewAnonymous" type="checkbox" value="" id="reviewAnonymous" <?=isset($_POST['reviewAnonymous'])? "checked" : "" ?>>
                                    <label class="form-check-label" for="reviewAnonymous"> Review Anonymously</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input size="25"type="text" name="restaurantName" id="restaurantName" placeholder="Restaurant Name" value="<?=$restaurant['Restaurant_Name']?>" disabled/>
                                </div>
                                <div class="form-group">
                                    <input size="25"type="text" name="restaurantAddress" id="restaurantAddress" placeholder="Address" value="<?=$restaurant['ResAddress']?>" disabled/>
                                </div>
                                <div class="form-group">
                                    <input size="25"type="text" name="restaurantPhone" id="restaurantPhone" placeholder="Phone" value="<?=$restaurant['Phone']?>" disabled/>
                                </div>
                                <div class="form-group">
                                    <input size="25"type="text" name="restaurantURL" placeholder="URL" value="<?=$restaurant['Restaurant_URL']?>" disabled/>
                                </div>
                                <div class="form-group">
                                    <input size="25"type="text" name="restaurantCategories" id="restaurantCategories" placeholder="EX: Fast Food, Burger, Fried"  value="<?=isset($_POST['restaurantCategories'])? $_POST['restaurantCategories']: '' ?>"/>
                                </div>
                                <div class="form-group">
                                    <label for="restaurantRating">Star Rating:</label>
                                    <input type="number" name="restaurantRating" id="restaurantRating" min="0" max="5" step="1" value="<?=isset($_POST['restaurantRating'])? $_POST['restaurantRating']: '' ?>"/>
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
                                <label for="exampleFormControlFile1">Upload Image Here: (Non-Funtional)</label>
                                <input type="file" class="form-control-file" id="exampleFormControlFile1" disabled>
                                
                            </div>
                            <div class="col">
                                <div class="form-group mt-4">
                                    <input size="25"type="text" name="food1" id="food1" placeholder="Food" value="<?=isset($_POST['food1'])? $_POST['food1']: (isset($item) ? $item['ItemName'] : '') ?>"/>
                                </div>
                                <div class="form-group">
                                    <input size="25"type="text" name="foodCategories1" id="foodCategories1" placeholder="Categories"  value="<?=isset($_POST['foodCategories1'])? $_POST['foodCategories1']: '' ?>"/>
                                </div>
                                <div class="form-group">
                                    <label for="foodRating1">Star Rating:</label>
                                    <input type="number" name="foodRating1" id="foodRating1" min="0" max="5" step="1" value="<?=isset($_POST['foodRating1'])? $_POST['foodRating1']: '' ?>"/>
                                </div>
                            </div>
                            <div class="col form-group">
                                <div class="d-flex justify-content-end mb-2">
                                    <h2 class="text-white mr-auto" style="font-family: textFont">Review</h2>
                                    <!--<button id="removeFoodButton" class="btn btn-outline-danger text-white border-white"type="submit">Remove Food Item</button>-->
                                </div>
                                <textarea class="form-control" name="foodReview1" rows="4"><?= isset($_POST['foodReview1'])? $_POST['foodReview1']: ''?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group m-3 d-flex justify-content-end">
                    <input name="hidden" id="hidden" type="number" min="1" step="1" value="<?= isset($_POST['hidden']) && $_POST['hidden'] >= 1 ? $_POST['hidden']: '1'?>" hidden>
                    <button id="addFoodButton" class="btn btn-outline-success mx-3 text-white border-white"type="submit" onclick = addFoodReviewArea()>Add Food Item</button>
                    <button id="removeFoodButton" class="btn btn-outline-danger text-white border-white"type="submit" onclick = removeFoodReviewArea()>Remove Food Item</button>
                    <button id="submitButton" class="btn btn-outline-light mx-3" name="submit" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</body>
<footer>
  <br/>
  <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.4); color:red; font-size:16px">
    © 2021 Copyright:
    <a class="text-blue" href="AboutUs.php">About Us</a>
  </div>
  </footer>
</html>