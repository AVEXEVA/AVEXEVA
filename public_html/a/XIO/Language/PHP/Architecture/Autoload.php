<?php
spl_autoload_register(function ($Class) {
	$Directory = "a/XIO/Language/PHP/Class/";
	$Namespace = str_replace("\", "/", $Class);
	$Path = $Directory . $Namespace . ".php";
	if(file_exists($Path)){require($Path);}
});
?>
