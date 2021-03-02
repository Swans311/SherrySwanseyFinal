<?php

include (__DIR__ . '/model/ModelReview.php');

//Pass ID of most recent message
echo json_encode(getRecentMessageRespondingTo($id));
?>