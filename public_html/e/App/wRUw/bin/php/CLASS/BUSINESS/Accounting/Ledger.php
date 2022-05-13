<?php
namespace Accounting;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Ledger {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $ID = NULL;
  protected $General_Ledger = NULL;
  protected $Name = NULL;
  protected $Code = NULL;
  protected $Balance = NULL;
  protected $Entries = array();
}
?>
