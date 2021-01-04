<?php
NAMESPACE \DATA\TYPE;
CLASS FOLDER EXTENDS \DATA\TYPE\INDEX {
  //VARIABLES
  protected $ID = NULL;
  protected $NAME = NULL;
  protected $PARENT = NULL;
  //ARGUMENTS
  PROTECTED $PATH = NULL;
  //CONTENTS
  PROTECTED $FILES = NULL;
  //FUNCTIONS
  ///MAGIC
  PUBLIC FUNCTION __construct( $_ARGS = NULL ){
    PARENT::__construct( $_ARGS );
  }
  PUBLIC FUNCTION __scan( ){
    $FILES = ARRAY( );
    IF( SELF::__check( ) ){
      FOREACH( scandir( PARENT::__get( 'PATH' ) ) AS $INDEX=>$FILE ){
        IF( $FILE == '.' || $FILE == '..'){ CONTINUE; }
        $FILES[ ] = new \DATA\TYPE\FILE( $FILE );
      }      
    }
    PARENT::__get( 'FILES', $FILES );
  }
  PUBLIC FUNCTION __check( ){ RETURN file_exists( parent::__get( 'PATH' ) ); }
}
?>
