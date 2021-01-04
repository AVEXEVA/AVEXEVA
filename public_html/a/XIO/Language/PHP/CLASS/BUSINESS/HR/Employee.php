<?php
namespace HR;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Employee {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $ID = NULL;
  protected $Person = NULL;
  protected $Union =  NULL;
  protected $Social_Security_Number = NULL;
  //Arrays
  protected $Time_Lapses = array();
}
?>
