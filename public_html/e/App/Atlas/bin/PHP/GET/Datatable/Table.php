<?php
require('../../index.php');
$Session = new \Session(array(
  'Reference' => 'HTML/' . $_GET['Name'],
  'Table' => $_GET['Name']
));
new \GET\Datatable( array( 'Session' => $Session ));
?>
