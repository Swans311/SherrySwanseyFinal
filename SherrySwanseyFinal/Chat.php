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
        $lastMessage = end($thread);
    }
    $adminIDs = getAllAdminIDs();
    $userID = getUserID($_SESSION['email']);
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gourmandize | Chat</title>
</head>
<script>
    if(<?= $threadID?> != 0)
        var lastMessage = <?= isset($lastMessage) ? json_encode($lastMessage) : json_encode(array()) ?>;
    console.log(lastMessage);

    async function sendMessage(event)
    {
        //Same for either case
        var senderID = <?php echo $userID ?>;
        var message = document.querySelector("#message").value;

        if(<?= $threadID?> == 0)
        {
            var adminID = <?= json_encode($adminIDs[array_rand($adminIDs, 1)])?>;

            var recipientID = adminID['User_ID'];
            var respondingToID =  null;
            var topic = document.querySelector("#topic").value;
            var threadID = null;
        }
        else
        {
            var recipientID = lastMessage['Sender_ID'] == <?= $userID ?> ? lastMessage['Recipient_ID'] : lastMessage['Sender_ID'] ;
            console.log(recipientID);
            var respondingToID = lastMessage['Message_ID'];
            var topic = lastMessage['Topic'];
            var threadID = lastMessage['Thread_ID'];
        }
        const url = 'ChatResponse.php';
        const data = {
            ThreadID : threadID,
            RespondingToID : respondingToID,
            SenderID : senderID,
            RecipientID : recipientID,
            Message : message,
            Topic : topic
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
        if (flag && <?= $threadID?> == 0)
        {
            document.location.href = "Inbox.php";
        }
        else if(flag)
        {
            document.querySelector("#message").value = "";
            //getNewMessage();
        }
    }
    async function getNewMessage()
    {
        //get newest message/message responding to latests message
        const url = 'GetRecentMessageInChain.php';
        const data = {
            MessageID : lastMessage['Message_ID']
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
            var responseDecoded = JSON.parse(json);
            //compare to latest message
            //if different
            var isDifferent = false;
            if(responseDecoded['Message_ID'] != lastMessage['Message_ID'] && responseDecoded.length != 0)
            {
                lastMessage = responseDecoded[0];
                isDifferent = true;
            }
        } catch (error) {
            console.error (error);
            flag = false;
        }
        console.log(lastMessage['SenderUsername']);
        if(isDifferent)
        {
            var chatContainer = document.querySelector("#convoContainer");
            //update latest message
            //javascript update the chat
            if(lastMessage['Sender_ID'] == <?=$userID?>)
            {
                chatContainer.innerHTML += '<div class="text-right" style="font-family: textFont; width:100%;margin-right:50px; margin-top:20px; color:black">'
                    + '<div style="background-color:white;border-radius:8px;border:3px inset silver; text-align:left;width:50%;float:right;padding:10px;">'
                        + lastMessage['Message']
                        + '<div style="font-size:10px; marigin-left:10px;">'
                            + '-Sent by ' + lastMessage['SenderUsername'] + ' at ' + lastMessage['TimeSent']
                        + '</div>'
                    + '</div>'
                + '</div>';
            }
            else
            {
                chatContainer.innerHTML += '<div class="text-left" style="font-family: textFont; width:100%;margin-left:50px; margin-top:20px; color:black">'
                    + '<div style="background-color:white;border-radius:8px;border:3px inset silver; text-align:left;width:50%;padding:10px;">'
                        + lastMessage['Message']
                        + '<div style="font-size:10px; marigin-left:10px;">'
                            + '-Sent by ' + lastMessage['SenderUsername'] + ' at ' + lastMessage['TimeSent']
                        + '</div>'
                    + '</div>'
                + '</div>';
            }
        }
    }
    //Every 10s getNewMessage if lastMessage set
    if(<?= isset($_GET['ThreadID']) ? 1 : 0?>)
        window.setInterval(function(){
            getNewMessage();
        }, 3000);
</script>
<body>
    <div class="container gz-div-glow">
        <div class="container gz-div-inner mx-auto text-center py-5 text-white" style="font-family: textFont;">
            <div id="convoContainer" class="row border border-white rounded m-2" style="background-image: radial-gradient(ellipse at center, #e75480,#f71a08); padding:20px;" <?=$threadID == 0 ? "hidden" : ""?>>
                <?php
                    if($threadID != 0)
                    {
                        foreach($thread as $message)
                        {
                            if($message['Sender_ID'] == $userID)
                            {
                                echo '<div class="text-right" style="font-family: textFont; width:100%;margin-right:50px; margin-top:20px; color:black">';
                                    echo '<div style="background-color:white;border-radius:8px;border:3px inset silver; text-align:left;width:50%;float:right;padding:10px;">';
                                        echo $message['Message'];
                                        echo '<div style="font-size:10px; marigin-left:10px;">';
                                            echo '-Sent by ' . getUsername($message['Sender_ID']) . ' at ' . $message['TimeSent'];
                                        echo '</div>';
                                    echo '</div>';
                                echo '</div>';
                            }
                            else
                            {
                                echo '<div class="text-left" style="font-family: textFont; width:100%;margin-left:50px; margin-top:20px; color:black">';
                                    echo '<div style="background-color:white;border-radius:8px;border:3px inset silver; text-align:left;width:50%;padding:10px;">';
                                        echo $message['Message'];
                                        echo '<div style="font-size:10px; marigin-left:10px;">';
                                            echo '-Sent by ' . getUsername($message['Sender_ID']) . ' at ' . $message['TimeSent'];
                                        echo '</div>';
                                    echo '</div>';
                                echo '</div>';
                            }
                        }
                    }
                ?>
            </div>
            <form method="post" style="width:90%;">
                    <?php
                        if($threadID == 0)
                            {
                                echo '<h3 style="float:left;margin-top:25px;margin-bottom:-25px;margin-left:40px;">Topic</h3>';
                                echo '<input type="text" id="topic" style="padding:5px; width:100%; margin:40px;background-color:white; border-radius:15px; border:3px inset silver;">';
                                echo '<h3 style="float:left;margin-top:25px;margin-bottom:-25px;margin-left:40px;">Message</h3>';
                            }
                    ?>
                    <textarea name="message" id="message" style="padding:5px; width:100%; margin:40px;background-color:white; border-radius:15px; border:3px inset silver;" rows="5" cols="100" text=""></textarea>
                    <input style="margin-bottom:20px;margin-top:-20px;float:right;width:100px;" type="button" value="Send" onclick="sendMessage()">
                </form>
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
