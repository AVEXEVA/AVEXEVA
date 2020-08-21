<?php
namespace Support;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Instance {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $ID = NULL;
  protected $Name = NULL;
  protected $Description = NULL;
  protected $Creator = NULL;
  protected $Time_Lapse = NULL;
  //Arrays
  protected $Assistances = array();
}
?>
