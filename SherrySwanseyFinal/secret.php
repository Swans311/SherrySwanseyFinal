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
                    <h4 style="color:black; text-decoration:underline;">Our Story</h4>
                    <p>Dave's portion of this project was to build out the user's accounts and connectivity. From Restaurant Owners being able to respond to reviews, to being able to log in was Dave's objective. He was also the mastermind behind the MySQL database, as well as the search bar and the code involved. Style wise, there were many changes that Dave had changed, and many bug fixes as well. A lot of work that was done for improving UX and UI was implemented by Dave while Joe was doing other very important work.</p>
                    <p>Joe was an all-rounder and did a bit all over the place. I tried to focus effort on parts of project that were a bit more than we can chew and taken on by my insistence. I was solely responsible for all of the ajax, a lot of the php functions. Really all over the project and basically whatever excited me or I decided was better done myself I would pick up.</p>
                    <h4 style="text-decoration:underline; color:black;">Our Links:</h4>
                    <a href="pdfs/proposal.pdf" style="color:white;">Proposal Pdf</a>
                    <p> </p>
                    <a href="https://marvelapp.com/project/5331792" style="color:white;">Wireframe Prototypes (Sorry I don't want to pay for these)</a>
                    <p> </p>
                    <a href="pdfs/TechSpec.pdf" style="color:white;">Technical Design Pdf</a>
                    <p> </p>
                    <a href="pdfs/RelationalTables.pdf" style="color:white;">Relational Tables</a>
                    <p> </p>
                    <a href="pdfs/Screenshots.pdf" style="color:white;">Screenshots</a>
                    <p> </p>
                    <a href="pdfs/Presentation.pdf" style="color:white;">Presentation PowerPoint</a>
                    <p> </p>
                    <a href="misc/SherrySwanseyFinal.zip" style="color:white;">Zip File of Code</a>
                    <p>To properly run the site, the user must run the code from the zip file along with XAMPP (run XAMPP as admin for ease of use), with the Apache service running. Ensure that the tasks.json has the accurate location of your preffered web browser. In addition, ensuring that launch.json has the correct information available to connect with your preffered web browser will make running the program much easier. In the url make sure that the location referred to follows the correct pathways when running files. Otherwise the optimal way to run the code is through this link active on heroku.com </p>
                    <p></p>
                    <a href="https://github.com/Swans311/SherrySwanseyFinal" style="color:white;"> Github Link</a>
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