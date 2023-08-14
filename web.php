<?php
// Start the session
session_start();
?>
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

// session helps us select something in all pages
if (isset($_POST['name']) && isset($_POST['password']) && isset($_POST['email'])) {
	$_SESSION["name"]=$_POST['name'];
	$_SESSION["password"]=$_POST['password'];
	$_SESSION["email"]=$_POST['email'];	
}

$errors = array();

if ($_POST['type'] == 'login') {

	$found_user = false;
	foreach($users as $row) {
		if ($row['name'] == $_SESSION["name"] && $row['password'] == $_SESSION["password"] && $row['email'] == $_SESSION["email"]) {
			$found_user = true;
			break;
		}
	}

	// Load sales table from database to php
	$sales = $mysqli -> query("SELECT * FROM sales");
	$rows = array();
	while ($r = mysqli_fetch_assoc($sales)) {
		$rows[] = $r;
	}
	$sales_json = json_encode($rows);

	$categories = $mysqli -> query("SELECT * FROM categories");
	$categories_ar = array();
	while ($r = mysqli_fetch_assoc($categories)) {
		$categories_ar[] = $r;
	}
	$categories_json = json_encode($categories_ar);
	$selected_category = ''; 

	if (isset($_POST['category']))
		$selected_category = $_POST['category'];

} else if ($_POST['type'] == 'signup') {
	$name = $_POST['name'];
	$password = $_POST['password'];
	$email = $_POST['email'];

	$found_user = false;
	foreach($users as $row) {
		if ($row['name'] == $name || $row['email'] == $email) {
			$found_user = true;
			break;
		}
	}

	if (strlen($password) < 8) {
		$errors[] = "Password should be min 8 characters";
	}
	if (!preg_match("/\d/", $password)) {
		$errors[] = "Password should contain at least one digit";
	}
	if (!preg_match("/[A-Z]/", $password)) {
		$errors[] = "Password should contain at least one Capital Letter";
	}
	if (!preg_match("/\W/", $password)) {
		$errors[] = "Password should contain at least one special character";
	}
	
	if ($errors) {
		foreach ($errors as $error) {
			echo $error . "\n";
		}
	} else {
		echo "$pass => MATCH\n";
	}


	if ($found_user == false && !$errors) {
		$res = $mysqli -> query("INSERT INTO customer (name, password, email) VALUES ('$name','$password', '$email')");
	} else {
		echo('user with same username or password already exists');
	}

}
?>
<?php
// allow login in map only if the values exist in database
if ($found_user == true && !$errors) {
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
			<form  action="web.php" method="post" >
				<select onchange="this.form.submit()" name="category">
					<option value=''>Select Category</option>
					<?php
					//
					foreach($categories_ar as $row)
					{
						$selected = ($selected_category ==  $row['id']) ? 'selected' : '';
						// print option with attribute selected if is chosen else print it without attribute selected
						echo "<option " .$selected. " value=" . $row['id'] . ">" . $row['name'] . "</option>";
					}
					?>
				</select>
			</form>
			<div id="mapid"></div> 
		</div>
		<script type='text/javascript'>
			// we pass the php variables to javascript
			var sales = '<?php echo($sales_json)?>';
			var categories = '<?php echo($categories_json)?>';
			//
			var selected_category = '<?php echo $selected_category?>';
		</script>
		<script type="module" src="map.js"></script>
	</body>
	</html>
<?php
}
else
	echo "Login Failed"	
?>