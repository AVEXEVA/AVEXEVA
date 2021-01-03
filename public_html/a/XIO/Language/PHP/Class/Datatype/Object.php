<?php
namespace Data;
Class Object extends \Magic {
  //Variables
  protected $Object = NULL;
  //Functions
  public function __check($Class){return is_a($this->__get('Object'), $Class);}
}
?>
