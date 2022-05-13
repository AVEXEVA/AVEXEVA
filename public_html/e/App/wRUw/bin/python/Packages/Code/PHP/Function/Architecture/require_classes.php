<?php
function require_classes($classes = array()){
	if(is_array($classes)){
		if(count($classes) > 0){
			$return = true;
			foreach($classes as $i=>$class){
				$return = require_class($class) ? $return : false;
			}
			return $return;
		}
	} elseif(is_string($classes)) {
		return require_class($classes);
	}
	return false;
}
function require_class($class){
	if(!class_exists($class)){
		if(file_exists("cgi-bin/PHP/Classes/{$class}.php")){
			require("cgi-bin/PHP/Classes/{$class}.php");
		}
	}
	return class_exists($class);
}
?>
