<?php 
    include (__DIR__.'/NavBar.php');
    
    include (__DIR__.'/model/ModelReview.php');

    $reviewarray=[];

    if(isset($_GET['rid']))
    {
        $reviewarray = findReview($_GET['rid']);
    }
    $uID=getUserID($_SESSION['email']);

    $restaurantInfo = getRestaurantByID($_GET['id']);
    $restaurantReviews = getMostRecentReviewsByRestaurant($_GET['id'], 3);

    if($uID == $restaurantInfo['ResOwnerId']){
    }
    else{
        header("Location: SearchResults.php");
    }
    
    
    if (!isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn']) 
    {
        header('Location: Login.php');
        exit;
    }

    //Submit the info and try to add the review
    if(isset($_POST['submit']))
    {
        if(isset($_POST['response']) && $_POST['response'] != ""){
            $response = $_POST['response'];
            addResponse($response, $reviewarray[0]['ResReview_ID']);
            header("Location: ViewRestaurant.php?id=".$reviewarray[0]['Restaurant_ID']."");

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

</head>
<body>
    <div class="container gz-div-glow">
        <div class="container gz-div-inner mx-auto text-left pt-4 pb-2 text-white" style="font-family: textFont;">
            <form method="post" enctype="multipart/form-data">
                <div id="inputs">
                    <div class="row border border-outline-white rounded mx-2 p-2">
                        <div class="row mx-2 p-1">
                            <div class="col form-group">
                                <h4 class="display-5 mb-5">Restaurant Review:</h4>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <p><?php echo $reviewarray[0]['Review']; ?></p>
                                    <?php 
                                    if ($reviewarray[0]['Visible']==false)
                                    {
                                        echo '<p>- Anonymous</p>';
                                    }
                                    else
                                    {
                                      echo '<p>- '.$reviewarray[0]['UserName'].'</p>';  
                                    }
                                    ?>
                                    
                                </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="row border border-outline-white rounded m-2 p-2">
                        <div class="col form-group" >
                            <h2 class="text-white mr-auto" style="font-family: textFont">Response:</h2>
                            <textarea name="response" class="form-control" rows="6" placeholder="Type Response Here..."></textarea>
                        </div>
                    </div>
                    <div class="form-group m-3 d-flex justify-content-end">
                    <button id="submitButton" class="btn btn-outline-light mx-3" name="submit" type="submit">Submit</button>
                </div>
                </div>
                
            </form>
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


