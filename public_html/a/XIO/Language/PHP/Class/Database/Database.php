<?php
namespace Database;
class Database extends Magic {
  //Variables
  private $ID;
  private $Hostname;
  private $Name;
  private $IP;
  //Arrays
  private $Tables = array();
  private $Users = array();
  //Functions
  public function __construct( $Array = array()){parent::__construct( $Array );}
	public function __constructor(){
    self::Tables();
  }
	public function __construction(){}
  //Helpers
  protected function Tables(){

  }
}
?>
