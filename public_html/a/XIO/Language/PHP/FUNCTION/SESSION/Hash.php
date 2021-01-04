<?php
function session_hash(){
 return hash('sha512',random(0,999999999999999999));
}
?>
