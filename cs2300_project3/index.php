<?php
require_once 'resources/config.php';
$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
$results = $mysqli->query("SELECT * FROM Albums");
?>
<!DOCTYPE html>
<html>
<head>
<link href="styles.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="main">

<img id="logo" src="images/logo.png" />

<?php
	require_once 'resources/navbar.php';
?>
<h3>Click on an album link below to view that album</h3>
<ul>
<table id="albumlinks">
<?php
$left = true;
while($result = $results->fetch_assoc()){
	if($left){
		echo "<tr><td class='link'><li><a href='albums.php?id=" . $result['albumID'] . "'>" . $result['albumName'] . "</a></li></td>";
	}
	else{
		echo "<td class='link'><li><a href='albums.php?id=" . $result['albumID'] . "'>" . $result['albumName'] . "</a></li></td></tr>";
	}
	$left = !$left;
}
if(!$left){
	echo "<td></td></tr>";
}

?>
</table>
</ul>
</div>

</body>
</html>