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
    || !isset($_POST['IDS'],$_POST['Worker'])
    || !is_array($_POST['IDS']) || !is_numeric($_POST['Worker']) || !($_POST['Worker'] > 0)){?><html><head><script>document.location.href='../login.php';</script></head></html><?php }
  else {
    $r = sqlsrv_query($NEI, "SELECT fWork FROM nei.dbo.Emp WHERE ID = ?;", array($_POST['Worker']));
    $fWork = NULL;
    if($r){$fWork = sqlsrv_fetch_array($r)['fWork'];}
    if(is_null($fWork)){return;}
    if(count($_POST['IDS']) > 0){
      foreach($_POST['IDS'] as $ID){
        if(is_numeric($ID) && $ID > 0){
          sqlsrv_query($NEI,"UPDATE nei.dbo.TicketO SET fWork = ?, Assigned = 1 WHERE ID = ?;", array($fWork, $ID));
        }
      }
    }
  }
}?>
