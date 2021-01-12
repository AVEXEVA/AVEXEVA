<?php
namespace Insurance;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Insured {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $Insurance_Plan = NULL;
  protected $Insurance_Company = NULL;
  protected $Entity = NULL;
  protected $Time_Lapse = NULL;
}
?>
