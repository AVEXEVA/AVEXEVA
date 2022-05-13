<?php
namespace Inventory;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Item_Category {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  public $ID = NULL;
  public $Name = NULL;
  public $Description = NULL;
  public $Parent = NULL;
  //Functions
  public function __toString(){return $this->__get('Name');}
}
?>
