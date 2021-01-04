<?php
namespace \DATA\TYPE;
CLASS FLOAT EXTENDS \DATA\TYPE\INDEX {
  //VARIABLES
  ///ARGUMENTS
  PROTECTED $FLOAT = NULL;
  //FUNCTIONS
  ///MAGIC
  PUBLIC FUNCTION __check(){ RETURN is_numeric( PARENT::__get( 'FLOAT' ) );}
}
?>
