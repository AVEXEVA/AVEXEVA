<?php
namespace XE/HTTP;
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
		new Element\Code(array(
			'ID' 	=>	'Editor_' . str_replace('/', '_', parent::__get('PATH')),
			'Class'	=>	'Editor',
			'REL'	=>	parent::__get('PATH'),
			'HTML' 	=>	parent::__get('CONTENTS')
		));
	}
}?>