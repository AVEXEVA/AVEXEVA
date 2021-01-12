<?php
namespace Rolodex;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Phone {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $ID = NULL;
  protected $Number =  NULL;
  //Functions
  public function __toString(){return $this->__get('Number');}
}
?>
