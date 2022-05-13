<?php
trait functional_magical_array {
	function __function($function = "__call"){
		if(is_array($this->__get("objects")) && count($this->__get("objects")) > 0){
			foreach($this->__get("objects") as $index=>$object){
				if(method_exists($object,$function)){
					$object->$function();
				}
			}
		}
	}
	function __pop($variable){ return array_pop($this->$variable); }
	function __push($variable, $value, $key = NULL){
		if(is_string($key)){ 	
			if($this->__isset($key)){
				$this->$variable[$key] = $value; 	
				return true;
			} else {
				return false;
			}
		} 
		else { 
			array_push(		$this->$variable, $value); 			
			return true;
		}
	}
	function __merge($variable, $array = array()){ 
		parent::__set($variable, array_merge($this->$variable, $array));
		return $this->$variable;
	}
	function __keys($variable){ return array_keys($this->$variable); }
	function __shift($variable){ return array_shift($this->$variable); }
}
?>