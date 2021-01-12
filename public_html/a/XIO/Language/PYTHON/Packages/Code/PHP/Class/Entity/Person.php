<?php
namespace Entity;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Person {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $ID = NULL;
  protected $Entity = NULL;
  protected $First_Name = NULL;
  protected $Middle_Name = NULL;
  protected $Last_Name = NULL;
  protected $Suffix = NULL;
  protected $Birthday = NULL;
  protected $Sex = NULL;
  protected $Email = NULL;
  protected $Home = NULL;
  protected $Work = NULL;
  protected $Cell = NULL;
  protected $Office = NULL;
  //Functions
  public __toString(){
    $String = $this->__get('First_Name') . ' ' . $this->__get('Middle_Name')[0] . $this->__get('Last_Name')
    $String .= strlen($this->__get('Suffix')) > 0 ? ' ' . $this->__get('Suffix') : NULL;
  }
}
?>
