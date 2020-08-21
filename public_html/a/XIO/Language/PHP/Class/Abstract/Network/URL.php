<?php
namespace Database;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class URL {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  public $ID = NULL;
  public $Address = NULL;
  //Functions
  protected __toString(){return $this->__get('URL');}
}
?>
