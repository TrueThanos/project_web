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
    echo 'Review date: '.date("F Y",strtotime($date));

    if (isset($_POST['action']) && !$user_reviewed_already) {        
        // Find user that submitted the review
        $users = $mysqli -> query("SELECT * FROM customer WHERE email = '".$email."'");
        $user = $users->fetch_assoc();

        // Find tokens entry for this month
        $tokens = $mysqli -> query("SELECT * FROM customer_tokens WHERE email = '".$email."' AND date = '".$date."'");
        $token = $tokens->fetch_assoc();
        if (!$token) {
            $new_month_tokens = 0;
        } else {
            $new_month_tokens = intval($token['tokens']);
        }

        if ($_POST['action'] == 'like') {
            $new_tokens = intval($user['tokens']) + 5;
            $new_month_tokens = $new_month_tokens + 5;
            $likes = intval($likes) + 1;
            $res = $mysqli -> query("UPDATE sales SET likes=$likes,reviewers='$new_reviewers' WHERE sale_id=$sale_id");
        } else if ($_POST['action' == 'dislike']) {
            $new_tokens = min(0, intval($user['tokens']) -  1);
            $new_month_tokens = $new_month_tokens - 1;
            $dislikes = intval($dislikes) + 1;
            $res = $mysqli -> query("UPDATE sales SET dislikes=$dislikes,reviewers='$new_reviewers' WHERE sale_id=$sale_id");
        }
        $res_user_update = $mysqli -> query("UPDATE customer SET tokens=$new_tokens WHERE email='".$email."'");
        if ($token) {
            $res_token_update = $mysqli -> query("UPDATE customer_tokens SET tokens=$new_month_tokens WHERE email = '".$email."' AND date = '".$date."'");
        } else {
            $res_token_update = $mysqli -> query("INSERT INTO customer_tokens (tokens, date, email) VALUES ('$new_month_tokens','$date', '$email')");

        }
    } 
?>
<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.7.1.min.js"></script>
    
    <?php
    if ($user_reviewed_already) echo "User reviewed already";
    if (!$user_reviewed_already) echo "<i onclick='clicklike(this)' class='fa fa-thumbs-up '" .$extra_class . "></i>";
    if (!$user_reviewed_already) echo "<i onclick='clickdislike(this)' class='fa fa-thumbs-down '" .$extra_class . "></i>";
    ?>

    <script>
    function clicklike(x) {
        console.info('test clicked like');
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



    <title>Product Review</title>
    <div class='container'>
        <h2>Sale ID <?php echo $row["sale_id"]; ?></h2>

        <label>Product ID</label>
        <span><?php echo $row["product_id"]; ?></span>
        
        <br>

        <label>Super market ID</label>
        <span><?php echo $row["super_market_id"]; ?></span>

        <br>

        <label>Likes</label>
        <span><?php echo $likes; ?></span>

        <br>

        <label>Dislikes</label>
        <span><?php echo $dislikes; ?></span>
    </div>
</head>	
<body>
    
</body>
<html>