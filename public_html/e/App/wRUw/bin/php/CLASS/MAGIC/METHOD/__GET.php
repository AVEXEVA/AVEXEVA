<?php
NAMESPACE MAGIC\METHOD;
TRAIT __GET {
  PUBLIC FUNCTION __GET( $_ARGS = NULL ){
    IF( is_array( $_ARGS ) ){
      $ARRAY = ARRAY();
      IF(count( $_ARGS ) > 0){
        FOREACH( $_ARGS AS $INDEX => $KEY ){
          $ARRAY[ ] = self::__ISSET( $KEY ) 
            ? self::__GET( $KEY ) 
            : '';
        }
      }
      RETURN $ARRAY; } 
    ELSEIF(self::__ISSET( $_ARGS ) ) {
      RETURN $this->$_ARGS;
    }
  }
}
?>
