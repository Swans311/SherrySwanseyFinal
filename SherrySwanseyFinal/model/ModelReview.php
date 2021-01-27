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

        if ($stmt2->execute() && $stmt2->rowCount()==0 && $stmt3->execute() && $stmt3->rowCount()==0)
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
    function addItemReview($restaurantID, $userID, $itemID, $resReviewID, $dateTime, $categories, $rating, $review, $anonymous, $imageFilePath)
    {
        global $db;
        $results = 'Data NOT Added';
        $stmt = $db->prepare("INSERT INTO review SET Restaurant_ID = :restaurantID, User_ID = :userID, Item_ID = :itemID, Review = :review, Star_lvl = :rating, Username = :username, Uname_Visible = :visible, ReviewDate = :revDate, Category = :cat, ResReview_ID = :resRevID");

        $stmt->bindValue(':restaurantID', $restaurantID);
        $stmt->bindValue(':userID', $userID);
        $stmt->bindValue(':itemID', $itemID);
        $stmt->bindValue(':review', $review);
        $stmt->bindValue(':rating', $rating);
        $stmt->bindValue(':username', getUsername($userID));
        $stmt->bindValue(':visible', $anonymous);
        $stmt->bindValue(':revDate', $dateTime);
        $stmt->bindValue(':cat', $categories);
        $stmt->bindValue(':resRevID', $resReviewID);

        $stmt->execute ();
        return( $stmt->rowCount() > 0);
    }
    function addRestaurantReview($restaurantID, $userID, $restaurantReview, $rating,  $anonymous, $imageFilePath, $itemReview2DList, $categories)
    {
        global $db;
        $results = 'Data NOT Added';
        $stmt = $db->prepare("INSERT INTO restaurantreview SET Restaurant_ID = :restaurantID, User_ID = :userID, Review = :review, Star_lvl = :rating, UserName = :username, ReviewDate = :revDate, Visible = :visible, Category = :category");
        $stmt->bindValue(':restaurantID', $restaurantID);
        $stmt->bindValue(':userID', $userID);
        $stmt->bindValue(':review', $restaurantReview);
        $stmt->bindValue(':rating', $rating);
        $stmt->bindValue(':username', getUsername($userID));
        
        $time = date('Y-m-d H:i:s');

        $stmt->bindValue(':revDate', $time);
        $stmt->bindValue(':visible', $anonymous);
        $stmt->bindValue(':category', $categories);

        $stmt->execute();

        $success = $stmt->rowCount();

        //get resReviewID by searching table for match on restaurantID, userID, date
        $stmt = $db->prepare("SELECT ResReview_ID FROM restaurantreview WHERE Restaurant_ID = :resID AND User_ID = :userID AND ReviewDate = :revDate");
        $stmt->bindValue(':resID', $restaurantID);
        $stmt->bindValue(':userID', $userID);
        $stmt->bindValue(':revDate', $time);

        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        $resRevID = $results['ResReview_ID'];

        //loop throught list and call addItemReview()
        foreach($itemReview2DList as $itemReviewList)
        {
            addItemReview($restaurantID, $userID, $itemReviewList['itemID'], $resRevID, $time, $itemReviewList['category'], $itemReviewList['rating'], $itemReviewList['review'], $anonymous, ''/*$itemReviewList['imageFilePath']*/);
        }
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
        $categories = explode(',', $category);
        foreach($results as $result)
        {
            $commonResCats = getCommonRestaurantCategories($result['Restaurant_ID'], 5);
            foreach($commonResCats as $commonResCat)
                foreach($categories as $cat)
                    if($category == '' || strpos(strtoupper($commonResCat), strtoupper($cat)) !== false)
                        if(calculateRestaurantStarRating($result['Restaurant_ID']) >= $minRating)
                            if(!in_array($result, $returnArray))
                                array_push($returnArray, $result);
        }

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
                    if($category == '' || strpos(strtoupper($commonItemCat), strtoupper($cat)) !== false)
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
    function getAllReviewsForRestaurantChronological($restaurantID, $limit)
    {
        global $db;
        //get connected ItemReviews
        $stmt = $db->prepare("SELECT ResReview_ID FROM restaurantreview WHERE Restaurant_ID = :ID ORDER BY ReviewDate DESC LIMIT :Lim;");
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
    function getAllReviewsForItemChronological($itemID, $limit)
    {
        global $db;
        //get connected ItemReviews
        $stmt = $db->prepare("SELECT Review_ID FROM review WHERE Item_ID = :ID ORDER BY ReviewDate DESC LIMIT :Lim;");
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
    function getAllResReviewsByUserChronological($userID, $limit)
    {
        global $db;
        //get connected ItemReviews
        $stmt = $db->prepare("SELECT ResReview_ID FROM restaurantreview WHERE User_ID = :ID ORDER BY ReviewDate DESC LIMIT :Lim;");
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
    function editRestaurantReview( $resReviewID, $review, $rating, $anonymous, $imageFilePath)
    {
        global $db;

        $results = "Data NOT Updated";
        
        $stmt = $db->prepare("UPDATE reviews SET Star_lvl = :rating, Uname_Visible = :anon, Review = :review WHERE Review_ID=:id");
        
        $stmt->bindValue(':rating', $rating);
        $stmt->bindValue(':anon', $anonymous);
        $stmt->bindValue(':review', $review);
        $stmt->bindValue(':id', $resReviewID);

        if ($stmt->execute() && $stmt->rowCount() > 0) 
        {
            $results = 'Data Updated';
        }
        
        return ($results);
    }
    function editRestaurant( $restaurantID, $name, $address, $phone, $url, $categories)
    {
        global $db;

        $results = "Data NOT Updated";
        
        $stmt = $db->prepare("UPDATE restaurant SET Restaurant_Name = :resName, ResAddress = :addr, Phone = :phone, Restaurant_URL = :resURL, Category = :categories WHERE Review_ID=:id");
        
        $stmt->bindValue(':resName', $name);
        $stmt->bindValue(':addr', $address);
        $stmt->bindValue(':phone', $phone);
        $stmt->bindValue(':resURL', $url);
        $stmt->bindValue(':categories', $categories);
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

        $ReviewList = array();
        //loop through and append to list
        foreach($results as $result)
        {
            $reviewID = $result['Review_ID'];
            array_push($ReviewList, getReview($reviewID));
        }
        return $ReviewList;
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
        $star /= $count;
        return $star;
    }
    function getCommonItemCategories($itemID, $numCategories)
    {
        $itemReviews = getAllReviewsForItem($itemID);
        $itemCatArray = [];

        foreach($itemReviews as $itemReview)
        {
            $categories = explode(",",$itemReview['Category']);
            foreach($categories as $category)
                array_push($itemCatArray, $category);
        }
        //An array with the categories as labels and number of instances as values
        $countArray = array_count_values($itemCatArray);

        //Sorts array by most frequent first descending order
        arsort($countArray);

        //Slices the first $numCategories values and returns an array with the categories
        return array_keys(array_slice($countArray, 0, count($countArray) ? $numCategories : count($countArray)));
    }
    function getCommonRestaurantCategories($restaurantID, $numCategories)
    {
        $resReviews = getAllReviewsForRestaurant($restaurantID);
        $resCatArray = [];

        foreach($resReviews as $resReview)
        {
            $categories = explode(",",$resReview['Category']);
            foreach($categories as $category)
                array_push($resCatArray, $category);
        }
        //An array with the categories as labels and number of instances as values
        $countArray = array_count_values($resCatArray);

        //Sorts array by most frequent first descending order
        arsort($countArray);
        
        //Slices the first $numCategories values and returns an array with the categories
        return array_keys(array_slice($countArray, 0, $numCategories <= count($countArray) ? $numCategories : count($countArray)));
    }
    function getMostCommonCategoriesAllItems($numCategories)
    {
        $allItems = searchByItem("","", -1);
        //$itemReviews = [];
        $itemCatArray = [];

        foreach($allItems as $item)
        {
            $temp = getAllReviewsForItem($item['Item_ID']);
            foreach($temp as $review)
            {
                    $categories = explode(",", $review['Category']);
                    foreach($categories as $category)
                        array_push($itemCatArray, ucfirst(trim($category)));
            }
        }
        
        //An array with the categories as labels and number of instances as values
        $countArray = array_count_values($itemCatArray);

        //Sorts array by most frequent first descending order
        arsort($countArray);

        //Slices the first $numCategories values and returns an array with the categories
        return array_keys(array_slice($countArray, 0, $numCategories <= count($countArray) ? $numCategories : count($countArray)));
    }
    function getMostRecentReviewsByUser($userID, $numReviews)
    {
        $resReviews = getAllResReviewsByUserChronological($userID, $numReviews);
        return array_slice($resReviews, 0,  $numReviews <= count($resReviews)? $numReviews : count($resReviews));   
    }
    function getMostRecentReviewsByRestaurant($restaurantID, $numReviews)
    {
        $resReviews = getAllReviewsForRestaurantChronological($restaurantID, $numReviews);
        return array_slice($resReviews, 0,  $numReviews <= count($resReviews)? $numReviews : count($resReviews));   
    }
    function getMostRecentReviewsByItem($itemID, $numReviews)
    {
        $itemReviews = getAllReviewsForItemChronological($itemID, $numReviews);
        return array_slice($itemReviews, 0,  $numReviews <= count($itemReviews)? $numReviews : count($itemReviews));   
    }
    function isPostRequest(){
        return (filter_input(INPUT_SERVER, 'REQUEST_METHOD')=== 'POST');
    }


    function addpicture($Img, $Restaurant_ID, $Review_ID){
        $stmt = $db->prepare("INSERT INTO rimages SET Img = :Img, Restaurant_ID = :Restaurant_ID, Review_ID = :Review_ID");

            $stmt -> bindValue(':Img', $Img);
            $stmt -> bindValue(':Restaurant_ID', $Restaurant_ID);
            $stmt -> bindValue(':Review_ID', $Review_ID);


            if ($stmt->execute() && $stmt->rowCount() > 0) 
            {
                $results = 'Data Added';
            }
        return ($results);

    }