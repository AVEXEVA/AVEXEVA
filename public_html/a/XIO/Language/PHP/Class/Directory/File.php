<?php
namespace Directory;
Class File extends Magic {
	//Variables
	private $Path;
	private $Name;
	private $Extension;
	//Functions
	public function __construct( $Array = array()){
		parent::__construct( $Array );
		self::__constructor();
		self::__construction();
	}
	public function __constructor(){}
	public function __construction(){parent::DIV(array(
		'ID'    => 'File_' 					. str_replace('/', '_', parent::__get('Path')),
		'Name'  => 'File_' 					. str_replace('/', '_', parent::__get('Path')),
		'Class' => 'Directory_File'
		'HTML'  => parent::__get('Name') //new Elements()
	));}
}?>
