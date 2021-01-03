<?php
session_start();
require('../index.php');
if(isset($_SESSION['User'],$_SESSION['Hash'])){
  $r = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.Connection WHERE Connector = ? AND Hash = ?;",array($_SESSION['User'],$_SESSION['Hash']));
  $array = sqlsrv_fetch_array($r);
  $Privileged = FALSE;
  if(!isset($_SESSION['Branch']) || $_SESSION['Branch'] == 'Nouveau Elevator'){
      $r = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.Emp WHERE ID = ?",array($_GET['User']));
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
      if(isset($My_Privileges['Ticket']) && $My_Privileges['Ticket']['User_Privilege'] >= 6){$Privileged = TRUE;}
  }
  if(!$Privileged || count($_POST) == 0 || !isset($_POST['ID']) || !is_numeric($_POST['ID'])){?><html><head><script>document.location.href='../login.php';</script></head></html><?php }
  else {
    /*Create TicketDPDA*/
    $r = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.TicketO WHERE TicketO.ID = ?;",array($_POST['ID']));
    $r2 = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.TicketDPDA WHERE TicketDPDA.ID = ?",array($_POST['ID']));
    $ticket2 = $r2 ? sqlsrv_fetch_array($r2) : null;
    if($r && !is_array($ticket2)){
      $row = sqlsrv_fetch_array($r);
      if(is_array($row)){
        sqlsrv_query($NEI,"UPDATE nei.dbo.TicketO SET TicketO.WorkOrder = ? WHERE TicketO.ID = ?;",array($_POST['Work_Order'],$_POST['ID']));
      }
    } elseif($r && is_array($ticket2)){
      $row = sqlsrv_fetch_array($r);
      if(is_array($row)){
        sqlsrv_query($NEI,"UPDATE nei.dbo.TicketO SET TicketO.WorkOrder = ? WHERE TicketO.ID = ?;",array($_POST['Work_Order'],$_POST['ID']));
      }
    }
  }
}?>