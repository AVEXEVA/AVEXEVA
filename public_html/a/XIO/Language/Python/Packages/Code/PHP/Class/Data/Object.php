<?php
namespace Data;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Object {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $Object = NULL;
  //Functions
  public function __check($Class){return is_a($this->__get('Object'), $Class);}
}
?>
