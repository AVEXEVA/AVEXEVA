<?php
NAMESPACE MAGIC\METHOD;
TRAIT __SLEEP {
  PUBLIC FUNCTION __SLEEP( $_ARGS = NULL ){
    IF( IS_NULL( $_ARGS ) ){ RETURN SELF::__SLEEP( GET_CLASS_VARS( GET_CLASS( $this  ) ) ); } 
    ELSEIF( IS_ARRAY( $_ARGS ) ){
      IF( COUNT( $_ARGS ) > 0 ){
        FOREACH( $_ARGS AS $KEY=>$VALUE ){
          IF( SELF::__ISSET( $KEY ) ){ $_ARGS[ $KEY ] = SELF::__GET( $KEY ); }
        }
      }
      RETURN $_ARGS; } 
    ELSEIF(SELF::__ISSET( $_ARGS )){
      RETURN ARRAY( $_ARGS => SELF::__GET( $_ARGS ) );
    }
  }
}?>
