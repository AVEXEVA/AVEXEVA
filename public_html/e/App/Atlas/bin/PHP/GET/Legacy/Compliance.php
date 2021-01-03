<?php
session_start();
$_GET['Type'] = isset($_GET['Type']) ? $_GET['Type'] : 'Live';
require('../../../cgi-bin/php/index.php');
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
	   	|| !isset($My_Privileges['Admin'])
	  		|| $My_Privileges['Admin']['User_Privilege']  < 4
	  		|| $My_Privileges['Admin']['Group_Privilege'] < 4
	  	    || $My_Privileges['Admin']['Other_Privilege'] < 4){
				?><?php require('../404.html');?><?php }
    else {
		sqlsrv_query($NEI,"
			INSERT INTO Portal.dbo.Activity([User], [Date], [Page])
			VALUES(?,?,?)
		;",array($_SESSION['User'],date("Y-m-d H:i:s"), "accounting.php"));
    $_GET['REFRESH'] = isset($_GET['REFRESH']) ? $_GET['REFRESH'] : date("Y-M-D H:i:s", strtotime("-7 days"));
    $r = sqlsrv_query($NEI,
      " SELECT  Top 1000
                CreatedStamp,
                fUser,
                Loc.Tag AS Location_Tag,
                Ref AS ID,
                'Changed ' + Field + ' from ' + OldVal + ' to ' + NewVal + '' AS Action
        FROM    nei.dbo.Log2
                LEFT JOIN nei.dbo.Violation ON Violation.ID = Log2.Ref
                LEFT JOIN nei.dbo.Loc ON Violation.Loc = Loc.Loc
        WHERE   Log2.Screen = 'Violation'
                AND CreatedStamp >= ?
      ;", array($_GET['REFRESH']));
    $data = array();
    if($r){while($row = sqlsrv_fetch_array($r)){$data[] = $row;}}
    print json_encode($data);
  }
} ?>
