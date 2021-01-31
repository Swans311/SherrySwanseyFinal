<?php
    include (__DIR__ . '/model/db.php');
    $query = "SELECT * from rimages WHERE Review_ID = 1"; 
    $stmt = $db->prepare( $query );
    
    //$stmt->bindParam(1, $_GET['Review_ID']);
    $stmt->execute();
    
    // to verify if a record is found
    $num = $stmt->rowCount();
    
    if( $num ){
        // if found
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // specify header with content type,
        // you can do header("Content-type: image/jpg"); for jpg,
        // header("Content-type: image/gif"); for gif, etc.
        header("Content-type: image/gif");
        
        //display the image data
        print $row['data'];
        exit;
    }else{
        //if no image found with the given id,
        //load/query your default image here
    }


?>



<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">



</head>
<body>
<form action="upload.php" method="post" enctype="multipart/form-data">
    <input type="file" name="image[]" />
    <button type="submit">Upload</button>
</form>

<?php
echo "<img src=".$I."/>";
?>
</body>
</html>