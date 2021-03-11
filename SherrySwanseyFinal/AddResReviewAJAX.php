<?php

include (__DIR__ . '/model/ModelReview.php');

$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

if ($contentType === "application/json") {
  //Receive the RAW post data.
  $content = trim(file_get_contents("php://input"));

  $decoded = json_decode($content, true);

  //If json_decode failed, the JSON is invalid.
  if( is_array($decoded)) {
     // echo json_encode($decoded['team_name']);
      $restaurantName = $decoded['RestaurantName'];
      $restaurantAddress = $decoded['RestaurantAddress'];
      $restaurantPhone = $decoded['RestaurantPhone'];
      $restaurantURL = $decoded['RestaurantURL'];
      $userID = $decoded['UserID'];
      $restaurantReview = $decoded['RestaurantReview'];
      $rating = $decoded['Rating'];
      $uNameVis = $decoded['Anonymous'] == true ? false : true;
      $imageFilePath = $decoded['ImageFilePath'];
      $itemReview2DList = $decoded['ItemReview2DList'];
      $categories = $decoded['Categories'];
      $results = addRestaurantReview($restaurantName, $restaurantAddress, $restaurantPhone, $restaurantURL, $userID, $restaurantReview, $rating,  $uNameVis, $imageFilePath, $itemReview2DList, $categories);
      echo json_encode ($results);
  } else {
    // Send error back to user.
  }
}

?>