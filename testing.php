<?php

    include ("model/db.php");

    if(isset($_POST['btn-add']))
    {
        $images=$_FILES['profile']['name'];
        $tmp_dir=$_FILES['profile']['tmp_name'];
        $imageSize=$_FILES['profile']['size'];
        $upload_dir='uploads';
        $imgExt=strtolower(pathinfo($images, PATHINFO_EXTENSION));
        $valid_extensions=array('jpeg', 'jpg', 'png', 'gif', 'pdf');
        $picProfile=rand(1000,1000000). ".".$imgExt;
        move_uploaded_file($tmp_dir, $upload_dir.$picProfile);
        $stmt=$db->prepare("INSERT INTO rimages SET Img = :Img");
        $stmt->bindParam(":Img", $picProfile);

        if($stmt->execute())
        {
            ?>
            <script>
                alert("new record added");
            </script>
            <?php
        }else{
            ?>
            <script>
                alert("record not added");
            </script>
            <?php
        }
    }

    function findpicture($id){
        global $db;
        $stmt = $db->prepare("SELECT Picture FROM rimages WHERE Img_ID = :Img_ID");

            $stmt -> bindValue(':Img_ID', $id);
            


            if ($stmt->execute() && $stmt->rowCount() > 0) 
            {
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            else{
                $results='';
            }
        return ($results);

    }

    $pic=array();
    $pic2=array();
    $pic=findpicture(2);
    var_dump($pic);



?>



<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">



</head>
<body>
<form method="post" enctype="multipart/form-data">
    <input type="file" name="profile" class="form-control" required="" accept="*/image" >
    <button type="submit" name="btn-add">Add Picture </button>
</form>

<?php foreach ($pic as $p){
    echo $p['Picture'];
    echo "<img src='uploads$p[Picture]' style='width:100px; height:100px;'/>";
}
?>

</body>
</html>