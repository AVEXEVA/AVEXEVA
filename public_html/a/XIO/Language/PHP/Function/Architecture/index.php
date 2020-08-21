<?php
if(!function_exists('require_functions')){require(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/' . Application_Directory . '/cgi-bin/PHP/Functions/Architecture/require_functions.php');}
if(!trait_exists('require_traits')){require(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/' . Application_Directory . '/cgi-bin/PHP/Functions/Architecture/require_traits.php');}
if(!class_exists('require_classes')){require(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/' . Application_Directory . '/cgi-bin/PHP/Functions/Architecture/require_classes.php');}
?>
