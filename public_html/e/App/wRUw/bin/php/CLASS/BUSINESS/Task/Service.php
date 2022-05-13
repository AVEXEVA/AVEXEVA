<?php
namespace Task;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Service_Type {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $ID = NULL;
  protected $Name = NULL;
  protected $Description = NULL;
}
Class Service {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  public $ID = Null;
  public $Name = NULL;
  public $Description = NULL;
  public $Parent = NULL;
  public $Reoccurence = NULL;
}
?>
