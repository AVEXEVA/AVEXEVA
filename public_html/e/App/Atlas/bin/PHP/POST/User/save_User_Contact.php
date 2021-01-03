<?php
session_start();
require('../get/index.php');
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
        $Privileged = TRUE;
    }
    if(!$Privileged || count($_POST) == 0){?><html><head><script>document.location.href='../login.php';</script></head></html><?php }
    else {
      if(isset($_POST['Email'], $_POST['Phone'])){
        $r = sqlsrv_query($Portal, "SELECT * FROM Portal.dbo.Person WHERE Person.Employee = ?", array($_SESSION['User']));
        $row = sqlsrv_fetch_array($r);
        if(is_array($row) && count($row) > 0){
          sqlsrv_query($Portal, "UPDATE Portal.dbo.Person SET Person.Email = ?, Person.Phone = ? WHERE Person.Employee = ?;", array($_POST['Email'], $_POST['Phone'], $_SESSION['User']));
        } else {
          sqlsrv_query($Portal, "INSERT INTO Portal.dbo.Person(Employee, Email, Phone) VALUES(?, ?, ?);", array($_SESSION['User'], $_POST['Email'], $_POST['Phone']));
        }
      }
    }
}?>
