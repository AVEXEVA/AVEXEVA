<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
session_start();
require('../../../cgi-bin/libraries/PHPMailer-master/src/Exception.php');
require('../../../cgi-bin/libraries/PHPMailer-master/src/PHPMailer.php');
require('../../../cgi-bin/libraries/PHPMailer-master/src/SMTP.php');
function generateMessageID()
{
  return sprintf(
    "<%s.%s@%s>",
    base_convert(microtime(), 10, 36),
    base_convert(bin2hex(openssl_random_pseudo_bytes(8)), 16, 36),
    $_SERVER['SERVER_NAME']
  );
}
function toInt($str)
{
  return preg_replace("/([^0-9\\.])/i", "", $str);
}
require('../index.php');
if(isset($_SESSION['User'],$_SESSION['Hash'])){
  $r = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.Connection WHERE Connector = ? AND Hash = ?;",array($_SESSION['User'],$_SESSION['Hash']));
  $array = sqlsrv_fetch_array($r);
  $Privileged = FALSE;
  if(!isset($_SESSION['Branch']) || $_SESSION['Branch'] == 'Nouveau Elevator'){
      $r = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.Emp WHERE ID = ?",array($_SESSION['User']));
      $My_User = sqlsrv_fetch_array($r);
      $Field = ($User['Field'] == 1 && $User['Title'] != "OFFICE") ? True : False;
      $r = sqlsrv_query($Portal,"
          SELECT Access_Table, User_Privilege, Group_Privilege, Other_Privilege
          FROM   Portal.dbo.Privilege
          WHERE  User_ID = ?
      ;",array($_SESSION['User']));
      $My_Privileges = array();
      while($array2 = sqlsrv_fetch_array($r)){$My_Privileges[$array2['Access_Table']] = $array2;}
      $Privileged = FALSE;
      if(isset($My_Privileges['Ticket']) && $My_Privileges['Ticket']['Other_Privilege'] >= 4){$Privileged = TRUE;}
  }
  if(!$Privileged
    || count($_POST) == 0
    || !isset($_POST['Location'],$_POST['Job'])
    || !is_numeric($_POST['Location']) || !is_numeric($_POST['Job'])
    || strlen($_POST['Date']) == 0){?><html><head><script>document.location.href='../login.php';</script></head></html><?php }
  else {
	$_POST['Unit'] = is_null($_POST['Unit']) ? NULL : $_POST['Unit'];

    $r = sqlsrv_query($NEI,
    " SELECT Max(Tickets.ID) AS ID
      FROM (
        (SELECT Max(ID) AS ID FROM nei.dbo.TicketO)
        UNION ALL
        (SELECT Max(ID) FROM nei.dbo.TicketD)
        UNION ALL (SELECT Max(ID) FROM nei.dbo.TicketDArchive)
      ) AS Tickets;
    ");
    if($r){$ID = sqlsrv_fetch_array($r)['ID'] + 1;}
    $r2 = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.Loc WHERE Loc.Loc = ?",array($_POST['Location']));
    if($r2){$Location = sqlsrv_fetch_array($r2);}


    $values = array_fill(0, count(array($ID, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $_POST['Date'], 1, $My_User['fWork'], 0, 0, NULL, $_POST['Description'], $My_User['fFirst'] . ' ' . $My_User['Last'], $My_User['fFirst'] . ' ' . $My_User['Last'], 0, $_POST['Location'], $_POST['Unit'], $Location['ID'], $Location['Tag'], 'LDesc3', 'LDesc4', 0, $_POST['Job'], 1, 'City', 'State', '00000', '0', '0', '0', '0', '0', '0', '0', '0', 1, '', '', '', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL,$ID, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0)), '?');
    $values = implode(',',$values);
    $r = sqlsrv_query($NEI,"SELECT Name FROM nei.dbo.Emp WHERE Emp.fWork = ?;", array($_POST['Worker']));
    $CallSign = sqlsrv_fetch_array($r)['Name'];

    sqlsrv_query($NEI,"INSERT INTO nei.dbo.TicketO(ID, CDate, DDate, EDate, Level, fWork, DWork, Type, Cat, fDesc, Who, fBy, LType, LID, LElev, LDesc1, LDesc2, LDesc3, LDesc4, Nature, Job, Assigned, City, State, Zip, Owner, Route, Terr,  Latt, fLong, CallIn, SpecType, SpecID, EN, Notes, fGroup, Source, High, Confirmed, Phone, Phone2, PriceL, Locked, Follow, Custom1, Custom2, Custom3, Custom4, Custom5, WorkOrder, TimeRoute, TimeSite, TimeComp, HandheldFieldsUpdated, BRemarks, CPhone, Custom6, Custom7, Custom8, Custom9, Custom10, SMile, EMile, idRolCustomContact, gpsStatus, ResolveSource, Comments, Internet, TFMCustom1, TFMCustom2, TFMCustom3, TFMCustom4, TFMCustom5, Est) VALUES({$values})", array($ID, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $_POST['Date'],$_POST['Level'], $_POST['Worker'], $CallSign, 0, 'None', $_POST['Description'], $_POST['Caller'], $My_User['fFirst'] . ' ' . $My_User['Last'], 0, $_POST['Location'], $_POST['Unit'], $Location['ID'], $Location['Tag'], 'LDesc3', 'LDesc4', 0, $_POST['Job'], isset($_POST['Worker']) && is_numeric($_POST['Worker']) && $_POST['Worker'] > 0 ? 1 : 0, 'City', 'NY', '00000', '0', '0', '0', '0', '0', '0', '0', '0', 1, '', '', 'Reference', 0, 0, '(', '(', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $ID, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0));

     if( ($errors = sqlsrv_errors() ) != null) {
        foreach( $errors as $error ) {
            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".$error[ 'message']."<br />";
        }
    }


    $_POST['ID'] = $ID;
    $r = sqlsrv_query($NEI,"
          SELECT  TicketO.*,
                  OwnerWithRol.Name AS Customer,
                  Loc.Tag AS Location,
                  Loc.Address AS Street,
                  Loc.City AS City,
                  Loc.State AS State,
                  Loc.Zip AS Zip,
                  Job.fDesc AS Job,
                  Elev.Unit AS Unit,
                  Emp.fFirst + ' ' + Emp.Last AS Worker
          FROM    nei.dbo.TicketO
                  LEFT JOIN nei.dbo.Job           ON TicketO.Job   = Job.ID
                  LEFT JOIN nei.dbo.OwnerWithRol  ON Job.OWner        = OwnerWithRol.ID
                  LEFT JOIN nei.dbo.Loc           ON TicketO.LID   = Loc.Loc
                  LEFT JOIN nei.dbo.Elev          ON TicketO.LElev  = Elev.ID
                  LEFT JOIN nei.dbo.Emp           ON TicketO.fWork = Emp.fWork
          WHERE   TicketO.ID = ?
        ;",array($_POST['ID']));

    $Ticket = sqlsrv_fetch_array($r);


    $to = 'psperanza@nouveauelevator.com';
    $from = "WebServices@NouveauElevator.com";
    $replyto = $from;
    $date = date("Y-m-d H:i:s");
    $subject = "Assistance: Ticket #{$_POST['ID']}";
    $Ticket['CDate'] = date('m/d/Y h:i A',strtotime($Ticket['CDate']));
	$levels = array(
		1=>'Service Call',
		2=>'Trucking',
		3=>'Modernization',
		4=>'Violation',
		5=>'DLM',
		6=>'Repair',
		7=>'Annual Test',
		8=>'Escalator',
		9=>'Email',
		10=>'Maintenance',
		11=>'Survey',
		12=>'Engineering',
		13=>'Support',
		14=>'M&R',
		16=>'HLS Days',
		17=>'Service Extras',
		18=>'Training'
	);
	$level = $levels[$_POST['Level']];
    $message = "<html>
<head>
<style>
td {padding:5px;}
tr {border-bottom:#555555;}
</style>
</head>
<body>
<table width='500px' style='background-color:#353535;color:white;'><tbody>
<tr><td colspan='2' style='font-size:18px;background-color:#252525;'><img src='https://www.nouveauelevator.com/Images/Icons/logo.png' width='25px' /> Nouveau Elevator</td></tr>
<tr><td colspan='2' style='text-decoration:underline;font-weight:bold;font-size:18px;background-color:#252525;'>Ticket #{$_POST['ID']}</td></tr>
<tr><td style='font-weight:bold;'>Location:</td><td>{$Ticket['Location']}</td></tr>
<tr><td style='font-weight:bold;'>Address:</td><td>{$Ticket['Street']},\n{$Ticket['City']}, {$Ticket['State']} {$Ticket['Zip']}</td></tr>
<tr><td style='font-weight:bold;'>Unit:</td><td>{$Ticket['Unit']}</td></tr>
<tr><td style='font-weight:bold;'>Caller:</td><td>{$_POST['Caller']}</td></tr>
<tr><td style='font-weight:bold;'>Phone:</td><td>{$_POST['CallPhone']}</td></tr>
<tr><td style='font-weight:bold;'>Email:</td><td>{$_POST['CallEmail']}</td></tr>
<tr><td style='font-weight:bold;'>Level:</td><td>{$level}</td></tr>
<tr><td style='font-weight:bold;'>Worker:</td><td>{$Ticket['Worker']}</td></tr>
<tr><td style='font-weight:bold;'>Description:</td><td><pre>{$Ticket['fDesc']}</pre></td></tr>
<tr><td style='font-weight:bold;'>Created:</td><td>{$Ticket['CDate']}</td></tr>
</tbody></table>
</body>
</html>";
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
    $mail = new PHPMailer(true);
    if($_POST['AutoEmail'] == 1){
      try {
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
          $Emails = array('psperanza@nouveauelevator.com', $_POST['CallEmail']);//, 'assistance@nouveauelevator.com', 'partners@nouveauelevator.com');
          if(count($Emails) > 0){
            foreach($Emails as $Email){
                $mail->addAddress($Email);     // Add a recipient
            }
          } else {
            $mail->addAddress($_POST['Email']);
          }

          //$mail->addAddress('ellen@example.com');               // Name is optional
          $mail->addReplyTo('webservices@nouveauelevator.com', 'NoReply');
          //$mail->addCC('cc@example.com');
          //$mail->addBCC('bcc@example.com');

          // Attachments
          //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
          //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

          // Content
          $mail->isHTML(true);                                  // Set email format to HTML
          $mail->Subject = $subject;
          $mail->Body    = $message;
          //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
          if($_POST['AutoEmail'] == 1){$mail->send();}
          echo 'Message has been sent';
      } catch (Exception $e) {
          echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
      }
    }
  }
}?>
