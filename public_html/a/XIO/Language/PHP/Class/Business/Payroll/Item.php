<?php
namespace Payroll;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Item {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $ID = NULL;
  protected $Item = NULL;
  protected $Amount = NULL;
  protected $Paid = NULL;
}
?>
