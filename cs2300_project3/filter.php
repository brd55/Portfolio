<?php
	require_once 'resources/config.php';
	$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
	$title = filter_input( INPUT_POST, 'title', FILTER_SANITIZE_STRING);
	$caption = filter_input( INPUT_POST, 'caption', FILTER_SANITIZE_STRING);
	$table = filter_input( INPUT_POST, 'table', FILTER_SANITIZE_STRING);
	$id = filter_input( INPUT_POST, 'id', FILTER_SANITIZE_STRING);
	$album = filter_input( INPUT_POST, 'album', FILTER_SANITIZE_STRING);
	$sql;
	$response = array();
	//Creates query unique to the table being searched
	if($table == 'Images'){
		//Initial query
		$sql = 'SELECT imageID FROM Images';
		//If title has a value, narrows down to results that have title like the user input
		if(!empty($title)){
			$sql = "SELECT imageID FROM Images WHERE imageTitle LIKE '%" . $title . "%' AND imageID IN (" . $sql . ")";
		}
		//If caption has a value, narrows down to results that have title like the user input
		if(!empty($caption)){
			$sql = "SELECT imageID FROM Images WHERE imageCaption LIKE '%" . $caption . "%' AND imageID IN (" . $sql . ")";
		}
		//If album has a value, narrows down to images that are in albums with names like the user input
		if(!empty($album)){
			$albumSql = "SELECT albumID FROM Albums WHERE albumName LIKE '%" . $album . "%'";
			$albumSql = "SELECT imageID FROM imagesalbums WHERE albumID IN (" . $albumSql . ")";
			$sql = "SELECT imageID FROM Images WHERE imageID IN (" . $sql . ") AND imageID IN (" . $albumSql . ")";
		}
		//Takes the imageID's we've generated, and uses them to grab all their fields.
		$sql = "SELECT * FROM Images WHERE imageID IN (" . $sql . ")";
		$i = 0;
		$results = $mysqli->query($sql);
		//Adds each value for each image in the same index of response
		while($result = $results->fetch_assoc()){
			$response[$i]['title'] = $result['imageTitle'];
			$response[$i]['id'] = $result['imageID'];
			$response[$i]['name'] = $result['imageName'];
			$response[$i]['caption'] = $result['imageCaption'];
			$i++;
		}
	}
	else{
		//If we're accessing a single album on edit_albums.php
		if(!empty($id)){
			$sql = "SELECT * FROM Albums WHERE albumID = '" . $id . "'";
			$results = $mysqli->query($sql)->fetch_assoc();
			//saves all the albums info to one index
			$response['album']['name'] = $results['albumName'];
			$response['album']['id'] = $results['albumID'];
			$response['album']['Cr'] = $results['dateCr'];
			$response['album']['Mod'] = $results['dateMod'];
			$sql = "SELECT imageID, imageTitle FROM Images";
			$results = $mysqli->query($sql);
			//for each image in the database, creates a subindex for that image in the img index
			//sets the inAlbum property to false by default
			while($result = $results->fetch_assoc()){
				$response['img'][$result['imageID']]['inAlbum'] = false;
				$response['img'][$result['imageID']]['title'] = $result['imageTitle'];
			}
			$sql = "SELECT imageID FROM imagesalbums WHERE albumID = '" . $response['album']['id'] . "'";
			$results = $mysqli->query($sql);
			//changes the inAlbum property for true for any images actually in the album
			while($result = $results->fetch_assoc()){
				$response['img'][$result['imageID']]['inAlbum'] = true;
			}
		}
		//if we're searching through albums
		else{
			if(isset($album)){
				$sql = "SELECT albumName, albumID FROM Albums WHERE albumName LIKE '%" . $album . "%'";
				$results = $mysqli->query($sql);
				$i = 0;
				while($result = $results->fetch_assoc()){
					$response[$i]['name'] = $result['albumName'];
					$response[$i]['id'] = $result['albumID'];
					$i++;
				}
			}
		}
	}
	
	print(json_encode($response));
	die();