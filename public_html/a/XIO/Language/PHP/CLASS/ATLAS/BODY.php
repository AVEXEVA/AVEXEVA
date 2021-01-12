<?PHP
NAMESPACE ATLAS;
CLASS BODY EXTENDS \INDEX {
  PUBLIC FUNCTION __CONSTRUCT( $_ARGS = NULL ){
    PARENT::__CONSTRUCT( $_ARGS );
    SELF::__CONSTRUCTION( );
  }
  PRIVATE FUNCTION __CONSTRUCTION(){
    ?><BODY STYLE='margin:0px;background-color:white;overflow:hidden;' ONKEYPRESS='keyPress(this);' onload='animateCharacters();'><?PHP
      NEW \ATLAS\HEADER( );
      NEW \ATLAS\CONTAINER( );
    ?></BODY><?PHP }
}?>
