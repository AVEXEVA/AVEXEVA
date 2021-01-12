<?php
namespace Rolodex;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Address {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $ID = NULL;
  protected $Name =  NULL;
  protected $Room = NULL;
  protected $Floor = NULL;
  protected $Street = NULL;
  protected $Locale = NULL;
  protected $City = NULL;
  protected $State = NULL;
  protected $Zip_Code = NULL;
  protected $Country = NULL;
  protected $GPS = NULL;
  //Functions
  public function __toString(){echo $this->__get('Name') . '\n'
                                    . $this->__get('Street') . ' ' . $this->__get('Locale') . ' Floor ' . $this->__get('Floor') . ' Room ' . $this->__get('Room') . '\n'
                                    . $this->__get('City') . ', ' . $this->__get('State') . ' '  $this->__get('Zip_Code');}
}
?>
