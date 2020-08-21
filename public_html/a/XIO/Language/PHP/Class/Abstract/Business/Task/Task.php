<?php
namespace Task;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Task_Status {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  public $ID = Null;
  public $Name = NULL;
  public $Description = NULL;
}
Class Task {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  public $ID = Null;
  public $Name = NULL;
  public $Description = NULL;
  public $Parent = NULL;
}
?>
