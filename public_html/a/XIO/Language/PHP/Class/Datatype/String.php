<?php
namespace Data;
Class String extends \Magic {
  //Variables
  protected $String = NULL;
  //Functions
  public function __check(){return is_string($this->__get('String'));}
}
?>
