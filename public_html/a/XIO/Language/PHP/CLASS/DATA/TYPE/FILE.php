<?php
NAMESPACE \DATA\TYPE;
CLASS FILE EXTENDS \DATA\TYPE\INDEX {
  //VARIABLES
  PROTECTED $ID        = NULL;
  PROTECTED $NAME      = NULL;
  PROTECTED $EXTENSION = NULL;
  PROTECTED $SIZE      = NULL;
  PROTECTED $FOLDER    = NULL;
  PROTECTED $LINK      = NULL;
  ///ARGUMENTS
  PROTECTED $PATH      = NULL;
  ///INFORMATION
  PROTECTED $CONTENT   = NULL;
  //Functions
  ///Magic 
  PUBLIC FUNCTION __construct( $_ARGS = NULL {
    PARENT::__construct( $_ARGS );
    SELF::__open( );
  }
  PUBLIC FUNCTION __size( ){ PARENT::__set( 'SIZE', filesize( parent::__get( 'PATH' ) ) ); }
  PUBLIC FUNCTION __destroy( ){ SELF::__close( ); }
  PUBLIC FUNCTION __open( $MODE = 'a+' ){
    IF( file_exists( parent::__get( 'PATH' ) ) ){
      parent::__set(
        'LINK',
        fopen( parent::__get( 'PATH' ), $MODE )
      );
    }
  }
  PUBLIC FUNCTION __close( ){ fclose( parent::__get( 'LINK' ) ); }
  PUBLIC FUNCTION __read( ){ PARENT::__set( 'CONTENT', fread( parent::__get( 'LINK' ), filesize( parent::__get( 'PATH' ) ) ) ); }
}
?>
