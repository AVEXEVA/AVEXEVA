<?php
session_start();
require('cgi-bin/php/index.php');
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
		sqlsrv_query($NEI,"
			INSERT INTO Portal.dbo.Activity([User], [Date], [Page])
			VALUES(?,?,?)
		;",array($_SESSION['User'],date("Y-m-d H:i:s"), "dispatch_ticket.php?ID=" . $_GET['ID']));
		$r = sqlsrv_query($NEI,
			"SELECT Tickets.*,
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
			           CASE WHEN Elev.State <> '' THEN
			           		 CASE 	 WHEN Elev.Unit <> '' THEN Elev.State + ' - ' + Elev.Unit 
			           		 		 ELSE Elev.State 
			           		  END 
			           ELSE Elev.Unit END AS Unit_Name,

			           Emp.fFirst        AS Worker_First_Name,
			           Emp.Last          AS Worker_Last_Name,
					   JobType.Type      AS Job_Type
				FROM (SELECT TicketO.ID       AS ID,
								TicketO.fDesc       AS Description,
								TicketO.WorkOrder 		    AS Work_Order,
								TicketDPDA.DescRes  AS Resolution,
								TicketO.CDate       AS Created,
								TicketO.DDate       AS Dispatched,
								TicketO.EDate       AS Date,
								TicketO.TimeSite    AS On_Site,
								TicketO.TimeComp    AS Completed,
								TicketO.Who 	      AS Caller,
								TicketO.fBy         AS Reciever,
								TicketO.Level       AS Level,
								TicketO.Cat         AS Category,
								TicketO.LID         AS Location,
								TicketO.Job         AS Job,
								TicketO.LElev       AS Unit,
								TicketO.Owner       AS Owner,
								TicketO.fWork       AS Mechanic,
								TickOStatus.Type    AS Status,
								TicketDPDA.Total    AS Total,
								TicketDPDA.Reg      AS Regular,
								TicketDPDA.OT       AS Overtime,
								TicketDPDA.DT       AS Doubletime,
                0                   AS ClearPR,
                TicketO.Assigned    AS Assigned
						 FROM   nei.dbo.TicketO
						        LEFT JOIN TickOStatus ON TicketO.Assigned = TickOStatus.Ref
                    LEFT JOIN nei.dbo.TicketDPDA ON TicketDPDA.ID = TicketO.ID
						 WHERE  TicketO.ID = ?
					) AS Tickets
					LEFT JOIN nei.dbo.Loc          ON Tickets.Location = Loc.Loc
					LEFT JOIN nei.dbo.Job          ON Tickets.Job      = Job.ID
					LEFT JOIN nei.dbo.Elev         ON Tickets.Unit     = Elev.ID
					LEFT JOIN nei.dbo.OwnerWithRol ON Tickets.Owner    = OwnerWithRol.ID
					LEFT JOIN nei.dbo.Emp          ON Tickets.Mechanic = Emp.fWork
					LEFT JOIN nei.dbo.JobType      ON Job.Type         = JobType.ID
			", array($_GET['ID']));
		if( ($errors = sqlsrv_errors() ) != null) {
        foreach( $errors as $error ) {
            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".$error[ 'message']."<br />";
        }
    }
		$Ticket = sqlsrv_fetch_array($r);

		?><div class='Ticket'></div><?php
    }
} else {?><html><head><script>document.location.href='../login.php?Forward=units.php';</script></head></html><?php }?>
