<?php
require('bin/PHP/index.php');
$Session = new Session( array(
   'Reference' => 'HTML/Home'
) );
new Page\index($Session);
?>
