<?php
    include (__DIR__ . '/model/db.php');


    $cn=mysqli_connect("ict.neit.edu","se266_David","David","rimages", 5500) or die("Could not Connect My Sql");
    $output_dir = "upload/";/* Path for file upload */
	$RandomNum   = time();
	$ImageName      = str_replace(' ','-',strtolower($_FILES['image']['name'][0]));
	$ImageType      = $_FILES['image']['type'][0];
 
	$ImageExt = substr($ImageName, strrpos($ImageName, '.'));
	$ImageExt       = str_replace('.','',$ImageExt);
	$ImageName      = preg_replace("/\.[^.\s]{3,4}$/", "", $ImageName);
	$NewImageName = $ImageName.'-'.$RandomNum.'.'.$ImageExt;
    $ret[$NewImageName]= $output_dir.$NewImageName;
	
	/* Try to create the directory if it does not exist */
	if (!file_exists($output_dir))
	{
		@mkdir($output_dir, 0777);
	}               
	move_uploaded_file($_FILES["image"]["tmp_name"][0],$output_dir."/".$NewImageName );
	     $sql = "INSERT INTO `rimages`(`Img`, `Restaurant_ID`, `Review_ID` )VALUES ('$NewImageName', 1,1)";
		if (mysqli_query($cn, $sql)) {
			echo "successfully !";
		}
		else {
		echo "Error: " . $sql . "" . mysqli_error($cn);
	 }

?>