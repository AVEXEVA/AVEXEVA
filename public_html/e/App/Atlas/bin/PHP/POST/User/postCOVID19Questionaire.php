<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require('../../../cgi-bin/libraries/PHPMailer-master/src/Exception.php');
require('../../../cgi-bin/libraries/PHPMailer-master/src/PHPMailer.php'); 
require('../../../cgi-bin/libraries/PHPMailer-master/src/SMTP.php');
session_start();
require('../index.php');
if(isset($_SESSION['User'],$_SESSION['Hash'])){
    $r = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.Connection WHERE Connector = ? AND Hash = ?;",array($_SESSION['User'],$_SESSION['Hash']));
    $array = sqlsrv_fetch_array($r);
    if(!isset($_SESSION['Branch']) || $_SESSION['Branch'] == 'Nouveau Elevator'){
        sqlsrv_query($Portal,"INSERT INTO Activity([User], [Date], [Page]) VALUES(?,?,?);",array($_SESSION['User'],date("Y-m-d H:i:s"), "ticket.php"));
        $r = sqlsrv_query($NEI,"SELECT *, fFirst AS First_Name, Last as Last_Name FROM Emp WHERE ID= ?",array($_SESSION['User']));
        $My_User = sqlsrv_fetch_array($r);
        $Field = ($My_User['Field'] == 1 && $My_User['Title'] != "OFFICE") ? True : False;
        $r = sqlsrv_query($Portal,"
            SELECT Access_Table, User_Privilege, Group_Privilege, Other_Privilege
            FROM   Portal.dbo.Privilege
            WHERE  User_ID = ?
        ;",array($_SESSION['User']));
        $My_Privileges = array();
        while($array2 = sqlsrv_fetch_array($r)){$My_Privileges[$array2['Access_Table']] = $array2;}
    }
    if(!isset($array['ID'])){?><html><head></head></html><?php }
    else {
      sqlsrv_query($Portal, "INSERT INTO COVID_19_Questionaire([User], Time_Stamp, Question_1, Question_2, Question_3, Release) VALUES(?, ?, ?, ?, ?, ?);", array($_SESSION['User'], date("Y-m-d H:i:s", strtotime('now')), $_POST['1'], $_POST['2'], $_POST['3'], substr(hash('sha256',rand(0,99999999)), 0, 16)));
	
      if($_POST['1'] == 'Yes' || $_POST['2'] == 'Yes' || $_POST['3'] == 'Yes'){
	      try {
		      $mail = new PHPMailer(true);
		      $from = "WebServices@NouveauElevator.com";
		      $replyto = $from;
		      //$Start_Time = $_POST['Start_Time'];
		      $date = date("Y-m-d H:i:s");
		      $_SERVER['SERVER_NAME'] = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : "Nouveau_Elevator_Portal";
		      //Server settings
		      $mail->SMTPDebug = 2;                                       // Enable verbose debug output
		      $mail->isSMTP();                                            // Set mailer to use SMTP
		      $mail->Host       = 'smtp.gmail.com';  // Specify main and backup SMTP servers
		      $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
		      $mail->Username   = 'webservices@nouveauelevator.com';                     // SMTP username
		      $mail->Password   = 'iudsnnlilgersxba';                               // SMTP password
		      $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
		      $mail->Port       = 587;                                    // TCP port to connect to

		      //Recipients
		      $mail->setFrom('webservices@nouveauelevator.com', 'Web Services');
		      $_POST['Email'] = "psperanza@nouveauelevator.com;gpuk@nouveauelevator.com";
		      $Emails = explode(";", $_POST['Email']);
		      if(count($Emails) > 0){
		        foreach($Emails as $Email){
		            $mail->addAddress($Email);     // Add a recipient
		        }
		      } else {
		        $mail->addAddress($_POST['Email']);
		      }
		      //$mail->addCC('cc@example.com');

		      //$mail->addAddress('ellen@example.com');               // Name is optional
		      $mail->addReplyTo('webservices@nouveauelevator.com', 'NoReply');
		      //$mail->addBCC('bcc@example.com');

		      // Attachments
		      //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		      //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
		      $subject = "Field Worker Health Notice - " . $My_User['First_Name'] . " " . $My_User['Last_Name'] . " - " . date("m/d/Y");
		      $message = "<html>
<head>
<style>
</style>
</head> 
<body> 
<div>{$My_User['First_Name']} {$My_User['Last_Name']}</div> 
<div>&nbsp;</div>
<div>Have you experienced a fever of 100.4 degrees F or greater, a new cough, new loss of taste or smell or shortness of breath within the past 10 days?</div>
<div>{$_POST['1']}</div>
<div>In the past 10 days, have you gotten a positive result from a Covid-19 test that tested saliva or used a nose or throat swab? (not a blood test)</div>
<div>{$_POST['2']}</div>
<div>To the best of your knowledge, in the past 14 days, have you been in close contact (within 6 feet for at least 10 minutes) with anyone while they had Covid-19?
<div>{$_POST['3']}</div>
</body>
</html>";

		      // Content
		      $mail->isHTML(true);                                  // Set email format to HTML
		      $mail->Subject = $subject;
		      $mail->Body = $message;
		      ob_start();
		      $mail->send();
		      ob_end_clean();
	    }
	catch (exception $e) {
 	   //code to handle the exception
	}
	finally {
	    //optional code that always runs
	}
    	echo 'No';
	}
}}?>
