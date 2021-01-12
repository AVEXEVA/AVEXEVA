<?php
namespace Payroll;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Payroll {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $ID = NULL;
  protected $Employee = NULL;
  //Functions
  public function __toString(){return 'Payroll #' . $this->__get('ID');}
}
?>
