<?php 
    include (__DIR__.'/NavBar.php');
    
    include (__DIR__. '/model/ModelReview.php');
    $result='';
    
    if(isset($_GET['Totalsearch'])){
      $src=filter_input(INPUT_GET,'Totalsearch');
      $src= $_GET['Totalsearch'];
      header("Location: SearchResults.php?Totalsearch=".$src."");
    }

    $userID = getUserID($_SESSION['email']);
    $threadIDs = getAllMessageThreadsForUser($userID);
    $threadLastMessages = array();

    if(!empty($threadIDs))
        foreach($threadIDs as $threadID)
        {
            array_push($threadLastMessages, getNewMessageInThread($threadID['Thread_ID'])[0]);
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
                    <a class="btn btn-outline-light" href="Chat.php" style="margin-left:-5px;width:160px;">Draft Message</a>
                </div>
            </div>
            <?php
            if(!empty($threadLastMessages))
            {
                foreach($threadLastMessages as $threadLastMessage)
                {
                    echo '<a href = "Chat.php?ThreadID=' . $threadLastMessage['Thread_ID'] . '" style="text-decoration: inherit;color: inherit; cursor: auto;">';
                        echo '<div class="row border border-white rounded m-2" style="background-image: radial-gradient(ellipse at center, #e75480,#f71a08)">';
                            echo '<div class="media mx-3" style="padding-top: 15px; padding-bottom: 15px;width:100%;">';
                                    echo '<div class="col-md-3">';
                                        echo '<p class="text-left">';
                                            echo 'Topic <br>';
                                            echo '<hr style="width:100%!important; border-top:2px solid white;"/>';
                                            echo '&emsp;' . $threadLastMessage['Topic'] . '<br>';
                                            /* remove or get origin message echo '&emsp; Date Opened';*/
                                        echo '</p>';
                                    echo '</div>';
                                    echo '<div id="row" style="width:100%;">';
                                        echo '<div class="col-md-12" style="margin-top:35px;">';
                                            echo '<p>';
                                                echo $threadLastMessage['Message'];
                                            echo '</p>';
                                        echo '</div>';
                                    echo '</div>';
                                    echo '<div id="row">';
                                        echo '<div class="col-md-15" style="margin-top:35px;">';
                                            echo '<p>';
                                                echo $threadLastMessage['TimeSent'];
                                            echo '</p>';
                                        echo '</div>';
                                    echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    echo '</a>';
                }
            }
            ?>
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
