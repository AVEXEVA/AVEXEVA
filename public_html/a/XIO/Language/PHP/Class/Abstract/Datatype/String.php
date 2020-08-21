<?php
namespace Data;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class String {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $String = NULL;
  //Functions
  public function __check(){return is_string($this->__get('String'));}
}
?>
