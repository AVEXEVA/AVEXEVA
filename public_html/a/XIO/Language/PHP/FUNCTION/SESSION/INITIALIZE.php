<?php 
NAMESPACE SESSION;
FUNCTION INITIALIZE(){
 if(session_id() == '' || !isset($_SESSION)) {
    // session isn't started
    session_start();
 } 
}
?>
