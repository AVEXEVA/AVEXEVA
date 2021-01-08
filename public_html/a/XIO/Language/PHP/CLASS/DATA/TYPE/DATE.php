<?PHP
NAMESPACE \DATA\TYPE;
Class DATE extends \DATA\TYPE\STRING {
  //TRAITS
  //VARIABLES
  ///COMPUTED
  PROTECTED $YEAR  = NULL;
  PROTECTED $MONTH = NULL;
  PROTECTED $DAY   = NULL;
  //FUNCTIONS
  PUBLIC FUNCTION __construct( $_ARGS = NULL ){
    PARENT::__construct( $_ARGS );
    SELF::__constructor( );
  }
  PUBLIC FUNCTION __constructor( ){
    IF( PARENT::__check( ) ){
      preg_match(
        '/(\d{4})-(\d{2})-(\d{2})/',
        parent::__get( 'STRING' ),
        $MATCHES
      );
      parent::__set( 'YEAR', $MATCHES[ 1 ] );
      parent::__set( 'MONTH', $MATCHES[ 2 ] );
      parent::__set( 'DAY', $MATCHES[ 3 ] );
    }
  }
  PUBLIC FUNCTION __strtotime( $STRING = NULL ){
    if( is_string( $STRING ) ){
      self::__construct(
        ARRAY(
          'STRING' => date(
            parent::__get( 'STRING' ),
            strtotime( $STRING )
          )
        )
      );
    }
  }
}?>
