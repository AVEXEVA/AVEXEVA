<?php
class FILES extends Magic {
	//Variables
	protected $PATH;
	//Functions
	public function __construct($array = array()){
		parent::__construct(self::__constructor($array));
		self::__constructor();
		self::__construction();
	}
	public function __constructor(){
		parent::__set('PATH', isset(parent::__get('GET')['PATH']) ? parent::__get('GET')['PATH'] : 'a/');
	}
	public function __construction(){
		self::__list($this->PATH);
	x}
	public static function __list($Folder){if(is_dir($Folder)){foreach(scandir($Folder) as $f){
		if(isDot($f)){continue;}
		if(is_dir($Folder)){
			$Attributes = array(
				'ID'	=>	'Folder_' . str_replace('/', '_', $Folder) . '_' . $f,
				'Class'	=>	'Folder',
				'REL'	=> 	$Folder . '/' . $f
			);
		} else {
			$Attributes = array(
				'ID'	=>	'File_' . str_replace('/', '_', $Folder) . '_' . $f,
				'Class'	=>	'File',
				'REL'	=> 	$Folder . '/' . $f
			);
		}
		new Element\LI($Attributes);
	}}}
	public function isDot($f){return $f == '.' || $f == '..';}
}
?>
