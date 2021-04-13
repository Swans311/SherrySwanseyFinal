<?php
    include (__DIR__ . '/db.php');

    function addUser($username, $email, $hashedPass, $first, $last, $imageFilePath)
    {
        global $db;
        $results = 'Data NOT Added';
        $stmt2 = $db->prepare("SELECT * FROM rusers WHERE Username = :username");
        $stmt2-> bindValue(':username', $username);
        $stmt3= $db->prepare("SELECT * FROM rusers WHERE User_Email = :email");
        $stmt3->bindValue(":email", $email);

        if (($stmt3->execute() && $stmt3->rowCount()>0) || ($stmt2->execute() && $stmt2->rowCount()>0)){
            $results = false;
            return $results;
        }

        else if ($stmt2->execute() && $stmt2->rowCount()==0 && $stmt3->execute() && $stmt3->rowCount()==0)
        {
            $stmt = $db->prepare("INSERT INTO rusers SET Username = :username, User_Password = :hashedPass, User_Email = :email, FName = :fname, LName = :lname");

            $stmt -> bindValue(':username', $username);
            $stmt -> bindValue(':hashedPass', $hashedPass);
            $stmt -> bindValue(':email', $email);
            $stmt -> bindValue(':fname', $first);
            $stmt -> bindValue(':lname', $last);


            if ($stmt->execute() && $stmt->rowCount() > 0) 
            {
                $results = 'Data Added';
            }
        }


        return ($results);
    }
    function checkLogin($email, $hashedPass)
    {
        global $db;
        $stmt = $db->prepare("SELECT User_ID FROM rusers WHERE User_Email =:email AND User_Password = :pass");

        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':pass', $hashedPass);
        
        $stmt->execute ();
    
        return( $stmt->rowCount() > 0);
    }
    function checkUser($email)
    {
        global $db;
        $stmt = $db->prepare("SELECT User_ID FROM rusers WHERE User_Email =:email");

        $stmt->bindValue(':email', $email);
        
        $stmt->execute ();
    
        return( $stmt->rowCount() > 0);
    }
    function delUser($userID)
    {
        global $db;
        $stmt = $db->query("DELETE FROM rusers WHERE User_ID = :userID;");

        $stmt->bindValue(':userID', $userID);

        $stmt->execute ();

        return( $stmt->rowCount() > 0);
    }
    function modifyUser($userID, $username, $email, $hashedPass, $first, $last, $imageFilePath)
    {
        global $db;
        $results = 'Data NOT Updated';
        $stmt = $db->prepare("Update rusers SET Username = :username, Password = :hashedPass, User_Email = :email, FName = :fname, LName = :lname WHERE User_ID = :id");

        $stmt -> bindValue(':username', $username);
        $stmt -> bindValue(':hashedpass', $hashedPass);
        $stmt -> bindValue(':email', $email);
        $stmt -> bindValue(':fname', $first);
        $stmt -> bindValue(':lname', $last);
        $stmt -> bindValue(':id', $userID);
        

        if ($stmt->execute() && $stmt->rowCount() > 0) 
        {
            $results = 'Data Updated';
        }
        
        return ($results);
    }
    function getUsername($userID)
    {
        global $db;
        $stmt = $db->prepare("SELECT Username FROM rusers WHERE User_ID =:userID");

        $stmt->bindValue(':userID', $userID);

        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

        return $results['Username'];
    }
    function getRestaurantName($restaurantID)
    {
        global $db;
        $stmt = $db->prepare("SELECT Restaurant_Name FROM restaurant WHERE Restaurant_ID =:resID");

        $stmt->bindValue(':resID', $restaurantID);

        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

        return $results['Restaurant_Name'];
    }
    function getItemName($itemID)
    {
        global $db;
        $stmt = $db->prepare("SELECT ItemName FROM menuitem WHERE Item_ID =:itemID");

        $stmt->bindValue(':itemID', $itemID);

        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

        return $results['ItemName'];
    }
    function getUserByID($userID)
    {
        global $db;
        $stmt = $db->prepare("SELECT * FROM rusers WHERE User_ID =:userID");

        $stmt->bindValue(':userID', $userID);

        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

        return $results;
    }
    function getRestaurantByID($restaurantID)
    {
        global $db;
        $stmt = $db->prepare("SELECT * FROM restaurant WHERE Restaurant_ID =:resID");

        $stmt->bindValue(':resID', $restaurantID);

        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

        return $results;
    }
    function getItemByID($itemID)
    {
        global $db;
        $stmt = $db->prepare("SELECT * FROM menuitem WHERE Item_ID =:itemID");

        $stmt->bindValue(':itemID', $itemID);

        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

        return $results;
    }
    function addItemReview($restaurantID, $userID, $itemName, $resReviewID, $dateTime, $categories, $rating, $review, $anonymous, $imageFilePath)
    {
        $itemID = searchOneItemId($restaurantID, $itemName);
        if($itemID == false)
        {
            addItem($restaurantID, $itemName);
            //Should be guaranteed to exist now
            $itemID = searchOneItemID($restaurantID, $itemName);
        }

        global $db;
        $results = 'Data NOT Added';

        //Convert comma separated tag names into tagIDs and add/increment in database
        $tags = explode(',', $categories);
        $catArray = array();
        foreach($tags as $tag)
        {
            addTagByItem(trim($tag), $itemID);
            array_push($catArray, getTagIdByNameAndItem(trim($tag), $itemID));
        }
        /*
        $stmt = $db->prepare("INSERT INTO review SET Restaurant_ID = :restaurantID, User_ID = :userID, Item_ID = :itemID, Review = :review, Star_lvl = :rating, Username = :username, Uname_Visible = :visible, ReviewDate = :revDate, Category = :cat, ResReview_ID = :resRevID");
        $stmt->bindValue(':restaurantID', $restaurantID);
        $stmt->bindValue(':userID', $userID);
        $stmt->bindValue(':itemID', $itemID);
        $stmt->bindValue(':review', $review);
        $stmt->bindValue(':rating', $rating);
        $stmt->bindValue(':username', getUsername($userID));
        $stmt->bindValue(':visible', $anonymous);
        $stmt->bindValue(':revDate', $dateTime);
        $stmt->bindValue(':cat', implode(',', $catArray));
        $stmt->bindValue(':resRevID', $resReviewID);
        */

        $str = "INSERT INTO review SET Restaurant_ID = " . $restaurantID . ", User_ID = " . $userID . ", Item_ID = " . $itemID . ", Review = '" . $review . "', Star_lvl = " . $rating . ", Username = '" . getUsername($userID) . "', Uname_Visible = " . $anonymous . ", ReviewDate = '" . $dateTime . "', Category = '" . implode(',', $catArray) . "', ResReview_ID = " . $resReviewID . "";
        $stmt = $db->prepare($str);
        $stmt->execute ();

        $stmt2 = $db->prepare("SELECT Review_ID FROM review WHERE User_ID = :userID AND ReviewDate = :revDate");
        $stmt2 ->bindValue(":userID", $userID);
        $stmt2 ->bindValue(":revDate", $dateTime);
        $stmt2->execute();

        $results = $stmt2->fetch(PDO::FETCH_ASSOC);
        $revID = $results['Review_ID'];        

        $stmt1 = $db->prepare("UPDATE review SET Rimage = :Img WHERE (Review_ID = :revID)");
        
        //var_dump($imageFilePath);
        $stmt1->bindValue(":Img", $imageFilePath);
        $stmt1->bindValue(':revID', $revID);

        $stmt1->execute();
    }
    function addRestaurantReview($restaurantName, $restaurantAddress, $restaurantPhone, $restaurantURL, $userID, $restaurantReview, $rating,  $uNameVis, $imageFilePath, $itemReview2DList, $categories)
    {
        global $db;
        $results = 'Data NOT Added';

        $restaurantID = searchOneRestaurantID($restaurantName, $restaurantAddress, $restaurantPhone, $restaurantURL);

        if($restaurantID == false)//if restaurantID wasnt found add restaurant
        {
            addRestaurant($restaurantName, $restaurantAddress, $restaurantPhone, $restaurantURL);
            $restaurantID = searchOneRestaurantID($restaurantName, $restaurantAddress, $restaurantPhone, $restaurantURL);
        }

        //Convert comma separated tag names into tagIDs and add/increment in database
        $tags = explode(',', $categories); 
        $catArray = array();
        foreach($tags as $tag)
        {
            addTagByRes(trim($tag), $restaurantID);
            array_push($catArray, getTagIdByNameAndRestaurant(trim($tag), $restaurantID));
        }
      
        /*$stmt = $db->prepare("INSERT INTO restaurantreview SET Restaurant_ID = :restaurantID, User_ID = :userID, Review = :review, Star_lvl = :rating, UserName = :username, ReviewDate = :revDate, Category = :cat, Visible = :visible");
      
        $stmt->bindValue(':restaurantID', $restaurantID);
        $stmt->bindValue(':userID', $userID);
        $stmt->bindValue(':review', $restaurantReview);
        $stmt->bindValue(':rating', $rating);
        $stmt->bindValue(':username', getUsername($userID));
        $stmt->bindValue(':cat', implode(',', $catArray));
        $time = date('Y-m-d H:i:s');
        $stmt->bindValue(':revDate', $time);
        $stmt->bindValue(':visible', $uNameVis);
        */
        $time = date('Y-m-d H:i:s');
        $str = $str = "INSERT INTO restaurantreview SET Restaurant_ID = " . $restaurantID .", User_ID = " . $userID . ", Review = '" . $restaurantReview . "', Star_lvl = " . $rating . ", UserName = '" . getUsername($userID) . "', ReviewDate = '" . $time . "', Category = '" . implode(',', $catArray) . "', Visible = " . $uNameVis . "";
        $stmt = $db->prepare($str);
        //$stmt->debugDumpParams();

        $stmt->execute();
        //var_dump($stmt);
        
        //TODO:: REMOVE THIS DUMMY
        //return json_encode($returnVal);

        $success = $stmt->rowCount();

        $stmt2 = $db->prepare("SELECT ResReview_ID FROM restaurantreview WHERE User_ID = :userID AND ReviewDate = :revDate LIMIT 1");
        $stmt2 ->bindValue(":revDate", $time);
        $stmt2 ->bindValue(":userID", $userID);
        $stmt2->execute();

        $results = $stmt2->fetch(PDO::FETCH_ASSOC);
        $resRevID = $results['ResReview_ID'];

        if($imageFilePath != "")
        {
            $stmt1 = $db->prepare("UPDATE restaurantreview SET ResImage = :Img WHERE (ResReview_ID = :resRevID)");
            
            $stmt1->bindValue(":Img", $imageFilePath);
            $stmt1->bindValue(':resRevID', $resRevID);

            $stmt1->execute();
        }
        $testing = array();
        //loop throught list and call addItemReview()
        foreach($itemReview2DList as $itemReviewList)
        {
            array_push($testing, addItemReview($restaurantID, $userID, $itemReviewList['itemName'], $resRevID, $time, $itemReviewList['category'], $itemReviewList['rating'], $itemReviewList['review'], $uNameVis, $itemReviewList['imageFilePath']));
        }
        return json_encode($restaurantID);
    }
    //use minRating = -1 to ignore rating and 0 to get all restaurants that have been reviewed at least once
    function searchByRestaurant($name, $category, $minRating)
    {
        global $db;
       
       $binds = array();
       $sql = "SELECT * FROM restaurant WHERE 0=0 ";
       if ($name != "") {
            $sql .= " AND UPPER(Restaurant_Name) LIKE UPPER(:name)";
            $binds['name'] = '%'.$name.'%';
       }
       $sql .= " ORDER BY Restaurant_Name DESC";
       $stmt = $db->prepare($sql);
      
        $results = array();
        if ($stmt->execute($binds) && $stmt->rowCount() > 0) {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        $returnArray = [];
        /*$categories = explode(',', $category);
        foreach($results as $result)
        {
            $commonResCats = getCommonRestaurantCategories($result['Restaurant_ID'], 5);
            foreach($commonResCats as $commonResCat)
                foreach($categories as $cat)
                    if($category == '' || strpos(strtoupper($commonResCat['Name']), strtoupper($cat)) !== false)
                        if(calculateRestaurantStarRating($result['Restaurant_ID']) >= $minRating)
                            if(!in_array($result, $returnArray))
                                array_push($returnArray, $result);
        }
        */
        return ($returnArray);
    }
    //use minRating = -1 to ignore rating and 0 to get all items that have been reviewed at least once
    function searchByItem($name, $category, $minRating)
    {
        global $db;
       
       $binds = array();
       $sql = "SELECT * FROM menuitem WHERE 0=0 ";
       if ($name != "") {
            $sql .= " AND UPPER(ItemName) LIKE UPPER(:name)";
            $binds['name'] = '%'.$name.'%';
       }
       
       $sql .= " ORDER BY ItemName DESC";

       $stmt = $db->prepare($sql);
      
        $results = array();
        if ($stmt->execute($binds) && $stmt->rowCount() > 0) {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        $returnArray = [];
        $categories = explode(',', $category);
        foreach($results as $result)
        {
            $commonItemCats = getCommonItemCategories($result['Item_ID'], 5);
            foreach($commonItemCats as $commonItemCat)
                foreach($categories as $cat)
                    if($category == '' || strpos(strtoupper($commonItemCat['Name']), strtoupper($cat)) !== false)
                        if(calculateItemStarRating($result['Item_ID']) >= $minRating)
                            if(!in_array($result, $returnArray))
                                array_push($returnArray, $result);
        }

        return ($returnArray);
    }
    function deleteRestaurantReview($resReviewID)
    {
        global $db;
        $stmt = $db->prepare("DELETE FROM restaurantreview WHERE ResReview_ID = :ID;");

        $stmt->bindValue(':ID', $resReviewID);

        $stmt->execute ();

        return( $stmt->rowCount() > 0);
    }
    function deleteRestaurantReviewAndItems($resReviewID)
    {
        $resDeleteSuccess = deleteRestaurantReview($resReviewID);

        global $db;
        //LoopThrough connected ItemReviews
        $stmt = $db->prepare("SELECT Review_ID, COUNT(*) AS reviewCount FROM review WHERE ResReview_ID = :ID;");
        $stmt->bindValue(':ID', $resReviewID);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);   
        
        $reviewCount = $results['reviewCount'];//Number
        $reviewIDs = $results['Review_ID'];//Array
        $itemsDeleteSuccess = true;

        foreach($reviewIDs as $reviewID)
        {
            $temp = deleteItemReview($reviewID);
            $itemsDeleteSuccess = $itemsDeleteSuccess ? $temp : false;
        }
        return $resDeleteSuccess && $itemsDeleteSuccess;
    }
    function deleteItemReview($itemReviewID)
    {
        global $db;
        $stmt = $db->prepare("DELETE FROM review WHERE Review_ID = :ID;");

        $stmt->bindValue(':ID', $itemReviewID);

        $stmt->execute ();

        return( $stmt->rowCount() > 0);
    }
    function addRestaurant($name, $address, $phone, $url)
    {
        global $db;
        $results = 'Data NOT Added';
        $stmt = $db->prepare("INSERT INTO restaurant SET Restaurant_Name = :resName, ResAddress = :resAddress, Phone = :phone, Restaurant_URL = :resURL");

        $stmt -> bindValue(':resName', $name);
        $stmt -> bindValue(':resAddress', $address);
        $stmt -> bindValue(':phone', $phone);
        $stmt -> bindValue(':resURL', $url);
        
        if ($stmt->execute() && $stmt->rowCount() > 0) 
        {
            $results = 'Data Added';
        }
        
        return ($results);
    }
    function addItem($restaurantID, $name)
    {
        global $db;
        $results = 'Data NOT Added';
        $stmt = $db->prepare("INSERT INTO menuItem SET Restaurant_ID = :resID, ItemName = :iName");

        $stmt -> bindValue(':resID', $restaurantID);
        $stmt -> bindValue(':iName', $name);
        
        if ($stmt->execute() && $stmt->rowCount() > 0) 
        {
            $results = 'Data Added';
        }
        
        return ($results);
    }
    function getRestaurantReview($resReviewID)
    {
        global $db;
        $stmt = $db->prepare("SELECT * FROM restaurantreview WHERE ResReview_ID =:ID");

        $stmt->bindValue(':ID', $resReviewID);

        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

        return $results;
    }
    function getItemReview($reviewID)
    {
        global $db;
        $stmt = $db->prepare("SELECT * FROM review WHERE Review_ID =:ID;");

        $stmt->bindValue(':ID', $reviewID);
        
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

        return $results;
    }
    function getItemsInRestaurantReview($resReviewID)
    {
        global $db;
        //get connected ItemReviews
        $stmt = $db->prepare("SELECT Review_ID FROM review WHERE ResReview_ID = :ID;");
        $stmt->bindValue(':ID', $resReviewID);

        $stmt->execute();
        $results = $stmt->fetchALL(PDO::FETCH_ASSOC);   

        $itemReviewList = array();
        //loop through and append to list
        foreach($results as $result)
        {
            $reviewID = $result['Review_ID'];
            array_push($itemReviewList, getItemReview($reviewID));
        }
        return $itemReviewList;
    }
    function getAllReviewsForRestaurant($restaurantID)
    {
        global $db;
        //get connected ItemReviews
        $stmt = $db->prepare("SELECT ResReview_ID FROM restaurantreview WHERE Restaurant_ID = :ID;");
        $stmt->bindValue(':ID', $restaurantID);

        $stmt->execute();
        $results = $stmt->fetchALL(PDO::FETCH_ASSOC);   

        $resReviewList = array();
        //loop through and append to list
        foreach($results as $result)
        {
            $resReviewID = $result['ResReview_ID'];
            array_push($resReviewList, getRestaurantReview($resReviewID));
        }
        return $resReviewList;
    }
    function getAllReviewsForRestaurantChronological($restaurantID, $limit, $newestFirst)
    {
        global $db;
        $string = "SELECT ResReview_ID FROM restaurantreview WHERE Restaurant_ID = :ID ORDER BY ReviewDate ";
        $string .= $newestFirst == True ? "DESC LIMIT :Lim;" : "ASC LIMIT :Lim;";
        //get connected ItemReviews
        $stmt = $db->prepare($string);
        $stmt->bindValue(':ID', $restaurantID);
        $stmt->bindValue(':Lim', $limit);

        $stmt->execute();
        $results = $stmt->fetchALL(PDO::FETCH_ASSOC);   

        $resReviewList = array();
        //loop through and append to list
        foreach($results as $result)
        {
            $resReviewID = $result['ResReview_ID'];
            array_push($resReviewList, getRestaurantReview($resReviewID));
        }
        return $resReviewList;
    }
    function getAllReviewsForItem($itemID)
    {
        global $db;
        //get connected ItemReviews
        $stmt = $db->prepare("SELECT Review_ID FROM review WHERE Item_ID = :ID;");
        $stmt->bindValue(':ID', $itemID);

        $stmt->execute();
        $results = $stmt->fetchALL(PDO::FETCH_ASSOC);   

        $itemReviewList = array();
        //loop through and append to list
        foreach($results as $result)
        {
            $reviewID = $result['Review_ID'];
            array_push($itemReviewList, getItemReview($reviewID));
        }
        return $itemReviewList;
    }
    function getAllReviewsForItemChronological($itemID, $limit, $newestFirst)
    {
        global $db;
        $string = "SELECT Review_ID FROM review WHERE Item_ID = :ID ORDER BY Review_Date ";
        $string .= $newestFirst == True ? "DESC LIMIT :Lim;" : "ASC LIMIT :Lim;";
        //get connected ItemReviews
        $stmt = $db->prepare($string);
        $stmt->bindValue(':ID', $itemID);
        $stmt->bindValue(':Lim', $limit);

        $stmt->execute();
        $results = $stmt->fetchALL(PDO::FETCH_ASSOC);   

        $itemReviewList = array();
        //loop through and append to list
        foreach($results as $result)
        {
            $reviewID = $result['Review_ID'];
            array_push($itemReviewList, getItemReview($reviewID));
        }
        return $itemReviewList;
    }
    function getAllResReviewsByUser($userID)
    {
        global $db;
        //get connected ItemReviews
        $stmt = $db->prepare("SELECT ResReview_ID FROM restaurantreview WHERE User_ID = :ID;");
        $stmt->bindValue(':ID', $userID);

        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);   

        $resReviewList = array();
        //loop through and append to list
        foreach($results as $result)
        {
            $reviewID = $result['ResReview_ID'];
            array_push($resReviewList, getRestaurantReview($reviewID));
        }
        return $resReviewList;
    }
    function getAllResReviewsByUserChronological($userID, $limit, $newestFirst)
    {
        global $db;
        $string = "SELECT ResReview_ID FROM restaurantreview WHERE User_ID = :ID ORDER BY ReviewDate ";
        $string .= $newestFirst == True ? "DESC LIMIT :Lim;" : "ASC LIMIT :Lim;";
        //get connected ItemReviews
        $stmt = $db->prepare($string);
        $stmt->bindValue(':ID', $userID);
        $stmt->bindValue(':Lim', $limit);

        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);   

        $resReviewList = array();
        //loop through and append to list
        foreach($results as $result)
        {
            $reviewID = $result['ResReview_ID'];
            array_push($resReviewList, getRestaurantReview($reviewID));
        }
        return $resReviewList;
    }
    function getAllResReviewsByUserChronologicalNoLimit($userID, $newestFirst)
    {
        global $db;
        $string = "SELECT ResReview_ID FROM restaurantreview WHERE User_ID = :ID ORDER BY ReviewDate ";
        $string .= $newestFirst == True ? "DESC;" : "ASC;";
        //get connected ItemReviews
        $stmt = $db->prepare($string);
        $stmt->bindValue(':ID', $userID);

        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);   

        $resReviewList = array();
        //loop through and append to list
        foreach($results as $result)
        {
            $reviewID = $result['ResReview_ID'];
            array_push($resReviewList, getRestaurantReview($reviewID));
        }
        return $resReviewList;
    }
    //Used when submitting review, returns restaurant id if found, false if not so it knows to add the restaurant
    function searchOneRestaurantID($name, $address, $phone, $url)
    {
        global $db;
        //get connected ItemReviews
        $stmt = $db->prepare("SELECT Restaurant_ID FROM restaurant WHERE Restaurant_Name = :resName AND ResAddress = :resAddress AND Phone = :phone AND Restaurant_URL = :resURL;");
        $stmt->bindValue(':resName', $name);
        $stmt->bindValue(':resAddress', $address);
        $stmt->bindValue(':phone', $phone);
        $stmt->bindValue(':resURL', $url);

        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        if($stmt->rowCount() == 1)
            return $results['Restaurant_ID'];
        else
            return false;
    }
    //Used when submitting review, returns item id if found, false if not so it knows to add the item
    function searchOneItemID($resID, $name)
    {
        global $db;
        //get connected ItemReviews
        $stmt = $db->prepare("SELECT Item_ID FROM menuitem WHERE ItemName = :itemName AND Restaurant_ID = :resID;");
        $stmt->bindValue(':itemName', $name);
        $stmt->bindValue(':resID', $resID);

        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        if($stmt->rowCount() == 1)
            return $results['Item_ID'];
        else
            return false;
    }
    function searchUser($username, $email, $first, $last)
    {
        global $db;
       
        $binds = array();
        $sql = "SELECT * FROM rusers WHERE 0=0 ";
        if ($username != "") {
             $sql .= " AND Username LIKE :name";
             $binds['name'] = '%'.$username.'%';
        }
        if ($email != "")
        {
             $sql .= " AND User_Email LIKE :email";
             $binds['email'] = '%'.$email.'%';
        }
        if ($first != "")
        {
             $sql .= " AND FName LIKE :fname";
             $binds['fname'] = '%'.$first.'%';
        }
        if ($email != "")
        {
             $sql .= " AND LName LIKE :lname";
             $binds['lname'] = '%'.$last.'%';
        }

        $sql .= " ORDER BY Username DESC";
 
        $stmt = $db->prepare($sql);
       
         $results = array();
         if ($stmt->execute($binds) && $stmt->rowCount() > 0) {
             $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
         }
         return ($results);
    }
    function editItemReview($reviewID, $rating, $anonymous, $categories, $review, $imageFilePath)
    {
        global $db;

        $results = "Data NOT Updated";

        //Get old review
        $oldReview = getReview($reviewID);
        //split categories into array
        $oldCatsArray = explode(',', $oldReview['Category']);
        //decrement all ids
        foreach($catsArray as $tagID)
            decrementTagID($tagID);
        //split categories into array
        $newTagsStringArray = explode(',', $categories);
        //add all new ids
        $tagIDs = array();
        foreach($newTagsStringArray as $newTagString)
            addTagByRes($newTagString, $oldReview['Restaurant_ID']);
            array_push($tagIDs, getTagIdByNameAndItem($newTagString, $oldReview['Restaurant_ID']));
        
        $stmt = $db->prepare("UPDATE reviews SET Star_lvl = :rating, Uname_Visible = :anon, Category = :categories, Review = :review WHERE Review_ID=:id");
        
        $stmt->bindValue(':rating', $rating);
        $stmt->bindValue(':anon', $anonymous);
        $stmt->bindValue(':categories', $categories);
        $stmt->bindValue(':review', $review);
        $stmt->bindValue(':id', $reviewID);

      
        if ($stmt->execute() && $stmt->rowCount() > 0) {
            $results = 'Data Updated';
        }
        
        return ($results);
    }
    function editRestaurantReview( $resReviewID, $review, $rating, $categories, $anonymous, $imageFilePath)
    {
        global $db;

        
        $results = "Data NOT Updated";
        
        //Get old review
        $oldResReview = getRestaurantReview($resReviewID);
        //split categories into array
        $oldCatsArray = explode(',', $oldResReview['Category']);
        //decrement all ids
        foreach($catsArray as $tagID)
            decrementTagID($tagID);
        //split categories into array
        $newTagsStringArray = explode(',', $categories);
        //add all new ids
        $tagIDs = array();
        foreach($newTagsStringArray as $newTagString)
            addTagByRes($newTagString, $oldResReview['Restaurant_ID']);
            array_push($tagIDs, getTagIdByNameAndRestaurant($newTagString, $oldResReview['Restaurant_ID']));
        //update with new ids
        $stmt = $db->prepare("UPDATE reviews SET Star_lvl = :rating, Uname_Visible = :anon, Review = :review, Category = :cats  WHERE Review_ID=:id");
        
        $stmt->bindValue(':rating', $rating);
        $stmt->bindValue(':anon', $anonymous);
        $stmt->bindValue(':review', $review);
        $stmt->bindValue(':id', $resReviewID);
        $stmt->bindValue(':cats', implode(',', $tagIDs));

        if ($stmt->execute() && $stmt->rowCount() > 0) 
        {
            $results = 'Data Updated';
        }
        
        return ($results);
    }
    function editRestaurant( $restaurantID, $name, $address, $phone, $url)
    {
        global $db;

        $results = "Data NOT Updated";
        
        $stmt = $db->prepare("UPDATE restaurant SET Restaurant_Name = :resName, ResAddress = :addr, Phone = :phone, Restaurant_URL = :resURL WHERE Review_ID=:id");
        
        $stmt->bindValue(':resName', $name);
        $stmt->bindValue(':addr', $address);
        $stmt->bindValue(':phone', $phone);
        $stmt->bindValue(':resURL', $url);
        $stmt->bindValue(':id', $restaurantID);

        if ($stmt->execute() && $stmt->rowCount() > 0) 
        {
            $results = 'Data Updated';
        }
        
        return ($results);
    }
    function getUserID($useremail)
    {
        global $db;
        $stmt = $db->prepare("SELECT User_ID FROM rusers WHERE User_Email =:useremail");

        $stmt->bindValue(':useremail', $useremail, PDO::PARAM_STR);

        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

        return $results['User_ID'];
    }

    function getReview($ReviewID)
    {
        global $db;
        $stmt = $db->prepare("SELECT * FROM review WHERE Review_ID =:ID");

        $stmt->bindValue(':ID', $ReviewID);

        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

        return $results;
    }
    function getAllReviewsByUser($userID)
    {
        global $db;
        //get connected ItemReviews
        $stmt = $db->prepare("SELECT * FROM review WHERE User_ID = :ID;");
        $stmt->bindValue(':ID', $userID);

        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $reviewList = array();
        //loop through and append to list
        foreach($results as $result)
        {
            $reviewID = $result['Review_ID'];
            array_push($reviewList, getReview($reviewID));
        }
        return $reviewList;
    }
    //For getting the tag names from the IDs stored with the reviews for display
    function getTagByID($tagID)
    {
        global $db;
        $stmt = $db->prepare("SELECT * FROM tags WHERE Tag_ID =:tagID");

        $stmt->bindValue(':tagID', $tagID);

        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

        return $results;
    }
    //For getting the ID's when submitting a resReview to store in the resReview SQL
    function getTagIdByNameAndRestaurant($name, $resID)
    {
        global $db;
        $stmt = $db->prepare("SELECT * FROM tags WHERE Name =:name AND Restaurant_ID = :resID");

        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':resID', $resID, PDO::PARAM_STR);

        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

        return $results == false? false:$results['Tag_ID'];
    }
    //For getting the ID's when submitting a review to store in the review SQL
    function getTagIdByNameAndItem($name, $itemID)
    {
        global $db;
        $stmt = $db->prepare("SELECT * FROM tags WHERE Name =:name AND Item_ID = :itemID");

        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':itemID', $itemID, PDO::PARAM_STR);

        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

        return $results == false? false:$results['Tag_ID'];
    }
    function addTagByRes($name, $resID)
    {
        global $db;
        $results = 'Data NOT Added';
        $tagID = getTagIdByNameAndRestaurant($name, $resID);
        if($tagID == false)//If tag not found add it
        {
            $stmt = $db->prepare("INSERT INTO tags SET Counter = 1, Restaurant_ID = :resID, Name = :name,  Active = 1");

            $stmt -> bindValue(':resID', $resID);
            $stmt -> bindValue(':name', $name);

            if ($stmt->execute() && $stmt->rowCount() > 0) 
                $results = 'Data Added';
            else
                $results = 'Data failed to add';
        }
        else
        {
            incrementTagID($tagID);//if tag exists increment it by 1
            $results = 'Tag Counter Incremented';
        }
        return ($results);
    }
    function addTagByItem($name, $itemID)
    {
        global $db;
        $results = 'Data NOT Added';
        $tagID = getTagIdByNameAndItem($name, $itemID);
        if($tagID == false)//If tag not found add it
        {
            $stmt = $db->prepare("INSERT INTO tags SET Counter = 1, Item_ID = :itemID, Name = :name,  Active = 1");

            $stmt -> bindValue(':itemID', $itemID);
            $stmt -> bindValue(':name', $name);

            if ($stmt->execute() && $stmt->rowCount() > 0) 
                $results = 'Data Added';
            else
                $results = 'Data failed to add';
        }
        else
        {
            incrementTagID($tagID);//if tag exists increment it by 1
            $results = 'Tag Counter Incremented';
        }
        return ($results);
    }
    function incrementTagID($tagID)
    {
        global $db;

        $results = "Data NOT Updated";
        
        $stmt = $db->prepare("UPDATE tags SET Counter = Counter + 1 WHERE Tag_ID=:tagID");
        
        $stmt->bindValue(':tagID', $tagID);
      
        if ($stmt->execute() && $stmt->rowCount() > 0) {
            $results = 'Data Updated';
        }
        return ($results);
    }
    function decrementTagID($tagID)
    {
        global $db;

        $results = "Data NOT Updated";
        
        $stmt = $db->prepare("UPDATE tags SET Counter = Counter - 1 WHERE Tag_ID=:tagID");
        
        $stmt->bindValue(':tagID', $tagID);
      
        if ($stmt->execute() && $stmt->rowCount() > 0) {
            $results = 'Data Updated';
        }
        return ($results);
    }
    function incrementSearchID($searchID)
    {
        global $db;

        $results = "Data NOT Updated";
        
        $stmt = $db->prepare("UPDATE searches SET Counter = Counter + 1 WHERE Search_ID=:searchID");
        
        $stmt->bindValue(':searchID', $searchID);
      
        if ($stmt->execute() && $stmt->rowCount() > 0) {
            $results = 'Data Updated';
        }
        return ($results);
    }
    function decrementSearchID($searchID)
    {
        global $db;

        $results = "Data NOT Updated";
        
        $stmt = $db->prepare("UPDATE searches SET Counter = Counter - 1 WHERE Search_ID=:searchID");
        
        $stmt->bindValue(':searchID', $searchID);
      
        if ($stmt->execute() && $stmt->rowCount() > 0) {
            $results = 'Data Updated';
        }
        return ($results);
    }
    function getSearchIdByTerm($term)
    {
        global $db;
        $stmt = $db->prepare("SELECT * FROM searches WHERE Term LIKE :term");

        $stmt->bindValue(':term', '%' . $term . '%', PDO::PARAM_STR);

        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

        return $results == false? false:$results['Search_ID'];
    }
    function addSearchTerm($term)
    {
        global $db;

        $results = 'Data NOT Added';
        $tagID = getSearchIdByTerm($term);
        
        if($tagID == false)//If tag not found add it
        {
            $stmt = $db->prepare("INSERT INTO searches SET Term = :term");

            $stmt -> bindValue(':term', $term);

            if ($stmt->execute() && $stmt->rowCount() > 0) 
                $results = 'Data Added';
            else
                $results = 'Data failed to add';
        }
        else
        {
            incrementSearchID($tagID);//if tag exists increment it by 1
            $results = 'Tag Counter Incremented';
        }
        return ($results);
    }
    function getAllMessageThreadsForUser($userID)
    {
        global $db;

        $string = "SELECT DISTINCT Thread_ID FROM adminmessage WHERE Sender_ID = :userID OR Recipient_ID = :userIDTwo";
        //get connected ItemReviews
        $stmt = $db->prepare($string);
        $stmt->bindValue(':userID', $userID);
        $stmt->bindValue(':userIDTwo', $userID);

        $stmt->execute();
        $results = $stmt->fetchALL(PDO::FETCH_ASSOC);

        return $results;
    }
    function getAllMessagesInThread($threadID)
    {
        global $db;

        $string = "SELECT * FROM adminmessage WHERE Thread_ID = :threadID ORDER BY TimeSent ASC";
        //get connected ItemReviews
        $stmt = $db->prepare($string);
        $stmt->bindValue(':threadID', $threadID);

        $stmt->execute();
        $results = $stmt->fetchALL(PDO::FETCH_ASSOC);   

        $returnArray = array();

        foreach($results as $result)
        {
            array_push($returnArray, $result);
        }

        return $returnArray;
    }       
    //Might not be needed
    function getNewMessageInThread($threadID)
    {
        global $db;

        $string = "SELECT * FROM adminmessage WHERE Thread_ID = :threadID ORDER BY TimeSent DESC LIMIT 1;";
        //get connected ItemReviews
        $stmt = $db->prepare($string);
        $stmt->bindValue(':threadID', $threadID);

        $stmt->execute();
        $results = $stmt->fetchALL(PDO::FETCH_ASSOC);   

        return $results;
    }
    function getAllAdminIDs()
    {
        global $db;

        $string = "SELECT User_ID FROM rusers WHERE Admin = 1";
        //get connected ItemReviews
        $stmt = $db->prepare($string);

        $stmt->execute();
        $results = $stmt->fetchALL(PDO::FETCH_ASSOC);   

        return $results;
    }
    /*
        #############################################################################################
        These would have been in fucntions.php but to avoid recursive includes I put them here
        Everything below this comment references above functions and does not access SQL table itself
        #############################################################################################
    */

    //Returns -1 if no results
    function calculateItemStarRating($itemID)
    {
        $itemReviews = getAllReviewsForItem($itemID);
        
        $count = 0;
        $star = 0;

        foreach($itemReviews as $itemReview)
        {
            $count++;
            $star += $itemReview['Star_lvl'];
        }
        if($count == 0)
            return -1;
        else
        {
            $star /= $count;
            return $star;
        }
    }
    //Returns -1 if no results
    function calculateRestaurantStarRating($restaurantID)
    {
        $resReviews = getAllReviewsForRestaurant($restaurantID);
        
        $count = 0;
        $star = 0;

        foreach($resReviews as $resReview)
        {
            $count++;
            $star += $resReview['Star_lvl'];
        }
        if($count == 0)
            return -1;
        else
        {
            $star /= $count;
            return $star;
        }
    }
    function calculateAvgStarRatingFromUser($userID)
    {
        $resReviews = getAllResReviewsByUser($userID);
        $itemReviews = [];

        $count = 0;
        $star = 0;

        foreach($resReviews as $resReview)
        {
            $count++;
            $star += $resReview['Star_lvl'];
            $temp = getItemsInRestaurantReview($resReview['ResReview_ID']);
            foreach($temp as $itemReview)
                array_push($itemReviews, $itemReview);
        }
        foreach($itemReviews as $itemReview)
        {
            $count++;
            $star += $itemReview['Star_lvl'];
        }
        $star = isset($star)? $star : 0;
        $star /= $count > 0 ? $count : 1;
        return $star;
    }
    function getCommonItemCategories($itemID, $numCategories)
    {
        global $db;
        $stmt = $db->prepare("SELECT * FROM tags WHERE Item_ID = :itemID AND Counter >= 1 LIMIT :num");

        $stmt->bindValue(':num', $numCategories, PDO::PARAM_STR);
        $stmt->bindValue(':itemID', $itemID, PDO::PARAM_STR);

        $stmt->execute();
        $results = $stmt->fetchALL(PDO::FETCH_ASSOC);

        return $results;
    }
    function getCommonRestaurantCategories($restaurantID, $numCategories)
    {
        global $db;
        $stmt = $db->prepare("SELECT * FROM tags WHERE Restaurant_ID = :restaurantID AND Counter >= 1 LIMIT :num");
        $stmt->bindValue(':num', $numCategories, PDO::PARAM_STR);
        $stmt->bindValue(':restaurantID', $restaurantID, PDO::PARAM_STR);

        $stmt->execute();
        $results = $stmt->fetchALL(PDO::FETCH_ASSOC);

        return $results;
    }
    function getMostCommonCategoriesAllItems($numCategories)
    {
        global $db;
        $stmt = $db->prepare("SELECT * FROM tags WHERE Restaurant_ID IS NULL AND Active = 1 AND Counter >= 1 ORDER BY Counter DESC LIMIT :num");

        $stmt->bindValue(':num', $numCategories, PDO::PARAM_STR);

        $stmt->execute();
        $results = $stmt->fetchALL(PDO::FETCH_ASSOC);

        return $results;
    }
    
    function getMostRecentReviewsByUser($userID, $numReviews)
    {
        $resReviews = getAllResReviewsByUserChronological($userID, $numReviews, True);
        return array_slice($resReviews, 0,  $numReviews <= count($resReviews)? $numReviews : count($resReviews));   
    }
    function getMostRecentReviewsByRestaurant($restaurantID, $numReviews)
    {
        $resReviews = getAllReviewsForRestaurantChronological($restaurantID, $numReviews, True);
        return array_slice($resReviews, 0,  $numReviews <= count($resReviews)? $numReviews : count($resReviews));   
    }
    function getMostRecentReviewsByItem($itemID, $numReviews)
    {
        $itemReviews = getAllReviewsForItemChronological($itemID, $numReviews, True);
        return array_slice($itemReviews, 0,  $numReviews <= count($itemReviews)? $numReviews : count($itemReviews));   
    }
    function isPostRequest(){
        return (filter_input(INPUT_SERVER, 'REQUEST_METHOD')=== 'POST');
    }

    




    function searchResName($totalSearch){
        global $db;
        //get connected ItemReviews
        $stmt = $db->prepare("SELECT * FROM restaurant WHERE Restaurant_Name LIKE :totalSearch;");
        $stmt->bindValue(':totalSearch', '%'.$totalSearch.'%');
        if ($stmt->execute() && $stmt->rowCount() > 0) {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        else{
            $results='';
        }
        return $results;
}

    function searchMenuItems($totalSearch){
        global $db;

        $stmt= $db->prepare("SELECT * FROM menuitem WHERE ItemName LIKE :totalSearch;");
        $stmt->bindValue(':totalSearch', '%'.$totalSearch.'%');
        if ($stmt->execute() && $stmt->rowCount()>0){
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        else{
            $results='';
        }
        return $results;
}


// ADDING IN FIND PICTURE FUNCTIONALITY FROM REVIEWS TABLE

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
//Takes the array of tag results and returns an array of names to implode
function extractNames($tagsArray)
{
    $returnArray = array();
    foreach($tagsArray as $tag)
        array_push($returnArray, $tag['Name']);
    return $returnArray;
}


function checkResOwnerLogin($userID)
{
    global $db;
    $stmt = $db->prepare("SELECT ResOwner FROM rusers WHERE User_ID=:userID ");

    $stmt->bindValue(':userID', $userID);
    
    $stmt->execute ();

    return( $stmt->rowCount() > 0);
}

function findOwnedRes($userID){
    global $db;
    $stmt = $db->prepare("SELECT * FROM restaurant WHERE ResOwnerID = :userID");

    $stmt->bindValue(':userID', $userID);

    $stmt->execute();

    if ($stmt->execute() && $stmt->rowCount()>0){
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    else{
        $results=false;
    }
    return $results;
}

function addResponse($response, $resReviewID){
    global $db;
    $stmt = $db->prepare("UPDATE restaurantreview SET Response = :Response WHERE ResReview_ID = :resReviewID");

    $stmt->bindValue(':Response', $response);
    $stmt->bindValue(':resReviewID', $resReviewID);

    $stmt->execute();
}

function getResReviewID($res_ID)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM restaurantreview WHERE Restaurant_ID =:ID");

    $stmt->bindValue(':ID', $res_ID);

    $stmt->execute();
    if ($stmt->execute() && $stmt->rowCount()>0){
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    else{
        $results=false;
    }
    return $results;
}

function findReview($resReview_ID){
    global $db;
    $stmt = $db->prepare("SELECT * FROM restaurantreview WHERE ResReview_ID = :resRevID");

    $stmt->bindValue(':resRevID', $resReview_ID);

    $stmt->execute();
    if ($stmt->execute() && $stmt->rowCount()>0){
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    else{
        $results=false;
    }
    return $results;
}

function getItemsByResID($res_ID)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM menuitem WHERE Restaurant_ID =:res_ID");

    $stmt->bindValue(':res_ID', $res_ID);

    $stmt->execute();
    if ($stmt->execute() && $stmt->rowCount()>0){
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    else{
        $results=false;
    }
    return $results;
}

function getMostPopularSearches($num){
    global $db;
        $stmt = $db->prepare("SELECT DISTINCT Term FROM searches ORDER BY Counter DESC LIMIT :num");

        $stmt->bindValue(':num', $num, PDO::PARAM_STR);


        $stmt->execute();
        $results = $stmt->fetchALL(PDO::FETCH_ASSOC);

        return $results;
}
function getReviewPictures($id){
    global $db;
    $sql = "SELECT rimage FROM review WHERE Review_ID=:id";
    $query = $db->prepare($sql);
    $query->execute(array(':id' => $id));

    $query->bindColumn(1, $rimage, PDO::PARAM_LOB);
    $query->fetch(PDO::FETCH_BOUND);
    //header("Content-Type: image");
    echo '<img style = "width:30%; height:100%; padding-right:5%" src="data:image/png;base64,'.base64_encode($rimage).'"/>';
}

function getResReviewPictures($id){
    global $db;
    $sql = "SELECT ResImage FROM restaurantreview WHERE ResReview_ID=:id";
    $query = $db->prepare($sql);
    $query->execute(array(':id' => $id));

    $query->bindColumn(1, $rimage, PDO::PARAM_LOB);
    $query->fetch(PDO::FETCH_BOUND);
    //header("Content-Type: image");
    echo '<img class="mr-3 align-self-top" style="height: auto; width: 25%;" src="data:image/png;base64,'.base64_encode($rimage).'" alt="img">';
}


function getRecentResReviewPictures($id){
    global $db;
    $sql = "SELECT ResImage FROM restaurantreview WHERE Restaurant_ID = :id ORDER BY RAND() DESC LIMIT 1";
    $query = $db->prepare($sql);
    $query->execute(array(':id' => $id));

    $query->bindColumn(1, $rimage, PDO::PARAM_LOB);
    $query->fetch(PDO::FETCH_BOUND);
    //header("Content-Type: image");
    echo '<img class="mr-3 align-self-top" style="height: auto; width: 30%;" src="data:image/png;base64,'.base64_encode($rimage).'" alt="img">';
}

function getRecentReviewPictures($id){
    global $db;
    $sql = "SELECT rimage FROM review WHERE Item_ID = :id ORDER BY RAND() DESC LIMIT 1";
    $query = $db->prepare($sql);
    $query->execute(array(':id' => $id));

    $query->bindColumn(1, $rimage, PDO::PARAM_LOB);
    $query->fetch(PDO::FETCH_BOUND);
    //header("Content-Type: image");
    echo '<img class="mr-3 align-self-top" style="height: auto; width: 30%;" src="data:image/png;base64,'.base64_encode($rimage).'" alt="img">';
}



//$pic=getReviewPictures(2);
//var_dump($pic);
//getReviewPictures(1);
//var_dump($pic);

################################################
#
# AJAX Stuff
#
#################################################
function getMatchingSearchTerms($term)
{
    global $db;
    $sql = "SELECT Term FROM searches WHERE Term LIKE :term LIMIT 8";
    $stmt = $db->query($sql);

    $stmt->bindValue(':term', $term);

    $terms = $stmt-> fetchAll(PDO::FETCH_ASSOC);
    $results = array();

    foreach ($terms as $term)
    {
        array_push($results, $term['Term']);
    }
    return json_encode($results);
}
function getRecentMessageRespondingTo($id)
{
    global $db;
    //$stmt = $db->prepare("SELECT * FROM adminmessage WHERE RespondingTo_ID = 17");
    $stmt = $db->prepare("SELECT * FROM adminmessage WHERE RespondingTo_ID = :id LIMIT 1");

    $stmt->bindValue(':id', $id);
    $stmt->execute();
    //should only be one
    $message = $stmt-> fetchAll(PDO::FETCH_ASSOC);

    $message[0]['SenderUsername'] = getUsername($message[0]['Sender_ID']);

    return json_encode($message);
}
function addMessage($threadID, $respondingToID, $senderID, $recipientID, $message, $topic)
{
    global $db;

    if($threadID == NULL)
    {
        $stmt = $db->prepare("SELECT MAX(Thread_ID) FROM adminmessage;");
        $stmt->execute();
        $threadID = ($stmt-> fetch(PDO::FETCH_ASSOC))['MAX(Thread_ID)'];
        $threadID = $threadID == NULL ? 1 : $threadID + 1;
    }

    $results = 'Data NOT Added';
    $str = "INSERT INTO adminmessage SET Thread_ID = :threadID, Sender_ID = :senderID, Recipient_ID = :recipientID, Message = :message, TimeSent = :timeSent, Topic = :topic";
    $str .= $respondingToID == NULL ? ";" : ", RespondingTo_ID = :respondingToID;";
    $stmt = $db->prepare($str);

    $stmt -> bindValue(':threadID', $threadID);
    if($respondingToID != NULL)
        $stmt -> bindValue(':respondingToID', $respondingToID);
    $stmt -> bindValue(':senderID', $senderID);
    $stmt -> bindValue(':recipientID', $recipientID);
    $stmt -> bindValue(':message', $message);
    $timeSent = date('Y-m-d H:i:s');
    $stmt -> bindValue(':timeSent', date('Y-m-d H:i:s'));
    $stmt -> bindValue(':topic', $topic);
    
    if ($stmt->execute() && $stmt->rowCount() > 0) 
    {
        $results = 'Data Added';
    }
    return json_encode($timeSent);
}






