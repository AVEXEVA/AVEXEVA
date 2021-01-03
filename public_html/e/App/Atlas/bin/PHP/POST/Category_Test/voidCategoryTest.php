<?php
session_start();
require('../index.php');
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
       if(isset($_POST['ID_Set'])){
	$IDs = explode(',', $_POST['ID_Set']);
	foreach($IDs as $ID){
       $r = sqlsrv_query($Portal,"UPDATE [Portal].dbo.[Category_Test] SET [Final] = 0 WHERE ID = ?", array($ID));
}
	}
    }
}
?>
