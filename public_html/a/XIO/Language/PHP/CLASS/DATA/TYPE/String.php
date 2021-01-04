<?php
namespace \Data\Type;
Class String extends \Data\Type\index {
  //Variables
  protected $String = NULL;
  //Functions
  public function __check( ){ return is_string( $this->__get( 'String' ) ); }
}
?>
