<?php 
session_start();
require('index.php');
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
	 if( isset($My_Privileges['Unit'],$My_Privileges['Job']) 
        && $My_Privileges['Unit']['Other_Privilege'] >= 4
	  	&& $My_Privileges['Job']['Other_Privilege'] >= 4){
            $Privileged = True;}
	if(isset($My_Privileges['Unit'],$My_Privileges['Job'])
		&& $My_Privileges['Unit']['Group_Privilege'] >= 4
		&& $My_Privileges['Job']['Group_Privilege'] >= 4){
			$r = sqlsrv_query($NEI,"
				SELECT Job.Loc AS Location_ID
				FROM   nei.dbo.Job
				WHERE  Job.ID = ?
			;", array($_GET['ID']));
			$Location_ID = sqlsrv_fetch_array($r)['Location_ID'];
			$r = sqlsrv_query($NEI,"
				SELECT Tickets.ID
				FROM 
				(
					(
						SELECT TicketO.ID
						FROM   nei.dbo.TicketO
						WHERE  TicketO.LID       = ?
						       AND TicketO.fWork = ? 
					) 
					UNION ALL
					(
						SELECT TicketD.ID
						FROM   nei.dbo.TicketD
						WHERE  TicketD.Loc       = ?
						       AND TicketD.fWork = ? 
					)
					UNION ALL
					(
						SELECT TicketDArchive.ID
						FROM   nei.dbo.TicketDArchive
						WHERE  TicketDArchive.Loc       = ?
						       AND TicketDArchive.fWork = ? 
					)
				) AS Tickets
			;", array($Location_ID, $My_User['fWork'], $Location_ID, $My_User['fWork'], $Location_ID, $My_User['fWork']));
			if(is_array(sqlsrv_fetch_array($r))){$Privileged = True;}}
	if(isset($My_Privileges['Job'])
		&& $My_Privileges['Job']['User_Privilege'] >= 4
		&& $My_Privileges['Unit']['User_Privilege'] >= 4
		&& is_numeric($_GET['ID'])){
			$r = sqlsrv_query($NEI,"
				SELECT Tickets.ID
				FROM 
				(
					(
						SELECT TicketO.ID
						FROM   nei.dbo.TicketO
						WHERE  TicketO.Job       = ?
						       AND TicketO.fWork = ? 
					) 
					UNION ALL
					(
						SELECT TicketD.ID
						FROM   nei.dbo.TicketD
						WHERE  TicketD.Job       = ?
						       AND TicketD.fWork = ? 
					)
					UNION ALL
					(
						SELECT TicketDArchive.ID
						FROM   nei.dbo.TicketDArchive
						WHERE  TicketDArchive.Job       = ?
						       AND TicketDArchive.fWork = ? 
					)
				) AS Tickets
			;", array($_GET['ID'], $My_User['fWork'],$_GET['ID'], $My_User['fWork'],$_GET['ID'], $My_User['fWork']));
			if(is_array(sqlsrv_fetch_array($r))){$Privileged = True;}}
    if(!isset($Connection['ID'])  || !is_numeric($_GET['ID']) || !$Privileged){print json_encode(array('data'=>array()));}
	else {
		$r = sqlsrv_query($NEI, "
			SELECT Units.Unit_ID AS ID,
				   Elev.State    AS State,
				   Elev.Unit     AS Unit,
				   Elev.Type     AS Type,
				   Elev.fDesc    AS fDesc,
				   Elev.Status   AS Status,
				   Loc.Tag       AS Location
			FROM (
				SELECT Units.Unit_ID 
				FROM
				(
					(
						SELECT TicketO.LElev AS Unit_ID
						FROM   nei.dbo.TicketO
						WHERE  TicketO.Job = ?
					)
					UNiON ALL
					(
						SELECT TicketD.Elev AS Unit_ID
						FROM   nei.dbo.TicketD
						WHERE  TicketD.Job = ?
					)
					UNION ALL 
					(
						SELECT TicketDArchive.Elev AS Unit_ID
						FROM   nei.dbo.TicketDArchive 
						WHERE  TicketDArchive.Job = ?
					)
				) AS Units 
				WHERE Units.Unit_ID IS NOT NULL
				GROUP BY Units.Unit_ID
			) AS Units
			LEFT JOIN nei.dbo.Elev ON Elev.ID  = Units.Unit_ID
			LEFT JOIN nei.dbo.Loc  ON Elev.Loc = Loc.Loc
		;",array($_GET['ID'], $_GET['ID'], $_GET['ID']));
		$data = array();
		if($r){while($array = sqlsrv_fetch_array($r)){$data[] = $array;}}
		print json_encode(array("data"=>$data));
    }
}?>