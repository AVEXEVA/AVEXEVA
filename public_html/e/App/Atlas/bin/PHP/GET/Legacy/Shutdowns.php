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
	 if( isset($My_Privileges['Ticket'])
        && (
				$My_Privileges['Ticket']['User_Privilege'] >= 4
			||	$My_Privileges['Ticket']['Group_Privilege'] >= 4
			||	$My_Privileges['Ticket']['Other_Privilege'] >= 4)){
            $Privileged = True;}
    if(!isset($Connection['ID']) || !$Privileged){print json_encode(array('data'=>array()));}
	else {
        if(isset($_GET['Mechanic']) && !$Field && is_numeric($_GET['Mechanic']) && $My_Privileges['Ticket']['Other_Privilege'] >= 4){$Mechanic = $_GET['Mechanic'];}
        elseif($My_Privileges['Ticket']['User_Privilege'] >= 4) {$Mechanic = is_numeric($_SESSION['User']) ? $_SESSION['User'] : -1;}
        if($Mechanic > 0){

            $Call_Sign = "";
            $r = sqlsrv_query($NEI,"select * from Emp where ID = '" . $Mechanic . "'");
            $array = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC);
            $Call_Sign = $array['CallSign'];
            $Alias = $array['fFirst'][0] . $array['Last'];
            $Employee_ID = $array['fWork'];
            while($array = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC)){}
            //GET TICKETS
            $Start_Date = isset($_GET['Start_Date']) ? date('Y-m-d 00:00:00.000',strtotime($_GET['Start_Date'])) : date('Y-m-d', strtotime('-1 days'));
        	$End_Date = isset($_GET['End_Date']) ? date('Y-m-d 00:00:00.000', strtotime($_GET['End_Date'])) : date('Y-m-d', strtotime('+1 days'));
			$r = sqlsrv_query($NEI,
      "  SELECT Top 1000 Tickets.*,
					   Loc.ID            AS Account,
             Loc.Loc AS Location_ID,
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
	           Zone.Name AS Division,
	           CASE WHEN Elev.State <> '' THEN
	           		 CASE 	 WHEN Elev.Unit <> '' THEN Elev.State + ' - ' + Elev.Unit
	           		 		 ELSE Elev.State
	           		  END
	           ELSE Elev.Unit END AS Unit_Name,
					   JobType.Type      AS Job_Type
				FROM (SELECT TicketD.ID       AS ID,
								TicketD.fDesc       AS Description,
								CONVERT(bigint, TicketD.WorkOrder)		    AS Work_Order,
								TicketD.DescRes  AS Resolution,
								TicketD.CDate       AS Created,
								TicketD.DDate       AS Dispatched,
								TicketD.EDate       AS Date,
								TicketD.TimeSite    AS On_Site,
								TicketD.TimeComp    AS Completed,
								TicketD.Who 	      AS Caller,
								TicketD.fBy         AS Reciever,
								TicketD.Level       AS Level,
								TicketD.Cat         AS Category,
								TicketD.Loc         AS Location,
								TicketD.Job         AS Job,
								TicketD.Elev       AS Unit,
								TicketD.fWork       AS Mechanic,
								TicketD.Total    AS Total,
								TicketD.Reg      AS Regular,
								TicketD.OT       AS Overtime,
								TicketD.DT       AS Doubletime
						 FROM   nei.dbo.TicketD
						 WHERE  (TicketD.fDesc LIKE '%shutdown%' OR TicketD.fDesc LIKE '%S/D%' OR TicketD.fDesc LIKE '%shut down%')
                    AND TicketD.CDate >= ?
                    AND TicketD.Level = 1
                    AND TicketD.WorkOrder <> '9117202818'
                    AND (
                      LEN(TicketD.WorkOrder) <= 10 AND TicketD.WorkOrder NOT LIKE N'%[^-0-9]%' AND CONVERT(bigint, TicketD.WorkOrder) BETWEEN 0 AND 2147483647 AND TicketD.ID = TicketD.WorkOrder
                      AND (TicketD.WorkOrder NOT IN (SELECT TicketO.WorkOrder FROM nei.dbo.TicketO WHERE TicketO.WorkOrder <> '9117202818' AND LEN(TicketO.WorkOrder) <= 10 AND TicketO.WorkOrder NOT LIKE N'%[^-0-9]%' AND CONVERT(bigint, TicketO.WorkOrder) BETWEEN 0 AND 2147483647))
                    )
                    AND ISNUMERIC(TicketD.ID) = 1

					) AS Tickets
					LEFT JOIN nei.dbo.Loc          ON Tickets.Location = Loc.Loc
					LEFT JOIN nei.dbo.Job          ON Tickets.Job      = Job.ID
					LEFT JOIN nei.dbo.Elev         ON Tickets.Unit     = Elev.ID
					LEFT JOIN nei.dbo.OwnerWithRol ON Job.Owner    = OwnerWithRol.ID 
					LEFT JOIN nei.dbo.Emp          ON Tickets.Mechanic = Emp.fWork
					LEFT JOIN nei.dbo.JobType      ON Job.Type         = JobType.ID
					LEFT JOIN nei.dbo.Zone 		   ON Zone.ID          = Loc.Zone
      ORDER BY Tickets.Created DESC
			",array(date("Y-m-d H:i:s", strtotime("-30 days"))),array("Scrollable"=>SQLSRV_CURSOR_KEYSET));
			if( ($errors = sqlsrv_errors() ) != null) {
        foreach( $errors as $error ) {
            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".$error[ 'message']."<br />";
        }
    }
			$data = array();
			$row_count = sqlsrv_num_rows( $r );
      $statuses = array(
        0=>'Open',
        1=>'Assigned',
        2=>'En Route',
        3=>'On Site',
        4=>'Completed',
        5=>'On Hold',
        6=>'Reviewing'
      );
      $levels = array(
      	1=>'Service Call',
      	2=>'Trucking',
      	3=>'Modernization',
      	4=>'Violation',
      	5=>'DLM',
      	6=>'Repair',
      	7=>'Annual Test',
      	8=>'Escalator',
      	9=>'Email',
      	10=>'Maintenance',
      	11=>'Survey',
      	12=>'Engineering',
      	13=>'Support',
      	14=>'M&R',
      	16=>'HLS Days',
      	17=>'Service Extras',
      	18=>'Training'
      );
    	if($r){
				while($i < $row_count){
					$Ticket = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC);
					if(is_array($Ticket) && $Ticket != array()){
            $r2 = sqlsrv_query($NEI,
              " SELECT  Sum(TicketD.Total) AS Sum
                FROM    nei.dbo.TicketD
                WHERE   TicketD.WorkOrder = ?
              ;", array($Ticket['ID']));
            $Ticket['Shutdown_Work'] = $r2 ? sqlsrv_fetch_array($r2)['Sum'] : 'Unknown';
            $r2 = sqlsrv_query($NEI,
              " SELECT  Max(TicketD.EDate) AS Max
                FROM    nei.dbo.TicketD
                WHERE   (LEN(TicketD.WorkOrder) <= 10
                        AND TicketD.WorkOrder NOT LIKE N'%[^-0-9]%'
                        AND CONVERTbigintint, TicketD.WorkOrder) BETWEEN 0 AND 2147483647
                        AND (
                          (
                                TicketD.WorkOrder = ?
                            AND TicketD.WorkOrder <> 0)
                        ))
                        OR TicketD.ID = ?
              ;", array($Ticket['Work_Order'], $Ticket['ID']));
            $Ticket['Fixed'] = $r2 ? sqlsrv_fetch_array($r2)['Max'] : 'Unknown';
            $Ticket['Downtime'] = round(abs(strtotime($Ticket['Created']) - strtotime($Ticket['Fixed'])) / 60,2). " minute";
            $Ticket['Fixed'] = date('m/d/Y h:i a',strtotime($Ticket['Fixed']));
      			$Ticket['Status'] = $statuses[$Ticket['Assigned']];
      			$Ticket['Level'] = $levels[$Ticket['Level']];
      			$Ticket['Work_Order'] = $Ticket['Work_Order'] == 0 ? NULL : $Ticket['Work_Order'];
      			$Ticket['Created'] = date('m/d/Y h:i a', strtotime($Ticket['Created']));
      			$Ticket['Dispatched'] = date('m/d/Y h:i a', strtotime($Ticket['Dispatched']));
      			$Ticket['Date'] = date('m/d/Y h:i a', strtotime($Ticket['Date']));
            $Ticket['Tags'] = array();
      			$Ticket['Tags'][] = $Ticket['Level'] == 'Maintenance' ? 'Maintenance' : NULL;
      			$Ticket['Tags'][] = strrpos($Ticket['Description'], 'shutdown') !== false  || strrpos($Ticket['Description'], 's/d') !== false  || strrpos($Ticket['Description'], 'S/D') !== false ? 'Shutdown' : NULL;
      			$Ticket['Tags'][] = strrpos($Ticket['Description'], 'P/T') !== false || strrpos($Ticket['Description'], 'trapped') !== false  || strrpos($Ticket['Description'], 'entrapment') !== false ? 'Entrapment' : NULL;
      			$Ticket['Tags'][] = strrpos($Ticket['Description'], 'H/O') !== false || strrpos($Ticket['Description'], 'Hold Over') !== false  || strrpos($Ticket['Description'], 'HO') !== false ? 'Hold Over' : NULL;
      			$Ticket['Tags'][] = strrpos($Ticket['Description'], 'OOS') !== false || strrpos($Ticket['Description'], 'Out of Service') !== false  || strrpos($Ticket['Description'], '') !== false ? 'Out of Service' : NULL;
      			$Ticket['Tags'][] = strrpos($Ticket['Description'], 'D/T') !== false || strrpos($Ticket['Description'], 'Door Trouble') !== false  || strrpos($Ticket['Description'], '') !== false ? 'Door Trouble' : NULL;
      			$Ticket['Tags'][] = strrpos($Ticket['Description'], 'F/U') !== false || strrpos($Ticket['Description'], 'Follow up') !== false  || strrpos($Ticket['Description'], '') !== false ? 'Follow Up' : NULL;
      			$Ticket['Tags'][] = strrpos($Ticket['Description'], 'T/S') !== false || strrpos($Ticket['Description'], 'Troubleshooting') !== false  || strrpos($Ticket['Trouble shooting'], '') !== false ? 'Troubleshooting' : NULL;
      			$Ticket['Tags'][] = strrpos($Ticket['Description'], 'Daily Ticket') !== false || strrpos($Ticket['Description'], '') !== false  || strrpos($Ticket['Trouble shooting'], '') !== false ? 'Daily Ticket' : NULL;
            $Ticket['Tags'][] = strrpos($Ticket['Description'], 'PVT') !== false ? 'PVT' : NULL;
            $Ticket['Tags'][] = strrpos($Ticket['Description'], 'ECB') !== false ? 'ECB' : NULL;
            $Ticket['Tags'][] = strrpos($Ticket['Description'], 'M/R') !== false ? 'M/R' : NULL;
            $Ticket['Tags'][] = $Ticket['Assigned'] == 2 && round(abs(strtotime($Ticket['TimeRoute']) - strtotime('now')) / 60, 2) > 30 ? 'Late(?)' : NULL;
            $Ticket['Tags'] = array_filter($Ticket['Tags']);
            $Ticket['Tags'] = implode(', ', $Ticket['Tags']);
						$data[] = $Ticket;
					}
					$i++;
				}
			}
      print json_encode(array('data'=>$data));
    }
  }
}?>
