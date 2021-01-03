<?php
session_start();
require('index.php');
if(isset($_SESSION['User'],$_SESSION['Hash'])){
    $r = sqlsrv_query($NEI,
      " SELECT *
        FROM   nei.dbo.Connection
        WHERE  Connection.Connector = ?
               AND Connection.Hash = ?
    ;", array($_SESSION['User'],$_SESSION['Hash']));
    $Connection = sqlsrv_fetch_array($r);
    $My_User    = sqlsrv_query($NEI,
      " SELECT Emp.*,
               Emp.fFirst AS First_Name,
               Emp.Last   AS Last_Name
        FROM   nei.dbo.Emp
        WHERE  Emp.ID = ?
    ;", array($_SESSION['User']));
    $My_User = sqlsrv_fetch_array($My_User);
    $My_Field = ($My_User['Field'] == 1 && $My_User['Title'] != "OFFICE") ? True : False;
    $r = sqlsrv_query($Portal,
      " SELECT Privilege.Access_Table,
               Privilege.User_Privilege,
               Privilege.Group_Privilege,
               Privilege.Other_Privilege
        FROM   Portal.dbo.Privilege
        WHERE  Privilege.User_ID = ?
    ;",array($_SESSION['User']));
    $My_Privileges = array();
    while($array2 = sqlsrv_fetch_array($r)){$My_Privileges[$array2['Access_Table']] = $array2;}
    $Privileged = False;
    if( isset($My_Privileges['User'])
        && (
				$My_Privileges['User']['Other_Privilege'] >= 4
			||	$My_Privileges['User']['Group_Privilege'] >= 4
			||	$My_Privileges['User']['User_Privilege'] >= 4
		)
	){
            $Privileged = True;}
	if( !isset($Connection['ID']) || !$Privileged ){print json_encode(array('data'=>array()));}
    else {
      $First_Name = isset($_GET['First_Name']) || $_GET['First_Name'] != '' ? $_GET['First_Name'] : null;
      $Last_Name  = isset($_GET['Last_Name'])  || $_GET['Last_Name']  != '' ? $_GET['Last_Name']  : null;
      $r = sqlsrv_query($NEI,
        " SELECT    Emp.Last AS Last_Name
          FROM      nei.dbo.Emp
          WHERE     Emp.Status = 0
                    AND (
                      (     ? IS NULL AND Emp.Last LIKE '%' + ? + '%')
                      OR
                      (Emp.fFirst LIKE '%' + ? + '%' AND Emp.Last LIKE '%' + ? + '%')
                    )
          GROUP BY  Emp.Last
      ;", array($First_Name, $Last_Name, $First_Name, $Last_Name));
      if( ($errors = sqlsrv_errors() ) != null) {
          foreach( $errors as $error ) {
              echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
              echo "code: ".$error[ 'code']."<br />";
              echo "message: ".$error[ 'message']."<br />";
          }
      }
      $data = array();
      if($r){while($array = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC)){$data[] = $array;}}
      print json_encode(array('data'=>$data));
    }
}?>
