<?php
require('../../index.php');
$Session = new \Session(array(
  'Reference' => 'HTML/' . $_GET['Name'],
  'Table' => $_GET['Name']
));
if( $Session->__validate() ){
  $Resource = sqlsrv_query(
    $Session->__get( 'Database' )->__get( 'Resource' ),
    " SELECT [Column].*
      FROM  (
        SELECT  ROW_NUMBER() OVER ( ORDER BY [Column].Position ASC ) AS Row,
                [Column].*
        FROM    (
          SELECT  [Database].[ID] AS Database_ID,
                  [Datatable].[ID] AS Datatable_ID,
                  [Datacolumn].[ID] AS Datacolumn_ID,
                  [columns].*,
                  [Datacolumn].[Display] AS Display,
                  [Datacolumn].[Datatype] AS Datatype,
                  CASE WHEN [Datacolumn].[Position] IS NULL
                    THEN 999
                    ELSE [Datacolumn].[Position]
                  END AS Position
          FROM    [information_schema].[columns]
                  LEFT JOIN [Portal2].dbo.[Database] ON [columns].[table_catalog] = [Database].[Name]
                  LEFT JOIN [Portal2].dbo.[Datatable] ON [columns].[table_name] = [Datatable].[Name]
                  LEFT JOIN [Portal2].dbo.[Datacolumn] ON [columns].[column_name] = [Datacolumn].[Name] AND Datacolumn.[Table] = [Datatable].ID
          WHERE   [Datatable].[ID] = ?
        ) AS [Column]
      ) AS [Column]
      WHERE   [Column].[Row] >= ?
              AND [Column].[Row] < ?
    ;",
    array(
      $Session->__get( 'GET' )[ 'Datatable' ],
      $Session->__get( 'GET' )[ 'start' ],
      $Session->__get( 'GET' )[ 'start' ] + $Session->__get( 'GET' )[ 'length' ]
    )
  );
  $Columns = array();
  while( $Row = sqlsrv_fetch_array( $Resource ) ){ $Columns[ ] = array(
    'Database' => $Row['Database_ID'],
    'Datatable' => $Row['Datatable_ID'],
    'Datacolumn' => $Row['Datacolumn_ID'],
    'Ordinal' => $Row['ORDINAL_POSITION'],
    'Name' => $Row['COLUMN_NAME'],
    'Display' => $Row['Display'],
    'Datatype' => $Row['Datatype'],
    'Position' => $Row['Position']
  );}
  $Output = array(
      "draw" => intval($_GET['draw']),
      "recordsTotal" => 500,
      "recordsFiltered" => 500,
      "data" => $Columns,
  );

  echo json_encode( $Output );
}?>
