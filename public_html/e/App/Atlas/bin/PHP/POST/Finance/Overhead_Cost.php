<?php 
session_start();
require('../get/index.php');
if(isset($_SESSION['User'],$_SESSION['Hash'])){
    $r = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.Connection WHERE Connector = ? AND Hash = ?;",array($_SESSION['User'],$_SESSION['Hash']));
    $array = sqlsrv_fetch_array($r);
    $Privileged = FALSE;
    if(!isset($_SESSION['Branch']) || $_SESSION['Branch'] == 'Nouveau Elevator'){
        $r = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.Emp WHERE ID = ?",array($_GET['User']));
        $My_User = sqlsrv_fetch_array($r);
        $Field = ($User['Field'] == 1 && $User['Title'] != "OFFICE") ? True : False;
        $r = sqlsrv_query($Portal,"
            SELECT Access_Table, User_Privilege, Group_Privilege, Other_Privilege
            FROM   Portal.dbo.Privilege
            WHERE  User_ID = ?
        ;",array($_SESSION['User']));
        $My_Privileges = array();
        while($array2 = sqlsrv_fetch_array($r)){$My_Privileges[$array2['Access_Table']] = $array2;}
        $Privileged = FALSE;
        if(isset($My_Privileges['Location']) && $My_Privileges['Location']['User_Privilege'] >= 6 && $My_Privileges['Location']['Group_Privilege'] >= 6 && $My_Privileges['Location']['Other_Privilege'] >= 6){$Privileged = TRUE;}
    }
    if(!$Privileged || count($_POST) == 0){?><html><head><script>document.location.href='../login.php';</script></head></html><?php }
    else {
		if(isset($_POST['action']) && $_POST['action'] == 'edit'){
			if(isset($_POST['data']) && count($_POST['data']) > 0){
				$data = array();
				foreach($_POST['data'] as $ID=>$Overhead_Cost){
					sqlsrv_query($NEI,"
						UPDATE Portal.dbo.Overhead_Cost
						SET    Ovearhead_Cost.Type  = ?,
							   Ovearhead_Cost.Start = ?,
							   Ovearhead_Cost.End   = ?,
							   Ovearhead_Cost.Rate  = ?
						WHERE  Ovearhead_Cost.ID    = ?
					;", array($Overhead_Cost['Type'], $Overhead_Cost['Start'], $Overhead_Cost['End'], $Overhead_Cost['Rate'], $ID));
					$data[] = $Overhead_Cost;
				}
				print json_encode(array('data'=>$data));
			}
		} elseif(isset($_POST['action']) && $_POST['action'] == 'create'){
			if(isset($_POST['data']) && count($_POST['data']) > 0){
				$data = array();
				foreach($_POST['data'] as $ID=>$Overhead_Cost){
					sqlsrv_query($NEI,"
						INSERT INTO Portal.dbo.Overhead_Cost([Type], [Start], [End], [Rate])
						VALUES(?,?,?,?);
					;",array($Overhead_Cost['Type'],$Overhead_Cost['Start'],$Overhead_Cost['End'],$Overhead_Cost['Rate']));
					if( ($errors = sqlsrv_errors() ) != null) {
						foreach( $errors as $error ) {
							echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
							echo "code: ".$error[ 'code']."<br />";
							echo "message: ".$error[ 'message']."<br />";
						}
					}
					$resource = sqlsrv_query($NEI,"
						SELECT Max(Overhead_Cost.ID) AS Overhead_Cost_ID
						FROM   Portal.dbo.Overhead_Cost
					;");
					$Overhead_Cost_ID = $resource ? sqlsrv_fetch_array($resource)['Overhead_Cost_ID'] : '-1';
					$Overhead_Cost["ID"] = $Overhead_Cost_ID;
					$data[] = $Overhead_Cost;
				}
				print json_encode(array('data'=>$data));
			}
		} elseif(isset($_POST['action']) && $_POST['action'] == 'remove'){
			if(isset($_POST['data']) && count($_POST['data']) > 0){
				foreach($_POST['data'] as $ID=>$Location){
					sqlsrv_query($NEI,"DELETE FROM Portal.dbo.Overhead_Cost WHERE Overhead_Cost.ID = ?",array($ID));
				}
				print json_encode(array('data'=>array()));
			}
		}
    }
}?>