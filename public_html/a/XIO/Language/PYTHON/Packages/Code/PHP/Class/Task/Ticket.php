<?php
namespace Task;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Ticket {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  public $ID = Null;
  public $Grouping = NULL;
  public $Time_Lapse = NULL;
  //Array
  public $Tasks = array();
}
?>
