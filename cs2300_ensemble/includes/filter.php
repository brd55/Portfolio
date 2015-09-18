<?php
require_once 'config.php';
session_start();
$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
$sql = "SELECT item_id FROM item";
$return_items = array();
$keyword;
	if(isset($_POST['key'])){
		$keyword = preg_replace('/"/', '\"', preg_replace("/'/", "\'", $_POST['key'], ENT_QUOTES));
	}
	if(!empty($keyword)) {
		$sql = "SELECT item_id FROM item WHERE name LIKE '%" . $keyword . "%' OR description LIKE '%" . $keyword . "%' OR gid IN (SELECT gid FROM gender WHERE name LIKE '%" . $keyword . "%') OR scid IN (SELECT scid FROM sub_category WHERE name LIKE '%" . $keyword . "%') OR season_id IN (SELECT season_id FROM season WHERE name LIKE '%" . $keyword . "%')";
	}
	if(!empty( $_POST['scid'])){
		$sql = "SELECT item_id FROM item WHERE scid='" . $_POST['scid'] . "' AND item_id IN (" . $sql . ")";
	}
	if(!empty( $_POST['gid'])){
		$sql = "SELECT item_id FROM item WHERE gid='" . $_POST['gid'] . "' AND item_id IN (" . $sql . ")";
	}
	if(!empty( $_POST['season_id'])){
		$sql = "SELECT item_id FROM item WHERE season_id='" . $_POST['season_id'] . "' AND item_id IN (" . $sql . ")";
	}
	$sql = "SELECT * FROM item WHERE item_id IN (" . $sql . ") ORDER by item_id DESC";
	$results = $mysqli->query($sql);
	if($results->num_rows > 0) {
		while ($result = $results->fetch_assoc()){
			$subCategory = $mysqli->query("SELECT name FROM sub_category WHERE scid=" . $result['scid'])->fetch_row();
			$gender = $mysqli->query("SELECT name FROM gender WHERE gid=" . $result['gid'])->fetch_row();
			$season = $mysqli->query("SELECT name FROM season WHERE season_id=" . $result['season_id'])->fetch_row();
			$result['scid'] = $subCategory;
			$result['gid'] = $gender;
			$result['season_id'] = $season;
			$result['description'] = htmlentities($result['description'], ENT_QUOTES);
			$result['alreadyLiked'] = isset($_SESSION['loved_items'][$result['item_id']]);
			$return_items[] = $result;
		}
	}


print(json_encode($return_items));
die();

?>