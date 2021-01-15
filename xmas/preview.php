<?php
session_start();
$csv = array_map('str_getcsv', file('previews.csv'));
array_walk($csv, function(&$a) use ($csv) {
  $a = array_combine($csv[0], $a);
});
array_shift($csv);

$check = true;

$i = 0;
if(count($csv) > 0){foreach($csv as $row){
	if($row['Person'] == $_SESSION['Person']){$i++;}
	if($i == 2){$check = false;}
}}
if($check){
	$f = fopen('previews.csv', 'a');
	fwrite($f, $_SESSION['Person'] . PHP_EOL);
	fclose($f);

	$csv = array_map('str_getcsv', file('amounts.csv'));
	array_walk($csv, function(&$a) use ($csv) {
	  $a = array_combine($csv[0], $a);
	});
	array_shift($csv);

	if(count($csv) > 0){foreach($csv as $row){
		if($row['Gift'] == $_GET['Gift']){echo $row['Amount'];}
	}}
}?>
