<?php
namespace Sales;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Order {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $ID = NULL;
  protected $Name = NULL;
  protected $Description = NULL;
  //Arrays
  protected $Products = array();
}
?>
