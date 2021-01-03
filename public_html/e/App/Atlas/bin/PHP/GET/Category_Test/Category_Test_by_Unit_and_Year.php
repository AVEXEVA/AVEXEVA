<?php
session_start();
require('index.php');
if(isset($_SESSION['User'],$_SESSION['Hash'])){
    $r = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.Connection WHERE Connector = ? AND Hash = ?;",array($_SESSION['User'],$_SESSION['Hash']));
    $array = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC);
    $User = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.Emp WHERE ID = ?",array($_GET['User']));
    $User = sqlsrv_fetch_array($User);
    $Field = ($User['Field'] == 1 && $User['Title'] != "OFFICE") ? True : False;
    $r = sqlsrv_query($Portal,"
        SELECT User_Privilege, Group_Privilege, Other_Privilege
        FROM   Portal.dbo.Privilege
        WHERE  User_ID = ? AND Access_Table='Ticket'
    ;",array($_SESSION['User']));
    $My_Privileges = array();
    if($r){
      $My_Privileges = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC);
    }

    if(!isset($array['ID']) || !isset($_GET['Unit']) || !is_numeric($_GET['Unit']) || !isset($My_Privileges['User_Privilege']) || $My_Privileges['User_Privilege'] < 6){
      ?><html><head><script>document.location.href='../login.php';</script></head></html><?php }
    else {
      $data = array();
			$r = sqlsrv_query($Portal,
        "SELECT   Deficiency.*
				 FROM     Portal.dbo.Deficiency
                  LEFT JOIN Portal.dbo.Category_Test ON Deficiency.Category_Test = Category_Test.ID
        WHERE     Category_Test.[Start] >= ?
                  AND Category_Test.[End] <= ?
                  AND Category_Test.Unit = ?
                  AND Deficiency.Elevator_Part IS NOT NULL
                  AND Deficiency.Elevator_Part <> 0
			;",array(date("Y-01-01 00:00.000"), date("Y-01-01 00:00:00.000", strtotime("+1 Year")), $_GET['Unit']));
      if( ($errors = sqlsrv_errors() ) != null) {
        foreach( $errors as $error ) {
            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".$error[ 'message']."<br />";
        }
    }
		if($r){while($array = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC)){
      $data[$array['Elevator_Part'] . '-' . $array['Condition'] . '-' . $array['Remedy']] = $array;
    }}
    print json_encode(array('data'=>$data));
  }
}?>
