<?php
require('bin/PHP/index.php');
$Session = new Session( array(
  'Reference' => 'HTML/Ticket',
  'Table' => 'Ticket',
  'ID' => 'ID',
  'Name' => 'ID'
));
new Page\index($Session);
?>
