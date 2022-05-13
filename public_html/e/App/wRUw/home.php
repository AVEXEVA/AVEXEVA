<?php 
if( !isset( $_SESSION[ 'User' ] ) ){
	header( 'Location: index.php' );
	exit;
}
require('bin/php/index.php');
?><html>
<head>
<?php 
	//require('bin/html/meta.php');
	require('bin/css/index.php');
	require('bin/javascript/index.php');
?>
	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
	<link rel='stylesheet' type='text/css' href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css' />
	<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js'></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<style>
		* {
			margin:0px;
		}
		header {
			background-color:black;
			font-size:18px;
			color:white;
			padding:10px;
		}
		.Popup {
			position:absolute;
			top:0;
			left:0;
			bottom:0;
			right:0;
			margin:auto;
			background-color:white;
			padding:10px;
		}
	</style>
	<script>
		function formItem( ){
			$.ajax({
				url     : 'bin/html/form/item.php',
				method  : 'get',
				success : function( form ){
					$( 'body' ).append( form );
					$( '#itemModal' ).modal( 'show' );
					$( '#itemModal' ).on( 'hidden.bs.modal' , function( e ){
						$( '#itemModal' ).remove( );
					});
				}
			});
		}
	</script>
</head>
<body>
<header style='height:5%;'>
	<div class='row'>
		<div class='col-lg-8'>What are you wearing <?php echo $_SESSION[ 'User' ][ 'Name' ];?>?</div>
		<div class='col-lg-1'><i class="fas fa-store"></i> Stores</div>
		<div class='col-lg-1'><i class="fas fa-door-closed"></i> Closets</div>
		</div>
	</div>
</header>
<section style='font-size:22px;'>
	<div class='row' style='height:5%;'>
		<div class='col-lg-3'>&nbsp;</div>
		<div class='col-lg-6' style='background-color:#2d2d2d;color:white;text-align:center;'><?php echo $_SESSION[ 'User' ][ 'Name' ];?>'s Closet</div>
		<div class='col-lg-3'>&nbsp;</div>
	</div>
	<div class='row' style='height:85%;'>
		<div class='col-lg-3' style='background-color:#2d2d2d;color:white;'>
			<div class='row'>
				<div class='col-lg-12' style='text-align:center;'>Calendar</div>
			</div>
			<div class='row'>
				<div class='col-lg-12' style=''><?php require('bin/html/calendar.php');?></div>
			</div>
		</div>
		<div class='col-lg-6' style='background-color:#1d1d1d;color:white;'>
			<div class='row'>
				<div class='col-lg-12' style='height:auto;'><?php require('bin/html/wardrobe-buttons.php');?></div>
			</div>
			<div class='row' style='height:750px;border:10px solid #1d1d1d;background-color:#fff;'>
				<div class='col-lg-12'>&nbsp;</div>
			</div>
		</div>
		<div class='col-lg-3' style='background-color:#2d2d2d;color:white;'>
			<div class='row'>
				<div class='col-lg-12' style='text-align:center;'>Dressing Room</div>
			</div>
			<div class='row' style='height:550px;border:10px solid #1d1d1d;background-color:#fff;'>
				<div class='col-lg-12' style=''>&nbsp;</div>
			</div>
			<div class='row'>
				<div class='col-lg-12'>
					<div class='row'>
						<div class='col-lg-2'><i class="fas fa-user-tie"></i></div>
						<div class='col-lg-10'>Outfit</div>
					</div>
				</div>
				<div class='col-lg-12'>
					<div class='row'>
						<div class='col-lg-2'><i class="fas fa-camera-retro"></i></div>
						<div class='col-lg-10'>Look</div>
					</div>
				</div>
				<div class='col-lg-12'>
					<div class='row'>
						<div class='col-lg-2'><i class="fas fa-video"></i></div>
						<div class='col-lg-10'>Live</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class='row' style='height:5%;'>
		<div class='col-lg-3'>&nbsp;</div>
		<div class='col-lg-6' style='background-color:#2d2d2d;color:white;'>&nbsp;</div>
		<div class='col-lg-3'>&nbsp;</div>
	</div>
</section>
<footer></footer>
</body>
</html>