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
$results = $mysqli->query("SELECT * FROM Images");
//verifes that form has been submitted with an album name
if(isset($_POST['submit'])){
	$errors = array();
	if(!empty($_POST['albumName'])){
		$album = $_POST['albumName'];
		$sql = "INSERT INTO Albums (albumName) VALUES ('" . $album . "')";
		//tries to create album
		if($mysqli->query($sql)){
			$album = $mysqli->query("SELECT albumID FROM Albums ORDER BY albumID DESC LIMIT 1")->fetch_assoc();
			//if album successfully created, add any selected images to it
			if(isset($_POST['image'])){
				$images = $_POST['image'];
				foreach($images as $image){
					$sql = "INSERT INTO imagesalbums (albumID, imageID) VALUES (" . $album['albumID'] . "," . $image . ")";
					$mysqli->query($sql);
				}
			}
		}
	}
	else{
		$errors[] = "You must submit an album title";
	}
}
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

<h1>Create an Album</h1>
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
<h3>Type an album name in the field below, and check off any images you want to be a part of it.</h3>
<form action="" method="post">

<label for="albumName">Title</label>
<input type="text" id="albumName" name="albumName"/>
<br />

<?php
//displays a checkbox for each image
while($result = $results->fetch_assoc()){
	echo "<input type='checkbox' value='" . $result['imageID'] . "' name='image[]' id='image' />" . $result['imageName'] . "<br />";
}

?>
<input type="submit" name="submit" value="Submit" />
</form>
</div>

</body>
</html>