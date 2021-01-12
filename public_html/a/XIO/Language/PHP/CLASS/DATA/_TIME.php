<?PHP
NAMESPACE DATA;
CLASS _TIME EXTENDS \DATA\_STRING {
  //TRAITS
  //VARIABLES
  PROTECTED $HOUR = NULL;
  PROTECTED $MINUTE = NULL;
  PROTECTED $SECOND = NULL;
  //FUNCTIONS
  PUBLIC FUNCTION __CONSTRUCT( $_ARGS = NULL ){
    PARENT::__CONSTRUCT( $_ARGS );
    SELF::__CONSTRUCTOR( );
  }
  PUBLIC FUNCTION __CONSTRUCTOR( ){
    IF( PARENT::__CHECK( ) ){
      PREG_MATCH(
        '/(\d{2}):(\d{2}):(\d{2})/',
        PARENT::__GET( 'STRING' ),
        $MATCHES
      );
      PARENT::__SET( 'HOUR', $MATCHES[ 1 ];
      PARENT::__SET( 'MINUTE', $MATCHES[ 2 ];
      PARENT::__SET( 'SECOND', $MATCHES[ 3 ];
    }
  }
  PUBLIC FUNCTION __STRTOTIME( $STRING = NULL ){
    IF( IS_STRING( $STRING ) ){
      SELF::__CONSTRUCT(
        ARRAY(
          'STRING' => DATE(
            PARENT::__GET( 'STRING' ),
            STRTOTIME( $STRING )
          )
        )
      );
    }
  }
}?>
