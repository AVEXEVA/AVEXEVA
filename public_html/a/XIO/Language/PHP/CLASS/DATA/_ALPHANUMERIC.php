<?PHP
NAMESPACE DATA;
CLASS _ALPHANUMERIC EXTENDS \DATA\_STRING {
  //FUNCTIONS
  PUBLIC FUNCTION __CONSTRUCT( $_ARGS = NULL ){
    SELF::__CONSTRUCTOR( $_ARGS );
  }
  PUBLIC FUNCTION __CONSTRUCTOR( $_ARGS = ARRAY( ) ){
    IF(     PARENT::__CHECK( ) 
        &&  PREG_MATCH(
          '/[^a-z0-9._-]/i',
          PARENT::__GET( 'STRING' ),
        )
    ){
      PARENT::__CONSTRUCT( $_ARGS );
    }
  }
}?>
