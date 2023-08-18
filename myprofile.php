<?php
    $mysqli = new mysqli("localhost","root","","customer");
    session_start();

    // Check connection
    if ($mysqli -> connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
        exit();
    }

	$name = $_SESSION["name"];
	$password = $_SESSION["password"];

    if (isset($_POST['change'])) {
    	$_SESSION["name"]=$_POST['name'];
	    $_SESSION["password"]=$_POST['password'];
        $name = $_SESSION["name"];
        $password = $_SESSION["password"];
        $email = $_SESSION["email"];

        $rule = "UPDATE customer SET name='$name',password='$password' WHERE email='$email'";
        $res = $mysqli -> query($rule);
    }
?>

<html>
<head>
    <title>My profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="profile.css">
    
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	</script>
</head>	
<body>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/project_web/map.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">My profiles</li>
    </ol>
</nav>

<div class="card">
  <div class="card-body">
    <h3 class="card-title">Edit profile</h5>
    <form action="login.php" method="post"> 
        <div class="form-group">
            <label>Name</label>
            <input class="form-control"  name="name" placeholder="Enter name here" value="<?php echo $name;?>">
        </div>
        <div class="form-group">			
            <label>Password</label>
            <input class="form-control"  type="password" name="password" placeholder="Enter password here" value="<?php echo $password;?>">
        </div>
        <button class="btn btn-primary" type="submit" value="change" name="change">Submit changes</button>
    </form>
  </div>
</div>
</body>
<html>