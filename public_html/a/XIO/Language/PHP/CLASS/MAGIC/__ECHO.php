<?PHP
NAMESPACE MAGIC;
TRAIT __ECHO {
  PUBLIC FUNCTION __ECHO( $KEY = NULL ){
    IF( SELF::__ISSET( $KEY ) ){ ECHO SELF::__GET( $KEY ); }
    ELSE { ERROR( "ERROR \MAGIC\ECHO : {$KEY}" ); } 
  }
}
?>