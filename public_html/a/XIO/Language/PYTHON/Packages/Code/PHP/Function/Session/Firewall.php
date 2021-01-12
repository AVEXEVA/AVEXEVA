<?php
function session_firewall(){
 session_check();
 if(isset($_SESSION['User_ID'],$_SESSION['Connection_Hash'])){
  $r = sqlsrv_query($Database, 
   "SELECT Top 1 
           * 
    FROM   Elevate.dbo.Connection
    WHERE  Conection.[User] = ? 
           AND Connection.[Hash] = ?
   ;",array($_SESSION['User_ID'],$_SESSION['Connection_Hash']));
  if($r && is_array(sqlsrv_fetch_array($r))){
   return true;
  }
 }
 return false;
}?>
