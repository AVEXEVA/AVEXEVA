<?php
namespace Inventory;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Package {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  public $ID = NULL;
  public $Product = NULL;
  public $Parent = NULL;
}
?>
