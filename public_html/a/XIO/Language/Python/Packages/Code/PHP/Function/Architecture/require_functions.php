<?php
function require_functions($functions = array()){
	if(is_array($functions)){
		if(count($functions) > 0){
			$return = true;
			foreach($functions as $i=>$function){
				$return = require_function($function) ? $return : false;
			}
			return $return;
		}
	} elseif(is_string($functions)) {
		return require_function($functions);
	}
	return false;
}
function require_function($function){
	if(!function_exists($function)){
		if(file_exists("cgi-bin/PHP/Functions/{$function}.php")){
			require("cgi-bin/PHP/Functions/{$function}.php");
		}
	}
	return function_exists($function);
}
?>
