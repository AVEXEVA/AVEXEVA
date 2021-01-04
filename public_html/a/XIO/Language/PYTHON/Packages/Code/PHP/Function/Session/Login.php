<?php
function loginUser(){
 if(isset($_POST['User_Name'],['User_Password']) && strlen($_POST['User_Name']) > 0 && strlen($_POST['User_Password']) >= 4){
  $r = sqlsrv_query($Database, 
   "SELECT Top 1 *
    FROM   Elevate.dbo.User 
    WHERE  User.Name = ?
           AND User.Password = ?
   ;", array($_POST['User_Name'], $_POST['User_Password']));
  if($r){
   $User = sqlsrv_fetch_array($r);
   if(is_array($User)){
    $_SESSION['Connection_Hash'] = randomHash();
    sqlsrv_query($Database, "INSERT INTO Elevate.dbo.Connection([User], [Hash]) VALUES(?, ?);", array($User['ID'], $_SESSION['Connection_Hash']));
   }
  } 
 }
}?>
