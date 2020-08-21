<?php
Class User extends Magic {
	//Variables
	public $ID;
	protected $Name;
	private $Password;
	private $Email;
	private $Person;
	//Arrays
	protected $Friends 	= array();
	protected $Followers 	= array();
	protected $Groups 	= array();
	//Functions
	public function __construct( $Array = array()){
		parent::__construct( $Array );
		self::__constructor();
		self::_construction();
	}
	public function __constructor(){}
	public function __construction(){}
}?>