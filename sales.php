<?php
    $mysqli = new mysqli("localhost","root","","customer");

    // Check connection
    if ($mysqli -> connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
        exit();
    }

    // if we are in post request and we passed through url the product id then 
    if (isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];
        $price = $_POST['price'];
        $super_market_id = $_GET['super_market_id'];
        $current_date = date('Y-m-d H:i:s');

        $result = $mysqli->query("SELECT * FROM products WHERE id=$product_id");
        $row = $result->fetch_assoc(); 
        $category_id = $row['category']; 

        $res = $mysqli -> query("INSERT INTO sales (product_id, super_market_id, date, stock, price, likes, dislikes, category_id) VALUES ('$product_id','$super_market_id', '$current_date', 1, '$price', 0 , 0, '$category_id')");
    }  

?>
<html>
<head>
<title>Sales</title>
    <link rel="stylesheet" href="sales.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" crossorigin="anonymous">
</head>	
<body>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/project_web/map.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Sales</li>
        </ol>
    </nav>
    
    
    <div class="card">
        <div class="card-body">
            <h3 class="card-title">Add sale here</h3>
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
    </div>
</body>
</html>