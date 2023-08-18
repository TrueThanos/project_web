<?php
    session_start();
    $mysqli = new mysqli("localhost","root","","customer");

    // Check connection{
    if ($mysqli -> connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
        exit();
    }

    $sale_id = $_REQUEST['sale_id'];
    $id=intval($sale_id);
    $result = $mysqli-> query("SELECT * FROM sales WHERE sale_id = '".$id."'");

    if (!$result) {
        echo("Error description: " . $mysqli -> error);
    }   
    
    // Check if user already reviewed
    $row = $result->fetch_assoc();
    $reviewers = $row['reviewers'];
    if ($reviewers == NULL) {
        $reviewers = "";
    }

    $email = $_SESSION["email"];
    $user_reviewed_already = False;
    if(strpos($reviewers, $email) !== false){
        $user_reviewed_already = True;
    }
    if ($user_reviewed_already) {
        $extra_class = " disabled";
    } else {
        $extra_class = " ";
    }

    $new_reviewers = $reviewers . "," .$email;
    $likes = $row['likes'];
    $dislikes = $row['dislikes'];
    $date = date("Y-01-m");

    if (isset($_POST['action']) && !$user_reviewed_already) {        
        // Find points entry for this month
        $points = $mysqli -> query("SELECT * FROM customer_points WHERE email = '".$email."' AND date = '".$date."'");
        $point = $points->fetch_assoc();
        if (!$point) {
            $new_month_points = 0;
        } else {
            $new_month_points = intval($point['points']);
        }

        if ($_POST['action'] == 'like') {
            $new_month_points = $new_month_points + 5;
            $likes = intval($likes) + 1;
            $res = $mysqli -> query("UPDATE sales SET likes=$likes,reviewers='$new_reviewers' WHERE sale_id=$sale_id");
        } else if ($_POST['action' == 'dislike']) {
            $new_month_points = $new_month_points - 1;
            $dislikes = intval($dislikes) + 1;
            $res = $mysqli -> query("UPDATE sales SET dislikes=$dislikes,reviewers='$new_reviewers' WHERE sale_id=$sale_id");
        }
        if ($point) {
            $res_point_update = $mysqli -> query("UPDATE customer_points SET points=$new_month_points WHERE email = '".$email."' AND date = '".$date."'");
        } else {
            $res_point_update = $mysqli -> query("INSERT INTO customer_points (points, date, email, tokens) VALUES ('$new_month_points','$date', '$email', '$tokens')");

        }
    } 
?>
<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.7.1.min.js"></script>
    <script>
    function clicklike(x) {
        $.ajax({
            error: function(errMsg) {
                console.info(errMsg);
            },
            type: "POST",
            url: "review.php",
            data: { action: "like", sale_id: '<?php echo($sale_id)?>'},
            success: function (result) {
                console.log(result);
            },
        });
        
    } 
    function clickdislike(x) {
        $.ajax({
            error: function(errMsg) {
                console.info(errMsg);
            },
            type: "POST",
            url: "review.php",
            data: { action: "dislike", sale_id: '<?php echo($sale_id)?>'},
            success: function (result) {
                // console.log(result);
            },
        });
        
    } 
    </script>

    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/project_web/map.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Review</li>
    </ol>
    </nav>
    <div class="card">
        <div class="card-body">
            <?php
            if ($user_reviewed_already) echo '<div class="alert alert-info" role="alert">User reviewed already</div>';
            if (!$user_reviewed_already) echo "<i onclick='clicklike(this)' class='fa fa-thumbs-up '" .$extra_class . "></i>";
            if (!$user_reviewed_already) echo "<i onclick='clickdislike(this)' class='fa fa-thumbs-down '" .$extra_class . "></i>";
            ?>
            
            <h3 class="card-title">Product review</h5>
            <h4 class="card-title">Sale ID <?php echo $row["sale_id"]; ?></h4>

            <label>Product ID</label>
            <span><?php echo $row["product_id"]; ?></span>
            
            <br>

            <label>Super market ID</label>
            <span><?php echo $row["super_market_id"]; ?></span>

            <br>

            <label>Total product likes</label>
            <span><?php echo $likes; ?></span>

            <br>

            <label>Total product dislikes</label>
            <span><?php echo $dislikes; ?></span>
        </div>
        </div>
    </div>
</head>	
<body>
    
</body>
<html>