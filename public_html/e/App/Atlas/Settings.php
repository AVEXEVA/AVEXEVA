<?php
require('bin/PHP/index.php');
$Session = new Session( array(
   'Reference' => 'HTML/Settings'
) );
new Page\index($Session);
?>
