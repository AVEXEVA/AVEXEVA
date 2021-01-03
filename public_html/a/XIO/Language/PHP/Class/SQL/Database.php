<?php
class Database extends \Magic {
  //Variables
  protected $SQL_DRIVER;
  protected $RESOURCE = NULL;
  //Arguments
  protected $ID;
  protected $Username = NULL;
  protected $Password = NULL;
  protected $IP = NULL;
  protected $Name;
  //Tables
  protected $TABLES = array();
  protected $VIEWS  = array();
  //Functions
  ///Magic
  public function __construct( $Args ){
    parent::__construct( $Args );
    self::connect();
  }
  ///SQL
  protected function connect(){
    switch( parent::__get( 'SQL_DRIVER' ) ){
      case 'SQLSRV' : $this->sqlsrv_connect();break;
      case 'MYSQLI' : $this->mysqli_connect();break;
      default       : $this->detect_connect();break;
    }
  }
  protected function detect_connect(){ }
  protected function mysqli_connect(){ }
  protected function sqlsrv_mssql_tables(){
   $QUERY = sqlsrv_query(
     parent::__get( 'RESOURCE' ),
     "  SELECT  *
        FROM    information_schema.columns
     ;"
   );
   $TABLES = array();
   if( $QUERY ){while( $ROW = sqlsrv_fetch_array( $ROW, SQLSRV_FETCH_ASSOC ){ $TABLES[] = new \SQL\TABLE( $TABLE ); }}
   parent::__set( 'TABLES', $TABLES );
  }
  protected function sqlsrv_connect( ){
    parent::__set(
      'Resource', sqlsrv_connect(
        parent::__get( 'IP' ),
        array(
    			'Database'              => parent::__get( 'Name' ),
  		    'Uid'                   => parent::__get( 'Username' ),
  		    'PWD'                   => parent::__get( 'Password' ),
  		    'ReturnDatesAsStrings'  => true,
  		    'CharacterSet'          => 'UTF-8'
    	  )
      )
    );
  }
}
?>
