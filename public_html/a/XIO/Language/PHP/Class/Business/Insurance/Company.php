<?php
namespace Insurance;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Company {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $ID = NULL;
  protected $Company = NULL;
}
?>
