<!--
5/5/15 Edited by Chenmin: Get content from the DB. 
Make sure in your local DB, the table 'web_info' has the 2 records: {caption: 'about_us'/'about_you'; content: XXXX; date_modified: 2015-XX-XX;}
-->
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
		<h1>Edit Info</h1>
		<div class="wrapper"> <a href='../index.php' id="home"></a>
			<?php
				if (!isset($_SESSION['logged_user'])) {
					//one-time message to be displayed on login page
					$_SESSION['login'] = "You must log in to access the edit info page";
					//one location to redirect to after successful login
					$_SESSION['login_url'] = 'edit_info.php';
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
		<form id='edit_info' action="edit_info.php" method="post" enctype="multipart/form-data">
            <pre>About us</pre>
			<textarea name="about_us" rows="15" cols="40"><?php
				require_once '../includes/config.php';			
				$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
				$result = $mysqli->query("SELECT * FROM web_info WHERE caption = 'about_us';");
				$row = $result->fetch_assoc();
				$content_1 = $row['content'];
				echo "$content_1";
			?>			
			</textarea><br>
			<pre>About you</pre>
			<textarea name="about_you" rows="15" cols="40"><?php
				//$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
				$result = $mysqli->query("SELECT * FROM web_info WHERE caption = 'about_you';");
				$row = $result->fetch_assoc();
				$content_2 = $row['content'];
				print("$content_2");
			?>						
			</textarea><br>
			<input type="submit" name="submit" value="submit">
			<input type="submit" name="reset"  value="current contents/reset">						
		</form>
		<?php
		    if(isset($_POST['reset'])){
				header("Location: edit_info.php");
		    }
		    if(isset($_POST['submit'])){
		    	$about_us_content = htmlentities($_POST['about_us']);
		    	$about_us_content = str_replace("'", "''", $about_us_content);
		    	$sql_1 = "UPDATE web_info SET content = '$about_us_content', date_modified = now() WHERE caption = 'about_us';";
				$result_1 = $mysqli->query($sql_1);
				if(!$result_1){
		    	    print($mysqli->error);
		    	    exit();
				}

		    	$about_you_content = htmlentities($_POST['about_you']);
		    	$about_you_content = str_replace("'", "''", $about_you_content);
		    	$sql_2 = "UPDATE web_info SET content = '$about_you_content', date_modified = now() WHERE caption = 'about_you';";
				$result_2 = $mysqli->query($sql_2);	

				if(!$result_2){
					print($mysqli->error);
					exit();
				} else {
                    print("Edit successfully!");
				}
		    }

		?>
	</div>



</body>

</html>