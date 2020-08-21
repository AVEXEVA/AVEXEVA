<?php
namespace Inventory;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Condition {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  public $ID = NULL;
  public $Name = NULL;
  public $Desription = NULL;
  //Functions
  public function __toString(){return $this->__get('Name');}
}
?>
