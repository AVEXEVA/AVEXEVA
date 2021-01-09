<?php
NAMESPACE MAGIC;
TRAIT __SLEEP {
  PUBLIC FUNCTION __sleep( $_ARGS = NULL ){
    IF( is_null( $_ARGS ) ){ RETURN SELF::__sleep( SELF::__get( 'toSleep' ) ); } 
    ELSEIF( is_array( $_ARGS ) ){
      IF( count( $_ARGS ) > 0 ){
        FOREACH( $_ARGS AS $KEY=>$VALUE ){
          IF( SELF::__isset( $KEY ) ){ $_ARGS[ $KEY ] = SELF::__get( $KEY ); }
        }
      }
      RETURN $_ARGS; } 
    ELSEIF(SELF::__isset( $_ARGS )){
      RETURN ARRAY( $_ARGS => SELF::__get( $_ARGS ) );
    }
  }
}?>
