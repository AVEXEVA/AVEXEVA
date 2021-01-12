<?PHP
NAMESPACE HTTP\SESSION;
CLASS INDEX EXTENDS \INDEX {
  //VARIABLES
  ///ARGUMENTS
  PROTECTED $SESSION    = NULL;
  PROTECTED $GET        = NULL;
  PROTECTED $POST       = NULL;
  PROTECTED $DATA       = NULL;
  ///SECURITY
  PROTECTED $USER       = NULL;
  PROTECTED $CONNECTION = NULL;
  ///SQL
  PROTECTED $DATASERVERS     = NULL;
  //FUNCTIONS
  ///MAGIC
  PUBLIC FUNCTION __CONSTRUCT( $_ARGS  = array( ) ){
    IF( SESSION_ID( ) == '' || !ISSET( $_SESSION )) { SESSION_START( ); }
    PARENT::__CONSTRUCT( 
      ARRAY(
        'SESSION' => $_SESSION,
        'POST'    => $_POST,
        'GET'     => $_GET,
        'DATA'    => $_ARGS
      )
    );
    SELF::__CONSTRUCTORS();
  }
  PRIVATE FUNCTION __CONSTRUCTORS( ){ 
    PARENT::__SET( 
      'DATASERVER', 
      ARRAY( 
        'LOCALHOST' => new \SQL\DATASERVER( ARRAY( 'SESSION' => $this->__SLEEP()  ))
      )
    ); 
  }
}?>

