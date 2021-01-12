<?php
class Member extends User {
	//Variables
	private $Group;
	//Functions
	public function __construct( $Array = array()){
		parent::__construct( $Array );
		self::__constructor();
		self::_construction();
	}
	public function __constructor(){}
	public function __construction(){}

}
?>
