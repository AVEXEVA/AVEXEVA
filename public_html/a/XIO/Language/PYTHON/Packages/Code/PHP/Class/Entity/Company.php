<?php
namespace Entity;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Company {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $ID = NULL;
  protected $Name = NULL;
  protected $Description = NULL;
  protected $Entity = NULL;
  protected $Parent = NULL;
  protected $Founded = NULL;
  protected $Created = NULL;
  protected $Headquarters = NULL;
  protected $Accounts_Payable = NULL;
  protected $Accounts_Recievable = NULL;
  protected $Human_Resources = NULL;
  protected $Engineering = NULL;
  protected $Legal = NULL;
}
?>
