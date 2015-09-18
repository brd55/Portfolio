<?php
// 5/4/15 Created by Chenmin
//Please Make sure email.csv in the includes directory!

    //Clean the existing emails
    if(file_exists("../includes/email_details.txt")){
	    $file = file_put_contents("../includes/email_details.txt", "");    	
    }
    //$file = file_put_contents("../includes/email_details.txt", "");
    //Write the current emails
    $file = fopen("../includes/email_details.txt", "w");

    require_once '../includes/config.php';

    $delimiter = "\n";
    
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
    if ($mysqli->errno) {
        print($mysqli->error);
        exit();
    }

    $sql = 'SELECT * FROM email;';

    $result = $mysqli->query($sql);

    if (!$result) {
        print($mysqli->error);
        exit();
    }
    
    $num = $result->num_rows;
 
    for($i = 0; $i < $num - 1; $i++){
        $row = $result->fetch_assoc();        
        $email = $row['email'];
        $date = $row['date_added'];
        $source = $row['source'];
        fwrite($file, "$email,$date,$source$delimiter");        
    }
    $row = $result->fetch_assoc();
    $email = $row['email'];
    $date = $row['date_added'];
    $source = $row['source'];
    fwrite($file, "$email,$date,$source");  

    fclose($file);
    //Open and use header to download the email.txt
    $file = '../includes/email_details.txt';
    if(!$file){ // file does not exist
        die('file not found');
    } else {
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename='email_details.txt'");
        header("Content-Type: application/zip");
        header("Content-Transfer-Encoding: binary");

        // read the file from disk
        readfile($file);
    }
?>