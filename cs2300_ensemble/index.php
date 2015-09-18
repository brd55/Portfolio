<!-- 
Change 4/15/15 by Brendan: Created document, linked stylesheet
Change 5/3/15 by Brendan: Added content with preliminary styles
Change 5/3/15 by Chenmin: Email signup function
Change 5/6/15 by Chenmin: Email signup function update (add source)
-->
<?php
session_start();
require_once 'includes/config.php';
$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
$results = $mysqli->query("SELECT * FROM item ORDER BY item_id DESC");
$subCategories = $mysqli->query("SELECT * FROM sub_category ORDER BY scid ASC");
$genders = $mysqli->query("SELECT * FROM gender ORDER BY gid ASC");
$seasons = $mysqli->query("SELECT * FROM season ORDER BY season_id ASC");
?>
<!DOCTYPE html>
<html>
<head>
<title>Ensemble</title>
<link href="CSS/styles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript" src="JavaScript/java.js"></script>
</head>
<body>
<?php
	include_once 'header.php';
	//Use a function to print the header, change the form action of the email bar to the current PHP file
	get_header("index.php");
	get_email_sign_up("ensemble");
	$keyword;
?>

<div class="centeredDiv" id="headerDiv">
<h1 class="journal">ensemble</h1>
</div>
<div class="centeredDiv" id="bodyDiv">
<!-- Filter Bar -->
	<div class="centeredDiv" id="filters">
		<input type="text" id="searchBar" name="searchBar" size="50" placeholder="Search Ensemble..." value=""/><br />
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
			</select><br /><br />
	</div>
<?php
//$_SESSION['loved_items'] = [];
	while($result = $results->fetch_assoc()){
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
				. (isset($_SESSION['loved_items'][$result['item_id']]) ? "" : "<span class='love' onclick='loveItem(" . $result['item_id'] . ", $(this))'><img src='pictures/love_arrow.gif' alt='Love this!'/>Love this!</span>") . "
				<span class='likeNum'>&nbsp;&nbsp;$likeNum " . ($likeNum == 1 ? "person":"people") . " love" . ($likeNum == 1 ? "s":"") . " this!</span>
			</div>";			
	}
?>
</div>
</body>
</html>