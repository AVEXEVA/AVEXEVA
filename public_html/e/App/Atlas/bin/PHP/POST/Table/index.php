<?php
require('../../index.php');
$Session = new \Session(array(
  'Reference' => 'POST/Table'
));
if( $Session->__validate() ){
  $r = sqlsrv_query(
    $Session->__get( 'Database' ),
    " UPDATE  [Portal2].dbo.[Datatable]
      SET     [Datatable].[Menu] = ?
      WHERE   [Datatable].[ID]   = ?
    ;",
    array( $Session->__get( 'POST' )['Menu'], $Session->__get( 'POST')[ 'ID' ] )
  );
}?>
