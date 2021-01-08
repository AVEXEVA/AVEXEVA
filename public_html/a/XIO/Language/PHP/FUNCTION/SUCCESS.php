<?PHP 
FUNCTION SUCCESS ( $MESSAGE = '' ){
  IF( IS_STRING( $MESSAGE ) ){
     ECHO "<DIV>{$MESSAGE}</DIV>";
  }
}
?>
