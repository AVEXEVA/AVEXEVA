<?php
namespace Support;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Assistance {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $Instance = NULL;
  protected $User = NULL;
  protected $Action = NULL;
}
?>
