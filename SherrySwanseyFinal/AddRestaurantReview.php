<?php 
    include (__DIR__.'/NavBar.php');
    
    include (__DIR__.'/model/ModelReview.php');

    //Sets Restaurant ID (stays php)
    if(isset($_GET['itemID']))
    {
        $item = getItemByID($_GET['itemID']);
        $restaurant = getRestaurantByID($item['Restaurant_ID']);
    }
    if(isset($_GET['RestaurantID']))
    {
        $restaurant = getRestaurantByID($_GET['RestaurantID']);
    }
    
    //Hidden Number counting foods in this review (wont be needed)
    $numFoodReviews = isset($_POST['hidden']) ? $_POST['hidden'] : 1;
    if(!isset($_POST))
        $_SESSION['numFoodReviews'] = 1;
    else
        $_SESSION['numFoodReviews'] = $numFoodReviews;
    //Make sure user logged in
    
    if (!isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn']) 
    {
        header('Location: Login.php');
        exit;
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
        var itemNums = [1];
        function addFoodReviewArea(event)
        {
            var x = Math.max.apply(Math,itemNums) + 1;
            itemNums.push(x);
            div = document.getElementById("inputs");
            //`<div class="row border border-outline-white rounded m-2 p-2" id="item`+x+`">`
            var container = document.createElement("div");
            container.setAttribute("class", "row border border-outline-white rounded m-2 p-2");
            container.setAttribute("id", "item"+x);
                //`<div class="row mx-2 p-1">`
                var subContainer = document.createElement("div");
                subContainer.setAttribute("class", "row mx-2 p-1");
                    //`<div class="col form-group">`
                    var formGroup = document.createElement("div");
                    formGroup.setAttribute("class", "col form-group");
                        //`<h2 class="display-5 mb-5">Food Item Review</h2>`
                        var title = document.createElement("h2");
                        title.setAttribute("class", "display-5 mb-5");
                        var titleNode = document.createTextNode("Food Item Review");
                        title.append(titleNode);
                        //`<label for="exampleFormControlFile`+ x +`">Upload Image Here: </label>`
                        var imageUploadLabel = document.createElement("label");
                        imageUploadLabel.setAttribute("for","exampleFormControlFile" + x);
                        var imageUploadNode = document.createTextNode("Upload Image Here:");
                        imageUploadLabel.append(imageUploadNode);
                        //`<input type="file" class="form-control-file" id="foodPic`+ x +`" name="foodPic`+ x +`" accept="*/image">`
                        var imageInput = document.createElement("input");
                        imageInput.setAttribute("type","file");
                        imageInput.setAttribute("class","form-control-file");
                        imageInput.setAttribute("id","foodPic"+x);
                        imageInput.setAttribute("accept","*/image");
                    //`</div>`
                    formGroup.append(title);
                    formGroup.append(imageUploadLabel);
                    formGroup.append(imageInput);
                    //`<div class="col">`
                    var col = document.createElement("div");
                    col.setAttribute("class", "col");
                        //`<div class="form-group mt-4">`
                        var secondFormGroup = document.createElement("div");
                        secondFormGroup.setAttribute("class", "form-group mt-4");
                            //`<input size="25"type="text" name="food`+x+`" id="food`+x+`" placeholder="Food" />`
                            var foodNameInput = document.createElement("input");
                            foodNameInput.setAttribute("size", "25");
                            foodNameInput.setAttribute("type", "text");
                            foodNameInput.setAttribute("name", "food" + x);
                            foodNameInput.setAttribute("id", "food" + x);
                            foodNameInput.setAttribute("placeholder", "Food" + x);
                        //+ `</div>`
                        secondFormGroup.append(foodNameInput);
                        //+ `<div class="form-group">`
                        var thirdFormGroup = document.createElement("div");
                        thirdFormGroup.setAttribute("class", "form-group");
                            //+ `<input size="25"type="text" name="foodCategories`+x+`" id="foodCategories`+x+`" placeholder="Categories" />`
                            var foodCat = document.createElement("input");
                            foodCat.setAttribute("size", "25");
                            foodCat.setAttribute("type", "text");
                            foodCat.setAttribute("name", "foodCategories" + x);
                            foodCat.setAttribute("id", "foodCategories" + x);
                            foodCat.setAttribute("placeholder", "Categories");
                        //+ `</div>`
                        thirdFormGroup.append(foodCat)
                        //+ `<div class="form-group">`
                        var fourthFormGroup = document.createElement("div");
                        fourthFormGroup.setAttribute("class", "form-group");
                            //+ `<label for="foodRating`+x+`">Star Rating:</label>`
                            var ratingLabel = document.createElement("label");
                            ratingLabel.setAttribute("for", "foodRating" + x);
                            ratingLabelText = document.createTextNode("Star Rating:");
                            ratingLabel.append(ratingLabelText);
                            //+ `<input type="number" name="foodRating`+x+`" id="foodRating`+x+`" min="0" max="5" step="1" />`
                            var ratingInput = document.createElement("input");
                            ratingInput.setAttribute("type", "number");
                            ratingInput.setAttribute("name", "foodRating" + x);
                            ratingInput.setAttribute("id", "foodRating" + x);
                            ratingInput.setAttribute("min", "0");
                            ratingInput.setAttribute("max", "5");
                            ratingInput.setAttribute("step", "1");
                        //+ `</div>`
                        fourthFormGroup.append(ratingLabel);
                        fourthFormGroup.append(ratingInput);
                    //+ `</div>`
                    col.append(secondFormGroup);
                    col.append(thirdFormGroup);
                    col.append(fourthFormGroup);
                    //+ `<div class="col form-group">`
                    var finalFormGroup = document.createElement("div");
                    finalFormGroup.setAttribute("class", "col form-group");
                        //+ `<div class="d-flex justify-content-end mb-2">`
                        var finalFormGroupInner = document.createElement("div");
                        finalFormGroupInner.setAttribute("class", "d-flex justify-content-end mb-2");
                            //+ `<h2 class="text-white mr-auto" style="font-family: textFont">Review</h2>`
                            var finalFormGroupTitle = document.createElement("h2");
                            finalFormGroupTitle.setAttribute("class", "text-white mr-auto");
                            finalFormGroupTitle.setAttribute("style", "font-family: textFont");
                            var finalFormGroupTitleText = document.createTextNode("Review");
                            finalFormGroupTitle.append(finalFormGroupTitleText);
                            //+`<button type="button" id="removeFoodButton" style="width:50%;" class="btn btn-outline-danger text-white border-white"  onclick = "removeFoodReviewArea(`+ x +`)">Remove</button>`
                            var removeFood = document.createElement("button");
                            removeFood.setAttribute("type", "button");
                            removeFood.setAttribute("id", "removeFoodButton");
                            removeFood.setAttribute("style", "width:50%;");
                            removeFood.setAttribute("class", "btn btn-outline-danger text-white border-white");
                            removeFood.setAttribute("onclick", "removeFoodReviewArea(" + x + ")");
                            removeFoodText = document.createTextNode("Remove");
                            removeFood.append(removeFoodText);
                        //+ `</div>`
                        finalFormGroupInner.append(finalFormGroupTitle);
                        finalFormGroupInner.append(removeFood);
                    //+ `<textarea class="form-control" id="foodReview`+x+`"name="foodReview`+x+`" rows="4"></textarea>`
                    var foodReviewArea = document.createElement("textarea");
                    foodReviewArea.setAttribute("class", "form-control");
                    foodReviewArea.setAttribute("id", "foodReview" + x);
                    foodReviewArea.setAttribute("name", "foodReview" + x);
                    foodReviewArea.setAttribute("rows", "4");
                    //+ `</div>`;
                    finalFormGroup.append(finalFormGroupInner);
                    finalFormGroup.append(foodReviewArea);
                //+ `</div>`;
                subContainer.append(formGroup);
                subContainer.append(col);
                subContainer.append(finalFormGroup);
            //+ `</div>`;
            container.append(subContainer);
            div.append(container);
            //event.preventDefault();
            //event.stopImmediatePropegation();
        }
        function removeFoodReviewArea(n)
        {
            itemNums.splice(itemNums.indexOf(n), 1);
            var divToRemove = document.getElementById("item" + n);
            divToRemove.remove();
        }

        async function sendReviewToServer(event)
        {
            //Gather the info?
            gatheredData = gatherData();
            console.log(gatheredData);
            if (gatheredData != false)
            {
                const url = 'AddResReviewAJAX.php';
                const data = {
                    RestaurantName : gatheredData['restaurantName'],
                    RestaurantAddress : gatheredData['restaurantAddress'],
                    RestaurantPhone : gatheredData['restaurantPhone'],
                    RestaurantURL : gatheredData['restaurantURL'],
                    UserID : gatheredData['userID'],
                    RestaurantReview : gatheredData['resReview'],
                    Rating : gatheredData['resRating'],
                    Anonymous : gatheredData['anonymous'],
                    ImageFilePath : gatheredData['imageFilePath'],
                    ItemReview2DList : gatheredData['itemReview2DList'],
                    Categories : gatheredData['categories']
                };
                var flag = true;
                try {
                    const response = await fetch(url, {
                    method: 'POST',
                    body: JSON.stringify(data),
                    headers:{
                        'Content-Type': 'application/json'
                    }
                    });
                    const json = await response.json(); 
                } catch (error) {
                    console.error (error);
                    flag = false;
                }
                if(flag)
                {
                    //redirect
                    window.location.href = "ViewRestaurant.php?id="+response;
                }
            }
        }
        function gatherData()
        {
            //Image stuff (need to figure out JS equivalent and use for each item)
            /*
            $images=$_FILES['restaurant']['name'];
            $tmp_dir=$_FILES['restaurant']['tmp_name'];
            $imageSize=$_FILES['restaurant']['size'];
            $upload_dir='uploads';
            $imgExt=strtolower(pathinfo($images, PATHINFO_EXTENSION));
            $valid_extensions=array('jpeg', 'jpg', 'png', 'gif', 'pdf');
            $restaurantPic=rand(1000,1000000). ".".$imgExt;
            move_uploaded_file($tmp_dir, $upload_dir.$restaurantPic);
            */
                    //$stmt=$db->prepare("INSERT INTO rimages SET Img = :Img");
                    //$stmt->bindParam(":Img", $restaurantPic); 
            
            //Tracks all required fields full
            var flag = true;
            var feedback = "";
            //will be filled with item reviews
            var twoDimArray = [];
            //if all restaurant info fields filled
            if(document.getElementById('restaurantName').value != "" && document.getElementById('restaurantAddress').value !="" && document.getElementById('restaurantPhone').value != "" && document.getElementById('restaurantURL').value !="")
            {
                //put together restaurantreview except 2d array portion
                var resReviewParams = {};
                var uID = <?= getUserID($_SESSION['email'])?>;
                resReviewParams['userID'] = uID;

                resReviewParams['imageFilePath'] = "";

                resReviewParams['restaurantName'] = document.getElementById('restaurantName').value;
                resReviewParams['restaurantAddress'] = document.getElementById('restaurantAddress').value;
                resReviewParams['restaurantPhone'] = document.getElementById('restaurantPhone').value;
                resReviewParams['restaurantURL'] = document.getElementById('restaurantURL').value;

                if(document.getElementById('restaurantReview').value != "")
                    resReviewParams['resReview'] = document.getElementById('restaurantReview').value;
                else     
                    flag = false;

                if(document.getElementById('restaurantCategories').value != "")
                    resReviewParams['categories'] = document.getElementById('restaurantCategories').value;
                else     
                    flag = false;

                if(document.getElementById('restaurantRating').value != "")
                    resReviewParams['resRating'] = document.getElementById('restaurantRating').value;
                else     
                    flag = false;

                resReviewParams['anonymous'] = document.getElementById('reviewAnonymous').checked;

                //if all those vars were present continue
                if(flag) 
                {
                    //code for each item review
                    for(key in itemNums)
                    {
                        let i = itemNums[key];
                        var singleItemArray = {};
                        /*
                        //Image stuff TODO: ADAPT TO JS OR COMMENT OUT
                        $images=$_FILES['foodPic'.$i]['name'];
                        $tmp_dir=$_FILES['foodPic'.$i]['tmp_name'];
                        $imageSize=$_FILES['foodPic'.$i]['size'];
                        $upload_dir='uploads';
                        $imgExt=strtolower(pathinfo($images, PATHINFO_EXTENSION));
                        $valid_extensions=array('jpeg', 'jpg', 'png', 'gif', 'pdf');
                        $foodPic=rand(1000,1000000). ".".$imgExt;
                        move_uploaded_file($tmp_dir, $upload_dir.$foodPic);
                        $singleItemArray['imgFilePath']=$foodPic;
                        */

                        singleItemArray['imageFilePath'] = "";

                        //Food fields
                        console.log(i);
                        console.log('food' + i);
                        if(document.getElementById('food' + i).value != "")
                        {
                            singleItemArray['itemName'] = document.getElementById('food' + i).value;
                        }
                        else     
                            flag = false;

                        if(document.getElementById('foodCategories' + i) != "")
                            singleItemArray['category'] = document.getElementById('foodCategories' + i).value;
                        else     
                            flag = false;
                            
                        if(document.getElementById('foodRating' + i) != "")
                            singleItemArray['rating'] = document.getElementById('foodRating' + i).value;
                        else     
                            flag = false;
                                                    
                        if(document.getElementById('foodReview' + i) != "")
                            singleItemArray['review'] = document.getElementById('foodReview' + i).value;
                        else     
                            flag = false;
                        //TODO:: Code for sending image location
                        twoDimArray.push(singleItemArray);
                    }
                    if(flag)
                    {
                        resReviewParams['itemReview2DList'] = twoDimArray
                        return resReviewParams;
                        //addRestaurantReview($resID, $uID, $resReviewParams['resReview'],  $resReviewParams['resRating'], $resReviewParams['resVisible'], $restaurantPic, $twoDimArray, $resReviewParams['categories']);
                        //var_dump($resID, $uID, $resReviewParams['resReview'],  $resReviewParams['resRating'], $resReviewParams['resVisible'], $restaurantPic, $twoDimArray, $resReviewParams['categories']);
                        //header('Location: homepage.php');
                    }
                }
            }
            return false;
        }
    </script>
</head>
<body>
    <div class="container gz-div-glow">
        <div class="container gz-div-inner mx-auto text-left pt-4 pb-2 text-white" style="font-family: textFont;">
            <div id="inputs">
                <div class="row border border-outline-white rounded mx-2 p-2">
                    <div class="row mx-2 p-1">
                        <div class="col form-group">
                            <h2 class="display-5 mb-5">Restaurant Review</h2>
                            <label for="exampleFormControlFile1">Upload Image Here:</label>
                            <!--TODO:: Make this stick around after a POST-->
                            <input type="file" name="restaurant" class="form-control-file"  accept="*/image" >
                            <div class="form-check mt-5">
                                <input class="form-check-input" name="reviewAnonymous" type="checkbox" value="" id="reviewAnonymous" <?=isset($_POST['reviewAnonymous'])? "checked" : "" ?>>
                                <label class="form-check-label" for="reviewAnonymous"> Review Anonymously</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input size="25"type="text" name="restaurantName" id="restaurantName" placeholder="Restaurant Name" value="<?php if(isset($restaurant['Restaurant_Name'])){echo $restaurant['Restaurant_Name'];}else{echo "";}?>" <?php if(isset($restaurant['Restaurant_Name'])){echo "readonly";}else{}?>/>
                            </div>
                            <div class="form-group">
                                <input size="25"type="text" name="restaurantAddress" id="restaurantAddress" placeholder="Address" value="<?php if(isset($restaurant['ResAddress'])){echo $restaurant['ResAddress'];}else{echo "";}?>" <?php if(isset($restaurant['ResAddress'])){echo "readonly";}else{}?>/>
                            </div>
                            <div class="form-group">
                                <input size="25"type="text" name="restaurantPhone" id="restaurantPhone" placeholder="Phone" value="<?php if(isset($restaurant['Phone'])){echo $restaurant['Phone'];}else{echo "";}?>" <?php if(isset($restaurant['Phone'])){echo "readonly";}else{}?>/>
                            </div>
                            <div class="form-group">
                                <input size="25"type="text" name="restaurantURL" placeholder="URL" id = "restaurantURL" value="<?php if(isset($restaurant['Restaurant_URL'])){echo $restaurant['Restaurant_URL'];}else{echo "";}?>" <?php if(isset($restaurant['Restaurant_URL'])){echo "readonly";}else{}?>/>
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
                            <textarea name="restaurantReview" id="restaurantReview" class="form-control" rows="6"><?= isset($_POST['restaurantReview'])? $_POST['restaurantReview']: ''?></textarea>
                        </div>
                    </div>
                </div>
                <div class="row border border-outline-white rounded m-2 p-2" id="item1">
                    <div class="row mx-2 p-1">
                        <div class="col form-group">
                            <h2 class="display-5 mb-5">Food Item Review</h2>
                            <label for="exampleFormControlFile1">Upload Image Here: </label>
                            <input type="file" name="foodPic1" id="foodPic1" class="form-control-file"  accept="*/image" >
                            
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
                            </div>
                            <textarea class="form-control" id="foodReview1" name="foodReview1" rows="4"><?= isset($_POST['foodReview1'])? $_POST['foodReview1']: ''?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group m-3 d-flex justify-content-end">
                <input name="hidden" id="hidden" type="number" min="1" step="1" value="<?= isset($_POST['hidden']) && $_POST['hidden'] >= 1 ? $_POST['hidden']: '1'?>" hidden>
                <button type="button" id="addFoodButton" class="btn btn-outline-success mx-3 text-white border-white"  onclick = "addFoodReviewArea()">Add Food Item</button>
                <button id="submitButton" class="btn btn-outline-light mx-3" name="submit" type="button" onclick = "sendReviewToServer()" >Submit</button>
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