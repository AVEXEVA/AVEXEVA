<?php
namespace SQL;
class Column extends \Magic {
  //Variables
  protected $RESOURCE = NULL;
  //Arguments
  protected $ID;
  protected $Name;
  protected $Datatype;
  protected $Position;
  //Functions
  ///Magic
  public function __construct( $_ARGS = NULL ){ parent::__construct( $_ARGS ); }
  ///SQL
  private function __connect( ){ }
}?>
