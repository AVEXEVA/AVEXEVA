<?php
namespace Data;
Class Integer extends \Magic {
  //Variables
  protected $Integer = NULL;
  //Functions
  public function __check(){return is_int($this->__get('Integer'));}
}
?>
