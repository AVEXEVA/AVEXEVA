<?php
namespace System;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Marketing {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $ID = NULL;
  protected $Platform = NULL;
  protected $Name = NULL;
  protected $Description = NULL;
}
?>
