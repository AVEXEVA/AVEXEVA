<?php
namespace Web;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Menu_Type {
  use Traits\Magic_Methods;
  protected $ID = NULL;
  protected $Name = NULL;
  protected $Description = NULL;
}
Class Menu {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $ID = NULL;
  protected $Name = NULL;
  protected $Description = NULL;
}
?>
