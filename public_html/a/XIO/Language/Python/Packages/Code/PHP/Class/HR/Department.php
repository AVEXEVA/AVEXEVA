<?php
namespace HR;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Department {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $ID = NULL;
  protected $Company = NULL;
  protected $Name = NULL;
  protected $Description = NULL;
  protected $Supervisor = NULL;
  protected $Rolodex = NULL;
}
?>
