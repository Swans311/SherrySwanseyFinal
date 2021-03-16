<?php 
    include (__DIR__.'/NavBar.php');

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
    <title>Gourmandize | About Us</title>
</head>
<body>
    <div class="container gz-div-glow">
        <div class="container gz-div-inner mx-auto text-left p-5 text-white" style="font-family: textFont;">
            <div class="media mr-auto mb-5">
                <div>
                    <h4 style="text-decoration:underline;">Our Links</h4>
                    <p>Proposal: </p>
                    <p>Prototype: </p>
                    <p>Technical Design: </p>
                    <p>Relationship Design: </p>
                    <p>Screenshots: </p>
                    <p>PowerPoint Presentation: </p>
                    <p>Zip File of Source Code: </p>
                    <p>Github: </p>
                </div>
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