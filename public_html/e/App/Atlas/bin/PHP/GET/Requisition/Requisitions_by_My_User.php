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
	 if( isset($My_Privileges['Requisition'],$My_Privileges['User']) 
        && $My_Privileges['Requisition']['User_Privilege'] >= 4){
            $Privileged = True;}
    if(!isset($Connection['ID']) || !$Privileged){print json_encode(array('data'=>array()));}
	else {
        $r = sqlsrv_query($NEI,"
            SELECT Requisition.ID      AS ID,
				   OwnerWithRol.Name   AS Customer,
				   Loc.Tag             AS Location,
				   Job.fDesc           AS Job,
				   Unit.State          AS Unit,
				   Requisition.Created AS Created,
				   CASE WHEN Requisition.Fulfilled <> '' THEN 'Fulfilled' ELSE CASE WHEN Requisition.Denied <> '' THEN 'Denied' ELSE 'Open' END END AS Status,
				   CASE WHEN Requisition.Fulfilled <> '' THEN Requisition.Fulfilled ELSE CASE WHEN Requisition.Denied <> '' THEN Requisition.Denied ELSE '' END END AS Status_Date
			FROM   Portal.dbo.Requisition
				   LEFT JOIN nei.dbo.OwnerWithRol ON Requisition.Customer = OwnerWithRol.ID
				   LEFT JOIN nei.dbo.Loc          ON Requisition.Loc      = Loc.Loc
				   LEFT JOiN nei.dbo.Job          ON Requisition.Job      = Job.ID
				   LEFT JOIN nei.dbo.Unit         ON Requisition.Unit     = Unit.ID
			WHERE  Requisitions.User = ?
		;",array($_SESSION['User']));
        $data = array();
        if($r){while($array = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC)){$data[] = $array;}}
        print json_encode(array('data'=>$data));	}
}?>