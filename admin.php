<?php error_reporting(E_ALL); ?> 
<?php
    $mysqli = new mysqli("localhost","root","","customer");

    // Check connection
    if ($mysqli -> connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
        exit();
    }

    // Perform query
    if ($result = $mysqli -> query("SELECT * FROM categories")) {

        echo "Returned rows are: " . $result -> num_rows;
        // Free result set
        $result -> free_result();
    }

    $mysqli -> close();
?>