<?php 
    include (__DIR__.'/NavBar.php');
    include (__DIR__. '/model/ModelReview.php');
    $result='';
    
    if(isset($_GET['Totalsearch'])){
      $src=filter_input(INPUT_GET,'Totalsearch');
      $src= $_GET['Totalsearch'];
      header("Location: SearchResults.php?Totalsearch=".$src."");
    }
    $threadID = 0;
    if(isset($_GET['ThreadID']))
    {
        $threadID = $_GET['ThreadID'];
        $thread = getAllMessagesInThread($threadID);
    }
    $userID = getUserID($_SESSION['email']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gourmandize | Chat</title>
</head>
<body>
    <div class="container gz-div-glow">
        <div class="container gz-div-inner mx-auto text-center py-5 text-white" style="font-family: textFont;">
            <div class="row border border-white rounded m-2" style="background-image: radial-gradient(ellipse at center, #e75480,#f71a08)">
                <?php
                    if($threadID != 0)
                    {
                        foreach($thread as $message)
                        {
                            if($message['Sender_ID'] == $userID)
                            {
                                echo '<div class="text-right" style="font-family: textFont; width:100%;margin-right:50px; margin-top:20px; color:black">';
                                    echo '<div style="background-color:white;border-radius:8px;border:3px inset silver; text-align:left;width:50%;float:right;padding:10px;">';
                                        echo 'Message';
                                        echo '<div style="font-size:10px; marigin-left:10px;">';
                                            echo '-Sent by user at 2:35PM 2/20/2021';
                                        echo '</div>';
                                    echo '</div>';
                                echo '</div>';
                            }
                            else
                            {
                                echo '<div class="text-left" style="font-family: textFont; width:100%;margin-left:50px; margin-top:20px; color:black">';
                                    echo '<div style="background-color:white;border-radius:8px;border:3px inset silver; text-align:left;width:50%;padding:10px;">';
                                        echo 'Response';
                                        echo '<div style="font-size:10px; marigin-left:10px;">';
                                            echo '-Sent by admin at 2:55PM 2/20/2021';
                                        echo '</div>';
                                    echo '</div>';
                                echo '</div>';
                            }
                        }
                    }
                ?>
                <form method="post" style="width:90%;">
                    <textarea name="message" style="padding:5px; width:100%; margin:40px;background-color:white; border-radius:15px; border:3px inset silver;" rows="5" cols="100" text=""></textarea>
                    <input style="margin-bottom:20px;margin-top:-20px;float:right;" type="submit" text="send">
                </form>
            </div>
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
