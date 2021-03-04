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
                <h1 class="glow text-white display-4 mr-3 align-self-center" style="font-family: logoFont;">Gourmandize</h1>
                <div class="media-body">
                    <p>Gourmandize or to eat good food, especially to excess. This is the main goal of our project, to create a site which allows people to not just find the best restaurant, but the best food. Stop ordering the best food a restaurant has to offer and instead order the food you want from the restaurant that offers the best version of it. Our site allows users to leave a review for any restaurant along with a review for each food they ate there. As our collection of reviews grows uses will be able to search for the food that they want and find the best place to get it. After all, "We all gourmandize from time to time" ~Tahm Kench 2015</p>
                    <a href="TechSpec.pdf" style="color:Purple">Tech Spec pdf </a>
                </div>
            </div>
            <hr style="width:100%!important; border-top:2px solid white;"/>
            <div class="row">
                <div class="col px-1 border-right border-white">
                    <div class="media mr-auto">
                        <div class="media-body">
                            <h3>Joseph Sherry</h3>
                            <ul>
                                <li><u><a class="text-white"href="mailto:jsherry10155@gmail.com">Email</a></u></li>
                                <li><u><a class="text-white" href="https://github.com/FieryXJoe">Github Profile</a></u></li>
                            </ul>
                        </div>
                        <img class="mr-3 align-self-top" style="height: auto; width: 28%;" src="misc\images\JoeSBio.jpg" alt="img">
                    </div>
                    <p>Primarily handled the PHP data access layer reponsible for 95% of it. I did a majority of the frontend PHP and JS including functionality and autogeneration for search, Item/Restaurant display pages, Home page categories, and Adding reviews. Finally communicated with my team to ensure that the backend SQL & frontend HTML had everything they should and nothing they shouldn't have to work with our functions and functionality we were hoping to achieve. Made sure the full stack could work together in theory and picked up work on all ends to make sure it did in reality</p>
                </div>
                <div class="col px-1 border-right border-white">
                    <div class="media mr-auto">
                        <div class="media-body">
                            <h3>David Swansey</h3>
                            <ul>
                                <li><u><a class="text-white"href="mailto:DSwansey1@gmail.com">Email</a></u></li>
                                <li><u><a class="text-white"href="https://github.com/Swans311">Github Profile</a></u></li>
                            </ul>
                        </div>
                        <img class="mr-3 align-self-top" style="height: auto; width: 28%;" src="misc\images\Swansey.jpg" alt="img">
                    </div>
                    <p>My part of this project was managing the SQL database and adjusting the tables to fit the data. As well as that, I also built and implemented the login/logout, Signup, and account viewing systems in PHP and JS. Along with Joe, we built the homepage and functional buttons on it as well. I was mainly responsible for a lot of frontend PHP and any/all SQL and the implentation of it. Also constructed a bit under half of the webpage designs using Casey's designs and stlye code as a reference</p>
                </div>
                <div class="col px-1">
                    <div class="media mr-auto">
                        <div class="media-body">
                            <h3>Casey Viens</h3>
                            <ul>
                                <li><u><a class="text-white"href="mailto:casey.viens@gmail.com">Email</a></u></li>
                                <li><u><a class="text-white"href="https://github.com/Casey-Viens">Github Profile</a></u></li>
                            </ul>
                        </div>
                        <img class="mr-3 align-self-top" style="height: auto; width: 28%;" src="misc\images\Casey_Bio.jpg" alt="img">
                    </div>
                    <p>My main role with the project was learning Bootstrap and using it to design our site around our theme. With food in mind, we wanted to design the site around a retro diner theme, with a checkered tiled background and glowing neon lights. As I completed styling each page I met with my group to make adjustments and make sure we all agreed on the style choices that were made. This was definitly and process, but I learned a lot from it. </p>
                </div>
            </div>
        </div>
    </div>
</body>
<footer style="bottom:0; position:fixed; width:100%;">
  <br/>
  <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.9); color:#ff3300; font-size:16px">
    © 2021 Copyright:
    <a class="text-blue" href="AboutUs.php">About Us</a>
  </div>
  </footer>
</html>