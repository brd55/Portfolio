<?php
require_once 'resources/config.php';
$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
$results = $mysqli->query("SELECT * FROM Albums");
?>
<!DOCTYPE html>
<html>
<head>
<link href="styles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script>
	var request;
		//ajax query
		function filter(table){
			var name = $('#filterName').val();
			var dataToSend= { 'table' : table,
				'album': name};
			request = $.ajax({
				url:"filter.php",
				type: "post",
				data:dataToSend,
				dataType: "json"
				});
			//function called on response
			request.done(function(d){
				var left = true;
				var table;
				//outputs each album matching the search
				$.each(d, function(e,f){
					//determines if entry is at the beginning or end of the table row
					if(left){
						table += "<tr><td class='link'><a href='albums.php?id=" + f['id'] + "' ><li>" + f['name'] + 
						"</a></li></td>";
					}
					else{
						table += "<td class='link'><a href='albums.php?id=" + f['id'] + "' ><li>" + f['name'] + 
						"</a></li></td></tr>";
					}
					left = !left;
				});
				//verifies that table row was properly closed
				if(!left){
					table += "<td class='link'></td></tr>";
				}
				//case if no albums match search
				if(jQuery.isEmptyObject(table)) {
					table = "<tr><td>No albums found matching your search</td></tr>";
				}
				$('.search').html(table);
			});
		}
</script>
</head>
<body>
<div id="main">

<img id="logo" src="images/logo.png" />

<?php
	require_once 'resources/navbar.php';
?>
<h1>Search Albums</h1>
<label for="filterName">Search by Title</label>
<input type="text" id="filterName" name="filterName" oninput="filter('Albums');"/> <br />
<h3>Click on an album link below to view that album</h3>
<ul>
<table id="albumlinks" class="search">
	<?php
	$left = true;
	//outputs all albums on start
	while($result = $results->fetch_assoc()){
		//determines if entry is at the beginning or end of the table row
		if($left){
			echo "<tr><td class='link'><li><a href='albums.php?id=" . $result['albumID'] . "'>" . $result['albumName'] . "</a></li></td>";
		}
		else{
			echo "<td class='link'><li><text><a href='albums.php?id=" . $result['albumID'] . "'>" . $result['albumName'] . "</a></text></li></td></tr>";
		}
		$left = !$left;
	}
	//verifies that table row was properly closed
	if(!$left){
		echo "<td></td></tr>";
	}

	?>
</table>
</ul>
</div>

</body>
</html>