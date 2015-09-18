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

	<?php
		if(isset($_SESSION['adminMessage'])){
			print("<div id='main_form'><p>" . $_SESSION['adminMessage'] . "</p></div>");
			unset($_SESSION['adminMessage']);
		}

		require_once '../includes/config.php';
		$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

		$result = $mysqli->query("SELECT  * FROM item");

		if ($result && !isset($_POST['modify'])) {
			print("<form id='album_photos' action='modify_post.php' method='post'>
			<p>Choose a Post to Modify</p>
	        <select name='option'>");
			while ( $row = $result->fetch_row() ) {
				print( "<option value=$row[0]>$row[1]</option>" );

			}
			print("</select>	
			<input type='submit' name= 'modify' value='Click to submit'>
		</form>");
		}

		if (isset($_POST['modify'])) {;
			
			modifyPost();
			$_SESSION['item_ID'] = $_POST[ "option" ];

		}

		function modifyPost(){
		?>
		<form id='main_form' action="modify_post.php" method="post" enctype="multipart/form-data">
			<input type="text" name="url" minlength='1' maxlength= '50'> Enter the modified URL <p></p>
			<input type="text" name="Header" minlength='1' maxlength= '50'> Enter the modified header <p></p>
			<textarea type="text" name="Description" minlength='1' maxlength= '500' rows="4" cols="50"></textarea> Enter the modified description for the item <p></p>
			<select name="sub_category">

			<?php
                //We don't need to choose category, because whenever the sub_category is choosen, category is set based on the relations
			    //in the table 'category_belongs'
			    //we can print the option list dynamiclly
                require_once '../includes/config.php';
				
				$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
				
				if ($mysqli->errno) {
					print($mysqli->error);
					exit();
				}

				$sql_1 = 'SELECT * FROM sub_category;';

		        $result_1 = $mysqli->query($sql_1);

		        if (!$result_1) {
					print($mysqli->error);
					exit();
				}
				
				while ($row = $result_1->fetch_assoc()) {

					$scid = $row['scid'];
					$sub_category_name = $row['name'];
					//get corresponding category name
					$sql_2 = "SELECT c1.name FROM category c1
					          INNER JOIN category_belongs c2 ON c1.cid = c2.cid
					          WHERE c2.scid = '$scid';";
		            $result_2 = $mysqli->query($sql_2);
		            $row_2 = $result_2->fetch_assoc();
		            $category_name = $row_2['name'];					
                    //print in the format "category:sub_category"
					print("<option value='$scid'>$category_name:$sub_category_name</option>");
                }
            ?>
            </select> Select a category <p></p>	
			<select name="gender">
				  <option value="1">Men's</option>
				  <option value="2">Women's</option>
				  <option value="3">Unisex</option>
			</select> What gender is the item for? <p></p>		
			<select name="season">
				  <option value="1">Spring</option>
				  <option value="2">Summer</option>
				  <option value="3">Fall</option>
				  <option value="4">Winter</option>
				  <option value="5">Spring/Fall</option>
				  <option value="6">Any Season</option>
			</select> What season is the item for? <p></p>
			<input type="submit" name="UploadItem">
		</form>
		<?php
		}
			if (isset($_POST['UploadItem'])) {
				item_table_query();
			}

			function item_table_query() {
				require_once '../includes/config.php';
				$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
				$item_ID = $_SESSION['item_ID'];
				print($item_ID);
				unset($_SESSION['item_ID']);
				$result = $mysqli->query("SELECT * FROM item WHERE item_id = $item_ID;");
				while ( $row = $result->fetch_row() ) {
					$temp = $row;
				}

				if (!empty( $_POST['url'])) {
					$URL = $_POST['url'];
				} else {
					$URL = $temp[3];
				}

				if (!empty( $_POST['Header'])) {
					$name = $_POST['Header'];
				} else {
					$name = $temp[1];
				}

				if (!empty( $_POST['Description'])) {
					$description = $_POST['Description'];
				} else {
					$description = $temp[2];
				}

				if (!empty( $_POST['sub_category'])) {
					$scid = $_POST['sub_category'];
				} else {
					$scid = $temp[8];
				}

				if (!empty( $_POST['gender'])) {
					$gid = $_POST['gender'];
				} else {
					$gid = $temp[9];
				}

				if (!empty( $_POST['season'])) {
					$season_id = $_POST['season'];
				} else {
					$season_id = $temp[10];
				}

				$img_URL = $temp[4];
				$like_num = $temp[5];
				$date_posted = $temp[7];


				$sql= "DELETE FROM item WHERE item_id = $item_ID;";
				$result = $mysqli->query($sql);

				$sql = "INSERT INTO item (item_id, name, description, URL, img_URL, like_num, date_posted, date_modified, scid, gid, season_id) 
					VALUES ($item_ID, '$name', '$description', '$URL', '$img_URL', $like_num, '$date_posted', now(), $scid, $gid, $season_id);";

				$result = $mysqli->query($sql);

				?>
				<script type="text/javascript">
				    document.getElementsByName('Header').value ='';
				    document.getElementsByName('Description').value ='';
				    document.getElementsByName('newphoto').value ='';
				    document.getElementsByName('url').value ='';
				    document.getElementsByName('category').value ='';
				    document.getElementsByName('sub-category').value ='';
				    document.getElementsByName('season').value ='';
				</script>
				<?php
			}
		?>

	




</body>

</html>