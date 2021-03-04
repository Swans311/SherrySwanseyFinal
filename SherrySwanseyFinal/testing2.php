<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gourmandize | Add Review</title>
    <script>
    



    //var hiddenInput = document.getElementById("hidden")
    function addFoodReviewArea()
    {
        var y = document.getElementById("hidden").value++;
        document.getElementById("hidden").value=y;
        return y;
    }
    //function removeFoodReviewArea()
    //{
        //document.getElementById("hidden").value--;
    //}



    /*function regenerateFoodReviewAreas()
    {
        var div = document.getElementById("inputs");

        for(let x = 2; x <= $_SESSION['numFoodReviews'] ; x++)
        {
            console.log(x)
            div.innerHTML += `<div class="row border border-outline-white rounded m-2 p-2" id="item`+x+`">`
            + `<div class="row mx-2 p-1">`
                + `<div class="col form-group">`
                    + `<h2 class="display-5 mb-5">Food Item Review</h2>`
                    + `<label for="exampleFormControlFile`+ x +`">Upload Image Here: </label>`
                    + `<input type="file" class="form-control-file" id="foodPic`+ x +`" name="foodPic`+ x +`" accept"/image">`
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
                    +`<button id="removeFoodButton" class="btn btn-outline-danger text-white border-white"  onclick = removeFoodReviewArea()>Remove Food Item</button>`
                + `</div>`
                + `<textarea class="form-control" name="foodReview`+x+`" rows="4"></textarea>`
                + `</div></div></div>`;
        }
    }
    window.addEventListener('load', regenerateFoodReviewAreas);*/
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
                                    <input size="25"type="text" name="restaurantName" id="restaurantName" placeholder="Restaurant Name" value="<?php if$restaurant['Restaurant_Name']?>" readonly/>
                                </div>
                                <div class="form-group">
                                    <input size="25"type="text" name="restaurantAddress" id="restaurantAddress" placeholder="Address" value="<?=$restaurant['ResAddress']?>" readonly/>
                                </div>
                                <div class="form-group">
                                    <input size="25"type="text" name="restaurantPhone" id="restaurantPhone" placeholder="Phone" value="<?=$restaurant['Phone']?>" readonly/>
                                </div>
                                <div class="form-group">
                                    <input size="25"type="text" name="restaurantURL" placeholder="URL" value="<?=$restaurant['Restaurant_URL']?>" readonly/>
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
                                    <!--<button id="removeFoodButton" class="btn btn-outline-danger text-white border-white"type="submit">Remove Food Item</button>-->
                                </div>
                                <textarea class="form-control" name="foodReview1" rows="4"><?= isset($_POST['foodReview1'])? $_POST['foodReview1']: ''?></textarea>
                            </div>
                        </div>
                    </div>
                    <div id="new">
                    </div>

                <div class="form-group m-3 d-flex justify-content-end">
                    <input name="hidden" id="hidden" type="number" min="1" step="1" value="<?= isset($_POST['hidden']) && $_POST['hidden'] >= 1 ? $_POST['hidden']: '1'?>" hidden>
                    <button onClick = 'addArea()'>Add Food Item</button>
                    <button id="submitButton" class="btn btn-outline-light mx-3" name="submit" type="submit">Submit</button>
                </div>
            
        </div>
    </div>
    <script>

    function addArea(){
        var z = addFoodReviewArea();
        console.log(z);
        for(let x = 2; x <=z ; x++)
        {
            document.getElementById("new").innerHTML += `<div class="row border border-outline-white rounded m-2 p-2" id="item`+x+`">`
            + `<div class="row mx-2 p-1">`
                + `<div class="col form-group">`
                    + `<h2 class="display-5 mb-5">Food Item Review</h2>`
                    + `<label for="exampleFormControlFile`+ x +`">Upload Image Here: </label>`
                    + `<input type="file" class="form-control-file" id="foodPic`+ x +`" name="foodPic`+ x +`" accept"/image">`
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
                    //+`<button id="removeFoodButton" class="btn btn-outline-danger text-white border-white"  onClick = removeArea(`+x+`)>Remove Food Item</button>`
                + `</div>`
                + `<textarea class="form-control" name="foodReview`+x+`" rows="4"></textarea>`
                + `</div></div></div>`;
        }
    }
/*fuction removeArea(x){
    var drop= document.getElementById("food"+x);
    drop.remove();
}*/



</script>
</body>
<footer>
  <br/>
  <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.9); color:#ff3300; font-size:16px">
    © 2021 Copyright:
    <a class="text-blue" href="AboutUs.php">About Us</a>
  </div>
  </footer>
</html>
