<?php
namespace Page;
class Template extends \Magic {
  public function Columns(){
    $Table = substr( parent::__get( 'Session' )->__get( 'Reference' ), 11 );
    $Resource = sqlsrv_query(
      parent::__get( 'Session' )->__get( 'Database' )->__get( 'Resource' ),
      " SELECT [Column].*
        FROM  (
          (
            SELECT  ROW_NUMBER() OVER ( ORDER BY columns.ordinal_position ASC ) AS Row,
                    [Database].[ID] AS Database_ID,
                    [Datatable].[ID] AS Datatable_ID,
                    [Datacolumn].[ID] AS Datacolumn_ID,
                    columns.*,
                    Datacolumn.Display AS Display,
                    Datacolumn.Datatype AS Datatype,
                    Datacolumn.Position AS Position
            FROM    information_schema.columns
                    LEFT JOIN Portal2.dbo.[Database] ON columns.table_catalog = [Database].Name
                    LEFT JOIN Portal2.dbo.Datatable ON columns.table_name = Datatable.Name
                    LEFT JOIN Portal2.dbo.Datacolumn ON columns.column_name = Datacolumn.Name AND Datacolumn.[Table] = [Datatable].ID
            WHERE	[Datatable].[ID] = ?
          ) UNION ALL (
            SELECT  ROW_NUMBER() OVER ( ORDER BY columns.ordinal_position ASC ) + (
                      SELECT  Count(*)
                      FROM    information_schema.columns
                              LEFT JOIN Portal2.dbo.[Database] ON columns.table_catalog = [Database].Name
                              LEFT JOIN Portal2.dbo.Datatable ON columns.table_name = Datatable.Name
                              LEFT JOIN Portal2.dbo.Datacolumn ON columns.column_name = Datacolumn.Name AND Datacolumn.[Table] = [Datatable].ID
                      WHERE	[Datatable].[ID] = ?
                    ) AS Row,
                    [Database].[ID] AS Database_ID,
                    [Datatable].[ID] AS Datatable_ID,
                    [Datacolumn].[ID] AS Datacolumn_ID,
                    [columns].*,
                    [Datacolumn].Display AS Display,
                    [Datacolumn].Datatype AS Datatype,
                    Datacolumn.Position AS Position
            FROM    information_schema.columns
                    LEFT JOIN Portal2.dbo.[Database]                    ON columns.table_catalog = [Database].Name
                    LEFT JOIN Portal2.dbo.Datatable                     ON columns.table_name = Datatable.Name
                    LEFT JOIN Portal2.dbo.Datacolumn                    ON columns.column_name = Datacolumn.Name AND Datacolumn.[Table] = [Datatable].ID
                    LEFT JOIN Portal2.dbo.[Key]                         ON Datacolumn.ID = [Key].[PrimaryKey]
                    LEFT JOIN Portal2.dbo.[Datacolumn] AS [Datacolumn2] ON [Key].[ForeignKey] = [Datacolumn2].ID
                    LEFT JOIN Portal2.dbo.[Datatable] AS [Datatable2]   ON [Datacolumn2].[Table] = [Datatable2].ID
            WHERE   [Datatable].[ID] IN (
              SELECT	[Datatable].[ID]
              FROM    information_schema.columns
                  LEFT JOIN Portal2.dbo.[Database]                    ON columns.table_catalog = [Database].Name
                  LEFT JOIN Portal2.dbo.Datatable                     ON columns.table_name = Datatable.Name
                  LEFT JOIN Portal2.dbo.Datacolumn                    ON columns.column_name = Datacolumn.Name AND Datacolumn.[Table] = [Datatable].ID
                  LEFT JOIN Portal2.dbo.[Key]                         ON Datacolumn.ID = [Key].[PrimaryKey]
                  LEFT JOIN Portal2.dbo.[Datacolumn] AS [Datacolumn2] ON [Key].[ForeignKey] = [Datacolumn2].ID
                  LEFT JOIN Portal2.dbo.[Datatable] AS [Datatable2]   ON [Datacolumn2].[Table] = [Datatable2].ID
                  LEFT JOIN information_schema.columns AS columns2    ON [Datacolumn2].[Name] = columns2.column_name AND [Datatable2].[Name] = columns2.table_name
              WHERE	[Datatable].[ID] = ?
            )
          )
        ) AS [Column]
        ORDER BY [Column].[Position] ASC
      ;",
      array( $Table, $Table, $Table )
    );
    $Columns = array();
    while( $Row = sqlsrv_fetch_array( $Resource ) ){ $Columns[ $Row[ 'Position' ] ] = array(
      'Name' => $Row['COLUMN_NAME'],
      'Display' => $Row['Display'],
      'Datatype' => $Row['Datatype'],
      'Position' => $Row['Position']
    );}
    $sColumns = [];
    parent::__set( 'Columns', $Columns );
  }
  public function initDatatableColumns(){
    $sData = array();
    if(count(parent::__get( 'Columns' )) > 0){foreach(parent::__get( 'Columns' ) as $Position => $Column ){
      $sRow = array();
      $sRow[] = "data : '" . $Column[ 'Name' ] . "' ";
      if( isset( $Column[ 'Display' ] ) ){ $sRow[] = $Column['Display'] == 0 ? "visible : false" : "visible : true"; }
      if( isset( $Column[ 'Datatype' ] ) ){
        switch( $Column['Datatype'] ){
          case 'mm/dd/yyyy' : $sRow[] = "render : function(d){return moment(d).format('MM/DD/YYYY');}";break;
          case 'currency' : $sRow[] = "render: function(d){return '$' + d;}";break;
          default:$sRow[] = "render : function(d){return d === null ? '' : d;}";break;
        }
      } else {
        $sRow[] = "render : function(d){return d === null ? '' : d;}";
      }
      $sData[] = '{' . implode( ',', $sRow ) . '}';
    }}
    return implode( ',', $sData );
  }
}
?>
