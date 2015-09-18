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
		<h1>Modify Posts</h1>
		<div class="wrapper"> <a href='../index.php' id="home"></a>
			<?php
				if (!isset($_SESSION['logged_user'])) {
					if (!isset($_SESSION['logged_user'])) {
					//one-time message to be displayed on login page
					$_SESSION['login'] = "You must log in to access the delete post page";
					//one location to redirect to after successful login
					$_SESSION['login_url'] = 'delete_post.php';
					//redirect to login page
					header('location: login.php');
				}
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
	
	<?php
	if(isset($_SESSION['adminMessage'])){
		print("<div id='main_form'><p>" . $_SESSION['adminMessage'] . "</p></div>");
		unset($_SESSION['adminMessage']);
	}
	?>


	<?php
		require_once '../includes/config.php';
		$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

		$result = $mysqli->query("SELECT  * FROM item");

		if ($result) {
			print("<form id='album_photos' action='delete_post.php' method='post'>
			<p>Choose a Post to Delete</p>
	        <select name='option'>");
			while ( $row = $result->fetch_row() ) {
				print( "<option value=$row[0]>$row[1]</option>" );

			}
			print("</select>	
			<input type='submit' name= 'submit_del' value='Click to submit'>
		</form>");
		}

		if (isset($_POST['submit_del'])) {;
			$item_ID = $_POST[ "option" ];
			$sql= "DELETE FROM item WHERE item_ID = $item_ID;";
			$result = $mysqli->query($sql);

			unset($_POST['submit_del']);
			while (ob_get_status()) 
			{
			    ob_end_clean();
			}
			header( "Location: delete_post.php" );
		}

	?>
		
	</div>
</body>

</html>