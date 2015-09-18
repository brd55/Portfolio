<?php 
require_once 'resources/config.php';
if(!empty($_SESSION['logged_user'])){
	unset($_SESSION['logged_user']);
}
$user;
header('Location:index.php');