<!--
5/6/Edited by Chenmin: Added the email sign up function
-->
<!DOCTYPE html>
<html>
<head>
<title>Ensemble - Balance</title>
<link href="CSS/styles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript" src="JavaScript/java.js"></script>
</head>
<body>
<?php
	include_once 'header.php';
	//Use a function to print the header, change the form action of the email bar to the current PHP file	
	get_header("balance.php");	
	get_email_sign_up("balance");	
?>

<div class="centeredDiv" id="headerDiv">
<h1 class="journal">balance</h1>
</div>
<div class="centeredDiv" id="bodyDiv">
<p>Coming soon...</p>

</div>
</body>
</html>