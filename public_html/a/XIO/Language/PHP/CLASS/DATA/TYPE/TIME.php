<?php
NAMESPACE \DATA\TYPE;
Class TIME extends \DATA\TYPE\STRING {
  //TRAITS
  //VARIABLES
  PROTECTED $HOUR = NULL;
  PROTECTED $MINUTE = NULL;
  PROTECTED $SECOND = NULL;
  //FUNCTIONS
  PUBLIC FUNCTION __construct( $_ARGS = NULL ){
    PARENT::__construct( $_ARGS );
    self::__constructor( );
  }
  PUBLIC FUNCTION __constructor( ){
    IF( PARENT::__check( ) ){
      preg_match(
        '/(\d{2}):(\d{2}):(\d{2})/',
        PARENT::__get( 'STRING' ),
        $MATCHES
      );
      parent::__set( 'HOUR', $MATCHES[ 1 ];
      parent::__set( 'MINUTE', $MATCHES[ 2 ];
      parent::__set( 'SECOND', $MATCHES[ 3 ];
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
