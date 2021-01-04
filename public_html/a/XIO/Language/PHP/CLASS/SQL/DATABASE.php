<?php
NAMESPACE SQL;
CLASS DATABASE EXTENDS \SQL\INDEX {
  //VARIABLES
  PROTECTED $RESOURCE = NULL;
  //ARGUMENTS
  PROTECTED $ID       = NULL;
  PROTECTED $SERVER   = NULL;
  PROTECTED $USERNAME = NULL;
  PROTECTED $PASSWORD = NULL;
  PROTECTED $IP       = NULL;
  PROTECTED $NAME     = NULL;
  //RELATED
  protected $TABLES   = Array();
  protected $VIEWS    = Array();
  //FUNCTIONS
  ///MAGIC
  PUBLIC FUNCTION __construct( $_ARGS = NULL ){
    PARENT::__construct( $_ARGS );
    SELF::__connect( );
  }
  PRIVATE FUNCTION __connect( ){
    SELF::__RESOURCE( );
    SELF::__TABLES( );
  }
  //Constructors
  PRIVATE FUNCTION __RESOURCE(){
    PARENT::__set( 
      'RESOURCE',
      new \SQL\RESOURCE( array ( 
        'DATABASE' => $this,
        'TYPE' => 'DEFAULT'
      ) );
    );
  }
  PRIVATE FUNCTION __TABLE( $TABLE = NULL ){ return new \SQL\TABLE ( $TABLE ); }
  PRIVATE FUNCTION __TABLES( ){
    switch( parent::__get( 'RESOURCE' )->__get( 'TYPE' ) ){
      case 'SQLSRV' : self::__TABLES_SQLSRV( ); break;
      case 'MYSQLI' : self::__TABLES_MYSQLI( ); break;
      default       : self::__TABLES_MYSLQI( ); break;
    }
  }
  private function __TABLES_SQLSRV( ){ }
  private function __TABLES_MYSQLI( ){
    $TABLES = Array();
    $QUERY = "SELECT * FROM information_schema.columns WHERE columns.table_catalog = ?;";
    $STATEMENT = mysqli_prepare( 
      parent::__get( 'RESOURCE' )->__get( 'LINK' ),
      $QUERY
    );
    mysqli_stmt_bind_param(
      $STATEMENT,
      'i',
      parent::__get( 'Name' )
    );
    mysqli_stmt_execute( $STATEMENT );
    $RESULT = mysqli_stmt_get_result( $STATEMENT );
    if( $QUERY ){while( $ROW = mysqli_fetch_row( $RESULT ){ $TABLES[] = self::__TABLE( $ROW ); }}
    parent::__set( 'TABLES', $TABLES );
  }
}
?>
