<?php 
    include (__DIR__.'/NavBar.php');
    include (__DIR__. '/model/ModelReview.php');
    $result='';
    
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
    <title>Gourmandize | Log In</title>
</head>
<body>
    <div class="container gz-div-glow">
        <div class="container gz-div-inner mx-auto text-center py-5 text-white" style="font-family: textFont;">
            <div class="media mr-auto mb-5">
                <div class="media-body ms-auto">
                    <a class="btn btn-dark" href="Inbox.php" style="width:160px;">Inbox</a>
                    <a class="btn btn-outline-light" href="SendMessage.php" style="margin-left:-5px;width:160px;">Draft Message</a>
                </div>
            </div>
            <a href = "Chat.php" style="text-decoration: inherit;color: inherit; cursor: auto;">
                <div class="row border border-white rounded m-2" style="background-image: radial-gradient(ellipse at center, #e75480,#f71a08)">
                    <div class="media mx-3" style="padding-top: 15px; padding-bottom: 15px;">
                        <!--Foreach loop of each message chain they are part of-->
                            <div class="col-md-3">
                                <p class="text-left">
                                    Topic <br>
                                    <hr style="width:100%!important; border-top:2px solid white;"/>
                                    &emsp; Status <br>
                                    &emsp; Date Opened
                                </p>
                            </div>
                            <div id="row">
                                <div class="col-md-12" style="margin-top:35px;">
                                    <p>
                                        Hello we need to see a deed or copy of the lease along with photo ID to approve you.
                                    </p>
                                </div>
                            </div>
                            <div id="row">
                                <div class="col-md-3" style="margin-top:35px;">
                                    <p>
                                        MM/DD/YYYY <br>
                                        HH:MM
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            <!--
            <a href = "Chat.php" style="text-decoration: inherit;color: inherit; cursor: auto;">
                <div class="row border border-white rounded m-2" style="background-image: radial-gradient(ellipse at center, #e75480,#f71a08)">
                    <div class="media mx-3" style="padding-top: 15px; padding-bottom: 15px;">
                            <div class="col-md-3">
                                <p class="text-left">
                                    Topic <br>
                                    <hr style="width:100%!important; border-top:2px solid white;"/>
                                    &emsp; Status <br>
                                    &emsp; Date Opened
                                </p>
                            </div>
                            <div id="row">
                                <div class="col-md-12" style="margin-top:35px;">
                                    <p>
                                        Hello we need to see a deed or copy of the lease along with photo ID to approve you.
                                    </p>
                                </div>
                            </div>
                            <div id="row">
                                <div class="col-md-3" style="margin-top:35px;">
                                    <p>
                                        MM/DD/YYYY <br>
                                        HH:MM
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            -->
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
