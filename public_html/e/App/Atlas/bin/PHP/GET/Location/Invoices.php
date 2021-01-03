<?php
require('../../index.php');
$Session = new Session();
if($Session->__validate() && $Session->access( 'Location' ) && $Session->access( 'Invoice' )){
  new Log('GET/Location/Units.php');
  $r = sqlsrv_query($Databases['Default'],
    " SELECT Invoice.Ref         AS  ID,
             Invoice.fDesc       AS  Description,
             Invoice.Total       AS  Total,
             Job.ID              AS  Job,
             Job.fDesc           AS  Job_Description,
             Loc.Tag             AS  Location,
             Invoice.fDate       AS  Date,
             Invoice.Status      AS  Status
      FROM   Invoice
             LEFT JOIN Loc ON Invoice.Loc = Loc.Loc
             LEFT JOIN Job ON Invoice.Job = Job.ID
      WHERE  Loc.Loc = ?;", array( $Session->__get('GET')['ID'] ) );
  $data = array();
  if($r){while($row = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC)){
    $row[ 'Date' ] = date( 'm/d/Y H:i A', strtotime( $row[ 'Date' ] ) );
    $data[] = $row;
  }}
  print json_encode( array( 'data' => $data ) );
}?>
