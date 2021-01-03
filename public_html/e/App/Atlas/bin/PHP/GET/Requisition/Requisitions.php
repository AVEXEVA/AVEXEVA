<?php
require('../../index.php');
$Session = new Session();
if($Session->__validate() && $Session->access( 'Requisition' )){
  $r = sqlsrv_query( $Session->__get('Database'),
    " SELECT  Requisition.ID AS ID,
              Emp.fFirst + ' ' + Emp.Last AS [User],
              Requisition.Date AS [Date],
              Requisition.Required AS Required,
              Loc.Tag AS Location,
              DropOff.Tag AS DropOff,
              Elev.State + ' - ' + Elev.Unit + ' - ' + Elev.fDesc AS Unit,
              Job.fDesc AS Job
      FROM    Portal.dbo.Requisition
              LEFT JOIN Loc ON Requisition.Location = Loc.Loc
              LEFT JOIN Loc AS DropOff ON Requisition.DropOff = DropOff.Loc
              LEFT JOIN Elev ON Requisition.Unit = Elev.ID
              LEFT JOIN Job ON Requisition.Job = Job.ID
              LEFT JOIN Emp ON Emp.ID = Requisition.[User];", array( ) );
  $data = array();
  sqlErrors();
  if($r){ while( $row = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC)){
    $row['Date']      = date("m/d/Y h:i A", strtotime( $row[ 'Date' ] ) );
    $row['Required']  = date("m/d/Y",       strtotime( $row[ 'Required' ] ) );
    $data[] = $row;
  }}
  print json_encode( array( 'data' => $data ) );
}?>
