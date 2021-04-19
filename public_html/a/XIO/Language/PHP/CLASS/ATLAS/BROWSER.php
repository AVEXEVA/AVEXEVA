<?PHP
NAMESPACE ATLAS;
CLASS BROWSER EXTENDS \INDEX {
  PROTECTED $NAME = NULL;
  PUBLIC FUNCTION __CONSTRUCT( $_ARGS = NULL ){
    PARENT::__CONSTRUCT( $_ARGS );
    ?><DIV REL='<?PHP ECHO ISSET( $_GET[ 'File' ] ) ? $_GET[ 'File' ] : NULL;?>'
           ONMOUSEMOVE="FOCUSMENU( event );" ID='<?PHP ECHO PARENT::__GET( 'NAME' );?>'
           CLASS='BROWSER'
           STYLE='display:<?php
             echo (ISSET($_GET['Folder']) || ISSET($_GET['File'])) &&
                  ((    isset($_GET['File'])
                    && substr($_GET['File'], 0, 1) == PARENT::__GET( 'NAME' ) )
                  || (    isset($_GET['Folder'])
                      &&  substr($_GET['Folder'], 0, 1) == PARENT::__GET( 'NAME' ) ) )
                          ? 'block'
                          : 'none';?>;'>
      <UL CLASS='MENU'>
        <?php \DIRECTORY\ListFiles( PARENT::__GET( 'NAME' ) );?>
      </UL>
      <DIV CLASS='CONTAINER'>
        <?PHP IF(ISSET($_GET['File']) && substr( $_GET['File'], 0, 1) == PARENT::__GET( 'NAME' ) ){
          ?><H2 style='HEIGHT:5%;'><?PHP ECHO $_GET['File'];?></H2><?PHP 
        }?>
        <?PHP IF(ISSET($_GET['File']) && substr( $_GET['File'], 0, 1) == PARENT::__GET( 'NAME' ) ){
          ?><DIV CLASS='BUTTONS'><?PHP
            $CONFIG = ARRAY(
              'SAVE_AS' => ARRAY(
                'DISABLED'  => TRUE,
                'CLASS'     => 'INTERFACE_BUTTON_SAVE_AS',
                'ONCLICK'   => 'SAVE_AS( this );',
                'INNERHTML' => 'SAVE AS'
              ),
              'PUBLISH' => ARRAY(
                'DISABLED'  => TRUE,
                'CLASS'     => 'INTERFACE_BUTTON_PUBLISH',
                'ONCLICK'   => 'PUBLISH( this );',
                'INNERHTML' => 'PUBLISH'
              ),
              'DISCARD' => ARRAY(
                'DISABLED'  => TRUE,
                'CLASS'     => 'INTERFACE_BUTTON_DISCARD',
                'ONCLICK'   => 'DISCARD( this );',
                'INNERHTML' => 'DISCARD'
              ),
              'VIEW' => ARRAY(
                'CLASS'     => 'INTERFACE_BUTTON_VIEW',
                'ONCLICK'   => 'VIEW( this );',
                'INNERHTML' => 'VIEW'
              )
            );
            FOREACH( $CONFIG AS $BUTTON ){ NEW \GUI\BUTTON( $BUTTON ); }
          ?></DIV><?PHP }?>
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
