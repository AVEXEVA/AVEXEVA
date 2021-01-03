<?php
session_start();
$csv = array_map('str_getcsv', file('throwbacks.csv'));
array_walk($csv, function(&$a) use ($csv) {
  $a = array_combine($csv[0], $a);
});
array_shift($csv);

$check = true;

$i = 0;

if(count($csv) > 0){foreach($csv as $row){
  if($row['Person'] == $_SESSION['Person']){$i++;}
}}
if($i > 7){$check = false;}

$csv = array_map('str_getcsv', file('gifts/index.csv'));
array_walk($csv, function(&$a) use ($csv) {
  $a = array_combine($csv[0], $a);
});
array_shift($csv);

if($check){
	if(count($csv) > 0){foreach($csv as $row){
		if($_GET['Gift'] == $row['Gift'] && $_SESSION['Person'] == $row['Person']){ $check = true; break; }
		else {$check = false;}
	}}
}

if($check){
	$f = fopen('throwbacks.csv', 'a');
	fwrite($f, $_SESSION['Person'] . PHP_EOL);
	fclose($f);

	$csv = array_map('str_getcsv', file('gifts/index.csv'));
	array_walk($csv, function(&$a) use ($csv) {
	  $a = array_combine($csv[0], $a);
	});
	array_shift($csv);

	$f = fopen('gifts/index.csv', 'wa+');
	fwrite($f, "Gift,Person" . PHP_EOL);
	if(count($csv) > 0){foreach($csv as $row){
		if($row['Gift'] == $_GET['Gift']){ continue; }
		else { fwrite($f, $row['Gift'] . ',' . $row['Person'] . PHP_EOL); }
	}}
	fclose($f);
}?>
