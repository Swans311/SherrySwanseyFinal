<?php
    include (__DIR__.'/NavBar.php');
    include (__DIR__. '/model/ModelReview.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gourmandize | Sign Up</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
    <script>
	    src="https://code.jquery.com/jquery-3.3.1.min.js"
	    integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous">
    </script>
</head>
<body>
    <div class="container gz-div-glow">
        <div class="container gz-div-inner d-flex justify-content-center mx-auto text-center py-5">
            <div class="py-3">
                <h1 class="glow text-white display-4" style="font-family: logoFont;">Gourmandize</h1>
                <hr/>
                <p>Gourmandize was created by three friends who all have a passion for eating. They always thought that something was missing when they went to look for food and only saw reviews for the restaurant as a whole. One day while Joseph Sherry (Chairman) ordered subpar French Fries, he knew that this would be the straw the broke the camel's back. A mere few weeks later is when he got together with his two vice-chairs, David Swansey and Casey Viens they came up with Gourmandize&trade; to never have this kind of experience again. </p>
                <h4 class="text-center">Local Cuisine</h4>
                <hr/>
                <form action="homepage.php" method="POST" style="width:50%;margin:auto;">
                    <table class="table-borderless">
                        <?php 
                            $Cat = getMostCommonCategoriesAllItems(6);
                            $rowCount = 0;
                            $i=0;
                            foreach($Cat as $cc):
                                if($rowCount == 0)
                                    echo '<tr>';
                                $rowCount ++;
                                $i++;
                                echo '<td style="padding:10px; width:30%; " class="col-md-4"><a href="SearchResults.php?type=food&categories='.$cc.'" class="btn btn-outline-light" style="min-height:75px;">Find ' . $cc . ' Nearby </a></td>';                                if($rowCount == 3)
                                {
                                    echo "</tr>";
                                    $rowCount = 0;
                                }
                            endforeach;
                            if($rowCount != 0)
                                echo "</tr>";
                        ?>
                    </table>
                </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
