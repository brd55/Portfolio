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
//array for php errors
$errors = array();
$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
$results = $mysqli->query("SELECT * FROM Images");
$album = filter_input( INPUT_POST, 'albumID', FILTER_SANITIZE_STRING);
$name = filter_input(INPUT_POST, 'newName', FILTER_SANITIZE_STRING);
$id = (isset($_POST['images'])?filter_var_array($_POST['images'], FILTER_SANITIZE_STRING):'');
$success = false;
//checks that user has submit changes
if(isset($_POST['submit'])){
	//checks if user is deleting album.  if not, verifies that an album name has been given
	if(empty($_POST['delete'])){
		if(!empty($name)){
			while($result = $results->fetch_assoc()){
				//checks if a given image is checked
				if(isset($id[$result['imageID']])) {
					//checks if image is already in album
					if($mysqli->query("SELECT * from imagesalbums WHERE imageID = '" . $id[$result['imageID']] . "' AND albumID = '" . $album . "'")->num_rows == 0){
						$success = ($mysqli->query("INSERT INTO imagesalbums (albumID, imageID) VALUES ($album," . $id[$result['imageID']] . ")"));
					}
				}
				//if image isn't checked, we can clear the entry for it in this album, if it exists
				else{
					$success = ($mysqli->query("DELETE FROM imagesalbums WHERE albumID = $album AND imageID = " . $result['imageID']));
				}
			}
			//updates albums dateMod if a change has been made
			if($success){
				$mysqli->query("UPDATE Albums SET albumName = '$name', dateMod = '" . date('Y-m-d') . "' WHERE albumID = $album");
			}
		}
		else{
			$errors[] = "Album must have a name";
		}
	}
	//deletes entire album
	else {
		$mysqli->query("DELETE FROM imagesalbums WHERE albumID = $album");
		$mysqli->query("DELETE FROM Albums WHERE albumID = $album");
	}
}

?>
<!DOCTYPE html>
<html>
<head>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script>
	var request;
		//Ajax search function
		function filter(table){
			var id = $('#dropdown').val();
			var dataToSend= { 'table' : table,
				'id': id};
			request = $.ajax({
				url:"filter.php",
				type: "post",
				data:dataToSend,
				dataType: "json"
				});
			//on successful query
			request.done(function(d){
				//generates album information
				$('#albumName').show().html(d.album.name);
				$('#newName').val(d.album.name)
				$('.hidden').show();
				$('#delete').val(d.album.id);
				$('#albumID').val(d.album.id);
				$('#created').text('Created on: ' + d.album.Cr);
				$('#modified').text('Last Modified: ' + d.album.Mod);
				//albumName has to be set at this point, so we can clear any "Required!" message
				$('#messageNewName').html('');
				//whether entry is first or last in row
				var left = true;
				//information to be stored in search table
				var table;
				//generates a table with each image in database
				//any image already in the album is set to checked by default
				$.each(d.img, function(e,f){
					if(left){
						table += "<tr><td><text style='font-weight: bold;'>" + f['title'] + "</text>"
						+ "<input type='checkbox' value='" + e + "' name='images[" + e + "]' id='image' " + (f['inAlbum']?"checked":"") + "/></td>";
					}
					else{
						table += "<td><text style='font-weight: bold;'>" + f['title'] + "</text>"
						+ "<input type='checkbox' value='" + e + "' name='images[" + e + "]' id='image' " + (f['inAlbum']?"checked":"") + "/></td></tr>";
					}
					left = !left;
				});
				$('.search').html(table);
			});
		}
		//Checks if album name is set or if album is being deleted
		function validAll(){
			if(($('#delete').is(":checked")) || validNewName()){
				return true;
			}
			$('#errorDiv').html('Album Title is required');
			return false
		}
		//checks if abum name is set
		function validNewName(){
			var newName = $('#newName').val().trim();
			if(newName.length == 0){
				$('#messageNewName').html('Required!');
				return false;
			}
			$('#messageNewName').html('');
			return true;
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

<h1>Edit Albums</h1>

<div id="errorDiv">
<?php
	//outputs any php errors
	if(!empty($errors)){
		foreach($errors as $error){
			echo "$error <br />";
		}
	}
?>
</div>
<h3>Select an Album from the Drop-down Menu</h3>
<select onchange="filter('Albums'); $('#filler').hide();" id="dropdown">
	<option value="" id="filler">Select an Album</option>
	<?php
		//Shows all albums by default
		$results = $mysqli->query("SELECT * FROM Albums");
		while($result = $results->fetch_assoc()){
			echo "<option value='" . $result['albumID'] . "'>" . $result['albumName'] . "</option>";
		}

	?>
</select>
<h2 style="display: none;" id="albumName"></h2>
<form action="" method="post" class="hidden" enctype="multipart/form-data" onsubmit="return validAll()">
	<table class="centered">
		<tr>
			<td class="field" id="fieldNewName">
			<label for="newName">Change Album Title: </label>
			<input type="text" id="newName" name="newName" maxlength="32" oninput="validNewName()" />
			</td>
			<td class="message" id="messageNewName"></td>
		</tr>
	</table>
	<p id="created"></p>
	<p  id="modified"></p>
	<h3>Check all of the images you want to be in this album</h3>
	<!-- Table for form fields to be entered -->
	<table class="search">
	</table>
	<br />
	<text style="font-weight: bold;">Delete Album</text><input type="checkbox" value="" name="delete" id="delete">
	<br />
	<br />
	<input type="hidden" value="" id="albumID" name="albumID" />
	<input type="submit" value="Save Changes" name="submit" />
</form>
</div>

</body>
</html>