<?php
namespace Entity;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Helper {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $ID = NULL;
  protected $Entity = NULL;
}
?>
