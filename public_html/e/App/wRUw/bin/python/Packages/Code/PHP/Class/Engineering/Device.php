<?php
namespace Engineering;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Device {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $ID = NULL;
  protected $Name = NULL;
  protected $Description = NULL;
  protected $Parent = NULL;
}
?>
