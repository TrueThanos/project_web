<?php
    $mysqli = new mysqli("localhost","root","","customer");

    // Check connection
    if ($mysqli -> connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
        exit();
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
			<form  action="map.php" method="post" >
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
?>