<?php
REQUIRE( 'FUNCTION/INDEX.php' );
REQUIRE( 'DOM/INDEX.php' );
REQUIRE( 'EVENT/INDEX.php' );
IF( file_exists( 'PAGE/' . substr( $_SERVER['SCRIPT_NAME'], 0, strlen( $_SERVER['SCRIPT_NAME'] ) - 4 ) . '/INDEX.php') ){
  REQUIRE( 'PAGE/' . substr( $_SERVER['SCRIPT_NAME'], 0, strlen( $_SERVER['SCRIPT_NAME'] ) - 4 ) . '/INDEX.php' );
}?>
