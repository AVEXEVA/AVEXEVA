<?php
FUNCTION AUTOLOADER( $STRING ){
  $STRING = str_replace( '\\', '/', $STRING );
  if( file_exists( PROJECT_ROOT . '/PHP/CLASS/' . $STRING . '.php' ) ){
    include( PROJECT_ROOT.'/PHP/CLASS/' . $STRING . '.php' );
  }
}
spl_autoload_register('AUTOLOADER');
?>

