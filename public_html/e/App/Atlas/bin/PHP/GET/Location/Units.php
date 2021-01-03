<?php
require('../../index.php');
$Session = new Session();
if($Session->__validate() && $Session->access( 'Location' ) && $Session->access( 'Unit' )){
  new Log('GET/Location/Units.php');
  $r = sqlsrv_query($Databases['Default'],
    " SELECT  Elev.ID     AS ID,
    				  Elev.State  AS State,
    				  Elev.Unit   AS Unit,
    				  Elev.Type   AS Type,
    				  Loc.Tag     AS Location,
    				  Elev.Status AS Status,
    				  Elev.fDesc  AS Description,
    				  Elev.Building AS Building
      FROM    Elev
              LEFT JOIN Loc ON Loc.Loc = Elev.Loc
              LEFT JOIN OwnerWithRol ON Loc.Owner = OwnerWithRol.ID
      WHERE   OwnerWithRol.ID = ?
    ;",array($_SESSION['Customer']));
  $data = array();
  if($r){while($row = sqlsrv_fetch_array($r)){
    $data[] = $row;
  }}
  print json_encode( array( 'data' => $data ));
}?>
