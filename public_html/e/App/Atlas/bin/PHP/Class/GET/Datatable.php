<?php
namespace GET;
class Datatable extends \Magic {
  //Variables
  ///Class
  protected $Session = NULL;
  protected $Tables = array();
  ///SQL
  protected $Table = NULL;
  protected $Columns = NULL;
  protected $Rows = NULL;
  ///Draw
  protected $Start = NULL;
  protected $Length = NULL;
  ///Meta
  protected $NumRows = NULL;
  //Results
  protected $Data = array();
  //Functions
  ///Magic
  public function __construct( $Args ){
    parent::__construct( $Args );
    $Resource = sqlsrv_query(
      parent::__get( 'Session' )->__get( 'Database' )->__get( 'Resource' ),
      " SELECT  [Datatable].[Name]
        FROM    [Portal2].dbo.[Datatable]
        WHERE   [Datatable].[Database] = 1;
      ;",
      array( )
    );
    $Tables = array();
    if( $Resource ){while( $Table = sqlsrv_fetch_array( $Resource ) ){ $Tables[ ] = $Table['Name']; }}
    parent::__set( 'Tables', $Tables );
    if( in_array( parent::__get( 'Session' )->__get( 'Table' ), parent::__get('Tables') ) ){
      $Resource = sqlsrv_query(
        parent::__get( 'Session' )->__get( 'Database' )->__get( 'Resource' ),
        " SELECT [Column].*
          FROM  (
            (
              SELECT  ROW_NUMBER() OVER ( ORDER BY Datacolumn.Position ASC ) AS Row,
                      [Database].[ID] AS Database_ID,
                      [Datatable].[ID] AS Datatable_ID,
                      [Datacolumn].[ID] AS Datacolumn_ID,
                      columns.*,
                      Datacolumn.Display AS Display,
                      Datacolumn.Datatype AS Datatype
              FROM    information_schema.columns
                      LEFT JOIN Portal2.dbo.[Database] ON columns.table_catalog = [Database].Name
                      LEFT JOIN Portal2.dbo.Datatable ON columns.table_name = Datatable.Name
                      LEFT JOIN Portal2.dbo.Datacolumn ON columns.column_name = Datacolumn.Name AND Datacolumn.[Table] = [Datatable].ID
              WHERE   columns.table_name = ?
            )
          ) AS [Column]
        ;",
        array( parent::__get( 'Session' )->__get( 'Table' ) )
      );
      if( ($errors = sqlsrv_errors() ) != null) {
        foreach( $errors as $error ) {
            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".$error[ 'message']."<br />";
        }
    }
      $Table = substr( parent::__get( 'Session' )->__get( 'Reference' ), 5 );
      $Columns = array();
      while( $Row = sqlsrv_fetch_array( $Resource, SQLSRV_FETCH_ASSOC ) ){
        $Columns[] = $Row['COLUMN_NAME'];
      }
      parent::__set( 'Columns', $Columns );
      $Order = 'ORDER BY ' . $Table . '.' . $Columns[0] . ' ASC';
      $Query = " SELECT  Tbl.*
        FROM    (
          SELECT  ROW_NUMBER() OVER ( {$Order} ) AS Row,
                  *
          FROM    {$Table}
        ) AS Tbl
        WHERE     Tbl.Row >= ?
              AND Tbl.Row < ?
      ;";
      $Resource = sqlsrv_query(
        parent::__get( 'Session' )->__get( 'Database' )->__get( 'Resource' ),
        $Query,
        array(
          parent::__get( 'Session' )->__get( 'GET' )['start'],
          parent::__get( 'Session' )->__get( 'GET' )['start'] + parent::__get( 'Session' )->__get( 'GET' )['length']
        )
      );
      if( ($errors = sqlsrv_errors() ) != null) {
        foreach( $errors as $error ) {
            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".$error[ 'message']."<br />";
        }
    }
      $tableData = array();
      if($Resource){while($Row = sqlsrv_fetch_array($Resource, SQLSRV_FETCH_ASSOC)){ $tableData[] = $Row; }}
      $Resource = sqlsrv_query(
        parent::__get( 'Session' )->__get( 'Database' )->__get( 'Resource' ),
        " SELECT  Count(*) AS Count
          FROM    {$Table}
        ;",
        array(
          parent::__get( 'Table' )
        )
      );
      $tableTotal = sqlsrv_fetch_array($Resource)['Count'];
      $Output = array(
          "draw" => intval($_GET['draw']),
          "recordsTotal" => $tableTotal,
          "recordsFiltered" => $tableTotal,
          "data" => $tableData
      );
    }
    echo json_encode ( $Output );
  }
}?>
