<?php
$Databases['NEI'] = sqlsrv_connect('172.16.12.45', array(
	'Database' => 'nei',
    'Uid' => 'sa',
    'PWD' => 'SQLABC!23456',
    'ReturnDatesAsStrings'=>true,
    'CharacterSet' => 'UTF-8'
));?>