<?php
namespace \Data\Type;
Class Object extends \Data\Type\index {
  //Variables
  protected $Object = NULL;
  //Functions
  public function __check( $Object = NULL ){ return is_a( $this->__get( 'Object' ), $Object ); }
}
?>
