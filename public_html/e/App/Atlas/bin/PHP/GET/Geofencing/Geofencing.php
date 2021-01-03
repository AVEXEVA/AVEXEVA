<?php
session_start();
require('../index.php');
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
	 if( isset($My_Privileges['Ticket'])
        && (
				  $My_Privileges['Ticket']['User_Privilege'] >= 7
			||	$My_Privileges['Ticket']['Group_Privilege'] >= 7
			||	$My_Privileges['Ticket']['Other_Privilege'] >= 7)){
            $Privileged = True;}
    if(!isset($Connection['ID']) || !$Privileged){print json_encode(array('data'=>array()));}
	else {
    sqlsrv_query($Portal,"
			INSERT INTO Portal.dbo.Activity([User], [Date], [Page])
			VALUES(?,?,?)
		;",array($_SESSION['User'],date("Y-m-d H:i:s"), "geofence_report.php"));
    $resource = sqlsrv_query($Portal_44,
      " SELECT  Geofence.Employee_ID AS ID,AVG([Distance]) AS Average
  FROM [Portal].[dbo].[Geofence]
  LEFT JOIN nei.dbo.Emp ON Geofence.Employee_ID = Emp.ID
  WHERE Time_Stamp >= ? AND Distance <> '99999'
  GROUP BY Geofence.Employee_ID  ORDER BY Average DESC      ;",array(date("Y-m-d H:i:s", strtotime("-2 days"))));
    if($resource){while($row = sqlsrv_fetch_array($resource)){
      $data[$row['ID']] = $row;
    }}
	$resource = sqlsrv_query($Portal, "SELECT Emp.ID, Emp.fFirst, Emp.Title, Emp.Last, tblWork.Super FROM nei.dbo.Emp LEFT JOIN nei.dbo.tblWork ON 'A' + convert(varchar(10),Emp.ID) + ',' = tblWork.Members;");
	while($row = sqlsrv_fetch_array($resource)){
		if(!isset($data[$row['ID']])){continue;}
		$data[$row['ID']]['fFirst'] = $row['fFirst'];
		$data[$row['ID']]['Last'] = $row['Last'];
		$data[$row['ID']]['Super'] = $row['Super'];
		$data[$row['ID']]['Title'] = $row['Title'];
	}
	$data2 = array();
	foreach($data as $row){
		$data2[] = $row;
	}
    print json_encode(array('data'=>$data2));
  }
}?>
