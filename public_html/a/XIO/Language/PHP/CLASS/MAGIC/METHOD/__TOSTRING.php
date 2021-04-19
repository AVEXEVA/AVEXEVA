<?php
NAMESPACE MAGIC\METHOD;
TRAIT __TOSTRING {
  PUBLIC FUNCTION __toString(){
    $STRINGS = [];
    FOREACH( get_object_vars( $this ) AS $KEY=>$VALUE ){
      IF( is_array( $VALUE ) || is_object( $VALUE ) ){ CONTINUE; }
      $STRINGS[ ] = "{$KEY}='{$VALUE}'";
    }
    RETURN implode( $DELIMTER, $STRINGS );
  }
}
?>
