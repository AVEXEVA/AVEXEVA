<?php
namespace Accounting;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Bank_Account {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $ID = NULL;
  protected $Number = NULL;
  protected $Routing_Number = NULL;
}
?>
