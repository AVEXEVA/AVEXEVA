<?php
session_start();
if( isset( $_SESSION[ 'Username' ] ) ){?><html>
<head>
	<title>Twisted Extracts</title>
	<?php require('bin/css/index.php');?>
	<?php require('bin/js/index.php');?>
</head>
<body>
	<?php if( !isset( $_SESSION[ '21' ] ) || $_SESSION['21'] < 21 ){?><?php require('bin/html/21.php');?><?php }?>
	<?php require('bin/html/header.php');?>
	<?php require('bin/html/body.php');?>
	<?php require('bin/html/footer.php');?>
</body>
</html>
<?php } else {?><script>document.location.href='login.php';</script><?php }?>
