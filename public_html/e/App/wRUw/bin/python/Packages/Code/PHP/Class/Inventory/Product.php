<?php
namespace Inventory;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Product {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  public $ID = NULL;
  public $Name = NULL;
  public $SKU = NULL;
  public $Category = NULL;
  public $Price = NULL;
}
?>
