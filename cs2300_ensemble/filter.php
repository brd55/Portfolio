<?php
	require_once 'includes/config.php';
	$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
	$type = filter_input( INPUT_POST, 'type', FILTER_SANITIZE_STRING);
	$checks = filter_var_array($_POST['checks']);
	$response = array();
	$sql = "SELECT * FROM item WHERE ";
	if($type == 'checkbox') {
		$checksString = "gid=" . implode(" OR gid=", $checks);
		$sql .= $checksString;
	}
	$results = $mysqli->query($sql);
	while($result = $results->fetch_assoc()){
		$response[] = $result;
	}
	print(json_encode($response));
	die();