<?php
require_once 'resources/config.php';
//redirects user if already logged in
if(!empty($_SESSION['logged_user'])){
	header('Location:index.php');
}
//checks if user submitted login form
if(isset($_POST['submit'])){
	$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
	$password =filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
	$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
	//gets user corresponding to provided username
	$results = $mysqli->query("SELECT * FROM users WHERE username = '$username'")->fetch_assoc();
	$errors = array();
	//error if provided username doesn't exist
	if(empty($results)){
		$errors[] = "Either the username you submitted doesn't exist or the password was incorrect";
	}
	//logs the user in if the password they provided matches the users password
	elseif($results['password'] == hash('sha256', $password . $results['salt'])){
		$_SESSION['logged_user'] = $results['username'];
		header('Location:index.php');
	}
	//error if incorrect password for username
	else{
		$errors[] = "Either the username you submitted doesn't exist or the password was incorrect";
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<link href="styles.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="main">

<img id="logo" src="images/logo.png" />

<?php
	require_once 'resources/navbar.php';
?>
<h3>Log in to the site</h3>
<?php 
//outputs php errors
if(!empty($errors)){
	foreach($errors as $error){
		echo $error . "<br />";
	}
}

?>
<form action="" method="post">
	<label for="username">Username: </label>
	<input type="text" name="username" id="username" />
	<br />
	<label for="password">Password: </label>
	<input type="password" name="password" id="password" />
	<br />
	<input type="submit" name="submit" id="submit" value="Login" />
</form>
</div>

</body>
</html>