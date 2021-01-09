<?php
NAMESPACE MAGIC;
TRAIT __SET {
  PUBLIC FUNCTION __SET( $KEY, $VALUE = NULL ){
    IF( IS_ARRAY( $KEY ) ){
      IF( COUNT( $KEY ) > 0){
        FOREACH( $KEY AS $K=>$V){
          IF( SELF::__ISSET($K)){
            $this->$K = $V;
          }}}} 
    ELSEIF( SELF::__ISSET( $KEY ) ){
      $this->$KEY = $VALUE;
    }
  }
}
?>
