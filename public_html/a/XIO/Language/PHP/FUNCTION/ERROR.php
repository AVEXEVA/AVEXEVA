<?PHP 
FUNCTION ERROR ( $MESSAGE = '', $OUTPUT = 'CONSOLE' ){
  IF( IS_STRING( $MESSAGE ) ){
    SWITCH( $OUTPUT ){
      CASE 'HTML':
        ECHO "<LI CLASS='ERROR' STYLE='BACKGROUND-COLOR:#ff3333;COLOR:BLACK;PADDING:5PX;MARGIN:5PX;'>{$MESSAGE}</LI>";
        BREAK;
      CASE 'CONSOLE':
        ECHO "<SCRIPT>console.log('{$MESSAGE}')</SCRIPT>";
        BREAK;
      DEFAULT:
        BREAK;
    }
  }
}?>