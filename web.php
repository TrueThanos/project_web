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

$found_user = false;
$errors = array();

if (isset($_POST['type']) && $_POST['type'] == 'login') {

	$found_user = false;
	foreach($users as $row) {
		if ($row['name'] == $_SESSION["name"] && $row['password'] == $_SESSION["password"] && $row['email'] == $_SESSION["email"]) {
			$found_user = true;
			break;
		}
	}
	echo $found_user;
	echo $_SESSION['password'];
	echo $_SESSION['email'];
	echo $_SESSION['name'];

} else if (isset($_POST['type']) && $_POST['type'] == 'signup') {
	$name = $_POST['name'];
	$password = $_POST['password'];
	$email = $_POST['email'];

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
		$found_user = true;
		$res = $mysqli -> query("INSERT INTO customer (name, password, email) VALUES ('$name','$password', '$email')");
	} else {
		echo('user with same username or password already exists');
	}

}

// allow login in map only if the values exist in database
if ($found_user == true && !$errors) {
	header("Location: http://localhost/project_web/map.php");
}
else {
	echo "Login Failed";
}
?>