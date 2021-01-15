<?PHP
NAMESPACE ATLAS;
CLASS HEADER EXTENDS \INDEX {
  PUBLIC FUNCTION __CONSTRUCT( $_ARGS = NULL ){
    PARENT::__CONSTRUCT( $_ARGS );
    ?><HEADER
        ID='Header'
        STYLE='TEXT-ALIGN:CENTER;VERTICAL-ALIGN:MIDDLE;FLOAT:LEFT;WIDTH:100%;DISPLAY:GRID;<?PHP ECHO ISSET($_GET['File']) || ISSET($_GET['Folder']) || ISSET($_GET['Image']) ? 'HEIGHT:50%;' : 'HEIGHT:100%;';?>'>
      <DIV style='grid-column:4 / span 1'>G</DIV>
      <DIV style='grid-column:4 / span 1'>e</DIV>
      <DIV style='grid-column:1 / span 1' onClick="document.location.href='index.php?Folder=a/';">a</DIV>
      <DIV style='grid-column:2 / span 1' onClick="document.location.href='index.php?Folder=v/';">v</DIV>
      <DIV style='grid-column:3 / span 1' onClick="document.location.href='index.php?Folder=e/';">e</DIV>
      <DIV style='grid-column:4 / span 1' onClick="document.location.href='/';">X</DIV>
      <DIV style='grid-column:5 / span 1'>Ǝ</DIV>
      <DIV style='grid-column:6 / span 1'>V</DIV>
      <DIV style='grid-column:7 / span 1'>A</DIV>
      <DIV style='grid-column:4 / span 1'>d</DIV>
      <DIV style='grid-column:4 / span 1'>S</DIV>
    </HEADER><?PHP
  }
}?>
