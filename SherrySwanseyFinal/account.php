<?php 
    include (__DIR__.'/NavBar.php');
    include (__DIR__.'/model/ModelReview.php');

    $resID=array();
    $resName=array();
    $stars=array();
    $txtreview=array();
    $resreview=array();
    $foodname=array();
    $foodstars=array();
    $itemreview=array();
    if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
        header('Location: Login.php');
        exit;
    }
    else{
        $userEmail=$_SESSION['email'];
        $upassword=sha1($_SESSION['upassword']);
        $uID=getUserID($userEmail);
        $userarray=getUserByID($uID);
        $reviewarray=getAllResReviewsByUser($uID);
        $amtOreviews=count($reviewarray);
        $revTable=getAllReviewsByUser($uID);
        $avgStar=calculateAvgStarRatingFromUser($uID);
        foreach($reviewarray as $reviewarray):{
            array_push($resID, $reviewarray['Restaurant_ID']);
            $resname=getRestaurantName($reviewarray['Restaurant_ID']);
            array_push($resName,$resname);
            array_push($resreview,$reviewarray['Review']);
            
        }
        endforeach;
        
        foreach($revTable as $revTable):{
            $itemname=getItemName($revTable["Item_ID"]);
            array_push($foodname, $itemname);
            array_push($foodstars,$revTable['Star_lvl']);
            array_push($txtreview, $revTable['Review']);
        }
        endforeach;
    }


    /*
    { ["ResReview_ID"]=> int(4) ["Restaurant_ID"]=> int(1) ["User_ID"]=> int(1) ["Review"]=> string(212) "" 
    ["Star_lvl"]=> int(1) ["UserName"]=> string(8) "" ["ReviewDate"]=> string(19) "" 
    ["Visible"]=> int(0) ["Category"]=> string(6) "" } }
    */


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
                <h1 class="display-4"style="font-family: titleFont;"><?=$userarray['Username'];?></h1>
                <h1 class="display-4"><?=$userarray['FName'];?></h1>
                <h1 class="display-5"><?=$userarray['Username'];?></h1>
                <h1 class="display-5">Number of Reviews: <?=$amtOreviews;?></h1>
                <h1 class="display-5">Average Star Reviews: <?php echo number_format((float)$avgStar, 2, '.', '');?></h1>
            </div>
            <?php $loopcount=0;
            foreach($resID as $r):?>
                
            <div class="row border border-white rounded m-2" style="background-image: radial-gradient(ellipse at center, #448a9a,#e1b10f66)">
                <div class="media mx-3" style="padding-top: 15px; padding-bottom: 15px;">
                    <!-- Adjust image source-->
                    <div class="media-body">
                        <div>
                            <!-- Adjust data-->
                            <h3>Restaurant's Name: <?=$resName[$loopcount]?></h3>
                            <h3>Stars: <?=$reviewarray['Star_lvl'];?></h3>
                            <p style="min-height: 110px"><?=$resreview[$loopcount];?></p>

                        </div>
                        <!--B Loop Start (Each food per restaurant)-->
                        <hr style="width:100%!important; border-top:2px solid white;"/>
                        <div class="media my-3">
                            <!-- Adjust image source-->
                            <div class="media-body">
                                <div>
                                    <!-- Adjust data-->
                                    <h3>Food Name: <?=$foodname[$loopcount];?></h3>
                                    <h3>Stars <?=$foodstars[$loopcount];?></h3>
                                    <p><?=$txtreview[$loopcount];?> </p>
                                </div>
                            </div>
                        </div>
                        
                        <!--B Loop end -->
                    </div>
                </div>
            </div>
            <?php $loopcount++;
            endforeach;?>
        </div>
    </div>
</body>
</html>