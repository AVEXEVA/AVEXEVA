<?php
namespace XE;
class GET extends XIO {
  //Variables
  ///Private
  private   $Database = NULL;
  private   $User     = NULL;
  private   $Token    = NULL;
  ///Protected
  protected $Data = array();
  protected $SQL  = NULL;
  //Functions
  ///Constructs
  public function __construct($array = array()){
		parent::__construct(self::__constructor($array));
	}
	public function __constructor($array = array()){if(is_array($array) && count($array) > 0){
		foreach($array as $key=>$value){
			$Function = '__' . $key;
			if(in_array($key, array(
				'Database',
				'User',
				'Token',
				'Data'
			)){
				self::$Function($value);
				unset($array['key'];
			}
		}
		return $array;
	}} else {return self::__constructor();}
  ///InitObject
  private function Database($array = array()){$this->Database = new SQL\Database($array);}
  private function User($array = array()){$this->User = new User($User);}
  private function Token($array = array()){$this->Token = new User\Token($Token);}
  private function Data($array = array()){$this->Data = new User\Data($Data);}
}
?>
