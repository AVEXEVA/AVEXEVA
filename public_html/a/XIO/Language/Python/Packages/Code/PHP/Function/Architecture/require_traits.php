<?php
function require_traits($traits = array()){
	if(is_array($traits)){
		if(count($traits) > 0){
			$return = true;
			foreach($traits as $i=>$trait){
				$return = require_trait($trait) ? $return : false;
			}
			return $return;
		}
	} elseif(is_string($traits)) {
		return require_trait($traits);
	}
	return false;
}
function require_trait($trait){
	if(!trait_exists($trait)){
		if(file_exists("cgi-bin/PHP/Traits/{$trait}.php")){
			require("cgi-bin/PHP/Traits/{$trait}.php");
		}
	}
	return trait_exists($trait);
}
?>
