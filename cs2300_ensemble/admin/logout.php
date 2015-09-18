<?php session_start(); 
	//Check if the user is logged
    //If the user is logged, get its old username for showing the name after
	if (isset($_SESSION['logged_user'])) {
		$olduser = $_SESSION['logged_user'];
		unset($_SESSION['logged_user']);
	} else {
		$olduser = false;
	}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin</title>
    <!-- <script src = "valid.js"></script> -->
    <link rel="stylesheet" href="../CSS/links_style1.css"/>
    

</head>



<body>

	<header>
		<h1>Logout</h1>
		<div class="wrapper"> <a href='../index.php' id="home"></a>
			<?php
				$post_username = filter_input( INPUT_POST, 'username', FILTER_SANITIZE_STRING );
				$post_password = filter_input( INPUT_POST, 'password', FILTER_SANITIZE_STRING );
				if ( isset($_SESSION['logged_user'])) {
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
	
	<div id='main_form'>
 		<?php

			if ( $olduser ) {
				print("<pre>Thank you, $olduser!</pre>");
				print("<pre>Return to <a href='login.php'>login page.</a></pre>");
			} else {
				print("<pre>You haven't logged in.</pre>");
				print("<pre>Go to our <a href='login.php'>login page.</a></pre>");
			}
		?>
	</div>
</body>

</html>