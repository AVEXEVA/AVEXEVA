<?php
NAMESPACE DATA;
CLASS _INTEGER EXTENDS \DATA\_INDEX {
  //VARIABLES
  ///ARGUMENTS
  PROTECTED $INTEGER = NULL;
  //FUNCTIONS
  ///MAGIC
  PUBLIC FUNCTION __VALIDATE(){ RETURN IS_INT( PARENT::__get( 'INTEGER') );}
}
?>