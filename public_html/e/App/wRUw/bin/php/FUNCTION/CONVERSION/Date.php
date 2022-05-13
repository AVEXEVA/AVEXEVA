<?php
function convertDate($Date = Null){
 if(!is_null($Date)){
  if(strpos($Date,'/'){
   return date("Y-m-d H:i:s",strtotime($Date));
  } else {
   return date("m/d/Y h:i A",strtotime($Date));
  }
 } else {
  return date("Y-m-d H:i:s");
 }
}
?>
