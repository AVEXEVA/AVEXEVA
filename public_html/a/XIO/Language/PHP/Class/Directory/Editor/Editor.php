<?php
namespace XE\HTTP;
Class Editor extends HTTP {
	//Variables
	protected $PATH;
	protected $CONTENTS;
	//Functions
	public function __construct($array = array()){
		parent::__construct($array);
		self::__constructor();
		self::__construction();
	}
	public function __constructor(){
		$f = fopen($PATH, "r+");
		parent::__set('CONTENTS', fread($f));
		fclose($f);
	}
	public function __construction(){
		/*new CSS\DOM\Editor($this);*/
		new CSS\Style(array(
			'border'	 					=> 	'1px',
			'border-color' 			=>	'black',
			'background-color' 	=> 	'white',
			'color'							=>	'black'
		));
		new HTML\Code(array(
			'ID' 		=>	'XE\HTTP\Editor',
			'Class' => 	'Editor',
			'REL'		=>	'XE\HTTP'
		));
		new Javascript\DOM\Editor($this);
	}
}?>
