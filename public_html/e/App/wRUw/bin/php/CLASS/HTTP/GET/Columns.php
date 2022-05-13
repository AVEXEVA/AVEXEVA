<?php
namespace Database;
class Columns extends Magic {
  //Variables
  private $Columns = array();
  //Functions
  public function __construct( $Array = array()){parent::__construct( $Array );}
	public function __constructor(){}
	public function __construction(){}
  //Helpers
  public function __toSelect(){
    $Select = array();
    if(parent::validate(parent::__get('Columns'), 'array+') != 'NaA+' ){foreach(parent::__get('Columns') as $Alias=>$Column){
      $Select[] = '`'. $Column->__get('Table') . '`.`' . $Column->__get('Name') . '`';
    }}
    return implode(',', $Select);
  }
}
?>
