<?php
//Change 4/27 by Brendan: added email bar
//Change 5/3/15 by Brendan: Replaced text box function with placeholder attribute
//Change 5/3/15 by Chenmin: Change email bar form attributes
//Change 5/6/15 by Chenmin: Change to 2 functions
//Change 5/6/15 by Brendan: Implemented bolding of current link

//<form action = "..." have to change between different pages because we need to know from which page the user add his/her email
function get_header($source){
	$header = '<div class="centeredDiv" id="topNav">
		<pre><a href="http://stitchcollection.com/" class="journal" id="stitchCollection">StitchCollection</a>		<a href="about_us.php" id="aboutUs" class="about questrial">About Us</a>		<a href="about_you.php" id="aboutYou" class="about questrial">About You</a>		</pre>
	</div>
	<div class="centeredDiv" id="bottomNav">
		<pre class="journal"><a href="index.php" class="bottomLink" >ensemble</a>		<a href="elements.php" class="bottomLink" >elements</a>		<a href="balance.php" class="bottomLink" >balance</a>		<a href="forward.php" class="bottomLink" >forward</a>		</pre><form replace_here method="POST" style="display: inline"><pre><input type="text" id="emailBar" name="emailBar" placeholder="Join us with your email!"/><input type="submit" id="emailSubmit" name="emailSubmit" value="Sign Up!" /></pre></form>
	</div>';

    $header = str_replace("replace_here", "action=$source", $header);
	$header = str_replace('href="' . $source . '"', 'href="' . $source . '" style="font-weight: 600;"', $header);
    echo "$header";
}

//sign up the email based on which part of site ($source)
function get_email_sign_up($source){
    require_once "includes/config.php";			
	$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );	

    if(isset($_POST['emailSubmit'])){

    	$email = htmlentities($_POST['emailBar']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		    echo "<p style='margin-right: auto; margin-left: auto; text-align: center; color: red'>Invalid email address!</p>";
		} else {  	
		    $pre_sql = "SELECT email FROM email WHERE email = '$email';";
	        $pre_result = $mysqli->query($pre_sql);

	        if ($pre_result->num_rows == 1) {
				echo "<p style='margin-right: auto; margin-left: auto; text-align: center; color: red'>You have already signed up!</p>";
			} else {

			    $today = date("Y-m-d");
				$sql_1 = "INSERT INTO email (email, date_added, source) 
				        VALUES ('$email', '$today', '$source');";

		        $result = $mysqli->query($sql_1);

		        if (!$result) {
					print($mysqli->error);
					exit();
				} else {
					//The prompt
					echo "<p style='margin-right: auto; margin-left: auto; text-align: center; color: green'>You signed up successfully!</p>";
				}
		    }
        }
    }
}
