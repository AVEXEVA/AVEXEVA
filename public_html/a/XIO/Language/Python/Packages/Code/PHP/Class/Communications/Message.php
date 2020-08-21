<?php
namespace Communications;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Message {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $ID = NULL;
  protected $Panent = NULL;
  protected $User = NULL;
  protected $Created = NULL;
  protected $Subject = NULL;
  protected $Text = NULL;
  //Arrays
  protected $Recipients = array();
}
?>
