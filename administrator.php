<?php error_reporting(0); ?> 
<?php
    $mysqli = new mysqli("localhost","root","","customer");

    // Check connection
    if ($mysqli -> connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
        exit();
    }

    if (isset($_POST['remove_product'])) {
        $id = $_POST['remove_product'];
        // Perform query
        $mysqli -> query("DELETE FROM products WHERE id=$id");
    }

    // Read json file of products
    $products_json = file_get_contents('products_prices.json');
    $products_decoded_json = json_decode($products_json, true);


    if (isset($_POST['add_product'])) {
        $id = $_POST['add_product'];
        // $key = array_search($id, $products_decoded_json['products']);
        $new_product = $products_decoded_json['products'][$id];
        // Perform query
        $id = $new_product['id'];
        $name = $new_product['name'];
        $category = $new_product['category'];
        $subcategory = $new_product['subcategory'];
        $mysqli -> query("INSERT INTO products VALUES ('$id','$name','$category','$subcategory')");

    }
    // Perform query
    $result = $mysqli -> query("SELECT * FROM products");
?>

<html>
<head>
    <title>Administrator</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="admin.css">
    
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	</script>
</head>	
<body>
    <div class="container">
        <h1>Remove product</h1> 
        <form method="POST">
            <label>Select a product to remove from DB</label>
            <select name="remove_product">
                <?php 
                foreach($result as $row)
                {
                    echo "<option value=".$row["id"].">".$row["name"]."</option>";
                }
                ?>
            </select>
            <br>
            <input type="submit" value="Remove the product" />
        </form>
    </div>
    <div class="container">   
        <h1>Add product</h1>
        <form method="POST">
            <label>Select a product to add to DB</label>
            <select name="add_product">
                <?php 
                foreach($products_decoded_json["products"] as $row)
                {
                    // we take id we show name
                    $found = false;
                    foreach ($result as $databaseObj) {
                        if ($databaseObj["id"] == $row["id"])
                            $found = true;
                    }
                    if ($found == false){
                        echo "<option value=".$row["id"].">".$row["name"]."</option>";
                    }    
                }
                ?>
            </select>
            <br>
            <input type="submit" value="Add the product" />
        </form>
    </div>
</body>
<html>