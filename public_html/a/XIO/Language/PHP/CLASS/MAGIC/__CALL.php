<?PHP
NAMESPACE MAGIC;
TRAIT __CALL {
  PUBLIC FUNCTION __CALL( $FUNCTION, $_ARGS){
    if( method_exists( $this, $FUNCTION ) ){ 
      $this->$FUNCTION($_ARGS);
    }
  }
}?>
