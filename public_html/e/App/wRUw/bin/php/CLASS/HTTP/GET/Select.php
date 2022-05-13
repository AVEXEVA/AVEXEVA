<?php
namespace Database;
class Select extends Magic {
  //Variables
  private $Text;
  //Arrays
  private $Columns;
  private $Tables;
  private $Conditions;
  //Result
  private $Result;
  //Functions
  public function __construct( $Array = array()){parent::__construct( $Array );}
	public function __constructor(){
    parent::__set(
      'Text',
      'SELECT ' . parent::__get('Columns')->__toSelect()    .
      'FROM '   . parent::__get('Tables')->__toFrom()       .
      'WHERE'   . parent::__get('Conditions')->__toWhere()
    );
  }
	public function __construction(){}
}
?>
