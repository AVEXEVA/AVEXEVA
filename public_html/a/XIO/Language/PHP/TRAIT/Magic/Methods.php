<?php
NAMESPACE MAGIC;
TRAIT METHODS {
  PUBLIC FUNCTION __call( $FUNCTION, $_ARGS){
    if( method_exists( $this, $FUNCTION ) ){ 
      $this->$FUNCTION($_ARGS);
    }
  }
  PUBLIC FUNCTION __construct( $ARRAY = NULL ){
    SELF::__set( $ARRAY );
  }
  PUBLIC FUNCTION __toString( $DELIMETER = '&'){
    $STRINGS = [];
    FOREACH( get_object_vars( $this ) AS $KEY=>$VALUE ){
      IF( is_array( $VALUE ) || is_object( $VALUE ) ){ CONTINUE; }
      $STRINGS[ ] = "{$KEY}='{$VALUE}'";
    }
    RETURN implode( $DELIMTER, $STRINGS );
  }
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
  PUBLIC FUNCTION __get( $_ARGS = NULL ){
    IF( is_array( $_ARGS ) ){
      $ARRAY = ARRAY();
      IF(count( $_ARGS ) > 0){
        FOREACH( $_ARGS AS $INDEX => $KEY ){
          $ARRAY[ ] = self::__isset( $KEY ) 
            ? self::__get( $KEY ) 
            : '';
        }
      }
      RETURN $ARRAY; } 
    ELSEIF(self::__isset( $KEY ) ) {
      RETURN $this->$KEY;
    }
  }
  PUBLIC FUNCTION __set( $KEY, $VALUE = NULL ){
    IF( is_array( $KEY ) ){
      IF( count( $KEY ) > 0){
        FOREACH( $KEY AS $K=>$V){
          IF( self::__isset($K)){
            $this->$K = $V;
          }}}} 
    ELSEIF( SELF::__isset( $KEY ) ){
      $this->$KEY = $VALUE;
    }
  }
  PUBLIC FUNCTION __isset( $KEY ){
    RETURN property_exists( $this, $KEY );
  }
  PUBLIC FUNCTION __destroy( ){ }
  PUBLIC FUNCTION __echo( $KEY ){
    IF( SELF::__isset( $KEY ) ){ ECHO SELF::__get( $KEY ); }
    ELSE{ ECHO NULL;}
  }
}
?>
