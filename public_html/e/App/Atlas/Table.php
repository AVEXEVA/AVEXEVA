<?php
require('bin/PHP/index.php');
$Session = new Session( array(
   'Reference' => 'HTML/Table/' . $_GET['ID']
) );
new Page\index($Session);
?>
