<?php
namespace SQL;
class Column extends \Magic {
  //Variables
  protected $SQL_DRIVER;
  protected $RESOURCE = NULL;
  //Arguments
  protected $ID;
  protected $Name;
  protected $Datatype;
  protected $Position;
  //Functions
  ///Magic
  public function __construct( $Args ){
    parent::__construct( $Args );
    if( !parent::__validate( $Args ) ){ self::sqlsrv_mssql_meta( ); }
  }
  ///SQL
  public function sqlsrv_mssql_meta(){
    $QUERY = FALSE;
    if( parent::__validate( 'ID' ) ){
     $QUERY = sqlsrv_query(
       parent::__get( 'RESOURCE' ),
       "  SELECT  *
          FROM    information_schema.columns
          WHERE   information_schema.id = ?
       ;",
       array(
         parent::__get( 'ID' )
       )
     );
   } elseif( parent::__validate( 'Name' ) ){
     $QUERY = sqlsrv_query(
       parent::__get( 'RESOURCE' ),
       "  SELECT  *
          FROM    information_schema.columns
          WHERE   information_schema.column_name = ?
       ;",
       array(
         parent::__get( 'Name' )
       )
     );
   }
   if( $QUERY ) { parent::__construct( $QUERY ); }
  }
}
?>
