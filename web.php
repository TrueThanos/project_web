<?php error_reporting(0); ?> 
<?php
$a=$_GET['nm'];
$b=$_GET['pass'];
$c=$_GET['eml'];

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
	
		<div id="mapid"></div> 
	</div>
	<script type="module" src="map.js"></script>
</body>
</html>