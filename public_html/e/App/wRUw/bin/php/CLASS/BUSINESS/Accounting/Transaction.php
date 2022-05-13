<?php
namespace Accounting;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Transaction {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $ID = NULL;
  protected $From = NULL;
  protected $To = NULL;
  protected $For = NULL;
  protected $Timestamp = NULL;
  //Arrays
  //Contents
  protected $Items = array();
  protected $Currencies = array();
  protected $Services = array();
  //Related
  protected $Orders = array();
}
?>
