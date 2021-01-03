<?php
session_start();
require('index.php');
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
	   	|| !isset($My_Privileges['Ticket'])
	  		|| $My_Privileges['Ticket']['User_Privilege']  < 4
	  		|| $My_Privileges['Ticket']['Group_Privilege'] < 4
        || $My_Privileges['Ticket']['Other_Privilege'] < 4){
				?><?php require('../404.html');?><?php }
    else {
		$data = array();
		$r = sqlsrv_query($Portal,
			"SELECT Service_Call.ID AS ID,
              Service_Call.Reason AS Reason,
              Service_Call.Date AS Date,
              Loc.Tag AS Location,
              OwnerWithRol.Name AS Customer,
              Company.Name AS Company,
              Individual.First_Name + ' ' + Individual.Last_Name AS Individual,
              Elev.State AS Device,
              OwnerWithRol.Name AS Customer
        FROM  [Portal].[dbo].[Service_Call]
              LEFT JOIN Portal.dbo.Individual ON Service_Call.Individual = Individual.ID
              LEFT JOIN Portal.dbo.Company    ON Individual.Company      = Company.ID
              LEFT JOIN nei.dbo.Loc           ON Service_Call.Location   = Loc.Loc
              LEFT JOIN nei.dbo.OwnerWithRol  ON Service_Call.Customer   = OwnerWithRol.ID
              LEFT JOIN nei.dbo.Elev          ON Service_Call.Device       = Elev.ID
		;",array());
		if($r){while($array = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC)){
      $array['Date'] = date('m/d/Y h:i:A', strtotime($array['Date']));
      $data[] = $array;
    }}
		print json_encode(array('data'=>$data));
    }
}?>
