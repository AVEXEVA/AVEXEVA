<?php
namespace Data;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class CSV {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $ID = NULL;
  protected $Name = NULL;
  //Arrays
  protected $Headers = array();
  protected $Rows = array();
}
?>
