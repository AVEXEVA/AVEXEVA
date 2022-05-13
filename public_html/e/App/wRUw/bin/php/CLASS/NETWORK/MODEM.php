<?php
namespace Network;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Modem {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  public $ID = NULL;
  public $Name = NULL;
  public $IP = NULL;
}
?>
