<?php
require('bin/PHP/index.php');
$Session = new Session( array(
   'Reference' => 'HTML/Datatable'
) );
new Page\index($Session);
?>
