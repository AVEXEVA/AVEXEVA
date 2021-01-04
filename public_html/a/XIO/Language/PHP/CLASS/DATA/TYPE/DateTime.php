<?php
namespace Data;
Class DateTime extends \String {
  //Functions
  ///Magic
  public function __construct( $_ARGS = NULL ){
    parent::__construct( $_ARGS );
  }
  public function __check( ){ 
    return    parent::__check( ) 
           && preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", parent::__get( 'String' ) ); 
  }
}
?>
