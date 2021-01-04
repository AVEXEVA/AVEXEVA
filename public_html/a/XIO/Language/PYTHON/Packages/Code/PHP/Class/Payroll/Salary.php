<?php
namespace Payroll;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Salary {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $ID = NULL;
  protected $Wage = NULL;
  protected $Time_Lapse = NULL;
  protected $Time_Frequency = NULL;
  protected $Currency = NULL;
  protected $Amount = NULL;
}
?>
