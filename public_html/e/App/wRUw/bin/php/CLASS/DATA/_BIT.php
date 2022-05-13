<?PHP
NAMESPACE DATA;
CLASS _BIT EXTENDS \DATA\INDEX {
  //TRAITS
  //VARIABLES
  PROTECTED $BIT = NULL;
  //FUNCTIONS
  PUBLIC FUNCTION __construct( $_ARGS = NULL ){
    PARENT::__construct( $_ARGS );
    SELF::__check( );
  }
  PRIVATE FUNCTION __check( ){
    RETURN in_array( 
      PARENT::__get( 'BIT' ),
      ARRAY( 0, 1 )
    );
  }
}?>
