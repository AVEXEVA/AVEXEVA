<?php 
if( isset( $_POST ) ){
	$wRUw = `head -n1 /home/avexeva/avexeva_wRUw`;
	$MySQLi = new mysqli(
		'localhost',
		'avexeva_wRUw',
		$wRUw,
		'avexeva_wRUw'
	);
	if( isset( $_POST[ 'Email' ], $_POST[ 'Password' ] ) ){
		$Statement = $MySQLi->prepare( "SELECT User.ID, User.Name FROM User WHERE User.Email = ? AND User.Password = ?;" );
		$Statement->bind_param( 'ss', $_POST[ 'Email' ], $_POST[ 'Password' ] );
		if( $Statement->execute( ) ){
			$Result = $Statement->get_result( );
			if( mysqli_num_rows( $Result ) == 1 ){
				$_SESSION[ 'User' ] = $Result->fetch_assoc( );
				header( 'Location: index.php?Login=Success' );
				exit;
			}
		}
	}
}
?><html>
<head>
	<title>What are you wearing?</title>
	<?php require( 'bin/css/index.php' );?>
	<link rel='stylesheet' type='text/css' href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css' />
	<style>
		* {
			margin:0px;
		}
	</style>
	<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js'></script>
</head>
<body>
<section id='login'>
	<div style='grid-column:1 / span 1;'>
		<h2>What are you wearing?</h2>
		<form method='POST' action='index.php'>
			<div class='row'>
				<div class='col'>Email:</div>
				<div class='col'><input type='text' name='Email' /></div>
			</div>
			<div class='row'>
				<div class='col'>Password:</div>
				<div class='col'><input type='password' name='Password' /></div>
			</div>
			<div class='row'>
				<div class='col'><button>Login</button></div>
			</div>
		</form>
	</div>
</section>
</body>
</html>