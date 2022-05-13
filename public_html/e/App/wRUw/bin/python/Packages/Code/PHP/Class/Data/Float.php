<?php
namespace Data;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Float {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $Number = NULL;
  //Functions
  public function __check(){return is_numeric($this->__get('Number'));}
}
?>
