<?php
namespace Land;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Apartment {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $ID = NULL;
  protected $Name = NULL;
  protected $Rolodex = NULL;
  protected $Building = NULL;
  //Arrays
  protected $Tenants = array();
}
?>
