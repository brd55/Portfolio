<!--
5/5/15 Edited by Chenmin: Get content from the DB. 
Make sure in your local DB, the table 'web_info' has the record: {caption: 'about_us'; content: XXXX; date_modified: 2015-XX-XX;}
5/6/Edited by Chenmin: Added the email sign up function
-->
<!DOCTYPE html>
<html>
<head>
<title>Ensemble - About You</title>
<link href="CSS/styles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript" src="JavaScript/java.js"></script>
</head>
<body>
<?php
	include_once 'header.php';
	//Use a function to print the header, change the form action of the email bar to the current PHP file	
	get_header("about_us.php");	
    get_email_sign_up("about us");
?>


<div class="centeredDiv" id="headerDiv">
<h1 class="journal">About Us</h1>
</div>
<div class="centeredDiv" id="bodyDiv">

<?php
	require_once 'includes/config.php';			
	$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
	$result = $mysqli->query("SELECT * FROM web_info WHERE caption = 'about_us';");
	$row = $result->fetch_assoc();
	$content = $row['content'];
	$paragraphs = explode("\n", $content);
	$para_num = count($paragraphs);

	for($i = 0; $i < $para_num; $i++){
		$para = $paragraphs[$i];
		print("<p>$para</p>");
	}
	
?>


</div>
</body>
</html>