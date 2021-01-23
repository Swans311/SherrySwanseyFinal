<?php 
  session_start();

if (!isset($_SESSION['loggedin'])) {
  $loggedin=false;
} else {
  $loggedin=$_SESSION['loggedin'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap link-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="SherrySwanseyFinal/misc/css/Style.css">
</head>
<body class="gz-body-bg"> 
<nav class="navbar sticky-top navbar-expand-lg navbar-dark text-white shadow-lg p-3 mb-6 gz-nav-bg">
    <!-- Project Name/Logo-->
    <a class="navbar-brand glow" href="NavBar.php" style="font-family: logoFont; font-size: 30px;">Gourmandize</a>
      <!-- Nav links, pushed to left-->
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link gz-nav-link" href="homepage.php">Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link gz-nav-link" href="SearchResults.php">Search</a>
        </li>
      </ul>

    <!-- Nav links, pushed to right-->
    <div class="nav-item ml-auto">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link gz-nav-link" href="AboutUs.php">About Us</a>
        </li>
        <!-- Logic to switch between buttons depending on if the user is logged in-->
        <?php if($loggedin == False) :?>
          <li class="nav-item">
            <a class="nav-link gz-nav-link" href="Login.php">Log In</a>
          </li>
          <li class="nav-item">
            <a class="nav-link gz-nav-link" href="SignUp.php"><img src="misc/img/user.svg" alt="Profile Icon" style="height: 24px; weight:24px;">  Sign Up</a>
          </li>
        <?php else:?>
          <li class="nav-item">
            <a class="nav-link gz-nav-link" href="logout.php"  >Log Out</a>
          </li>
          <li class="nav-item">
            <a class="nav-link gz-nav-link" href="account.php"><img src="misc/img/user.svg" alt="Profile Icon" style="height: 24px; weight:24px;">  Account</a>
          </li>
        <?php endif ?>
      </ul>
    </div>
  </nav>
</body>
</html>