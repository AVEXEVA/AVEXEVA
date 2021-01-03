<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
session_start();
set_time_limit (120);
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
  if(!isset($array['ID']) || !$Privileged || count($_POST) == 0)){?><html><head><script>document.location.href='../login.php';</script></head></html><?php }
  else {
    if(isset($_POST['IDS']) && is_array($_POST['IDS']) && count($_POST['IDS']) > 0){
      foreach($IDS as $ID){
        if(is_numeric($ID) && $ID > 0){
          sqlsrv_query($NEI,"UPDATE nei.dbo.TicketO SET Comments = 'Behind Time' WHERE ID = ?;", array($ID));
        }
      }
    }
  }
}?>
