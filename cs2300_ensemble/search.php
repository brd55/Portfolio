<?php
require_once 'includes/config.php';
$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
$sql = "SELECT i.item_id, i.name, i.description, i.URL, i.img_URL, i.like_num, i.date_posted, i.date_modified, i.scid, s.name as sub_category_name,
               i.gid, g.name as gender_name, i.season_id, se.name as season_name
        FROM item i INNER JOIN sub_category s ON i.scid = s.scid
                             INNER JOIN gender g ON g.gid = i.gid
                             INNER JOIN season se ON se.season_id = i.season_id
                             ORDER BY item_id DESC;";
$results = $mysqli->query($sql);
$subCategories = $mysqli->query("SELECT * FROM sub_category ORDER BY scid ASC");
$genders = $mysqli->query("SELECT * FROM gender ORDER BY gid ASC");
$seasons = $mysqli->query("SELECT * FROM season ORDER BY season_id ASC");
?>
<!DOCTYPE html>
<html>
<head>
<title>Ensemble - Search</title>
<link href="CSS/styles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript" src="JavaScript/java.js"></script>
</head>
<body>
<?php
	include_once 'header.php';
	//Use a function to print the header, change the form action of the email bar to the current PHP file
	get_header("search.php");
	get_email_sign_up("ensemble");
	if(isset( $_POST['searchSubmit'])){
		$keyword = filter_input(INPUT_POST, 'searchBar_main', FILTER_SANITIZE_STRING);   	
    } else if(isset( $_POST['search'])){
		$keyword = filter_input(INPUT_POST, 'searchBar', FILTER_SANITIZE_STRING);
	}
?>

<div class="centeredDiv" id="headerDiv">
<h1 class="journal">ensemble</h1>
</div>

<!-- Search Bar-->
<form action="search.php" method="POST" style="display: inline">
	<div class="centeredDiv" id="searchBar">
		<input type="text" name="searchBar" size="50" placeholder="Search Ensemble" value="<?php echo isset($keyword) ? $keyword : ''; ?>"/><input type="submit" name="search" value="Search"/>
	</div>


	<!-- Filter Bar -->
	<div class="centeredDiv" id="filters">
			<select name="scid" id="sub">
				<option id="scidDefault" value="">Filter by item type...</option>
				<?php
					while($subCategory = $subCategories->fetch_row()){
						echo "<option value ='" . $subCategory[0] . "'" . (isset($_POST['scid']) && $_POST['scid'] == $subCategory[0] ? " selected='selected'" : "") . ">" . $subCategory[1] . "</option>";
					}
				?>
			</select>
			<select name="season_id" id="season">
				<option  id="season_idDefault" value="">Filter by season...</option>
				<?php
					while($season = $seasons->fetch_row()){
						echo "<option value ='" . $season[0] . "'" . (isset($_POST['season_id']) && $_POST['season_id'] == $season[0] ? " selected='selected'" : "") . ">" . $season[1] . "</option>";
					}
				?>
			</select>
			<select name="gid" id="gender">
				<option id="gidDefault" value="">Filter by gender...</option>
				<?php
					while($gender = $genders->fetch_row()){
						echo "<option value ='" . $gender[0] . "'" . (isset($_POST['gid']) && $_POST['gid'] == $gender[0] ? " selected='selected'" : "") . ">" . $gender[1] . "</option>";
					}
				?>
			</select>
	</div>
</form>

<!-- Search Results -->
<div class="centeredDiv" id="bodyDiv">
<?php
	if(!empty($keyword)){
		echo "<input type='hidden' id='key' value = '".$keyword."' />";
		$empty = true;
		echo "<div id='results' class='contentDiv'>
				<h4>Showing search results for '".$keyword."':</h4>
			</div>";
		while($result = $results->fetch_assoc()){
			$gidMatch = ($_POST['gid'] == $result['gid'] || empty($_POST['gid']));
			$season_idMatch = ($_POST['season_id'] == $result['season_id'] || empty($_POST['season_id']));
			$scidMatch = ($_POST['scid'] == $result['scid'] || empty($_POST['scid']));
			if((stripos($result['name'], $keyword) !== false || stripos($result['description'], $keyword) !== false ||  stripos($result['sub_category_name'], $keyword) !== false || stripos($result['gender_name'], $keyword) !== false || stripos($result['season_name'], $keyword) !== false) && $gidMatch && $season_idMatch && $scidMatch){
				$subCategory = $mysqli->query("SELECT name FROM sub_category WHERE scid=" . $result['scid'])->fetch_row();
				$gender = $mysqli->query("SELECT name FROM gender WHERE gid=" . $result['gid'])->fetch_row();
				$season = $mysqli->query("SELECT name FROM season WHERE season_id=" . $result['season_id'])->fetch_row();
				$likeNum = $result['like_num'];
				echo "<div class='searchDiv contentDiv'>
					<a href='" . $result['URL'] . "' class='contentLink'>
						<span class='contentThumbSpan'><img src='" . $result['img_URL'] . "' class='contentThumb' alt='" . htmlentities($result['description'], ENT_QUOTES) . "' /><br /><br /></span>
						<h4 class='contentH4'>" . $result['name'] . " </h4>
						" . $result['description'] . " <br /><br />
						<span class='contentTags'>" . $subCategory[0] . " &nbsp;&nbsp;&nbsp; " . $gender[0] . " &nbsp;&nbsp;&nbsp; " . $season[0] . "</span>
					</a>&nbsp;&nbsp;"
					//Add like function to the span's onclick attribute
					. "<span class='love' onclick='loveItem(" . $result['item_id'] . ", $(this))'><img src='pictures/love_arrow.gif' alt='Love this!'/>Love this!</span>
					<span class='likeNum'>&nbsp;&nbsp;$likeNum " . ($likeNum == 1 ? "person":"people") . " love" . ($likeNum == 1 ? "s":"") . " this!</span>
				</div>";
				$empty = false;
			}
		}
		if($empty == true){
			echo "<div id='noResults' class='centeredDiv contentDiv'>
				Sorry, no search results were found.
				</div>";
		}
	} 
	else{
		echo "<div id='emptySearchPrompt' class='centeredDiv contentDiv'>
				Please enter a search term.
			</div>
			<input type='hidden' id='key' value='' />";
	}
?>
</div>
</body>
</html>