<LINK REL='STYLESHEET' TYPE='TEXT/CSS' HREF='a/XIO/Language/CSS/SITE.css?<?PHP ECHO RAND( 0,99999999 );?>' />
<?PHP
IF( file_exists( __DIR__ . '/PAGE/' . substr( $_SERVER['SCRIPT_NAME'], 1, strlen( $_SERVER['SCRIPT_NAME'] ) - 5 ) . '/INDEX.php') ){
  LOAD( '../CSS/PAGE/' . substr( $_SERVER['SCRIPT_NAME'], 1, strlen( $_SERVER['SCRIPT_NAME'] ) - 5 ) . '/INDEX.php' );
}?>
