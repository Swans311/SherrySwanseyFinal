<?php 
    include (__DIR__.'/NavBar.php');
    include (__DIR__.'/model/ModelReview.php');




    $Error="";
    $emptypw=sha1('');

    if (isPostRequest()){
        $UName=filter_input(INPUT_POST,'UName');
        $UName=$_POST['UName'];
        $FName=filter_input(INPUT_POST,'FName');
        $FName=$_POST['FName'];
        $LName=filter_input(INPUT_POST,'LName');
        $LName=$_POST['LName'];
        $Email=filter_input(INPUT_POST,'Email');
        $Email=$_POST['Email'];
        $Email2=filter_input(INPUT_POST,'Email2');
        $Email2=$_POST['Email2'];
        $pw=sha1(filter_input(INPUT_POST,'pw'));
        $pw=sha1($_POST['pw']);
        $pw2=sha1(filter_input(INPUT_POST,'pw2'));
        $pw2=sha1($_POST['pw2']);

        if($UName==''){
            $Error="Username must be filled in.";
        }
        else{

        }


       if($Email2!=$Email || $Email=='' || $Email2==''){
            $Error=$Error . " Email and Email Verification did not match.";
        }
        else{
            $Email=$Email2;
        }

        if($pw2!=$pw || $pw==$emptypw || $pw2==$emptypw){
            $Error=$Error . " Passwords did not match.";
        }
        else{
            $pw=$pw2;
        }

        if($Error==''){
            addUser($UName, $Email, $pw, $FName, $LName, "");                
            header('Location: Login.php');    
        }
        else{
            
        } 
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gourmandize | Sign Up</title>
</head>
<body>
    <div class="container gz-div-glow">
        <div class="container gz-div-inner d-flex justify-content-center mx-auto text-center py-5">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <div>
                    <h2 class="text-white display-4" style="font-family: textFont">Sign Up For</h2>
                </div>
                <div class="py-3">
                    <h1 class="glow text-white display-4" style="font-family: logoFont;">Gourmandize</h1>
                </div>
                <div class="form-group ">
                    <input size="30"type="text" name="UName" placeholder="Username"/>
                </div>
                <div class="form-group">
                    <input size="30" type="text" name="FName" placeholder="First Name"/>
                </div>
                <div class="form-group">
                    <input size="30" type="text" name="LName" placeholder="Last Name"/>
                </div>
                <div class="form-group">
                    <input size="30" type="text" name="Email" placeholder="Email"/>
                </div>
                <div class="form-group">
                    <input size="30" type="text" name="Email2" placeholder="Email Confirmation"/>
                </div>
                <div class="form-group">
                    <input size="30" type="password" name="pw" placeholder="Password"/>
                </div>
                <div class="form-group">
                    <input size="30" type="password" name="pw2" placeholder="Password Confirmation"/>
                </div>
                <div class="form-group mx-auto">
                    <button class="btn btn-outline-light"type="submit">Sign Up</button>
                </div>
                <h5 style="color:red;"><?=$Error;?></h5>
            </form>
        </div>
    </div>
</body>
<footer>
  <hr style="color:red;"/>
  <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2)">
    © 2021 Copyright:
    <a class="text-dark" href="AboutUs.php">About Us</a>
  </div>
  </footer>
</html>