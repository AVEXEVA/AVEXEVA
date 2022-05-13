<?php
namespace Land;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Plot {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $ID = NULL;
  protected $Owner = NULL;
  //Arrays
  protected $Buildings = array();
}
?>
