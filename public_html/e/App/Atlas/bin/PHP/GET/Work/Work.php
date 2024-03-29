<?php
session_start();
require('../../index.php');
if(isset($_SESSION['User'],$_SESSION['Hash'])){
    $r = sqlsrv_query($Databases['NEI'],"
		SELECT *
		FROM   nei.dbo.Connection
		WHERE  Connection.Connector = ?
			   AND Connection.Hash = ?
	;", array($_SESSION['User'],$_SESSION['Hash']));
    $Connection = sqlsrv_fetch_array($r);
	$My_User    = sqlsrv_query($Databases['NEI'],"
		SELECT Emp.*,
			   Emp.fFirst AS First_Name,
			   Emp.Last   AS Last_Name
		FROM   nei.dbo.Emp
		WHERE  Emp.ID = ?
	;", array($_SESSION['User']));
	$My_User = sqlsrv_fetch_array($My_User);
	$My_Field = ($My_User['Field'] == 1 && $My_User['Title'] != "OFFICE") ? True : False;
	$r = sqlsrv_query($Databases['Portal'],"
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
				$My_Privileges['Ticket']['User_Privilege'] >= 4
			||	$My_Privileges['Ticket']['Group_Privilege'] >= 4
			||	$My_Privileges['Ticket']['Other_Privilege'] >= 4)){
            $Privileged = True;}
    if(!isset($Connection['ID']) || !$Privileged){print json_encode(array('data'=>array()));}
	else {
      //$Type = $_GET['Type'] == 'Service_Calls' ? "Tickets.Level = 1" : "Tickets.Level <> 1";
      $Type = '';
			$r = sqlsrv_query($Databases[ 'Default' ],
      "   SELECT Tickets.*,
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
					   'Unknown'         AS ClearPR,
					   JobType.Type      AS Job_Type,
             Tickets.Level AS Level
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
                TicketO.Assigned,
                TicketO.High AS Priority
						 FROM   nei.dbo.TicketO
						        LEFT JOIN TickOStatus ON TicketO.Assigned = TickOStatus.Ref
                    LEFT JOIN Emp ON Emp.fWork = TicketO.fWork
						 WHERE  Emp.ID = ?
                    AND ((TicketO.Assigned >= 1
                    AND TicketO.Assigned <= 4)
                    OR (TicketO.EDate >= ?
                    AND TicketO.EDate < ?)
                    OR (TicketO.Assigned = 6))
						)
					) AS Tickets
					LEFT JOIN nei.dbo.Loc          ON Tickets.Location = Loc.Loc
					LEFT JOIN nei.dbo.Job          ON Tickets.Job      = Job.ID
					LEFT JOIN nei.dbo.Elev         ON Tickets.Unit     = Elev.ID
					LEFT JOIN nei.dbo.OwnerWithRol ON Tickets.Owner    = OwnerWithRol.ID
					LEFT JOIN nei.dbo.Emp          ON Tickets.Mechanic = Emp.fWork
					LEFT JOIN nei.dbo.JobType      ON Job.Type         = JobType.ID
        ORDER BY Tickets.Assigned ASC
			",array($_SESSION['User'],date("Y-m-d 00:00:00.000"),date("Y-m-d 00:00:00.000",strtotime('tomorrow'))),array("Scrollable"=>SQLSRV_CURSOR_KEYSET));
			$data = array();
			$row_count = sqlsrv_num_rows( $r );
    	if($r){
				while($i < $row_count){
					$Ticket = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC);
					if(is_array($Ticket) && $Ticket != array()){
            $levels = array(
              0=>'',
              1=>'Service Call',
              2=>'Trucking',
              3=>'Modernization',
              4=>'Violations',
              5=>'Level 5',
              6=>'Repair',
              7=>'Annual',
              8=>'Escalator',
              9=>'Email',
              10=>'Maintenance',
              11=>'Survey',
              12=>'Engineering',
              13=>'Support',
              14=>"M/R"
            );
            $Ticket['Level'] = isset($Ticket['Level']) && isset($levels[$Ticket['Level']]) ? $levels[$Ticket['Level']] : 'Unknown';
            $Ticket['Status'] = strlen($Ticket['Status']) > 0 ? $Ticket['Status'] : 'Reviewing';
						$data[] = $Ticket;
					} else {

          }
					$i++;
				}
			}
      $r = sqlsrv_query($Databases[ 'Default' ],"SELECT Tickets.*,
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
                     Elev.Unit         AS Unit_Label,
                     Elev.State        AS Unit_State,
                     Emp.fFirst        AS Worker_First_Name,
                     Emp.Last          AS Worker_Last_Name,
           'Unknown'         AS ClearPR,
           JobType.Type      AS Job_Type,
           Tickets.Level AS Level
      FROM (
          (SELECT TicketD.ID       AS ID,
              TicketD.fDesc    AS Description,
              ''               AS Resolution,
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
              TicketD.Elev    AS Unit,
              TicketD.fWork    AS Mechanic,
              'Completed'      AS Status,
              0                AS Total,
              0                AS Regular,
              0                AS Overtime,
              0                AS Doubletime
           FROM   nei.dbo.TicketD
                  LEFT JOIN Emp ON Emp.fWork = TicketD.fWork
           WHERE  Emp.ID = ?
                  AND TicketD.EDate >= ?
                  AND TicketD.EDate < ?
          )
        ) AS Tickets
        LEFT JOIN nei.dbo.Loc          ON Tickets.Location = Loc.Loc
        LEFT JOIN nei.dbo.Job          ON Tickets.Job      = Job.ID
        LEFT JOIN nei.dbo.Elev         ON Tickets.Unit     = Elev.ID
        LEFT JOIN nei.dbo.Emp          ON Tickets.Mechanic = Emp.fWork
        LEFT JOIN nei.dbo.JobType      ON Job.Type         = JobType.ID",array($_SESSION['User'],date("Y-m-d 00:00:00.000"),date("Y-m-d 00:00:00.000",strtotime('tomorrow'))),array("Scrollable"=>SQLSRV_CURSOR_KEYSET));
        $row_count = sqlsrv_num_rows( $r );
        $i = 0;
        if($r){
  				while($i < $row_count){
  					$Ticket = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC);
  					if(is_array($Ticket) && $Ticket != array()){
              $levels = array(
                0=>'',
                1=>'Service Call',
                2=>'Trucking',
                3=>'Modernization',
                4=>'Violations',
                5=>'Level 5',
                6=>'Repair',
                7=>'Annual',
                8=>'Escalator',
                9=>'Email',
                10=>'Maintenance',
                11=>'Survey',
                12=>'Engineering',
                13=>'Support',
                14=>"M/R"
              );
              $Ticket['Level'] = isset($Ticket['Level']) && isset($levels[$Ticket['Level']]) ? $levels[$Ticket['Level']] : 'Unknown';
              $Ticket['Status'] = strlen($Ticket['Status']) > 0 ? $Ticket['Status'] : 'Reviewing';
  						$data[] = $Ticket;
              $i++;
  					} else {

            }

  				}
  			}
      print json_encode(array('data'=>$data));
    }
}
