<?php
    $mysqli = new mysqli("localhost","root","","customer");

    // Check connection{
    if ($mysqli -> connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
        exit();
    }


    $sale_id = $_GET['sale_id'];
    $result = $mysqli->query("SELECT * FROM sales WHERE sale_id=$sale_id");

    $row = $result->fetch_assoc();
?>
<html>
<head>
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