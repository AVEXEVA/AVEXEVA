<?php
require('../../index.php');
$Session = new \Session(array(
  'Reference' => 'HTML/' . $_GET['Name'],
  'Table' => $_GET['Name']
));
if( $Session->__validate() ){
  $Database = NULL;
  $Datatable = NULL;
  $Datacolumn = NULL;
  $Column = NULL;
  $Display = NULL;
  $Datatype = NULL;
  $Position = NULL;
  $SelectPrepare = sqlsrv_prepare(
    $Session->__get( 'Database' )->__get( 'Resource' ),
    " SELECT  [Datacolumn].[ID]
      FROM    [Portal2].dbo.[Datacolumn]
              LEFT JOIN [Portal2].dbo.[Datatable] ON Datacolumn.[Table] = [Datatable].[ID]
              LEFT JOIN [Portal2].dbo.[Database] ON Datatable.[Database] = [Database].[ID]
      WHERE       [Database].[ID] = ?
              AND [Datatable].[ID] = ?
              AND [Datacolumn].[ID] = ?
    ;",
    array(
      &$Database,
      &$Datatable,
      &$Datacolumn
    )
  );
  $UpdatePrepare = sqlsrv_prepare(
    $Session->__get( 'Database' )->__get( 'Resource' ),
    " UPDATE  [Portal2].dbo.[Datacolumn]
      SET     [Datacolumn].[Display] = ?,
              [Datacolumn].[Datatype] = ?,
              [Datacolumn].[Position] = ?
      WHERE   [Datacolumn].[ID] = ?
    ;",
    array(
      &$Display,
      &$Datatype,
      &$Position,
      &$Datacolumn
    )
  );
  $InsertPrepare = sqlsrv_prepare(
    $Session->__get( 'Database' )->__get( 'Resource' ),
    " INSERT INTO Portal2.dbo.Datacolumn([Table], [Name], [Display], [Datatype], [Position])
      VALUES( ?, ?, ?, ?, ?)
    ;",
    array(
      &$Datatable,
      &$Column,
      &$Display,
      &$Datatype,
      &$Position
    )
  );
  if( isset( $Session->__get( 'POST' )[ 'Datatable' ]) && is_array( $Session->__get( 'POST' )[ 'Datatable' ]) && count( $Session->__get( 'POST' )[ 'Datatable' ] ) > 0 ){
    foreach( $Session->__get( 'POST' )[ 'Datatable' ] AS $Index=>$Row ){
      $Database = $Row['Database'];
      $Datatable = $Row['Datatable'];
      $Datacolumn = $Row['Datacolumn'];
      $Column = $Row['Column'];
      $Display = $Row['Display'] == 'No' ? 0 : $Row['Display'] == 'Yes' ? 1 : 0;
      $Datatype = $Row['Datatype'];
      $Position = is_numeric($Row['Position']) ? $Row['Position'] : null;
      $Resource = sqlsrv_execute( $SelectPrepare );
      if( $Resource === false || is_null( $Row['Datacolumn'] ) || $Row['Datacolumn'] == '' ){ sqlsrv_execute( $InsertPrepare ); }
      else { sqlsrv_execute( $UpdatePrepare ); }
    }
  }
}?>
