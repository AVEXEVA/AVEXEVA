<?php
require('bin/PHP/index.php');
$Session = new Session( array(
   'Reference' => 'HTML/Map'
) );
new Page\index($Session);
?>
