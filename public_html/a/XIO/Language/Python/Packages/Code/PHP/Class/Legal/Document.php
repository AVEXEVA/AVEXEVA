<?php
namespace Legal;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Document {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $ID = NULL;
  protected $File = NULL;
  protected $Name = NULL;
  protected $Description = NULL;
}
?>