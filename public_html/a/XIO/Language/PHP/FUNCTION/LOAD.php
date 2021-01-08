<?PHP
FUNCTION LOAD( $PATH = NULL, $DIR = 'a/XIO/Language/PHP' ){
  IF( FILE_EXISTS( $DIR . '/' . $PATH ) ){
    REQUIRE( $DIR . '/' . $PATH );
    SUCCESS("SUCCESS \PROCEDURE\LOAD : {$DIR}/{$PATH})");
  } ELSE {
    SUCCESS("ERROR \PROCEDURE\LOAD : {$DIR}/{$PATH})");
  }
}?>
