<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
session_start();
require('../index.php');
require('../../../cgi-bin/libraries/PHPMailer-master/src/Exception.php');
require('../../../cgi-bin/libraries/PHPMailer-master/src/PHPMailer.php');
require('../../../cgi-bin/libraries/PHPMailer-master/src/SMTP.php');
if(isset($_SESSION['User'],$_SESSION['Hash'])){
    $r = sqlsrv_query($NEI,"
		SELECT *
		FROM   nei.dbo.Connection
		WHERE  Connection.Connector = ?
		       AND Connection.Hash  = ?
	;",array($_SESSION['User'],$_SESSION['Hash']));
    $My_Connection = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC);
    $r = sqlsrv_query($NEI,"
		SELECT *,
		       Emp.fFirst AS First_Name,
			   Emp.Last   AS Last_Name
		FROM   nei.dbo.Emp
		WHERE  Emp.ID = ?
	;",array($_SESSION['User']));
    $My_User = sqlsrv_fetch_array($r);
	$r = sqlsrv_query($NEI,"
		SELECT *
		FROM   Portal.dbo.Privilege
		WHERE  Privilege.User_ID = ?
	;",array($_SESSION['User']));
	$My_Privileges = array();
	if($r){while($My_Privilege = sqlsrv_fetch_array($r)){$My_Privileges[$My_Privilege['Access_Table']] = $My_Privilege;}}
    if(	!isset($My_Connection['ID'])
	   	|| !isset($My_Privileges['Testing_Admin'])
	  		|| $My_Privileges['Testing_Admin']['User_Privilege']  < 4
	  		|| $My_Privileges['Testing_Admin']['Group_Privilege'] < 4){
				?><?php require('../404.html');?><?php }
    else {
      $d_array = array();
      $d_string = '';
      $value_string = "Deficiencies\n";
      if(count($_POST['Part']) == 0){$d_string = "<tr><td style='font-weight:bold;border:1px solid black;'>Deficiencies:</td><td style='border:1px solid black;'>NONE</td></tr>";}
      else {
        $d_string = "<tr><td colspan='4' style='font-weight:bold;border:1px solid black;'>Deficiencies:</td><td style='border:1px solid black;'>N/C</td><td style='border:1px solid black;'>M</td><td style='border:1px solid black;'>P</td><td style='border:1px solid black;'>R</td><td style='border:1px solid black;'>B</td></tr>";
        foreach($_POST['Part'] as $index=>$Part){
          $Part = explode(";", $Part);
          $Part_ID = $Part[0];
          $Part_Name = $Part[1];
          $Condition = explode(";",$_POST['Condition'][$index]);
          $Condition_ID = $Condition[0];
          $Condition_Name = $Condition[1];
          $Remedy = explode(";",$_POST['Remedy'][$index]);
          $Remedy_ID = $Remedy[0];
          $Remedy_Name = $Remedy[1];
          $Comment = $_POST['Comments'][$index];
          $row = $index + 1;
          $d_array = array('Part' => $Part_Name, 'Condition' => $Condition_Name, 'Remedy' => $Remedy_Name);
          $d_string .= "<tr><td style='font-weight:bold;border:1px solid black;'>{$row}:</td><td style='border:1px solid black;'>({$Part_ID})  {$Part_Name}</td><td style='border:1px solid black;'>({$Condition_ID}) {$Condition_Name}</td><td style='border:1px solid black;'>($Remedy_ID) {$Remedy_Name}</td><td style='border:1px solid black;'><input type='checkbox' style='width:25px;height:25px;' /></td><td style='border:1px solid black;'><input type='checkbox' style='width:25px;height:25px;' /></td><td style='border:1px solid black;'><input type='checkbox' style='width:25px;height:25px;' /></td><td style='border:1px solid black;'><input type='checkbox' style='width:25px;height:25px;' /></td><td style='border:1px solid black;'><input type='checkbox' style='width:25px;height:25px;' /></td></tr><tr><td>&nbsp;</td><td colspan='3' rowspan='2' style='border:1px solid black;'>{$Comment}</td><td colspan='5' rowspan='2' style='border:1px solid black;'>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";
          $value_string .= "({$Part_ID})  {$Part_Name} - ({$Condition_ID}) {$Condition_Name} - ($Remedy_ID) {$Remedy_Name} - {$Comment}\n";
        }
      }
      $value_string .= "General Comments: {$_POST['GeneralComments']}\n";
      $value_string .= "Internal Comments: {$_POST['Internal_Comments']}\n";
      $value_string .= "Fire Service: {$_POST['Fire_Service_Test']}\n";
      $_POST['ID'] = 1;



      $_SERVER['SERVER_NAME'] = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : "Nouveau_Elevator_Portal";
      function generateMessageID()
      {
        return sprintf(
          "<%s.%s@%s>",
          base_convert(microtime(), 10, 36),
          base_convert(bin2hex(openssl_random_pseudo_bytes(8)), 16, 36),
          $_SERVER['SERVER_NAME']
        );
      }
      $from = "WebServices@NouveauElevator.com";
      $replyto = $from;
      $Start_Time = $_POST['Start_Time'];
      $date = date("Y-m-d H:i:s");
      $formatted_date = date('m/d/Y h:i A');
      $subject = "Category Test: {$date}";


      $r = sqlsrv_query($NEI,"SELECT Loc.*, Zone.Name AS Division, Terr.Name AS Territory FROM Loc LEFT JOIN Zone ON Loc.Zone = Zone.ID LEFT JOIN Terr ON Loc.Terr = Terr.ID WHERE Loc.Loc = ?;", array($_POST['Location']));
      $row = sqlsrv_fetch_array($r);
      $Tag = $row['Tag'];
      $Zone_Name = $row['Division'];
      $Territory_Name = $row['Territory'];
      $r = sqlsrv_query($NEI,"SELECT * FROM Elev WHERE Elev.ID = ?;", array($_POST['Unit']));
      $State = sqlsrv_fetch_array($r)['State'];
      $Building_ID = sqlsrv_fetch_array($r)['Unit'];

      $ID = 1;

      $r = sqlsrv_query($NEI, "SELECT Max(ID) AS ID FROM nei.dbo.Violation;");
      if(!$r){return false;}
      $ID = sqlsrv_fetch_array($r)['ID'];
      $r = sqlsrv_query($NEI,"SELECT Zone.ID FROM Loc LEFT JOIN nei.dbo.Zone ON Loc.Zone = Zone.ID WHERE Loc.Loc = ?", array($_POST['Location']));
      $Zone = sqlsrv_fetch_array($r)['ID'];
      $Divisions = array(
        '3' =>  'Custom12',
        '2' =>  'Custom15',
        '5' =>  'Custom16',
        '4' =>  'Custom19'
      );
      if(is_null($State)){$Unit_Label = $Building_ID;}
      elseif(is_null($Building_ID)){$Unit_Label = $State;}
      else{$Unit_Label = $State . ' - ' . $Building_ID;}

      $value_string = "{$Tag}\n{$Unit_Label}\n" . $value_string;


      sqlsrv_query($Portal,
        "INSERT INTO [Portal].dbo.[Category_Test]([Status], [Parent], [Final], [User], [Start], [End], [Location], [Unit], [Fire_Service_Test], [Modernization], [General_Comments], [Internal_Comments], [Witness_Name], [Witness_License], [Witness_Date], [Inspector_Name], [Inspector_License], [Inspector_Date], [Type]) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array($_POST['Status'], $_POST['Parent'], 1,
          $_POST['Mechanic'], date("Y-m-d",strtotime($_POST['Start_Time'])), date("Y-m-d", strtotime($_POST['Start_Time'])), $_POST['Location'], $_POST['Unit'], $_POST['Fire_Service_Test'] == 'Performed' ? 1 : 0, $_POST['Under_Mod'] == 'Yes' ? 1 : 0, $_POST['GeneralComments'], $_POST['Internal_Comments'], $_POST['witness_name'], $_POST['witness_license'], $_POST['witness_date'], $_POST['inspector_name'], $_POST['inspector_license'], $_POST['inspector_date'], $_POST['Test_Type']
      ));
        if( ($errors = sqlsrv_errors() ) != null) {
            foreach( $errors as $error ) {
                echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
                echo "code: ".$error[ 'code']."<br />";
                echo "message: ".$error[ 'message']."<br />";
            }
        }
      $r = sqlsrv_query($Portal,"SELECT Max(ID) AS ID FROM [Portal].dbo.[Category_Test];");
      $Category_Test_ID = sqlsrv_fetch_array($r)['ID'];
	sqlsrv_query($Portal, "UPDATE [Portal].dbo.[Category_Test] SET [Final] = 1 WHERE ID = ?", array($_POST['Parent']));

      echo $Category_Test_ID;
      if(count($_POST['Part']) == 0){$_POST['ID'] = 'X-' . rand(0,999999999);}
      else {
        $Pass = FALSE;
        foreach($_POST['Part'] as $index=>$Part){
          if($Part == ''){continue;}
          $Pass = TRUE;
        }
        if($Pass){

          sqlsrv_query($NEI,
            " INSERT INTO [nei].dbo.Violation(Loc, Elev, fdate, Status, Quote, Job, Ticket, Remarks, Custom7, Name, Estimate, Remarks2, idTestItem, Price, Custom12, Custom15, Custom16, Custom19)
              VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
              ", array($_POST['Location'], $_POST['Unit'], date("Y-m-d 00:00:00.000", strtotime($_POST['Start_Time'])), 'Preliminary Report', 0, 0, 0, strtoupper($value_string), date("Y-m-d", strtotime("+120 days", strtotime($_POST['Start_Time']))), 'CAT ' . date("Y", strtotime($_POST['Start_Time'])), 0, ' ', 0, NULL, $Divisions[$Zone] == 'Custom12' ? 1 : 0, $Divisions[$Zone] == 'Custom15' ? 1 : 0, $Divisions[$Zone] == 'Custom16' ? 1 : 0, $Divisions[$Zone] == 'Custom19' ? 1 : 0));
          $r = sqlsrv_query($NEI,"SELECT Max(ID) AS ID FROM [nei].dbo.[Violation];");
          if($r){$_POST['ID'] = sqlsrv_fetch_array($r)['ID'];}
          sqlsrv_query($Portal,
            " UPDATE  [Portal].dbo.[Category_Test]
              SET     [Category_Test].Violation = ?
              WHERE   [Category_Test].ID = ?
            ;", array($_POST['ID'], $Category_Test_ID));
          foreach($_POST['Part'] as $index=>$Part){
            $Part = explode(";", $Part);
            $Part_ID = $Part[0];
            $Part_Name = $Part[1];
            $Condition = explode(";",$_POST['Condition'][$index]);
            $Condition_ID = $Condition[0];
            $Condition_Name = $Condition[1];
            $r = sqlsrv_query($Portal, "SELECT ID FROM [Portal].dbo.[Category_Violation_Condition] WHERE ID = ?;", array($Condition_ID));
            $Condition_Primary_ID = sqlsrv_fetch_array($r)['ID'];
            $Remedy = explode(";",$_POST['Remedy'][$index]);
            $Remedy_ID = $Remedy[0];
            $Remedy_Name = $Remedy[1];
            $Comment = $_POST['Comments'][$index];
            sqlsrv_query($Portal,
              " INSERT INTO Deficiency(Category_Test, Violation, Ticket, Elevator_Part, [Condition], Remedy, Comments, Percentage)
                VALUES(?, ?, ?, ?, ?, ?, ?, ?);
              ;", array($Category_Test_ID, $_POST['ID'], NULL, $Part_ID, $Condition_Primary_ID, $Remedy_ID, $Comment, 0));
          }
        }
      }
      $img = $_POST['Witness_Signature'];
      $img = str_replace('data:image/jpeg;base64,', '', $img);
      $img = str_replace(' ', '+', $img);
      $data = base64_decode($img);
      file_put_contents("../../../media/images/category_test/w-{$Category_Test_ID}.jpg",$data);

      unset($img, $data);

      $img = $_POST['Inspector_Signature'];
      $img = str_replace('data:image/jpeg;base64,', '', $img);
      $img = str_replace(' ', '+', $img);
      $data = base64_decode($img);
      file_put_contents("../../../media/images/category_test/i-{$Category_Test_ID}.jpg",$data);

	$Status = $_POST['Status'];
  $r = sqlsrv_query($NEI,"SELECT Emp.fFirst + ' ' + Emp.Last AS MechanicName FROM nei.dbo.Emp WHERE Emp.ID = ?;", array($_POST['Mechanic']));
  $Mechanic_Name = sqlsrv_fetch_array($r)['MechanicName'];
      $message = "<html>
      <head>
      <style>
      td {padding:5px;}
      tr {border-bottom:#555555;}
      </style>
      </head>
      <body>
      <table width='700px' style='background-color:white;color:black;border:1px solid black;'><tbody>
      <tr><td colspan='10' style='font-size:18px;background-color:black;color:white;border:1px solid black;'><img src='https://www.nouveauelevator.com/Images/Icons/logo.png' width='25px' /> Nouveau Elevator</td></tr>
      <tr><td colspan='10' style='text-decoration:underline;font-weight:bold;font-size:18px;background-color:#2d2d2d;color:white;border:1px solid black;'>Category Test Results</td></tr>
      <tr><td colspan='10' style='text-decoration:underline;font-weight:bold;font-size:18px;background-color:#2d2d2d;color:white;border:1px solid black;'>{$Zone_Name} - {$Tag} - {$Unit_Label}</td></tr>
      <tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Territory:</td><td colspan='3'  style='border:1px solid black;'>{$Territory_Name}</td></tr>
      <tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Division:</td><td colspan='3'  style='border:1px solid black;'>{$Zone_Name}</td></tr>
      <tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Location:</td><td colspan='3' style='border:1px solid black;'>{$Tag}</td></tr>
      <tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Unit:</td><td colspan='3' style='border:1px solid black;'>{$Unit_Label}</td></tr>
      <tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Mechanic:</td><td colspan='3' style='border:1px solid black;'>{$Mechanic_Name}</td></tr>
      <tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Date:</td><td colspan='3' style='border:1px solid black;'>{$Start_Time}</td></tr>
      <tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Status:</td></td colspan='3' style='border:1px solid black;'>{$Status}</td></tr>
      <tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Test Type:</td><td colspan='3' style='border:1px solid black;'>{$_POST['Test_Type']}</td></tr>
      <tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Fire Service Test:</td><td colspan='3' style='border:1px solid black;'>{$_POST['Fire_Service_Test']}</td></tr>
      <tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Under Mod:</td><td colspan='3' style='border:1px solid black;'>{$_POST['Under_Mod']}</td></tr>
      {$d_string}
      <tr><td style='font-weight:bold;border:1px solid black;'>General Comments:</td><td colspan='9' style='border:1px solid black;'><pre>{$_POST['GeneralComments']}</pre></td></tr>
      <tr><td style='font-weight:bold;border:1px solid black;'>Internal Comments:</td><td colspan='9' style='border:1px solid black;'><pre>{$_POST['Internal_Comments']}</pre></td></tr>
      <tr><td colspan='2' style='font-weight:bold;border:1px solid black;'>Witness:</td></tr>
      <tr><td style='font-weight:bold;border:1px solid black;'>Name:</td><td style='border:1px solid black;'>{$_POST['witness_name']}</td></tr>
      <tr><td style='font-weight:bold;border:1px solid black;'>Date:</td><td style='border:1px solid black;'>{$_POST['witness_date']}</td></tr>
      <tr><td style='font-weight:bold;border:1px solid black;'>Witness Signature:</td><td style='border:1px solid black;'><img src='https://www.nouveauelevator.com/portal/media/images/category_test/w-{$Category_Test_ID}.jpg?v=1' style='width:100%;' /></td></tr>
      <tr><td colspan='2' style='font-weight:bold;border:1px solid black;'>Inspector:</td></tr>
      <tr><td style='font-weight:bold;border:1px solid black;'>Name:</td><td style='border:1px solid black;'>{$_POST['inspector_name']}</td></tr>
      <tr><td style='font-weight:bold;border:1px solid black;'>License:</td><td style='border:1px solid black;'>{$_POST['inspector_license']}</td></tr>
      <tr><td style='font-weight:bold;border:1px solid black;'>Date:</td><td style='border:1px solid black;'>{$_POST['inspector_date']}</td></tr>
      <tr><td style='font-weight:bold;border:1px solid black;'>Inspector:</td><td style='border:1px solid black;'><img src='https://www.nouveauelevator.com/portal/media/images/category_test/i-{$Category_Test_ID}.jpg?v=1' style='width:100%;' /></td></tr>
      </tbody></table>
      </body>
      </html>";
      //echo $message;
      //echo $message;
      $Arranger = "WebServices";

      $headers = array();
      $headers[] = "MIME-Version: 1.0";
      $headers[] = "Content-Type: text/html; charset=UTF-8";
      $headers[] = "Mesaage-id: " .generateMessageID();
      $headers[] = "From: 'WebServices' <$from>";
      $headers[] = "Reply-To: $Arranger <$replyto>";
      $headers[] = "Date: $date";
      $headers[] = "Return-Path: <$from>";
      $headers[] = "X-Priority: 3";//1 = High, 3 = Normal, 5 = Low
      $headers[] = "X-Mailer: PHP/" . phpversion();
      //$_SESSION['Email'] = $_POST['Email'];
      //mail($to, $subject, $message, implode("\r\n", $headers));
      //$message = implode("\r\n",$headers) . $message;

      //require('cgi-bin/libraries/PHPMailer-master/src/Exception.php');
      //require('cgi-bin/libraries/PHPMailer-master/src/PHPMailer.php');
      //require('cgi-bin/libraries/PHPMailer-master/src/SMTP.php');
      $mail = new PHPMailer(true);
      try {
          //Server settings
          $mail->SMTPDebug = 2;                                       // Enable verbose debug output
          $mail->isSMTP();                                            // Set mailer to use SMTP
          $mail->Host       = 'smtp.gmail.com';  // Specify main and backup SMTP servers
          $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
          $mail->Username   = 'webservices@nouveauelevator.com';                     // SMTP username
          $mail->Password   = 'daxlxnzndgvwczth';                               // SMTP password
          $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
          $mail->Port       = 587;                                    // TCP port to connect to

          //Recipients
          $mail->setFrom('webservices@nouveauelevator.com', 'Web Services');
          $_POST['Email'] = "psperanza@nouveauelevator.com";
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

          // Content
          $mail->isHTML(true);                                  // Set email format to HTML
          $mail->Subject = $subject;
          $mail->Body = $message;
          ob_start();
          $mail->send();
          ob_end_clean();
          //echo 'Message has been sent';
      } catch (Exception $e) {
          //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
      }
    }
}
?>
