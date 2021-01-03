<?php
require('bin/PHP/index.php');
$Session = new Session( array(
   'Reference' => 'HTML/Reports'
) );
new Page\index($Session);
?>
