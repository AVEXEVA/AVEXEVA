<?PHP
NAMESPACE HTTP\SESSION;
CLASS INDEX EXTENDS \INDEX {
  //TRAITS
  USE \MAGIC\METHODS\__CONSTRUCTORS;
  //VARIABLES
  ///ARGUMENTS
  PROTECTED $SESSION    = NULL;
  PROTECTED $GET        = NULL;
  PROTECTED $POST       = NULL;
  PROTECTED $ARGS       = NULL;
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
        'SESSION'    => ARRAY( 
          'NAME'  => 'SESSION',
          'TYPE'  => '\DATA\_SESSION',
          'VALUE' => $_SESSION
        ),
        'POST'    => ARRAY( 
          'NAME'  => 'POST',
          'TYPE'  => '\DATA\_POST',
          'VALUE' => $_POST
        ),
        'GET'    => ARRAY( 
          'NAME'  => 'GET',
          'TYPE'  => '\DATA\_GET',
          'VALUE' => $_GET
        ),
        'ARGS'    => ARRAY( 
          'NAME'  => 'POST',
          'TYPE'  => '\DATA\_ARGS',
          'VALUE' => $_ARGS
        )
      )
    );
    SELF::__CONSTRUCTORS( );
  }
}?>

