<?php
class Database extends \Magic {
  //Variables
  protected $RESOURCE = NULL;
  //Arguments
  protected $ID       = NULL;
  protected $Server   = NULL;
  protected $Username = NULL;
  protected $Password = NULL;
  protected $IP       = NULL;
  protected $Name     = NULL;
  //Tables
  protected $TABLES   = Array();
  protected $VIEWS    = Array();
  //Functions
  ///Magic
  public function __construct( $_ARGS = NULL ){
    parent::__construct( $_ARGS );
    self::__connect( );
  }
  private function __connect( ){
    self::__RESOURCE( );
    self::__TABLES( );
  }
  //Constructors
  private function __RESOURCE(){
    parent::__set( 
      'RESOURCE',
      new \SQL\RESOURCE( array ( 
        'DATABASE' => $this,
        'TYPE' => 'DEFAULT'
      ) );
    );
  }
  private function __TABLE( $TABLE = NULL ){ return new \SQL\TABLE ( $TABLE ); }
  private function __TABLES( ){
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
