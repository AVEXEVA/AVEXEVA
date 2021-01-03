<?php
session_start();
require('index.php');
if(isset($_SESSION['User'],$_SESSION['Hash'])){
    $r = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.Connection WHERE Connector = ? AND Hash = ?;",array($_SESSION['User'],$_SESSION['Hash']));
    $array = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC);
    $User = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.Emp WHERE ID = ?",array($_GET['User']));
    $User = sqlsrv_fetch_array($User);
    $Field = ($User['Field'] == 1 && "OFFICE" != $User['Title']) ? True : False;
    $r = sqlsrv_query($Portal,"
            SELECT Access_Table, User_Privilege, Group_Privilege, Other_Privilege
            FROM   Portal.dbo.Privilege
            WHERE  User_ID = ?
        ;",array($_SESSION['User']));
    $My_Privileges = array();
    while($array2 = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC)){$My_Privileges[$array2['Access_Table']] = $array2;}
    $Privileged = false;
    if(isset($My_Privileges['Invoice']) && $My_Privileges['Invoice']['User_Privilege'] >= 4){$Privileged = true;}
    if(isset($_SESSION['Branch_ID']) && $_SESSION['Branch_ID'] == $_GET['ID']){$Privileged = true;}
    if(!isset($array['ID']) || !$Privileged){?><html><head><script>document.location.href='../login.php';</script></head></html><?php }
    else {
		$data = array();
    $Start = isset($_GET['Start']) && strlen($_GET['Start']) > 0 ? date('Y-m-d H:i:s', strtotime($_GET['Start'])) : '2017-03-30 00:00:00.000';
    $End = isset($_GET['End']) && strlen($_GET['End']) > 0 ? date('Y-m-d H:i:s', strtotime($_GET['End'])) : '2065-12-01 00:00:00.000';
    $Active_SQL = isset($_GET['Active']) && $_GET['Active'] == 1 ? "AND Loc.Maint = 1" : NULL;
    $SQL = "SELECT  Locations.*
    FROM (
      SELECT  Loc.Owner AS Customer_ID,
              CASE WHEN Loc.Maint = 1 THEN 'Maintained' ELSE '' END AS Active,
              CASE WHEN Loc.Custom3 <> '' THEN Loc.Custom3 ELSE Rol.Name END AS Customer_Name,
              Rol.Name AS Customer_N,
              Loc.Loc AS Location_ID,
              Loc.Tag AS Location_Name,
              Ticket_Count.Count AS Ticket_Count,
              Maintenance_Count.Count AS Maintenance_Count,
              Service_Count.Count AS Service_Count,
              Modernization_Count.Count AS Modernization_Count,
              Repair_Count.Count AS Repair_Count,
              Shutdown_Count.Count AS Shutdown_Count
      FROM    nei.dbo.Loc
              LEFT JOIN nei.dbo.Owner ON Owner.ID = Loc.Owner
              LEFT JOIN nei.dbo.Rol ON Owner.Rol = Rol.ID
              LEFT JOIN (SELECT Job.Loc, Count(TicketD.ID) AS Count FROM TicketD LEFT JOIN Job ON TicketD.Job = Job.ID  WHERE TicketD.EDate >= ? AND TicketD.EDate < ? GROUP BY Job.Loc) AS Ticket_Count ON Loc.Loc = Ticket_Count.Loc
              LEFT JOIN (SELECT Job.Loc, Count(TicketD.ID) AS Count FROM TicketD LEFT JOIN Job ON TicketD.Job = Job.ID WHERE Job.Type = 0 AND TicketD.Level = 10 AND TicketD.EDate >= ? AND TicketD.EDate < ?  GROUP BY Job.Loc) AS Maintenance_Count ON Loc.Loc = Maintenance_Count.Loc
              LEFT JOIN (SELECT Job.Loc, Count(TicketD.ID) AS Count FROM TicketD LEFT JOIN Job ON TicketD.Job = Job.ID WHERE Job.Type = 0 AND TicketD.Level = 1 AND TicketD.EDate >= ? AND TicketD.EDate < ?  GROUP BY Job.Loc) AS Service_Count ON Loc.Loc = Service_Count.Loc
              LEFT JOIN (SELECT Job.Loc, Count(TicketD.ID) AS Count FROM TicketD LEFT JOIN Job ON TicketD.Job = Job.ID WHERE Job.Type = 2 AND TicketD.EDate >= ? AND TicketD.EDate < ?   GROUP BY Job.Loc) AS Modernization_Count ON Loc.Loc = Modernization_Count.Loc
              LEFT JOIN (SELECT Job.Loc, Count(TicketD.ID) AS Count FROM TicketD LEFT JOIN Job ON TicketD.Job = Job.ID WHERE Job.Type = 6 AND TicketD.EDate >= ? AND TicketD.EDate < ?   GROUP BY Job.Loc) AS Repair_Count ON Loc.Loc = Repair_Count.Loc
              LEFT JOIN (SELECT Job.Loc, Count(TicketD.ID) AS Count FROM TicketD LEFT JOIN Job ON TicketD.Job = Job.ID
                          WHERE (TicketD.fDesc LIKE '%S/D%'
                                OR TicketD.fDesc LIKE '%Shutdown%'
                                OR TicketD.fDesc LIKE '%P/T%'
                                OR TicketD.fDesc LIKE '%PT%'
                                OR TicketD.fDesc LIKE '%Entrapment%')
                                AND TicketD.EDate >= ? AND TicketD.EDate < ?
                          GROUP BY Job.Loc) AS Shutdown_Count ON Loc.Loc = Shutdown_Count.Loc
        WHERE (Ticket_Count.Count <> 0)
              AND Loc.Loc NOT IN (8626, 5164, 5156, 6293, 5166, 8671, 5167, 8726, 8684, 8652, 8614, 8848, 8644, 8997)
              {$Active_SQL}
      ) AS Locations
    ;";
    $r = sqlsrv_query($NEI,$SQL,array($Start, $End, $Start, $End, $Start, $End, $Start, $End, $Start, $End, $Start, $End), array("Scrollable"=>SQLSRV_CURSOR_KEYSET));
    $row_count = sqlsrv_num_rows($r);
    $i = 0;
    $amount = 0;
		if($r){
      while($i < $row_count){
      $Customer = sqlsrv_fetch_array($r);
      foreach($Customer as $index=>$value){
        $Customer[$index] = is_null($value) ? 0 : $value;
      }
      $data[] = $Customer;
      $i++;
    }}
		print json_encode(array('data'=>$data));
	}
}?>
