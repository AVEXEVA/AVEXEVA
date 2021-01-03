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
  if(!$Privileged || count($_POST) == 0 || !isset($_POST['Option']) || !($_POST['Option'] == '0' || $_POST['Option'] == '1' || $_POST['Option'] == 'null')){?><html><head><script>document.location.href='../login.php';</script></head></html><?php }
  else {
    if($_POST['Option'] == 'null'){$_POST['Option'] = null;}
    if(is_array($_POST['ID_Set']) && count($_POST['ID_Set']) > 0){
      foreach($_POST['ID_Set'] AS $index=>$ID){ 
        sqlsrv_query($Portal, "UPDATE Portal.dbo.[Location] SET [Location].GPS = ? WHERE [Location].[Total_Service_ID] = ?;", array($_POST['Option'], $ID));
      }
    }
  }
}?>
