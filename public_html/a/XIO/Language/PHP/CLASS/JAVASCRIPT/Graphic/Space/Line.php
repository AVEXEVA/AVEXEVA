<?php
namespace Graphic;
class Line extends Magic {
  //Variables
  private $ID;
  private $Coordinates = array();
  private $Weight;
  private $Length;
  //Functions
  public function __construct( $Array = array()){
    parent::__construct( $Array );
    self::__constructor();
    self::__construction();
  }
  public function __constructor(){}
  public function __construction(){parent::CSS(array(
    'height:' . parent::validate($Weight, 'px') . 'px'
    'width:' . parent::validate($Length, 'px') . 'px'
  ));}
}
?>
