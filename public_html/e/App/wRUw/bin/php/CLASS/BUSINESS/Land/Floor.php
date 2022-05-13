<?php
namespace Land;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Floor {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $ID = NULL;
  protected $Building = NULL;
  protected $Name = NULL;
  //Functions
  public function __toString(){return $this->__get('Name');}
}
?>
