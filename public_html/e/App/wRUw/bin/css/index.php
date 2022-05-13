<link rel='stylesheet' type='text/css' href='bin/css/site.css?<?php echo rand( 0,99999999 );?>' />

<link rel='stylesheet' type='text/css' href='bin/css/calendar.css?<?php echo rand( 0,99999999 );?>' />

<?php
if( file_exists( __dir__ . '/page/' . substr( $_server['script_name'], 1, strlen( $_server['script_name'] ) - 5 ) . '/index.php') ){
  load( '../css/page/' . substr( $_server['script_name'], 1, strlen( $_server['script_name'] ) - 5 ) . '/index.php' );
}?>
