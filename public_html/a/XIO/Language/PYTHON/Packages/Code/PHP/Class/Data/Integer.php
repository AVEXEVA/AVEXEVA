<?php
namespace Data;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Integer {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $Integer = NULL;
  //Functions
  public function __check(){return is_int($this->__get('Integer'));}
}
?>
