<?php 
    include (__DIR__.'/NavBar.php');
    include (__DIR__.'/model/ModelReview.php');

    
    if(isset($_GET['Totalsearch'])){
      $Src=filter_input(INPUT_GET,'Totalsearch');
      $Src= $_GET['Totalsearch'];
      header("Location: SearchResults.php?Totalsearch=".$Src."");
    }

    $errorMsg="";
    $pwEmpty=sha1('');

    if (isPostRequest()){
        $uName=filter_input(INPUT_POST,'uName');
        $uName=$_POST['uName'];
        $fName=filter_input(INPUT_POST,'fName');
        $fName=$_POST['fName'];
        $LName=filter_input(INPUT_POST,'lName');
        $lName=$_POST['lName'];
        $Email=filter_input(INPUT_POST,'email');
        $email=$_POST['email'];
        $emailConfirmation=filter_input(INPUT_POST,'emailConfirmation');
        $emailConfirmation=$_POST['emailConfirmation'];
        $pw=sha1(filter_input(INPUT_POST,'pw'));
        $pw=sha1($_POST['pw']);
        $pwConfirmation=sha1(filter_input(INPUT_POST,'pwConfirmation'));
        $pwConfirmation=sha1($_POST['pwConfirmation']);

        if($uName==''){
            $errorMsg="Username must be filled in.";
        }
        else{

        }


       if($emailConfirmation!=$email || $email=='' || $emailConfirmation==''){
            $errorMsg=$errorMsg . " Email and Email Verification did not match.";
        }
        else{
            $email=$emailConfirmation;
        }

        if($pwConfirmation!=$pw || $pw==$pwEmpty || $pwConfirmation==$pwEmpty){
            $errorMsg=$errorMsg . " Passwords did not match.";
        }
        else{
            $pw=$pwConfirmation;
        }

        if($errorMsg==''){
            addUser($uName, $email, $pw, $fName, $lName, "");                
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
                    <input size="30"type="text" name="uName" placeholder="Username"/>
                </div>
                <div class="form-group">
                    <input size="30" type="text" name="fName" placeholder="First Name"/>
                </div>
                <div class="form-group">
                    <input size="30" type="text" name="lName" placeholder="Last Name"/>
                </div>
                <div class="form-group">
                    <input size="30" type="text" name="email" placeholder="Email"/>
                </div>
                <div class="form-group">
                    <input size="30" type="text" name="emailConfirmation" placeholder="Email Confirmation"/>
                </div>
                <div class="form-group">
                    <input size="30" type="password" name="pw" placeholder="Password"/>
                </div>
                <div class="form-group">
                    <input size="30" type="password" name="pwConfirmation" placeholder="Password Confirmation"/>
                </div>
                <div class="form-group mx-auto">
                    <button class="btn btn-outline-light"type="submit">Sign Up</button>
                </div>
                <h5 style="color:red;"><?=$errorMsg;?></h5>
            </form>
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