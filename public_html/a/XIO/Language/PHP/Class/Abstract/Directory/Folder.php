<?php  
namespace Directory;
Class Folder extends Magic {
	//Variables
	private $Path;
	private $Name;
	private $Extension;
	private $Contents = array();
	//Functions
	public function __construct( $Array ){
		parent::__construct( $Array );
		self::__constructor();
		self::__construction();
	}
	public function __constructor(){
		if(is_string(parent::__get('Path') && strlen(parent::__get('Path') > 0){parent::__set('Name', substring(parent::__get('Path'), strrpos(parent::__get('Path'), '/')));}
		$Contents = array(scandir(parent::__return('Path'));
		if(count($Contents) > 0){foreach($Contents as $Content){
			if(self::isDOT($Content)){continue;}
			elseif(is_dir(self::__parent('Path') . '/' . $Content)){array_push($this->Contents, new Directory\Folder(array('Path' => parent::__get('Path') . '/' . $Content)));}
			else {array_push($this->Contents, new Directory\File(array('Path' => parent::__get('Path') . '/' . $Content)));}
		}}
	}
	public static function isDOT( $Variable ){return $Variable == '.' || $Variable == '..';}
}?>