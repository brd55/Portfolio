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
$errors = array();
$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
//verifies that user has submitted data
if(isset($_POST['submit']) && isset($_POST['image'])){
	foreach($_POST['image'] as $image){
		//deletes image if delete is checked
		if(isset($_POST['delete'][$image])){
			$mysqli->query("DELETE FROM Images WHERE imageID = '$image'");
			$mysqli->query("DELETE FROM imagesalbums WHERE imageID = '$image'");
			unlink("images/" . $_POST['name'][$image]);
		}
		else{
			$imgTitle = filter_var($_POST['imageTitle'][$image], FILTER_SANITIZE_STRING);
			$imgCaption = filter_var($_POST['imageCaption'][$image], FILTER_SANITIZE_STRING);
			if(!empty($imgCaption) && !empty($imgTitle)){
				$mysqli->query("UPDATE Images SET imageTitle = '" . $imgTitle . "', imageCaption = '" . $imgCaption . "' WHERE imageID = " . $image);
			}
			//set php errors
			else {
				if(empty($imgCaption)){
					$errors[] = "Images must have a caption";
				}
				if(empty($imgTitle)){
					$errors[] = "Images must have a title";
				}
			}
		}
	}
}

?>
<!DOCTYPE html>
<html>
<head>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script>
	var request;
		//ajax query
		function filter(table){
			var caption = $('#filterCaption').val();
			var title = $('#filterTitle').val();
			var album = $('#filterAlbum').val();
			var dataToSend= { 'table' : table,
				'caption': caption,
				'title' : title,
				'album' : album};
			request = $.ajax({
				url:"filter.php",
				type: "post",
				data:dataToSend,
				dataType: "json"
				});
			//function executed on response
			request.done(function(d){
				var i = 1;
				var table;
				//generates each image with fields matching user input
				$.each(d, function(e,f){
					//determines if image is in beginning, end, or middle of table row
					if(i == 1){
						table += "<tr><td><img src='images/" + f['name'] + "' class='photothumb' /><br />"
						+ "<input type='checkbox' value='" + f['id'] + "' name='images[]' id='image' />" + f['title'] + "</td>";
						i++;
					}
					else if(i == 5){
						table += "<td><img src='images/" + f['name'] + "' class='photothumb' /><br />"
						+ "<input type='checkbox' value='" + f['id'] + "' name='images[]' id='image' />" + f['title'] + "</td></tr>";
						i = 1;
					}
					else{
						table += "<td><img src='images/" + f['name'] + "' class='photothumb' /><br />"
						+ "<input type='checkbox' value='" + f['id'] + "' name='images[]' id='image' />" + f['title'] + "</td>";
						i++;
					}
				});
				//case where search yields no matches
				if(jQuery.isEmptyObject(table)) {
					table = "<tr><td>No images found matching your search</td></tr>";
				}
				$('.search').html(table);
			});
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

<h1>Edit Images</h1>

<div>
<?php
	//generates fields for each image selected to edit
	if(isset($_POST['select']) && !empty($_POST['images'])){
		$ids = implode(',',$_POST['images']);
		$results = $mysqli->query("SELECT * FROM Images WHERE imageID IN (" . $ids . ")");
		echo "<form action='' method='post' enctype='multipart/form-data'>";
		while($result = $results->fetch_assoc()){
			echo "<div class='centered' style='text-align: left; min-height: 145px; width: 550px;'>
			<img src='images/" . $result['imageName'] . "' style='float: left;' class='photothumb'/>
			<p>&nbsp;&nbsp;<text style='font-weight: bold'>Image Title: </text><input type='text' name='imageTitle[" . $result['imageID'] . "]' value='" . $result['imageTitle'] . "' />
			<p>&nbsp;&nbsp;<text style='font-weight: bold'>Image Caption: </text><input type='text' name='imageCaption[" . $result['imageID'] . "]' value='" . $result['imageCaption'] . "' /></p>
			<p>&nbsp;&nbsp;<text style='font-weight: bold'>Uploaded: </text>" . $result['dateT'] . "</p>
			&nbsp;&nbsp;<text style='font-weight: bold'>Delete: </text><input type='checkbox' name='delete[" . $result['imageID'] . "]' value='" . $result['imageID'] . "' />
			<input type='hidden' name='image[]' value='" . $result['imageID'] . "' />
			<input type='hidden' name='name[" . $result['imageID'] . "]' value='" . $result['imageName'] . "' />
			</div>";
		}
		echo "<input type='submit' value='Save Changes' name='submit' /></form>";
	}
?>
<div id="errorDiv">
<?php
	//outputs php errors
	if(!empty($errors)){
		foreach($errors as $error){
			echo "$error <br />";
		}
	}
?>
</div>
<h3>Filter Images</h3>
<form action="" method="post" enctype="multipart/form-data">
	<label for="filterTitle">Filter by Title</label>
	<input type="text" id="filterTitle" name="filterTitle" oninput="filter('Images');"/> <br />
	<label for="filterCaption">Filter by Caption</label>
	<input type="text" id="filterCaption" name="filterCaption" oninput="filter('Images');"/> <br />
	<label for="filterAlbum">Filter by Album</label>
	<input type="text" id="filterAlbum" name="filterAlbum" oninput="filter('Images');"/> <br />
	<h3>Select Images to Edit</h3>
	<table class="search">
		<?php
		$results = $mysqli->query("SELECT * FROM Images");
		$i = 1;
		//outputs all images to start
		while($result = $results->fetch_assoc()){
			//determines if image is in beginning, end, or middle of table row
			if($i == 1){
				echo "<tr><td><img src='images/" . $result['imageName'] . "' class='photothumb' /><br />
					<input type='checkbox' value='" . $result['imageID'] . "' name='images[]' id='image' />" . $result['imageTitle'] . "</td>";
				$i++;
				}
			elseif($i == 5){
				echo "<td><img src='images/" . $result['imageName'] . "' class='photothumb' /><br />
					<input type='checkbox' value='" . $result['imageID'] . "' name='images[]' id='image' />" . $result['imageTitle'] . "</td></tr>";
				$i = 1;
			}
			else{
				echo "<td><img src='images/" . $result['imageName'] . "' class='photothumb' /><br />
					<input type='checkbox' value='" . $result['imageID'] . "' name='images[]' id='image' />" . $result['imageTitle'] . "</td>";
				$i++;
			}
		}

		?>
	</table>
	<br />
	<input type="submit" name="select" value="Edit Selected Images" />
</form>
</div>

</body>
</html>