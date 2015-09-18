<?php session_start(); ?>
<?php
require_once 'config.php';
$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);

$item_id = filter_input(INPUT_POST, "item_id", FILTER_SANITIZE_STRING);
$new_like_num = 0;
$alreadyLoved = false;
$error = false;
//Initialize the $_SESSION varaible
if(!isset($_SESSION['loved_items'])){
	$_SESSION['loved_items'] = array();
} 

if (in_array($item_id, $_SESSION['loved_items'])){
	$alreadyLoved = true;
} else {
	$_SESSION['loved_items'][$item_id] = $item_id;
    
    $sql_1 = "SELECT like_num FROM item WHERE item_id = '$item_id';";
    $result_1 = $mysqli->query($sql_1);
    $row_1 = $result_1->fetch_assoc();
    $current_like_num = $row_1['like_num'];
    $new_like_num = $current_like_num + 1;
	
	$sql_2 = "UPDATE item SET like_num = '$new_like_num' WHERE item_id = '$item_id';";
	$result_2 = $mysqli->query($sql_2);
    
    if( !$result_2 ) {
		$error = true;
	}
}
$result = array('item_id' => $item_id, 'like_num' => $new_like_num, 'error' => $error, 'alreadyLoved' => $alreadyLoved);
	print(json_encode($result));
	die();
	