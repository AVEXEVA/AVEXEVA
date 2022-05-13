<?php
namespace User;
Class Group extends Magic {
	//Variables
	private $ID;
	private $Type;
	private $Name;
	//Arrays
	private $Members = array();
	//Functions
	public function __construct( $Array = array()){
		parent::__construct( $Array );
		self::__constructor();
		self::_construction();
	}
	public function __constructor(){}
	public function __construction(){}
}?>