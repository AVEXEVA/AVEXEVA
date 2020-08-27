<?php
namespace Graphic;
class Coordinates extends Magic {
  //Variables
  private $ID;
  private $Coordinates = array();
  //Functions
  public function __construct( $Array = array()){
    parent::__construct( $Array );
    self::__constructor();
    self::__construction();
  }
  public function __constructor(){}
  public function __construction(){parent::DIV(array(
    'ID'    =>  parent::__get('ID'),
    'Rel'   =>  parent::__get('Coordinates')
  ));}
?>
