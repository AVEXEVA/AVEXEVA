<?php 
session_start();
require('index.php');
if(isset($_SESSION['User'],$_SESSION['Hash'])){
    $r = sqlsrv_query($NEI,"
		SELECT * 
		FROM nei.dbo.Connection 
		WHERE Connector = ? 
		AND Hash = ?
		;",array($_SESSION['User'],$_SESSION['Hash']));
    $array = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC);
    $User = sqlsrv_query($NEI,"
		SELECT * 
		FROM nei.dbo.Emp 
		WHERE ID = ?
		",array($_GET['User']));
    $User = sqlsrv_fetch_array($User);
    $Field = ($User['Field'] == 1 && $User['Title'] != "OFFICE") ? True : False;
	$Privileged = False;
    if( isset($My_Privileges['Customer'], $My_Privileges['Location']) 
        && $My_Privileges['Customer']['Other_Privilege'] >= 4
	  	&& $My_Privileges['Location']['Other_Privilege'] >= 4){
            $Privileged = True;}
    if(!isset($array['ID'])  || !is_numeric($_GET['ID'])){?><html><head><script>document.location.href='../login.php';</script></head></html><?php }
    else {
		$data = array();
        $r = sqlsrv_query($NEI,"
            SELECT Loc.Owner         AS Customer_ID,
				   OwnerWithRol.Name AS Customer_Name
			FROM   nei.dbo.Loc
			       LEFT JOIN nei.dbo.OwnerWithRol ON Loc.Owner = OwnerWithRol.ID
			WHERE  Loc.Loc = ?
        ;",arraY($_GET['ID']));
        if($r){while($array = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC)){$data[] = $array;}}
        print json_encode(array('data'=>utf8ize($data)));  }
}?>