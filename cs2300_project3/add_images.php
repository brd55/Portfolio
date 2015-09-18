<?php
require_once 'resources/config.php';
if(empty($_SESSION['logged_user'])){
	header('Location:index.php');
}
if(!($user['admin'])){
	header('Location:index.php');
}
$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
if(isset($_POST['submit'])){
	$newImgs = $_FILES['image'];
	$errors = array();
	$size = sizeof($newImgs['name']);
	$success = false;
	if(!empty($_POST['newAlbum'])){
		$album = $_POST['newAlbum'];
		$sql = "INSERT INTO Albums (albumName) VALUES ('" . $album . "')";
		if($mysqli->query($sql)){
			$album = $mysqli->query("SELECT albumID FROM Albums ORDER BY albumID DESC LIMIT 1")->fetch_assoc();
			$success = true;
		}
	}
	for($i = 0; $i < $size; $i++){
		$oname = $newImgs['name'][$i];
		$tname = $newImgs['tmp_name'][$i];
		$error = $newImgs['error'][$i];
		if($error == 0){
			move_uploaded_file($tname, "images/$oname");
			$imgID;
			$sql = "INSERT INTO Images (imageName) VALUES ('" . $oname . "')";
			if($mysqli->query($sql)){
				$imgID = $mysqli->query("SELECT imageID FROM Images ORDER BY imageID DESC LIMIT 1")->fetch_assoc();
				$imgID = $imgID['imageID'];
			}
			if($success){
					$sql = "INSERT INTO imagesalbums (albumID, imageID) VALUES (" . $album['albumID'] . "," . $imgID . ")";
					$mysqli->query($sql);
				}
			if(!empty($_POST['album'])){
				foreach($_POST['album'] as $album){
					$sql = "INSERT INTO imagesalbums (albumID, imageID) VALUES (" . $album . "," . $imgID . ")";
					$mysqli->query($sql);
				}
			}
		}
		elseif($oname == null){
			
		}
		else{
			$errors[] = "Failed to successfully upload $oname";
		}
	}
}

$results = $mysqli->query("SELECT * FROM Albums");
?>
<!DOCTYPE html>
<html>
<head>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script>
		function input(){
			var canSubmit = (!($('#image').val() == ""));
			if(!canSubmit){
				$('#errorDiv').text('You must submit at least one image');
			}
			return canSubmit;
		}

</script>
<link href="styles.css" rel="stylesheet" type="text/css" />
</head>
<body>

<div id="main">

<img id="logo" src="images/logo.png" />

<?php
	require_once 'resources/navbar.php';
?>

<h1>Upload Images</h1>

<div id="errorDiv">
<?php
	if(!empty($errors)){
		foreach($errors as $error){
			echo "$error <br />";
		}
	}
?>
</div>
<form action="" method="post" enctype="multipart/form-data" onsubmit="return input();">
<input type="file" name="image[]" id="image" multiple/>
<h3>Add Images to New Album</h3>

<label for="albumName">Album Title</label>
<input type="text" id="albumName" name="newAlbum"/>
<br />
<h3>Select Existing Albums to Add Images to</h3>
<?php

while($result = $results->fetch_assoc()){
	echo "<input type='checkbox' value='" . $result['albumID'] . "' name='album[]' id='album' />" . $result['albumName'] . "<br />";
}

?>
<br />
<input type="submit" name="submit" value="Submit" />
</form>
</div>

</body>
</html>