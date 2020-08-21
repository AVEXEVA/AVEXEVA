<?php
namespace System;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Business {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $ID = NULL;
  protected $CRM = NULL;
  protected $ERP = NULL;
  protected $HR = NULL;
  protected $Inventory = NULL;
  protected $Legal = NULL;
  protected $Marketing = NULL;
  protected $Payroll = NULL;
  protected $Procurement = NULL;
  protected $Storage = NULL;
}
?>
