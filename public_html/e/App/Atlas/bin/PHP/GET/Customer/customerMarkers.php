<?php
session_start();
set_time_limit (30);
require('../index.php');
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
  $r = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.Connection WHERE Connector = ? AND Hash = ?;",array($_SESSION['User'],$_SESSION['Hash']));
  $array = sqlsrv_fetch_array($r);
  $Privileged = FALSE;
  if(!isset($_SESSION['Branch']) || $_SESSION['Branch'] == 'Nouveau Elevator'){
      $r = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.Emp WHERE ID = ?",array($_SESSION['User']));
      $My_User = sqlsrv_fetch_array($r);
      $Field = ($User['Field'] == 1 && $User['Title'] != "OFFICE") ? True : False;
      $r = sqlsrv_query($Portal,"
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
    $r = sqlsrv_query($NEI,
      "   SELECT  Elev.*,
                  Elev.State + ' - ' + Elev.Unit AS Unit_Name, 
                  Latitude.Value AS Latitude,
                  Longitude.Value AS Longitude
          FROM    nei.dbo.Elev 
                  LEFT JOIN nei.dbo.ElevTItem as Latitude ON Latitude.Elev = Elev.ID AND Latitude.ElevT = 8 AND Latitude.Line = 1
                  LEFT JOIN nei.dbo.ElevTItem as Longitude ON Longitude.Elev = Elev.ID AND Longitude.ElevT = 8 AND Longitude.Line = 2
          WHERE   Elev.Loc = ?
      ;", array(9615));
    $Units = array();
    if($r){while($row = sqlsrv_fetch_array($r)){
      $r2 = sqlsrv_query($NEI, 
        " SELECT  *
          FROM    nei.dbo.TicketO
          WHERE   TicketO.Assigned < 4
                  AND TicketO.Level = 3
                  AND TicketO.LElev = ?
        ;", array($row['ID']),array("Scrollable"=>SQLSRV_CURSOR_KEYSET));
      if($r2){
        $row['Running'] = sqlsrv_num_rows($r2) > 0 ? 'Modernizing' : 'Healthy';
      } else {
        $row['Running'] = 'Healthy';
      }
      if($row['Running'] == 'Healthy'){
        $r2 = sqlsrv_query($NEI, 
          " SELECT  *
            FROM    nei.dbo.TicketO
            WHERE   TicketO.Assigned < 4
                    AND (
                      TicketO.fDesc LIKE '%S/D%'
                      OR TicketO.fDesc LIKE '%Shutdown%'
                      OR TicketO.fDesc LIKE '%s/d%'
                      OR TicketO.fDesc LIKE '%car stuck%'
                    )
                    AND TicketO.LElev = ?
          ;", array($row['ID']),array("Scrollable"=>SQLSRV_CURSOR_KEYSET));
        if($r2){
          $row['Running'] = sqlsrv_num_rows($r2) > 0 ? 'Shutdown' : $row['Running'];
        } else {
          $row['Running'] = 'Healthy';
        }
      }
      if($row['Running'] == 'Healthy'){
        $r2 = sqlsrv_query($NEI, 
          " SELECT  *
            FROM    nei.dbo.TicketO
            WHERE   TicketO.Assigned < 4
                    AND TicketO.Level = 6
                    AND TicketO.LElev = ?
          ;", array($row['ID']),array("Scrollable"=>SQLSRV_CURSOR_KEYSET));
        if($r2){
          $row['Running'] = sqlsrv_num_rows($r2) > 0 ? 'Repairing' : $row['Running'];
        } else {
          $row['Running'] = 'Healthy';
        }
      }
      $Units[$row['ID']] = $row;
    }}
    print json_encode($Units);
  }
}?>
