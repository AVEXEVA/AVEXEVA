<?php
namespace Rolodex;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class GPS {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $ID = NULL;
  protected $Latitude = NULL;
  protected $Longitude = NULL;
  //Functions
  public function __toString(){return array(  'Latitude'  => $this->__get('Latitude'),
                                              'Longitude' => $this->__get('Longitude'));}
}
?>
