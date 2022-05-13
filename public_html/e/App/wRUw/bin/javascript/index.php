<?php
LOAD( '../JAVASCRIPT/FUNCTION/INDEX.php' );
LOAD( '../JAVASCRIPT/DOM/INDEX.php' );
LOAD( '../JAVASCRIPT/EVENT/INDEX.php' );
LOAD( '../JAVASCRIPT/GUI/INDEX.php' );
IF( file_exists( __DIR__ . '/PAGE/' . substr( $_SERVER['SCRIPT_NAME'], 1, strlen( $_SERVER['SCRIPT_NAME'] ) - 5 ) . '/INDEX.php') ){
  LOAD( '../JAVASCRIPT/PAGE/' . substr( $_SERVER['SCRIPT_NAME'], 1, strlen( $_SERVER['SCRIPT_NAME'] ) - 5 ) . '/INDEX.php' );
}?>