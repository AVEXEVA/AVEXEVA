<?php
session_start();
set_time_limit (30);
require('../../index.php');
function distance($lat1, $lon1, $lat2, $lon2, $unit) {
  if (($lat1 == $lat2) && ($lon1 == $lon2)) {
    return 0;
  }
  else {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);

    if ($unit == "K") {
      return ($miles * 1.609344);
    } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
      return $miles;
    }
  }
}
if(isset($_SESSION['User'],$_SESSION['Hash'])){
  $r = sqlsrv_query($Databases['Default'],"SELECT * FROM nei.dbo.Connection WHERE Connector = ? AND Hash = ?;",array($_SESSION['User'],$_SESSION['Hash']));
  $array = sqlsrv_fetch_array($r);
  $Privileged = FALSE;
  if(!isset($_SESSION['Branch']) || $_SESSION['Branch'] == 'Nouveau Elevator'){
      $r = sqlsrv_query($Databases['Default'],"SELECT * FROM nei.dbo.Emp WHERE ID = ?",array($_SESSION['User']));
      $My_User = sqlsrv_fetch_array($r);
      $Field = ($User['Field'] == 1 && $User['Title'] != "OFFICE") ? True : False;
      $r = sqlsrv_query($Databases['Default'],"
          SELECT Access_Table, User_Privilege, Group_Privilege, Other_Privilege
          FROM   Portal.dbo.Privilege
          WHERE  User_ID = ?
      ;",array($_SESSION['User']));
      $My_Privileges = array();
      while($array2 = sqlsrv_fetch_array($r)){$My_Privileges[$array2['Access_Table']] = $array2;}
      $Privileged = FALSE;
      if(isset($My_Privileges['Map']) && $My_Privileges['Map']['User_Privilege'] >= 4 && $My_Privileges['Map']['User_Privilege'] >= 4 && $My_Privileges['Map']['User_Privilege'] >= 4){$Privileged = TRUE;}
  }
  if(!$Privileged){?><html><head><script>document.location.href='../login.php';</script></head></html><?php }
  else {
    $r = sqlsrv_query($Databases['Default'],
      " SELECT  TicketO.*,
                Loc.Latt AS Latitude,
                Loc.fLong AS Longitude,
                Loc.Tag AS Tag,
                Loc.Tag AS Title,
                Loc.Loc,
                Emp.fFirst + ' ' + Emp.Last AS Full_Name,
                Zone.Name AS Division,
                TicketO.Assigned
        FROM    nei.dbo.TicketO
                LEFT JOIN nei.dbo.Loc ON TicketO.LID = Loc.Loc
                LEFT JOIN nei.dbo.Emp ON Emp.fWork = TicketO.fWork
                LEFT JOIN nei.dbo.Zone ON Loc.Zone = Zone.ID
        WHERE   (TicketO.fDesc LIKE '%p/t%'
                OR TicketO.fDesc LIKE '%entrapment%'
                OR TicketO.fDesc LIKE '%trapped%')
                AND TicketO.fDesc NOT LIKE '%follow up%'
                AND TicketO.fDesc NOT LIKE '%h/o%'
                AND TicketO.fDesc NOT LIKE '%was p/t%'
                AND TicketO.fDesc NOT LIKE '%was trapped%'
                AND TicketO.fDesc NOT LIKE '%no entrapment%'
                AND TicketO.fDesc NOT LIKE '%was entrapment%'
                AND TicketO.fDesc NOT LIKE '%were trapped%'
                AND TicketO.Assigned < 40
                AND TicketO.Level = 1
      ;",array());
    $Shutdowns = array();
    if($r){while($row = sqlsrv_fetch_array($r)){
      $row['Title'] = strlen($row['Full_Name']) > 1 ? $row['Title'] . ' being serviced by ' . $row['Full_Name'] : $row['Title'] . ' is not being serviced.';
      $row['Serviced'] = $row['Assigned'] > 2 ? 1 : 0;
      $Shutdowns[$row['Loc']] = $row;
    }}
    print json_encode($Shutdowns);
  }
}?>