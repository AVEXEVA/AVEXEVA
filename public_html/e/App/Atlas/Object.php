<?php
require('bin/PHP/index.php');
$Session = new Session( array(
   'Reference' => 'HTML/Form/' . $_GET['Table']
) );
new Page\index($Session);
?>
