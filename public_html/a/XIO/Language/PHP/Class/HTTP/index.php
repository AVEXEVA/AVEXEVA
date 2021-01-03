<?php
namespace XE;
class HTTP extends XIO {
	//Variable
	protected $FILES;
	protected $GET;
	protected $POST;
	protected $SERVER;
	protected $SESSION;
	//Functions
	//Magic
	public function __construct($array = array()){
		parent::__construct(self::__constructor($array));
	}
	public function __constructor($array = array()){if(is_array($array) && count($array) > 0){
		array_merge($array, array(
			'FILES' 	=> 	$_FILES,
			'GET'		=>	$_GET,
			'POST'		=>	$_POST,
			'SERVER'	=>	$_SERVER,
			'SESSION'	=>	$_SESSION
		));
		foreach($array as $key=>$value){
			$Function = '__' . $KEY;
			if(in_array($key, array(
				'FILES',
				'GET',
				'POST',
				'SERVER',
				'SESSION'
			)){
				self::$Function($value);
				unset($array['key'];
			}
		}
		return $array;
	}} else {return self::__constructor();}
	private function __FILES($array = array()){if(parent::__validate_array($array){parent::__set(new HTTP\FILES($array));}elseif(is_a($array, 'HTTP\FILES')){parent::__set($array);}else {parent::__set(new HTTP\FILES($_FILES));}}
	private function __GET($array = array()){if(parent::__validate_array($array){parent::__set(new HTTP\GET($array));}elseif(is_a($array, 'HTTP\GET')){parent::__set($array);}else {parent::__set(new HTTP\GET($_GET));}}
	private function __POST($array = array()){if(parent::__validate_array($array){parent::__set(new HTTP\POST($array));}elseif(is_a($array, 'HTTP\POST')){parent::__set($array);}else {parent::__set(new HTTP\POST($_POST));}}
	private function __SERVER($array = array()){if(parent::__validate_array($array){parent::__set(new HTTP\SERVER($array));}elseif(is_a($array, 'HTTP\SERVER')){parent::__set($array);}else {parent::__set(new HTTP\SERVER($_SERVER));}}
	private function __SESSION($array = array()){if(parent::__validate_array($array){parent::__set(new HTTP\SESSION($array));}elseif(is_a($array, 'HTTP\SESSION')){parent::__set($array);}else{parent::__set(new HTTP\SESSION($_SESSION));}}
}?>
