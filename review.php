<?php
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

    $row = $result->fetch_assoc();
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'like') {
            $new_likes = intval($row['likes']) + 1;
            $res = $mysqli -> query("UPDATE sales SET likes=$new_likes WHERE sale_id=$sale_id");
        } else {
            $new_dislikes = intval($row['dislikes']) + 1;
            $res = $mysqli -> query("UPDATE sales SET dislikes=$new_dislikes WHERE sale_id=$sale_id");
        }
    } 
?>
<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.7.1.min.js"></script>
    

    <i onclick="clicklike(this)" class="fa fa-thumbs-up"></i>
    <i onclick="clickdislike(this)" class="fa fa-thumbs-down"></i>


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
                // console.log(result);
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
        <span><?php echo $row["likes"]; ?></span>

        <br>

        <label>Dislikes</label>
        <span><?php echo $row["dislikes"]; ?></span>
    </div>
</head>	
<body>
    
</body>
<html>