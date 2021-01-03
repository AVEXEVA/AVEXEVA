<?php
namespace SQL;
class Table extends \Magic {
  //Variables
  protected $SQL_DRIVER;
  protected $RESOURCE = NULL;
  //Arguments
  protected $ID;
  protected $Name;
  //Tables
  protected $COLUMNS = array();
  //Functions
  ///Magic
  public function __construct( $Args ){
    parent::__construct( $Args );
  }
  ///SQL
  public function sqlsrv_mssql_columns(){
   $QUERY = sqlsrv_query(
     parent::__get( 'RESOURCE' ),
     "  SELECT  *
        FROM    information_schema.columns
        WHERE   information_schema.table_name = ?
     ;",
     array(
       parent::__get( 'Name ')
     )
   );
   $COLUMNS = array();
   if( $QUERY ){while( $COLUMN = sqlsrv_fetch_array( $QUERY, SQLSRV_FETCH_ASSOC ){ $COLUMNS[] = new \SQL\COLUMN( $COLUMN ); }}
   parent::__set( 'COLUMNS', $COLUMNS );
  }
}
?>
