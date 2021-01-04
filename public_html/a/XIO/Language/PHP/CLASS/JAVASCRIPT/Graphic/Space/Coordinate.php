<?php
namespace Graphic;
class Coordinate extends Magic {
  //Variables
  private $Float;
  private $Type;
  //Traits
  public function __construct( $Array = array()){
    parent::__construct( $Array );
    self::__constructor();
    self::__construction();
  }
  public function __constructor(){}
  public function __construction(){parent::DIV(array(
    'ID'    => 'Coordinate_' . parent::__get('Float') . '_' . parent::__get('Type'),
    'Class' => 'Coordinate_Type_' . parent::__get('Type')
    'Rel'   => parent::__get('Float')
  ))}
}
?>
