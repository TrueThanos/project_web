<?php
// Start the session
session_start();


$mysqli = new mysqli("localhost","root","","customer");

// Check connection
if ($mysqli -> connect_errno) {
	echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
	exit();
}

?>
<html>
    <?php
        $name =  $_REQUEST['name'];
        $password = $_REQUEST['password'];
        $email =  $_REQUEST['email'];
        $tokens = $_REQUEST['tokens'];
         
        // Performing insert query execution
        // here our table name is college
        $sql = "INSERT INTO customer VALUES ('$name',
            '$password','$email','$tokens')";
         
        if(mysqli_query($conn, $sql)){
            echo "<h3>data stored in a database successfully."
                . " Please browse your localhost php my admin"
                . " to view the updated data</h3>";
 
            echo nl2br("\n$name\n $password\n "
                . "$email\n $tokens\n");
        } else{
            echo "ERROR: Hush! Sorry $sql. "
                . mysqli_error($conn);
        }
         
        // Close connection
        mysqli_close($conn);
    ?>
</html>