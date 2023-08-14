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

if (isset($_POST['login'])) {

	$found_user = false;
	foreach($users as $row) {
		if ($row['name'] == $_SESSION["name"] && $row['password'] == $_SESSION["password"] && $row['email'] == $_SESSION["email"]) {
			$found_user = true;
			break;
		}
	}
    if (!$found_user) {
        $errors[] = 'user with the inserted username or password does not exist';
    }

} else if (isset($_POST['signup'])) {
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
	
	if (!$errors) {
		echo "$pass => MATCH\n";
	}

	if ($found_user == false && !$errors) {
		$found_user = true;
		$res = $mysqli -> query("INSERT INTO customer (name, password, email) VALUES ('$name','$password', '$email')");
	} else {
		$errors[] = 'user with same username or password already exists';
	}

}

// allow login in map only if the values exist in database
if ($found_user == true && !$errors) {
	header("Location: http://localhost/project_web/map.php");
    exit();
}
?>
<html>
<head>
<title>project 22-23</title>
	<link rel="stylesheet" href="webstyle.css">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
</head>	
<body>
	<a class="admin_button" href="./administrator.php">admin</a>
	<div class="login">
		<form class="pizza" method="post"> 
			<h1>LOG IN</h1>
			<div>
				<label>Name</label>
				<input name="name" placeholder="Enter name here">	
			</div>
			<div>			
				<label>Password</label>
				<input type="password" name="password" placeholder="Enter password here">
			</div>
			<div>
				<label>Email</label>
				<input type="email" name="email" placeholder="Enter email here">
			</div>
            <?php
					//
					foreach($errors as $error)
					{
						echo "<div class='error'>" . $error . "</div>";
					}
					?>
			<button type="submit" value="login" name="login">LOG IN</button>
			<button type="submit" value="signup" name="signup">SIGN UP</button>
		</form>
	</div>		
</body>
<html>
<?php
?>