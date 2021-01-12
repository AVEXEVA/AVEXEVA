<?php
namespace Graphic;
class Polygon extends Magic {
  //Variables
  private $ID;
  private $Coordinates = array();
  private $Weight;
  //Functions
  public function __construct( $Array = array()){
    parent::__construct( $Array );
    self::__constructor();
    self::__construction();
  }
  public function __constructor(){}
  public function __construction(){parent::Javascript(array());}
}
?>
