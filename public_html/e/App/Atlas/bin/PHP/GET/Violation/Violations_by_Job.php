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
	if( isset($My_Privileges['Violation']) 
	   	&& $My_Privileges['Violation']['Other_Privilege'] >= 4
	  	&& $My_Privileges['Job']['Other_Privilege'] >= 4){
			$Privileged = True;} 
	elseif(isset($My_Privileges['Violation']) 
		&& $My_Privileges['Violation']['Group_Privilege'] >= 4
		&& $My_Privileges['Job']['Group_Privilege'] >= 4
		&& is_numeric($_GET['ID'])){
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
			;", array($Location_ID, $My_User['fWork'],$Location_ID, $My_User['fWork'],$Location_ID, $My_User['fWork']));
			$Privileged = is_array(sqlsrv_fetch_array($r)) ? True : False;}
	elseif(isset($My_Privileges['Job'])
		&& $My_Privileges['Job']['User_Privilege'] >= 4
		&& $My_Privileges['Violation']['User_Privilege'] >= 4
		&& is_numeric($_GET['ID'])){
			//You can add violations security here...
			$Privileged = FALSE;}
    if(!isset($Connection['ID'])  || !is_numeric($_GET['ID']) || !$Privileged){?><html><head><script>document.location.href="../login.php?Forward=job<?php echo (!isset($_GET['ID']) || !is_numeric($_GET['ID'])) ? "s.php" : ".php?ID={$_GET['ID']}";?>";</script></head></html><?php }
    else {
		$data = array();
		$r = sqlsrv_query($NEI,"
			SELECT Violation.ID      AS ID,
				   Violation.Name    AS Name,
				   Violation.fDate   AS Date,
				   Violation.Status  AS Status,
				   Violation.Remarks AS Description
			FROM   nei.dbo.Violation
				   LEFT JOIN nei.dbo.Elev ON Violation.Elev = Elev.ID
				   LEFT JOIN nei.dbo.Loc  ON Elev.Loc       = Loc.Loc
			WHERE  Violation.Job = ?
		;",array($_GET['ID']));
		if($r){while($array = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC)){$data[] = $array;}}
		print json_encode(array('data'=>$data));
    }
}?>