<?php
session_start( );
if( isset( $_SESSION[ 'User' ] ) ){
	require( 'home.php' );
} else {
	require( 'login.php' );
}
?>