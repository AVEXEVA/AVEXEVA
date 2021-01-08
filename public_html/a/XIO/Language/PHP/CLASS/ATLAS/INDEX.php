<?PHP
NAMESPACE ATLAS;
CLASS INDEX EXTENDS \MAGIC {
  //VARIABLES
  PROTECTED $SESSION = NULL;
  //FUNCTIONS
  PUBLIC FUNCTION __construct( $_ARGS = NULL ){
    PARENT::__construct( $_ARGS );
    $this->__constructor( );
    IF( !SELF::__validate( ) ){ return; }
    $this->__construction( );
  }
  PUBLIC FUNCTION __constructor( ){
    IF( is_array( PARENT::__get( 'SESSION' ) ) ){
      PARENT::__set( 'SESSION', new \HTTP\SESSION\INDEX( PARENT::__get( 'SESSION' ) ) );
    }
  }
  PUBLIC FUNCTION __validate( ){
    RETURN is_a( PARENT::__get( 'SESSION' ), '\HTTP\SESSION\INDEX' ) && PARENT::__get( 'SESSION' )->__validate( );
  }
  PUBLIC FUNCTION __construction( ){
    new \ATLAS\HOME( $this->__sleep() );
  }
}?>
