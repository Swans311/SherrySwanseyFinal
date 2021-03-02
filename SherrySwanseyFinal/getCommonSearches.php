<?php

include (__DIR__ . '/model/ModelReview.php');


echo json_encode(getMatchingSearchTerms($term));
?>