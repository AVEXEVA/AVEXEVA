<?php
session_start();
require('index.php');
if(isset($_SESSION['User'],$_SESSION['Hash'])){
    $r = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.Connection WHERE Connector = ? AND Hash = ?;",array($_SESSION['User'],$_SESSION['Hash']));
    $array = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC);
    $r = sqlsrv_query($NEI,"
        SELECT User_Privilege, Group_Privilege, Other_Privilege
        FROM   Portal.dbo.Privilege
        WHERE User_ID = ? AND Access_Table='Admin'
    ;",array($_SESSION['User']));
    $My_Privileges = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC);
    if(!isset($array['ID']) || !is_array($My_Privileges)){?><html><head><script>document.location.href='../login.php';</script></head></html><?php }
    else {
        $data = array();
        $r = sqlsrv_query($NEI,
          " SELECT  Emp.fFirst + ' ' + Emp.Last AS Mechanic,
                    Route.Name AS Route,
                    Units.Count AS Units,
                    Tickets.Count AS Tickets,
                    Tickets2.Count AS Other_Tickets
            FROM    nei.dbo.Route
                    LEFT JOIN nei.dbo.Emp ON Route.Mech = Emp.fWork
                    LEFT JOIN (
                      SELECT  Location.Route AS Route,
                              Count(Elev.ID) AS Count
                      FROM    nei.dbo.Loc AS Location
                              LEFT JOIN nei.dbo.Elev ON Location.Loc = Elev.Loc
                      WHERE   Location.Maint = 1
                              AND Elev.Status = 0
                      GROUP BY  Location.Route
                    ) AS Units ON Units.Route = Route.ID
                    LEFT JOIN (
                      SELECT  Location.Route AS Route,
                              Count(TicketO.ID) AS Count
                      FROM    nei.dbo.Loc AS Location
                              LEFT JOIN nei.dbo.TicketO ON Location.Loc = TicketO.LID
                              LEFT JOIN nei.dbo.Elev ON TicketO.LElev = Elev.ID
                              LEFT JOIN nei.dbo.Route AS R ON Location.Route = R.ID
                      WHERE   TicketO.Level = 10
                              AND Location.Maint = 1
                              AND TicketO.Assigned = 1
                              AND Elev.Status = 0
                              AND TicketO.EDate >= ?
                              AND TicketO.EDate < ?
                              AND TicketO.fWork = R.Mech
                      GROUP BY  Location.Route
                    ) AS Tickets ON Tickets.Route = Route.ID
                    LEFT JOIN (
                      SELECT  TicketO.fWork AS fWork,
                              Count(TicketO.ID) AS Count
                      FROM    nei.dbo.Loc AS Location
                              LEFT JOIN nei.dbo.TicketO ON Location.Loc = TicketO.LID
                              LEFT JOIN nei.dbo.Elev ON TicketO.LElev = Elev.ID
                              LEFT JOIN nei.dbo.Route AS R ON Location.Route = R.ID
                      WHERE   TicketO.Level = 10
                              AND Location.Maint = 1
                              AND TicketO.Assigned = 1
                              AND Elev.Status = 0
                              AND TicketO.EDate >= ?
                              AND TicketO.EDate < ?
                              AND TicketO.fWork <> R.Mech
                      GROUP BY  TicketO.fWork
                    ) AS Tickets2 ON Tickets2.fWork = Emp.fWork
          ;", array(date("Y-m-01 00:00:00.000"), date("Y-m-01 00:00:00.000", strtotime("+1 month")), date("Y-m-01 00:00:00.000"), date("Y-m-01 00:00:00.000", strtotime("+1 month"))));
          if( ($errors = sqlsrv_errors() ) != null) {
        foreach( $errors as $error ) {
            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".$error[ 'message']."<br />";
        }
    }
        while($row = sqlsrv_fetch_Array($r)){
          $data[] = $row;
        }
        print json_encode(array('data'=>$data));
    }
}?>
