<?php 
function __session_initialization(){
 if(session_id() == '' || !isset($_SESSION)) {
    // session isn't started
    session_start();
 } 
}
?>
