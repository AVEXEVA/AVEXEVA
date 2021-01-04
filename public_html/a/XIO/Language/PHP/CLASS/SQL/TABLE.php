<?php 
namespace SQL;
Class Table extends \SQL\index {
  //VARIABLES
  protected $RESOURCE = NULL;
  //ARGUMENTS
  protected $ID = NULL;
  protected $DATABASE = NULL;
  protected $NAME = NULL;
  //FUNCTIONS
  ///MAGIC
  public function __construct( $_ARGS ){
    parent::__construct( $_ARGS );
    if( parent::__validate( ) ){ self::__connect( ); }
  }
  ///CONSTRUCTORS
  private function __connect( ){
  }
  private static function __COLUMN( $_ARGS = NULL ){ return \SQL\COLUMN( $_ARGS ); }
  private function __COLUMNS( ){
    switch( parent::__get( 'RESOURCE' )->__get( 'TYPE' ){
      case 'SQLSRV' : self::__COLUMNS_SQLSRV( ); break;
      case 'MYSQLI' : self::__COLUMNS_MYSQLI( ); break;
      default       : self::__COLUMNS_MYSQLI( ); break;
    }
  }
  private function __COLUMNS_SQLSRV( ){
    $COLUMNS = Array( );
    $QUERY = "SELECT * FROM information_schema.columns WHERE columns.table_name = ?";
    $RESULT = sqlsrv_query(
      parent::__get( 'RESOURCE' )->__get( 'LINK' ),
      $QUERY,
      Array( 
        parent::__get( 'NAME' )
      )
    );
    if( $RESULT ){
      while( $ROW = sqlsrv_fetch_array( $RESULT ) ){
        $COLUMNS[] = self::__COLUMN( $ROW );
      }
    }
    parent::__set( 'COLUMNS', $COLUMNS );
  }
  private function __COLUMNS_MYSQLI( ){
    $COLUMNS = Array( );
    $QUERY = "SELECT * FROM information_schema.columns WHERE columns.table_name = ?;";
    $STATEMENT = mysqli_prepare(
      parent::__get( 'RESOURCE' )->__get( 'LINK' ),
      $QUERY
    );
    mysqli_stmt_bind_param(
      $STATEMENT,
      's',
      parent::__get( 'NAME' )
    );
    mysqli_stmt_execute( $STATEMENT );
    $RESULT = mysqli_stmt_get_result( $STATEMENT );
    if( $RESULT ){
      while( $ROW = mysqli_fetch_row( $RESULT ){
        $COLUMNS[] = self::__COLUMN( $ROW );
      }
    }
    parent::__set( 'COLUMNS', $COLUMNS );
  }
  private function __ROW( $_ARGS = NULL ){
    $Table = parent::__get( 'NAME' );
    if(class_exists( '\\SQL\\TABLE\\' . $TABLE ) ){ return \SQL\TABLE\$TABLE( $_ARGS ); }
    else { return \SQL\ROW( $_ARGS ); }
  }
  private function __ROWS( ){
    switch( parent::__get( 'RESOURCE' )->__get( 'TYPE' ) ){
      case 'SQLSRV' : self::__ROWS_SQLSRV( ); break;
      case 'MYSQLI' : self::__ROWS_MYSQLI( ); break;
      default       : self::__ROWS_MYSQLI( ); break;
    }
  }
  private function __ROWS_SQLSRV( ){
    $ROWS = Array();
    $TABLE = parent::__get( 'NAME' );
    $QUERY = "SELECT * FROM {$TABLE};";
    $RESULT = sqlsrv_query(
      parent::__get( 'RESOURCE' )->__get( 'LINK' ),
      $QUERY,
      Array(
        parent::__get( 'NAME' )
      )
    );
    if( $RESULT ){
      while( $ROW = sqlsrv_fetch_array( $RESULT ){
        $ROWS[] = self::__ROW( $ROW );
      }
    }
  }
  private function __ROWS_MYSQLI( ){
    $ROWS = Array();
    $TABLE = parent::__get( 'NAME' );
    $QUERY = "SELECT * FROM {$TABLE};";
    $STATEMENT = mysqli_prepare(
      parent::__get( 'RESOURCE' )->__get( 'LINK' )
      $QUERY
    );
    mysqli_execute( $STATEMENT );
    $RESULT = mysqli_stmt_get_result( $STATEMENT );
    if( $RESULT ){
      while( $ROW = mysqli_fetch_row( $RESULT ) ){
        $ROWS[] = self::__ROW( $ROW );
      }
    }
  }
}
?>
