<?php
session_start();
set_time_limit (30);
require('../index.php');
$r = sqlsrv_query($Databases['Default'],
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
  $r2 = sqlsrv_query($Databases['Default'],
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
    $r2 = sqlsrv_query($Databases['Default'],
      " SELECT  *
        FROM    nei.dbo.TicketO
        WHERE   TicketO.Assigned < 4
                AND (TicketO.Level = 1 OR TicketO.Level = 6)
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
    $r2 = sqlsrv_query($Databases['Default'],
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
