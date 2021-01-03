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
        	$R = sqlsrv_query($NEI,"SELECT LID FROM nei.dbo.TicketO WHERE TicketO.ID = ?", array($_GET['ID']));
        	if($R){
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
						   Loc.Latt 		 AS Latitude,
						   Loc.fLong 		 AS Longitude,
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
				           Emp.ID 			 AS Employee_ID,
				           Emp.fFirst        AS Worker_First_Name,
				           Emp.Last          AS Worker_Last_Name,
				           Emp.fFirst + ' ' + Emp.Last          AS Worker_Name,
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
							        LEFT JOIN nei.dbo.TickOStatus ON TicketO.Assigned = TickOStatus.Ref
	                    			LEFT JOIN nei.dbo.TicketDPDA  ON TicketDPDA.ID = TicketO.ID
							 WHERE  TicketO.LID = ?

						) AS Tickets
						LEFT JOIN nei.dbo.Loc          ON Tickets.Location = Loc.Loc
						LEFT JOIN nei.dbo.Job          ON Tickets.Job      = Job.ID
						LEFT JOIN nei.dbo.Elev         ON Tickets.Unit     = Elev.ID
						LEFT JOIN nei.dbo.OwnerWithRol ON Tickets.Owner    = OwnerWithRol.ID
						LEFT JOIN nei.dbo.Emp          ON Tickets.Mechanic = Emp.fWork
						LEFT JOIN nei.dbo.JobType      ON Job.Type         = JobType.ID
						LEFT JOIN nei.dbo.Zone 		   ON Zone.ID          = Loc.Zone
					WHERE  Tickets.Level <> 4 AND Tickets.Level <> 7
				",array(sqlsrv_fetch_array($R)['LID']),array("Scrollable"=>SQLSRV_CURSOR_KEYSET));
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
            			$Ticket['Status'] = $statuses[$Ticket['Assigned']];
            			$Ticket['Level'] = $levels[$Ticket['Level']];
            			$Ticket['Work_Order'] = $Ticket['Work_Order'] == 0 ? NULL : $Ticket['Work_Order'];
            			$Ticket['Created'] = date('m/d/Y h:i a', strtotime($Ticket['Created']));
            			$Ticket['Dispatched'] = date('m/d/Y h:i a', strtotime($Ticket['Dispatched'])); 
            			$Ticket['Date'] = date('m/d/Y h:i a', strtotime($Ticket['Date']));
            			$Ticket['Tags'] = $Ticket['Level'] == 'Maintenance' ? 'Maintenance' : NULL;
            			$Ticket['Tags'] = strrpos($Ticket['Description'], 'shutdown') !== false  || strrpos($Ticket['Description'], 's/d') !== false  || strrpos($Ticket['Description'], 'S/D') !== false ? 'Shutdown' : $Ticket['Tags'];
            			$Ticket['Tags'] = strrpos($Ticket['Description'], 'P/T') !== false || strrpos($Ticket['Description'], 'trapped') !== false  || strrpos($Ticket['Description'], 'entrapment') !== false ? 'Entrapment' : $Ticket['Tags'];
						$data[] = $Ticket;
					}
					$i++;
				}
			}
		}
      print json_encode(array('data'=>$data));
    }
  }
}?>
