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
		<h1>Modify Post</h1>
		<div class="wrapper"> <a href='../index.php' id="home"></a>
			<?php
				if (!isset($_SESSION['logged_user'])) {
					//one-time message to be displayed on login page
					$_SESSION['login'] = "You must log in to access the modify post page";
					//one location to redirect to after successful login
					$_SESSION['login_url'] = 'modify_post.php';
					//redirect to login page
					header('location: login.php');
				}
				$post_username = filter_input( INPUT_POST, 'username', FILTER_SANITIZE_STRING );
				$post_password = filter_input( INPUT_POST, 'password', FILTER_SANITIZE_STRING );
				if ( true ) {
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
		if(isset($_SESSION['adminMessage'])){
				print("<p>" . $_SESSION['adminMessage'] . "</p>");
				unset($_SESSION['adminMessage']);
			}
		?>
		<pre>Use this page to modify
posts</pre>
	</div>



</body>

</html>