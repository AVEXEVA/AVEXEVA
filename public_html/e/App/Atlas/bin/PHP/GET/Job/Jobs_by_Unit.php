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
    if( isset($My_Privileges['Unit'], $My_Privileges['Job']) 
        && $My_Privileges['Unit']['Other_Privilege'] >= 4
	  	&& $My_Privileges['Job']['Other_Privilege'] >= 4){
            $Privileged = True;} 
    elseif(isset($My_Privileges['Unit'], $My_Privileges['Job']) 
        && $My_Privileges['Unit']['Group_Privilege'] >= 4
		&& $My_Privileges['Job']['Group_Privilege'] >= 4
        && is_numeric($_GET['ID'])){
			$r = sqlsrv_query($NEI,"
				SELECT Elev.Loc AS Location_ID
				FROM   nei.dbo.Elev
				WHERE  Elev.ID = ?
			;",array($_GET['ID']));
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
    elseif(isset($My_Privileges['Unit'])
        && $My_Privileges['Unit']['User_Privilege'] >= 4
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
    if(!isset($Connection['ID'])  || !is_numeric($_GET['ID']) || !$Privileged){print json_encode(array('data'=>array()));}
    else {
        $data = array();
        $r = sqlsrv_query($NEI,"
            SELECT Jobs.*
            FROM 
            (
                (
                    SELECT
                        Job.ID            AS  ID,
                        Job.fDesc         AS  Name,
                        JobType.Type      AS  Type,
                        Job.fDate         AS  Date,
                        Job_Status.Status AS  Status,
                        Loc.Tag           AS  Location
                    FROM 
                        nei.dbo.TicketO
                        LEFT JOIN nei.dbo.Job        ON TicketO.Job    = Job.ID
                        LEFT JOIN nei.dbo.Loc        ON TicketO.LID    = Loc.Loc
                        LEFT JOIN nei.dbo.Job_Status ON Job.Status + 1 = Job_Status.ID
                        LEFT JOIN nei.dbo.JobType    ON Job.Type      = JobType.ID
                    WHERE 
                        TicketO.LElev =  ?
                        AND Job.Type  <> 9 
                        AND Job.Type  <> 12
                )
                UNION ALL
                (
                    SELECT
                        Job.ID            AS  ID,
                        Job.fDesc         AS  Name,
                        JobType.Type      AS  Type,
                        Job.fDate         AS  Date,
                        Job_Status.Status AS  Status,
                        Loc.Tag           AS  Location
                    FROM 
                        nei.dbo.TicketD 
                        LEFT JOIN nei.dbo.Job        ON TicketD.Job    = Job.ID
                        LEFT JOIN nei.dbo.Loc        ON TicketD.Loc    = Loc.Loc
                        LEFT JOIN nei.dbo.Job_Status ON Job.Status + 1 = Job_Status.ID
                        LEFT JOIN nei.dbo.JobType    ON Job.Type       = JobType.ID
                    WHERE
                        TicketD.Elev = ?
                        AND Job.Type <> 9 
                        AND Job.Type <> 12
                )   
                UNION ALL 
                (
                    SELECT
                        Job.ID            AS  ID,
                        Job.fDesc         AS  Name,
                        JobType.Type      AS  Type,
                        Job.fDate         AS  Date,
                        Job_Status.Status AS  Status,
                        Loc.Tag           AS  Location
                    FROM 
                        nei.dbo.TicketDArchive
                        LEFT JOIN nei.dbo.Job        ON TicketDArchive.Job = Job.ID
                        LEFT JOIN nei.dbo.Loc        ON TicketDArchive.Loc = Loc.Loc
                        LEFT JOIN nei.dbo.Job_Status ON  Job.Status + 1    = Job_Status.ID
                        LEFT JOIN nei.dbo.JobType    ON  Job.Type          = JobType.ID
                    WHERE  
                        TicketDArchive.Elev  =  ?
                        AND Job.Type        <> 9 
                        AND Job.Type        <> 12
                )
            ) AS Jobs
        GROUP BY Jobs.ID, Jobs.Name, Jobs.Type, Jobs.Date, Jobs.Status, Jobs.Location
        ;",array($_GET['ID'],$_GET['ID'],$_GET['ID']));
        if($r){while($array = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC)){$data[] = $array;}}
        print json_encode(array('data'=>$data));
    }
}?>

