<?PHP
FUNCTION LOAD( $PATH = NULL, $DIR = 'a/XIO/Language/PHP' ){
  $OUTPUT = 'CONSOLE';
  TRY {
    IF( FILE_EXISTS( $DIR . '/' . $PATH ) ){
      LOADING("LOADING \PROCEDURE\LOAD : {$DIR}/{$PATH})", $OUTPUT );
      REQUIRE( $DIR . '/' . $PATH );
      SUCCESS("SUCCESS \PROCEDURE\LOAD : {$DIR}/{$PATH})", $OUTPUT );
    } ELSE {
      ERROR("ERROR \PROCEDURE\LOAD : {$DIR}/{$PATH})", $OUTPUT );
    }
  } CATCH( EXCEPTION $E ){
    ERROR( $E );
  }
}?>
