<?php
//nav links relevant to everyone
echo '<table id="nav">
	<tr>
		<td>
			<a href="index.php">Home</a>&nbsp;
		</td>';
	//nav links when not logged in
	if(empty($user)){
		echo '<td>
				&nbsp;&nbsp;<a href="login.php">Login</a>
			</td>
			<td>
				/
			</td>
			<td>
				<a href="register.php">Register</a>
			</td>';
	}
	//nav links when logged in
	else{
		//nav links for user only
		 if($user['admin']){
			 echo '<td>
					&nbsp;<a href="add_album.php">Add an Album</a>&nbsp;
				</td>
				<td>
					&nbsp;<a href="add_image.php">Add an Image</a>&nbsp;
				</td>
				<td>
					&nbsp;<a href="edit_albums.php">Edit Albums</a>&nbsp;
				</td>
				<td>
					&nbsp;<a href="edit_images.php">Edit Images</a>
				</td>';
			}
		echo '<td>
				&nbsp;&nbsp;<a href="logout.php">Logout</a>&nbsp;
			</td>';
	}
// nav links for ever
echo '<td>
				&nbsp;<a href="search_albums.php">Search Albums</a>&nbsp;
			</td>
			<td>
				&nbsp;<a href="search_images.php">Search Images</a>
			</td>
		</tr>
	</table>';