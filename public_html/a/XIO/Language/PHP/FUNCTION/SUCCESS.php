<?PHP 
FUNCTION SUCCESS ( $MESSAGE = '', $OUTPUT = 'CONSOLE' ){
  IF( IS_STRING( $MESSAGE ) ){
     SWITCH( $OUTPUT ){
       CASE 'HTML':
         ECHO "<LI CLASS='SUCCESS' STYLE='BACKGROUND-COLOR:#90ee90;COLOR:BLACK;MARGIN:5px;PADDING:5px;'>{$MESSAGE}</LI>";
         BREAK;
       CASE 'CONSOLE':
         ECHO "<SCRIPT>console.log('{$MESSAGE}');</SCRIPT>";
         BREAK;
       DEFAULT:
         BREAK;
     }
  }
}
?>
