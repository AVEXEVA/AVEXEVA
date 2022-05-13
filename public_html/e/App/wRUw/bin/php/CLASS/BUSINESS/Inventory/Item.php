<?php
namespace Inventory;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Item {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  public $ID = NULL;
  public $Name = NULL;
  public $Product = NULL;
  public $Condition = NULL;
  public $Category = NULL;
  //Functions
  public function __toString(){return $this->__get('Name');}
}
?>
