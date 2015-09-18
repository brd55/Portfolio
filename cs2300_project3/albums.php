<?php
require_once 'resources/config.php';
$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
$name;
$images;
$id = $_GET['id'];
//verifies that an album is provided and could exist
if(!empty($_GET) && is_numeric($id)){
	$albums = $mysqli->query("SELECT * FROM Albums WHERE albumID =" . $id);
	$albums = $albums->fetch_assoc();
	//checks that album exists
	if(sizeof($albums) > 0){
		$name = $albums['albumName'];
		//gets images in album
		$images = $mysqli->query("SELECT * FROM imagesalbums INNER JOIN Albums ON imagesalbums.albumID = Albums.albumID
			INNER JOIN Images ON imagesalbums.imageID = Images.imageID WHERE Albums.albumID = " . $id);
	}
	//redirects to home page if album doesn't exist
	else{
		header('Location:index.php');
	}
}
//redirects to home page if album isn't given or isn't numeric
else{
	header('Location:index.php');
}

?>
<!DOCTYPE html>
<html>
<head>
<link href="styles.css" rel="stylesheet" type="text/css" />
<link href="gallery.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript" src="resources/gallery.js"></script>
</head>
<body>

<div id="main">

<img id="logo" src="images/logo.png" />

<?php
	require_once 'resources/navbar.php';
?>
<h1>Album: <?php echo $name; ?></h1>
<table class="centered">
<?php
//outputs all images in album
$imgCount = 1;
$trCount = 1;
while($image = $images->fetch_assoc()){
	//determines if image is at beginning, end, or middle of table row
	if($trCount == 1) {
		echo "<tr>
			<td class='gallerythumb'>
			<img src='images/" . $image['imageName'] . "' alt='" . $image['imageTitle'] . "' class='photothumb' class='photothumb' id='small$imgCount' data-num='$imgCount' data-caption='" . $image['imageCaption'] . "' />
			</td>";
		$trCount++;
		$imgCount++;
	}
	else {
		if($trCount == 5 || $imgCount == $images->num_rows) {
			echo "<td class='gallerythumb'>
			<img src='images/" . $image['imageName'] . "' alt='" . $image['imageTitle'] . "' class='photothumb' class='photothumb' id='small$imgCount' data-num='$imgCount' data-caption='" . $image['imageCaption'] . "' />
			</td>
			</tr>";
			$trCount = 1;
			$imgCount++;
		}
		else {
			echo "<td class='gallerythumb'>
			<img src='images/" . $image['imageName'] . "' alt='" . $image['imageTitle'] . "' class='photothumb' class='photothumb' id='small$imgCount' data-num='$imgCount' data-caption='" . $image['imageCaption'] . "' />
			</td>";
			$trCount++;
			$imgCount++;
		}
	}
}

?>
</table>
</div>

</body>
</html>