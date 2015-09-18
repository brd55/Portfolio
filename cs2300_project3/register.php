<?php
require_once 'resources/config.php';
//redirects the user if already logged in
if(!empty($_SESSION['logged_user'])){
	header('Location:index.php');
}
//checks if user submitted the post
if(isset($_POST['submit'])){
	$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
	$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
	$password2 = filter_input(INPUT_POST, 'password2', FILTER_SANITIZE_STRING);
	$errors = array();
	//errors if username and/or password isn't submitted
	if(empty($username)){
		$errors[] = "You must submit a username";
	}
	if(empty($password)){
		$errors[] = "You must submit a password";
	}
	//error if both password fields don't match
	elseif($password != $password2){
		$errors[] = "The passwords you submitted didn't match";
	}
	$salt = mcrypt_create_iv(32);
	$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
	$results = $mysqli->query("SELECT username FROM users WHERE username = '$username'");
	//checks if username already exists
	if(mysqli_num_rows($results) != 0){
		$errors[] = "Username: " . $username . " already exists";
	}
	//if no error, creates user in database
	elseif(empty($errors)){
		$password = hash('sha256', $password . $salt);
		$sql = "INSERT INTO users (username, password, salt, admin) VALUES ('$username', '$password', '$salt', 0)";
		if($mysqli->query($sql)){
			$_SESSION['logged_user'] = $username;
			header('Location:index.php');
		}
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
<h3>Register a site account</h3>
<?php 
//php errors
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
	<label for="password2">Retype password: </label>
	<input type="password" name="password2" id="password2" />
	<br />
	<input type="submit" name="submit" id="submit" value="Register" />

</form>
</div>

</body>
</html>