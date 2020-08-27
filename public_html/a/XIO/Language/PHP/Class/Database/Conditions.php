<?php
namespace Database;
class Conditions extends Magic {
  //Variables
  private $Conditions = array();
  //Functions
  public function __construct( $Array = array()){parent::__construct( $Array );}
	public function __constructor(){}
	public function __construction(){}
  //Helpers
  public function __toWhere(){
    $Where = array();
    if(parent::validate(parent::__get('Conditions'), 'array+') != 'NaA+' ){foreach(parent::__get('Conditions') as $Condition){
      $Where = $Condition;
    }}
    return implode(' AND ', $Where);
  }
}
?>
