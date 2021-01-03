<?php
session_start();
$csv = array_map('str_getcsv', file('gifts/index.csv'));
array_walk($csv, function(&$a) use ($csv) {
  $a = array_combine($csv[0], $a);
});
array_shift($csv);

$amounts = array_map('str_getcsv', file('amounts.csv'));
array_walk($amounts, function(&$a) use ($amounts) {
  $a = array_combine($amounts[0], $a);
});
array_shift($amounts);

$check = true;

$owned = array();

if(count($csv) > 0){foreach($csv as $row){
  if($row['Gift'] == $_GET['Gift']){
    $check = false;
  }
  if($row['Person'] == $_SESSION['Person']){
    $owned[] = $row['Gift'];
  }
}}
$budget = 0;
if(count($amounts) > 0 && count($owned) > 0){foreach($amounts as $row){
  if(in_array($row['Gift'], $owned) || $row['Gift'] == $_GET['Gift'] ){
    $budget = $budget + $row['Amount'];
  }
}}
if($budget > 150 ){$check = false;}

if($check){
  $f = fopen('gifts/index.csv', 'a');
  fwrite($f, $_GET['Gift'] . ',' . $_SESSION['Person'] . PHP_EOL);
  fclose($f);
  echo 'true';
} else {
  echo 'false';
}
?>
