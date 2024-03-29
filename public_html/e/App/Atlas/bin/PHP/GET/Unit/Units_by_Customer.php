<?php 
session_start();
require('index.php');
setlocale(LC_MONETARY, 'en_US');
if(isset($_SESSION['User'],$_SESSION['Hash'])){
    $r = sqlsrv_query($NEI,"
		SELECT * 
		FROM   nei.dbo.Connection 
		WHERE  Connection.Connector = ? 
			   AND Connection.Hash = ?
	;", array($_SESSION['User'],$_SESSION['Hash']));
    $Connection = sqlsrv_fetch_array($r);
	$My_User    = sqlsrv_query($NEI,"
		SELECT Emp.*, 
			   Emp.fFirst AS First_Name, 
			   Emp.Last   AS Last_Name 
		FROM   nei.dbo.Emp
		WHERE  Emp.ID = ?
	;", array($_SESSION['User']));
	$My_User = sqlsrv_fetch_array($My_User); 
	$My_Field = ($My_User['Field'] == 1 && $My_User['Title'] != "OFFICE") ? True : False;
	$r = sqlsrv_query($Portal,"
		SELECT Privilege.Access_Table, 
			   Privilege.User_Privilege, 
			   Privilege.Group_Privilege, 
			   Privilege.Other_Privilege
		FROM   Portal.dbo.Privilege
		WHERE  Privilege.User_ID = ?
	;",array($_SESSION['User']));
	$My_Privileges = array();
	while($array2 = sqlsrv_fetch_array($r)){$My_Privileges[$array2['Access_Table']] = $array2;}
	$Privileged = False;
	 if( isset($My_Privileges['Unit'],$My_Privileges['Customer']) 
        && $My_Privileges['Unit']['Other_Privilege'] >= 4
	  	&& $My_Privileges['Customer']['Other_Privilege'] >= 4){
            $Privileged = True;}
    if(!isset($Connection['ID'])  || !is_numeric($_GET['ID']) || !$Privileged){print json_encode(array('data'=>array()));}
	else {
		$r = sqlsrv_query($NEI,"
			SELECT Elev.ID     AS ID,
				   Elev.State  AS State, 
				   Elev.Unit   AS Unit,
				   Elev.Type   AS Type,
				   Loc.Tag     AS Location,
				   Elev.Status AS Status,
				   Elev.fDesc  AS Description
			FROM   nei.dbo.Elev
				   LEFT JOIN nei.dbo.Loc ON Loc.Loc = Elev.Loc
			WHERE  Loc.Owner = ?
		;",array($_GET['ID']));
		$data = array();
		if($r){while($array = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC)){
			$Unit = $array;
			$r2 = sqlsrv_query($NEI,"
				SELECT *
				FROM   nei.dbo.ElevTItem
				WHERE  ElevTItem.ElevT    = 1
					   AND ElevTItem.Elev = ?
			;",array($Unit['ID']));
			if($r2){while($array2 = sqlsrv_fetch_array($r2,SQLSRV_FETCH_ASSOC)){$Unit[$array2['fDesc']] = $array2['Value'];}}
			$r3 = sqlsrv_query($NEI,"
				SELECT *
				FROM   nei.dbo.ElevTItem
				WHERE  ElevTItem.ElevT    = 1
					   AND ElevTItem.Elev = ?
			;",array(0));
			if($r3){while($array3 = sqlsrv_fetch_array($r3,SQLSRV_FETCH_ASSOC)){if(!isset($Unit[$array3['fDesc']])){$Unit[$array3['fDesc']] = '';}}}
			$data[] = $Unit;
		}}
		print json_encode(array('data'=>$data));   	
	}
}