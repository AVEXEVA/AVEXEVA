<?PHP
NAMESPACE ATLAS;
CLASS BROWSER EXTENDS \INDEX {
  PRIVATE $NAME = NULL;
  PUBLIC FUNCTION __CONSTRUCT( $_ARGS = NULL ){
    PARENT::__CONSTRUCT( $_ARGS );
    ?><DIV ID='<?PHP ECHO PARENT::__GET( 'NAME' );?>' class='BROWSER' style='display:<?php echo (isset($_GET['File']) && substr($_GET['File'], 0, 1) == PARENT::__GET( 'NAME' ) ) || (isset($_GET['Folder']) && substr($_GET['Folder'], 0, 1) == 'a') ? 'block' : 'none';?>;'>
      <UL Class='Menu'>
        <?php \DIRECTORY\ListFiles( PARENT::__GET( 'NAME' ) );?>
      </UL>
      <DIV Class='Container'><PRE><?php
        IF(ISSET($_GET['File']) && SUBSTR($_GET['File'], 0, 1) == 'a'){
          $f = FOPEN($_GET['File'], 'r');
          echo HIGHLIGHT_STRING( FREAD( $f, FILESIZE( $_GET[ 'File' ] ) ) );
          FCLOSE($f);
        } ELSEIF(IN_ARRAY($_GET['Folder'], ARRAY( PARENT::__GET( 'NAME' ) . '/XOR',  PARENT::__GET( 'NAME' ) . '//XOR'))){
          echo IMPLODE(',',  \DIRECTORY\combineFiles($_GET['Folder']));
        }
      ?></PRE></DIV>
    </DIV><?PHP
  }
}
?>
