<?php 
    include (__DIR__.'/NavBar.php');
    include (__DIR__. '/model/ModelReview.php');
    
    if (isPostRequest()){
        $_SESSION['email']=filter_input(INPUT_POST,'email');
        $_SESSION['userEncryptedPassword']=filter_input(INPUT_POST, 'userEncryptedPassword');

        $userEmail=$_SESSION['email'];
        $userEncryptedPassword=sha1($_SESSION['userEncryptedPassword']);


        if (checkLogin($userEmail, $userEncryptedPassword)==true)
        {
            $_SESSION['loggedin']=true;
            header("Location: account.php");
        }
        else{
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
    <title>Gourmandize | Log In</title>
</head>
<body>
    <div class="container gz-div-glow">
        <div class="container gz-div-inner">
            <div class="d-flex justify-content-center mx-auto text-center py-5">
                <form method="post" action="Login.php">
                    <div>
                        <h2 class="text-white display-4" style="font-family: textFont">Log In To</h2>
                    </div>
                    <div class="py-3">
                        <h1 class="glow text-white display-4" style="font-family: logoFont;">Gourmandize</h1>
                    </div>
                    <div class="form-group">
                        <input size="30" type="text" name="email" placeholder="Email"/>
                    </div>
                    <div class="form-group">
                        <input size="30" type="password" name="userEncryptedPassword" placeholder="Password"/>
                    </div>
                    <div class="form-group mx-auto">
                        <button class="btn btn-outline-light"type="submit">Log In</button>
                    </div>
                </form>
            </div>
            <div class="pb-3 d-flex justify-content-end">
                <h2 class="text-white pr-3 mb-0" style="font-family: textFont">New to Gourmandize? </h2>
                <a class="btn btn-outline-light" href="SignUp.php">Sign Up</a>
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
