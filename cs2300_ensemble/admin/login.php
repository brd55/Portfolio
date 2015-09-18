<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin</title>
    <!-- <script src = "valid.js"></script> -->
    <link rel="stylesheet" href="../CSS/links_style1.css"/>
    

</head>



<body>

	<header>
		<h1>Home</h1>
		<div class="wrapper"> <a href='../index.php' id="home"></a>
			<?php
				$post_username = filter_input( INPUT_POST, 'username', FILTER_SANITIZE_STRING );
				$post_password = filter_input( INPUT_POST, 'password', FILTER_SANITIZE_STRING );
				if ( true) {
					
			?>
				<nav>
					<ul id='nav' class='menu'>
						<li class='current-menu-item'><a href='index.php'>Home<span class='subheader'></span></a></li>
						<li class='current-menu-item'><a href='edit_info.php'>Edit Info<span class='subheader'></span></a></li>
						<li class='current-menu-item'><a href='create_post.php'>Create Post<span class='subheader'></span></a></li>
						<li class='current-menu-item'><a href='delete_post.php'>Delete Post<span class='subheader'></span></a></li>
						<li class='current-menu-item'><a href='modify_post.php'>Modify Post<span class='subheader'></span></a></li>
						<li class='current-menu-item'><a href='database.php'>Email Database<span class='subheader'></span></a></li>
						<li class='current-menu-item'><a href='logout.php'>Logout<span class='subheader'></span></a></li>
					</ul>
				</nav>
			<?php
				}
			?>
			<div class='clearfix'></div>
		</div>
	</header>	
	
	<?php
		$post_username = filter_input( INPUT_POST, 'username', FILTER_SANITIZE_STRING );
		$post_password = filter_input( INPUT_POST, 'password', FILTER_SANITIZE_STRING );
		print("<div id='main_form'>");
		if(isset($_SESSION['login'])){
			echo $_SESSION['login'];
			unset($_SESSION['login']);
		}
		if ( (empty( $post_username ) || empty( $post_password )) && !isset($_SESSION['logged_user']) ) {
			print("<h2>Log in</h2>
				<form action='login.php' method='post'>
				Username: <input type='text' name='username'> <br>
				Password: <input type='password' name='password'> <br>
				<input type='submit' value='Submit'>
			</form>");
			
		} elseif (!isset($_SESSION['logged_user'])) {
			
			//Getting login.
			require_once '../includes/config.php';
			$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

			$hashed_password = hash("sha256",$post_password);

			$sql = "SELECT * FROM admin WHERE name = '$post_username' AND password = '$hashed_password'";

			$result = $mysqli->query($sql);



			if ( $result && $result->num_rows == 1) {
				$row = $result->fetch_assoc();
				$db_username = $row['name'];
				print("<p>You have successfully logged in.</p><p>Welcome to Ensemble Admin!</p>");
				$_SESSION['logged_user'] = $db_username;
				//check if one-time redirect is set
				if(isset($_SESSION['login_url'])){
					$redirect = $_SESSION['login_url'];
					unset($_SESSION['login_url']);
					//one-time message to be displayed on admin page
					$_SESSION['adminMessage'] = "You are now logged in!";
					//redirect to specified page
					header('location: ' . $redirect);
				}
				//$_SESSION['userID'] = $row['aid'];
			} else {
				echo '<p>' . $mysqli->error . '</p>';
				?>
				<p>You did not login successfully.</p>
				<p>Please <a href="index.php">login</a> again.</p>
				<?php
			
			
				$mysqli->close();
			} //end if isset username and password
		} 
		else {
				//one-time message to be displayed on admin page
				$_SESSION['admin'] = "You are already logged in";
				header('location: index.php');
		}
		print("</div>");
		
	?>



</body>

</html>