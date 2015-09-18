<?php
require_once 'resources/config.php';

$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);

?>
<!DOCTYPE html>
<html>
<head>
<link href="gallery.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript" src="resources/gallery.js"></script>
<script>
	var request;
		//Ajax query
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
			//action once query is done
			request.done(function(d){
				var i = 1;
				var table;
				//displays all matching images
				$.each(d, function(e,f){
					//Determines if cell is at beginning, end, or middle of row
					if(i == 1){
						table += "<tr><td class='gallerythumb'><img src='images/" + f['name'] + "' class='photothumb' id='small" + e + "' alt='" + f['title'] + "' data-num='" + (e + 1) + "' data-caption='" + f['caption'] + "' /><br />"
						+ f['title'] + "</td>";
						i++;
					}
					else if(i == 5 || e == (Object.keys(d).length -1)){
						table += "<td class='gallerythumb'><img src='images/" + f['name'] + "' class='photothumb' id='small" + e + "' alt='" + f['title'] + "' data-num='" + (e + 1) + "' data-caption='" + f['caption'] + "' /><br />"
						+ f['title'] + "</td></tr>";
						i = 1;
					}
					else{
						table += "<td><img src='images/" + f['name'] + "' class='photothumb' id='small" + e + "' alt='" + f['title'] + "' data-num='" + (e + 1) + "' data-caption='" + f['caption'] + "' /><br />"
						+ f['title'] + "</td>";
						i++;
					}
				});
				//case where search yields no results
				if(jQuery.isEmptyObject(table)) {
					table = "<tr><td>No images found matching your search</td></tr>";
				}
				$('.search').html(table);
				//re-loads gallery function 
				imgGal();
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

<h1>Search Images</h1>

<div id="errorDiv">
<?php
	if(!empty($errors)){
		foreach($errors as $error){
			echo "$error <br />";
		}
	}
?>
</div>
<label for="filterTitle">Search by Title</label>
<input type="text" id="filterTitle" name="filterTitle" oninput="filter('Images');"/> <br />
<label for="filterCaption">Search by Caption</label>
<input type="text" id="filterCaption" name="filterCaption" oninput="filter('Images');"/> <br />
<label for="filterAlbum">Search by Album</label>
<input type="text" id="filterAlbum" name="filterAlbum" oninput="filter('Images');"/> <br />
<table class="search">
	<?php
	$results = $mysqli->query("SELECT * FROM Images");
	$imgCount = 1;
	$trCount = 1;
	//Displays images
	while($result = $results->fetch_assoc()){
		//Determines if cell is at beginning, end, or middle of row
		if($trCount == 1) {
			echo "<tr>
				<td class='gallerythumb'>
				<img src='images/" . $result['imageName'] . "' alt='" . $result['imageTitle'] . "' class='photothumb' id='small$imgCount' data-num='$imgCount' data-caption='" . $result['imageCaption'] . "'/>
				<br />" . $result['imageTitle'] . "</td>";
			$trCount++;
			$imgCount++;
		}
		else {
			if($trCount == 5 || $imgCount == $results->num_rows) {
				echo "<td class='gallerythumb'>
				<img src='images/" . $result['imageName'] . "' alt='" . $result['imageTitle'] . "' class='photothumb'id='small$imgCount' data-num='$imgCount' data-caption='" . $result['imageCaption'] . "' />
				<br />" . $result['imageTitle'] . "</td>
				</tr>";
				$trCount = 1;
				$imgCount++;
			}
			else {
				echo "<td class='gallerythumb'>
				<img src='images/" . $result['imageName'] . "' alt='" . $result['imageTitle'] . "' class='photothumb' id='small$imgCount' data-num='$imgCount' data-caption='" . $result['imageCaption'] . "' />
				<br />" . $result['imageTitle'] . "</td>";
				$trCount++;
				$imgCount++;
			}
		}
	}
	?>
</table>
<br />
</div>

</body>
</html>