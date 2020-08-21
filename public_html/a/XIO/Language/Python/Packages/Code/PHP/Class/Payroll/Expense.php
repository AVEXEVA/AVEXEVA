<?php
namespace Payroll;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Expense {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $ID = NULL;
  protected $Expenses = NULL;
  protected $Amount = NULL;
  protected $Paid = NULL;
}
?>
