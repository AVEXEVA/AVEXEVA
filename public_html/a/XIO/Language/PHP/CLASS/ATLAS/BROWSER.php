<?PHP
NAMESPACE ATLAS;
CLASS BROWSER EXTENDS \INDEX {
  PROTECTED $NAME = NULL;
  PUBLIC FUNCTION __CONSTRUCT( $_ARGS = NULL ){
    PARENT::__CONSTRUCT( $_ARGS );
    ?><DIV ONMOUSEMOVE="FOCUSMENU( event );" ID='<?PHP ECHO PARENT::__GET( 'NAME' );?>'
           CLASS='BROWSER'
           STYLE='display:<?php
             echo (ISSET($_GET['Folder']) || ISSET($_GET['File'])) &&
                  ((    isset($_GET['File'])
                    && substr($_GET['File'], 0, 1) == PARENT::__GET( 'NAME' ) )
                  || (    isset($_GET['Folder'])
                      &&  substr($_GET['Folder'], 0, 1) == PARENT::__GET( 'NAME' ) ) )
                          ? 'block'
                          : 'none';?>;'>
      <UL Class='MENU'>
        <?php \DIRECTORY\ListFiles( PARENT::__GET( 'NAME' ) );?>
      </UL>
      <DIV Class='CONTAINER'>
        <?PHP IF(ISSET($_GET['File']) && substr( $_GET['File'], 0, 1) == PARENT::__GET( 'NAME' ) ){?><H2 style='HEIGHT:10%;'><?PHP ECHO $_GET['File'];?></H2><?PHP }?>
        <?PHP
          IF(ISSET($_GET['File']) && SUBSTR($_GET['File'], 0, 1) == PARENT::__GET( 'NAME' ) ){
            $f = FOPEN($_GET['File'], 'r');
            ECHO PRINTCODE( FREAD( $f, FILESIZE( $_GET[ 'File' ] ) ) );
            FCLOSE($f);
          } ELSEIF(IN_ARRAY($_GET['Folder'], ARRAY( PARENT::__GET( 'NAME' ) . '/XOR',  PARENT::__GET( 'NAME' ) . '//XOR'))){
            ECHO PRINTCODE( IMPLODE(',',  \DIRECTORY\combineFiles($_GET['Folder'] ) ) );
          }
        ?>
      </DIV>
    </DIV>
    <SCRIPT>
      LOAD( '<?PHP ECHO PARENT::__GET( 'NAME' );?>' );
    </SCRIPT><?PHP
  }
}
?>
