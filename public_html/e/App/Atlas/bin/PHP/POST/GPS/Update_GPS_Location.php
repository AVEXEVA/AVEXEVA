<?php
session_start();
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
      if(isset($My_Privileges['Location']) && $My_Privileges['Location']['User_Privilege'] >= 7){$Privileged = TRUE;}
  }
  if(!$Privileged || count($_POST) == 0){?><html><head><script>document.location.href='../login.php';</script></head></html><?php }
  else {
    if(isset($_POST['action']) && $_POST['action'] == 'edit'){
        if(isset($_POST['data']) && count($_POST['data']) > 0){
          $data = array();
          foreach($_POST['data'] as $ID=>$data_set){
            $data[] = $data_set;
            sqlsrv_query($NEI, "UPDATE nei.dbo.Loc SET Loc.fLong = ?, Loc.Latt = ? WHERE Loc.Loc = ?", array($data_set['fLong'], $data_set['Latt'], $ID));
            if(isset($data_set['Distance'])){
              sqlsrv_query($Portal, "UPDATE [Portal].dbo.[Location] SET [Location].Distance = ? WHERE [Location].[Total_Service_ID] = ?;", array($data_set['Distance'], $ID));
            }
          }
          print json_encode(array("data"=>$data));
        }
      }
  }
}?>
