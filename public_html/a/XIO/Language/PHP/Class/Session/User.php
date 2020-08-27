<?php
namespace Session;
Class User extends Magic {
	private $User;
	private $Connection;
	public function __construct( $Array = array()){
		parent::__construct( $Array );
		self::__constructor();
		self::__construction();
	}
	public function __constructor(){
		parent::__validate(array(
			'User'       => 'User',
			'Connection' => 'Connection'
		));
	}
	public function __construction(){
		self::CSS();
		self::HTML();
		self::Javascript();
	}
	public function CSS(){}
	public function HTML(){}
	public function Javascript(){}
}?>
