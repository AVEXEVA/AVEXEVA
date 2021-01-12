<?php 
namespace HTML;
class Element extends Magic {
	//Variables
	public $ID;
	public $Name;
	public $Class;
	public $Rel;
	public $HTML;
	//Traits
	use \magic\methods;
	//Functions
	protected function HTML(){echo $this->HTML;}
	protected function Attributes(){
		$strings = [];
		foreach(get_object_vars($this) as $key=>$value){
			if(is_array($value)){continue;}
			elseif(in_array($key, array('HTML')){continue;}
			elseif(is_object($value) && is_a($value, $key){echo $value;}
			else{$strings[] = "{$key}='{$value}'";}
		}
		echo implode($delimiter, $strings);
	}
}
?>