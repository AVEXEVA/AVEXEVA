<?PHP
FUNCTION AUTOLOADER( $STRING ){
  $STRING = str_replace( '\\', '/', $STRING );
  IF( file_exists( 'a/XIO/Language/PHP/CLASS/' . $STRING . '.php' ) ){
    LOAD('CLASS/' . $STRING . '.php' );
  }
}
spl_autoload_register('AUTOLOADER');
?>
