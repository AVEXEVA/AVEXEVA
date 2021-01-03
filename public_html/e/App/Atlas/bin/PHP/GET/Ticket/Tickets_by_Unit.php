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
	 if( isset($My_Privileges['Ticket'],$My_Privileges['Unit'])
        && $My_Privileges['Ticket']['Other_Privilege'] >= 4
	  	&& $My_Privileges['Unit']['Other_Privilege'] >= 4){
            $Privileged = True;}
	elseif(isset($My_Privileges['Ticket'],$My_Privileges['Unit'])
		&& $My_Privileges['Ticket']['Group_Privilege'] >= 4
		&& $My_Privileges['Unit']['Group_Privilege'] >= 4){
			$r = sqlsrv_query($NEI,"
				SELECT Elev.Loc AS Location_ID
				FROM   nei.dbo.Elev
				WHERE  Elev.ID = ?
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
	elseif(isset($My_Privileges['Location'])
		&& $My_Privileges['Location']['User_Privilege'] >= 4
		&& $My_Privileges['Ticket']['User_Privilege'] >= 4
		&& is_numeric($_GET['ID'])){
			$r = sqlsrv_query($NEI,"
				SELECT Tickets.ID
				FROM
				(
					(
						SELECT TicketO.ID
						FROM   nei.dbo.TicketO
						WHERE  TicketO.LElev       = ?
						       AND TicketO.fWork = ?
					)
					UNION ALL
					(
						SELECT TicketD.ID
						FROM   nei.dbo.TicketD
						WHERE  TicketD.Elev       = ?
						       AND TicketD.fWork = ?
					)
					UNION ALL
					(
						SELECT TicketDArchive.ID
						FROM   nei.dbo.TicketDArchive
						WHERE  TicketDArchive.Elev       = ?
						       AND TicketDArchive.fWork = ?
					)
				) AS Tickets
			;", array($_GET['ID'], $My_User['fWork'],$_GET['ID'], $My_User['fWork'],$_GET['ID'], $My_User['fWork']));
			$Privileged = is_array(sqlsrv_fetch_array($r)) ? True : False;}
    if(!isset($Connection['ID'])  || !is_numeric($_GET['ID']) || !$Privileged){?><html><head><script>document.location.href="../login.php?Forward=job<?php echo (!isset($_GET['ID']) || !is_numeric($_GET['ID'])) ? "s.php" : ".php?ID={$_GET['ID']}";?>";</script></head></html><?php }
    else {
        $r = sqlsrv_query($NEI,"
			SELECT Tickets.*,
				   Loc.ID            AS Account,
				   Loc.Tag           AS Tag,
				   Loc.Tag           AS Location,
				   Loc.Address       AS Address,
				   Loc.Address       AS Street,
				   Loc.City          AS City,
				   Loc.State         AS State,
				   Loc.Zip           AS Zip,
				   Job.ID            AS Job_ID,
				   Job.fDesc         AS Job_Description,
				   OwnerWithRol.ID   AS Owner_ID,
				   OwnerWithRol.Name AS Customer,
				   Elev.Unit         AS Unit_Label,
				   Elev.State        AS Unit_State,
				   Emp.fFirst        AS Worker_First_Name,
				   Emp.Last          AS Worker_Last_Name,
				   Emp.fFirst + ' ' + Emp.Last AS Mechanic,
				   'Unknown'         AS ClearPR,
				   JobType.Type      AS Job_Type,
				   Tickets.Date      AS Worked,
				   Route.Name        AS Route,
				   Zone.Name         AS Division
			FROM (
					(SELECT TicketO.ID       AS ID,
							TicketO.fDesc    AS Description,
							''               AS Resolution,
							TicketO.CDate    AS Created,
							TicketO.DDate    AS Dispatched,
							TicketO.EDate    AS Date,
							TicketO.TimeSite AS On_Site,
							TicketO.TimeComp AS Completed,
							TicketO.Who 	 AS Caller,
							TicketO.fBy      AS Reciever,
							TicketO.Level    AS Level,
							TicketO.Cat      AS Category,
							TicketO.LID      AS Location,
							TicketO.Job      AS Job,
							TicketO.LElev    AS Unit,
							TicketO.Owner    AS Owner,
							TicketO.fWork    AS Mechanic,
							TickOStatus.Type AS Status,
							0                AS Total,
							0                AS Regular,
							0                AS Overtime,
							0                AS Doubletime,
							TicketO.fBy      AS Taken_By
					 FROM   nei.dbo.TicketO
							LEFT JOIN TickOStatus ON TicketO.Assigned = TickOStatus.Ref
					 WHERE  TicketO.LElev = ?
					)
					UNION ALL
					(SELECT TicketD.ID       AS ID,
							TicketD.fDesc    AS Description,
							TicketD.DescRes  AS Resolution,
							TicketD.CDate    AS Created,
							TicketD.DDate    AS Dispatched,
							TicketD.EDate    AS Date,
							TicketD.TimeSite AS On_Site,
							TicketD.TimeComp AS Completed,
							TicketD.Who 	 AS Caller,
							TicketD.fBy      AS Reciever,
							TicketD.Level    AS Level,
							TicketD.Cat      AS Category,
							TicketD.Loc      AS Location,
							TicketD.Job      AS Job,
							TicketD.Elev     AS Unit,
							Loc.Owner        AS Owner,
							TicketD.fWork    AS Mechanic,
							'Completed'      AS Status,
							TicketD.Total    AS Total,
							TicketD.Reg      AS Regular,
							TicketD.OT       AS Overtime,
							TicketD.DT       AS Doubletime,
							TicketD.fBy      AS Taken_By
					 FROM   nei.dbo.TicketD
							LEFT JOIN nei.dbo.Loc ON TicketD.Loc = Loc.Loc
					 WHERE  TicketD.Elev = ?
					)
					UNION ALL
					(SELECT TicketDArchive.ID       AS ID,
							TicketDArchive.fDesc    AS Description,
							TicketDArchive.DescRes  AS Resolution,
							TicketDArchive.CDate    AS Created,
							TicketDArchive.DDate    AS Dispatched,
							TicketDArchive.EDate    AS Date,
							TicketDArchive.TimeSite AS On_Site,
							TicketDArchive.TimeComp AS Completed,
							TicketDArchive.Who 	    AS Caller,
							TicketDArchive.fBy      AS Reciever,
							TicketDArchive.Level    AS Level,
							TicketDArchive.Cat      AS Category,
							TicketDArchive.Loc      AS Location,
							TicketDArchive.Job      AS Job,
							TicketDArchive.Elev     AS Unit,
							Loc.Owner               AS Owner,
							TicketDArchive.fWork    AS Mechanic,
							'Completed'             AS Status,
							TicketDArchive.Total    AS Total,
							TicketDArchive.Reg      AS Regular,
							TicketDArchive.OT       AS Overtime,
							TicketDArchive.DT       AS Doubletime,
							TicketDArchive.fBy      AS Taken_By
					 FROM   nei.dbo.TicketDArchive
							LEFT JOIN nei.dbo.Loc ON TicketDArchive.Loc = Loc.Loc
					 WHERE  TicketDArchive.Elev = ?
					)
				) AS Tickets
				LEFT JOIN nei.dbo.Loc          ON Tickets.Location = Loc.Loc
				LEFT JOIN nei.dbo.Job          ON Tickets.Job      = Job.ID
				LEFT JOIN nei.dbo.Elev         ON Tickets.Unit     = Elev.ID
				LEFT JOIN nei.dbo.OwnerWithRol ON Tickets.Owner    = OwnerWithRol.ID
				LEFT JOIN nei.dbo.Emp          ON Tickets.Mechanic = Emp.fWork
				LEFT JOIN nei.dbo.JobType      ON Job.Type         = JobType.ID
				LEFT JOIN nei.dbo.Route        ON Loc.Route        = Route.ID
				LEFT JOIN nei.dbo.Zone         ON Loc.Zone         = Zone.ID
		",array($_GET['ID'],$_GET['ID'],$_GET['ID']),array("Scrollable"=>SQLSRV_CURSOR_KEYSET));
		$data = array();
		$row_count = sqlsrv_num_rows( $r );
		if($r){
			while($i < $row_count){
				$Ticket = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC);
				if(is_array($Ticket) && $Ticket != array()){
					//Tags
					$Tags = array();
					if(strpos($Ticket['Description'],"s/d") !== false || strpos($Ticket['Description'],"S/D") !== false || strpos($Ticket['Description'],"shutdown") !== false){
						$Tags[] = "Shutdown";
					}
					if($Ticket['Level'] == 10){
						$Tags[] = "Maintenance";
					}
					if($Ticket['Level'] == 1){
						$Tags[] = "Service Call";
					}
					$Ticket['Tags'] = count($Tags) > 0 ? implode(", ",$Tags) : null;

					//On Site / Completed Time
					if($Ticket['On_Site'] == NULL || $Ticket['On_Site'] == ''){
						$Ticket['On_Site'] = 'None';
					} else {
						$Ticket['On_Site'] = date("H:i:s",strtotime($Ticket['On_Site']));
					}
					if($Ticket['Completed'] == NULL || $Ticket['Completed'] == ''){
						$Ticket['Completed'] = 'None';
					} else {
						$Ticket['Completed'] = date("H:i:s",strtotime($Ticket['Completed']));
					}
					if($Ticket['Created'] == NULL || $Ticket['Created'] == ''){
						$Ticket['Created'] = 'None';
					} else {
						$Ticket['Created'] = date("m/d/Y H:i:s",strtotime($Ticket['Created']));
					}
					if($Ticket['Dispatched'] == NULL || $Ticket['Dispatched'] == ''){
						$Ticket['Dispatched'] = 'None';
					} else {
						$Ticket['Dispatched'] = date("m/d/Y H:i:s",strtotime($Ticket['Dispatched']));
					}
					$data[] = $Ticket;
				}
				$i++;
			}
		}
		print json_encode(array('data'=>$data));
    }
}
sqlsrv_close($NEI);
?>
