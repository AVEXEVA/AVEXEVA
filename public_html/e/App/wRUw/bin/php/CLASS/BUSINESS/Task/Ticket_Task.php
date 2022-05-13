<?php
namespace Task;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Ticket_Task {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $Ticket = NULL;
  protected $Task = NULL;
  protected $User = NULL;
  protected $Time_Lapse = NULL;
  protected $Status = NULL;
}
?>
