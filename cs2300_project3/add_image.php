<?php
require_once 'resources/config.php';
//redirect to login if no user
if(empty($_SESSION['logged_user'])){
	header('Location:login.php');
}
//redirect to home if not admin
if(!($user['admin'])){
	header('Location:index.php');
}
$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
//Checks if there form is submitted and if each field is filled out
if(isset($_POST['submit']) && isset($_POST['imageTitle']) && isset($_POST['imageCaption'])){
	$errors = array();
	$imgTitle = filter_var($_POST['imageTitle'], FILTER_SANITIZE_STRING);
	if(empty($imgTitle)){
		$errors[] = 'You must submit an image title';
	}
	$imgCaption = filter_var($_POST['imageCaption'], FILTER_SANITIZE_STRING);
	if(empty($imgCaption)){
		$errors[] = 'You must submit an image caption';
	}
	$newImgs = $_FILES['image'];
	//php error array
	$success = false;
	//variables for easier access to file properties
	$oname = $newImgs['name'];
	$tname = $newImgs['tmp_name'];
	$error = $newImgs['error'];
	$type = $newImgs['type'];
	//checks if image name exists.  if it does, tries to add an increment to the file
	$nameCheck = $oname;
	$i = 2;
	while(mysqli_num_rows($mysqli->query("SELECT imageName FROM Images WHERE imageName = '" . $nameCheck . "'")) > 0){
		switch($type){
			case 'image/jpeg':
				$temp = explode ('.jpg', $oname);
				$nameCheck = $temp[0] . '_' . $i . '.jpg';
				break;
			case 'image/png':
				$temp = explode ('.png', $oname);
				$nameCheck = $temp[0] . '_' . $i . '.png';
				break;
			case 'image/gif':
				$temp = explode ('.gif', $oname);
				$nameCheck = $temp[0] . '_' . $i . '.gif';
				break;
		}
		$i++;
	}
	$oname = $nameCheck;
	//verifies there are no errors
	if($error == 0){
		if(empty($errors)){
			//if user named a new album to add their images to, creates one
			if(!empty($_POST['newAlbum'])){
				$album = filter_var($_POST['newAlbum'], FILTER_SANITIZE_STRING);
				$sql = "INSERT INTO Albums (albumName, dateCr, dateMod) VALUES ('" . $album . "', '" . date("Y-m-d") . "', '" . date("Y-m-d") . "')";
				if($mysqli->query($sql)){
					$album = $mysqli->query("SELECT albumID FROM Albums ORDER BY albumID DESC LIMIT 1")->fetch_assoc();
					//verifies album was successfully created
					$success = true;
				}
			}
			move_uploaded_file($tname, "images/$oname");
			$imgID;
			//creates new image, then gets its imageID
			$sql = "INSERT INTO Images (imageName, imageTitle, imageCaption, dateT) VALUES ('" . $oname . "', '" . $imgTitle . "', '" . $imgCaption . "', '" . date("Y-m-d") . "')";
			if($mysqli->query($sql)){
				$imgID = $mysqli->query("SELECT imageID FROM Images ORDER BY imageID DESC LIMIT 1")->fetch_assoc();
				$imgID = $imgID['imageID'];
			}
			//if album was successfully created, inserts image into it
			if($success){
					$sql = "INSERT INTO imagesalbums (albumID, imageID) VALUES (" . $album['albumID'] . "," . $imgID . ")";
					$mysqli->query($sql);
				}
			//if user added image to existing albums, creates corresponding entries for each in imagesalbums
			if(!empty($_POST['album'])){
				foreach($_POST['album'] as $album){
					$sql = "INSERT INTO imagesalbums (albumID, imageID) VALUES (" . $album . "," . $imgID . ")";
					if($mysqli->query($sql)){
						$mysqli->query("UPDATE Albums SET dateMod = '" . date('Y-m-d') . "' WHERE albumID = '" . $album . "'");
					}
				}
			}
		}
	}
	//elseif no longer does anything, but not bad to have around in case
	elseif($oname == null){
		
	}
	//if error uploading, generate error
	else{
		$errors[] = "Failed to successfully upload $oname";
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
				$('#errorDiv').text('You must submit an image');
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
	//displays all errors
	if(!empty($errors)){
		foreach($errors as $error){
			echo "$error <br />";
		}
	}
?>
</div>
<form action="" method="post" enctype="multipart/form-data" onsubmit="return input();">
	<label for="imageTitle">Image Title</label>
	<input type="text" id="imageTitle" name="imageTitle" maxlength="32" /> <br />
	<label for="imageCaption">Image Caption</label>
	<input type="text" id="imageCaption" name="imageCaption" size="50" maxlength="96"/> <br />
	<input type="file" name="image" id="image"/>
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