<?php
namespace Network;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class IP {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $ID = NULL;
  protected $Address = NULL;
  //Functions
  public function __toString(){return $this->__get('Address');}
  public function __insert(){

  }
}
?>
