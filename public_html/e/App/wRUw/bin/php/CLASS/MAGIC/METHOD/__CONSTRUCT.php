<?php
NAMESPACE MAGIC\METHOD;
TRAIT __CONSTRUCT {
  PUBLIC FUNCTION __CONSTRUCT( $_ARGS = NULL ){
    TRY {
      //SUCCESS('SUCCESS ' . get_class($this) . '->__CONSTRUCT( $_ARGS );');
      SELF::__SET( $_ARGS );
    } catch( EXCEPTION $EXCEPTION ){
      //ERROR('ERROR ' . get_class($this) . '->__CONSTRUCT( $_ARGS);');
    }
  }
}
?>
