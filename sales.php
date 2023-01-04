<?php error_reporting(0); ?> 
<?php
    $mysqli = new mysqli("localhost","root","","customer");

    // Check connection
    if ($mysqli -> connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
        exit();
    }

    if (isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];
        $price = $_POST['price'];
        $super_market_id = $_GET['super_market_id'];
        $current_date = date('Y-m-d H:i:s');
        $res = $mysqli -> query("INSERT INTO sales VALUES ('$product_id','$super_market_id','$current_date',true,'$price', 0 , 0 )");
        $res->fetch_assoc();
    }  

?>
<html>
<head>
<title>Sales</title>
    <link rel="stylesheet" href="sales.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    

</head>	
<body>
<div class="container">
    <h2>Add sale here</h2>
    <form method="post">
        <div class="mb-3">
            <label for="product_id" class="form-label">Product ID</label>
            <input type="text" class="form-control" id="product_id" name="product_id">
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="text" class="form-control" id="price" name="price">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
</body>
</html>