<?php
session_start();
define('DB_HOST', 'localhost');
define('DB_NAME', '2300project3');
define('DB_USER', 'root');
define('DB_PASSWORD', 'takeaway');
/**
define('DB_HOST', 'localhost');
define('DB_NAME', 'info230_SP15_brd55sp15');
define('DB_USER', 'brd55sp15');
define('DB_PASSWORD', 'Kalamazoo');
*/
global $user;
if(!empty($_SESSION['logged_user'])){
	$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
	$user = $mysqli->query("SELECT * FROM users WHERE username = '" . $_SESSION['logged_user'] . "'")->fetch_assoc();
}