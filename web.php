<?php error_reporting(0); ?> 
<?php

$mysqli = new mysqli("localhost","root","","customer");

// Check connection
if ($mysqli -> connect_errno) {
	echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
	exit();
}

// database for users
// test the login criteria
$users = $mysqli -> query("SELECT * FROM customer");

if (isset($_POST['name']) && isset($_POST['password']) && isset($_POST['email'])) {
	$name=$_POST['name'];
	$password=$_POST['password'];
	$email=$_POST['email'];	

	$found_user = false;
	foreach($users as $row) {
		if ($row['name'] == $name && $row['password'] == $password && $row['email'] == $email)
			$found_user = true;
			break;
	}
}



// Perform query
$sales = $mysqli -> query("SELECT * FROM sales");
$rows = array();
while ($r = mysqli_fetch_assoc($sales)) {
	$rows[] = $r;
}
$sales_json = json_encode($rows);
?>



<?php
// allow login in map only if the values exist in database
if ($found_user == true){
?>	
	<html>
	<head>
	<title>project 22-23</title>
		<link rel="stylesheet" href="https:/unpkg.com/leaflet@1.3.4/dist/leaflet.css"/>
		<link rel="stylesheet" href="map.css">
		
		<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"></script>

	</head>	
	<body>
		<div class='mp'>
			<input type='text' id='name-filter' class="filter" placeholder="Super markets filter by name">
			</input>
			<input type='text' id='product-filter' class="filter" placeholder="Products filter by name">
			</input>

			<div id="mapid"></div> 
		</div>
		<script type='text/javascript'>
			var sales = <?php echo($sales_json)?>;
			console.info({ sales });
		</script>
		<script type="module" src="map.js"></script>
	</body>
	</html>
<?php
}
else
	echo "Login Failed"	
?>