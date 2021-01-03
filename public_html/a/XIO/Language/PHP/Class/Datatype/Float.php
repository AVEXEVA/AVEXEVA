<?php
namespace Data;
Class Float extends \Magic {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $Number = NULL;
  //Functions
  public function __check(){return is_numeric($this->__get('Number'));}
}
?>
