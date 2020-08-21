<?php
function convertCurrency($number){
 if(is_numeric($number)){
  return "$ ".number_format($number, 2);
 } else {
  return "$0.00";
 }
}
?>
