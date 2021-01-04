<?php
namespace \Data\Type;
Class Float extends \Data\Type\index {
  //Variables
  protected $Number = NULL;
  //Functions
  public function __check(){return is_numeric($this->__get('Number'));}
}
?>
