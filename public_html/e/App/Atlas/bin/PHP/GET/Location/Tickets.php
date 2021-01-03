<?php
require('../../index.php');
$Session = new Session( array( 'Reference' => 'PHP/GET/Location/Tickets' ) );
if($Session->__validate() && $Session->access( 'Unit' ) && $Session->access( 'Ticket' )){
    new Log( array( 'Session' => $Session ) );
        $r = sqlsrv_query( $Session->__get('Database'),
          " SELECT Top 2000
                   Tickets.*,
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
        				   Zone.Name         AS Division,
                   Terr.Name         AS Territory
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
              LEFT JOIN nei.dbo.Terr         ON Terr.ID          = Loc.Terr
        WHERE Tickets.Location = ?
  		", array( $Session->__get('GET')['ID']), array( 'Scrollable' => SQLSRV_CURSOR_KEYSET ) );
		$data = array();
		while( $row = sqlsrv_fetch_array( $r ) ){
      $row['Date'] = date('m/d/Y h:i A', strtotime( $row[ 'Date' ] ) );
      $data[] = $row;
    }
		print json_encode(array('data'=>$data));
}
?>
