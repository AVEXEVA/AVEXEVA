<?php
session_start();
require('../../index.php');
setlocale(LC_MONETARY, 'en_US');
if(isset($_SESSION['User'],$_SESSION['Hash'])){
    $r = sqlsrv_query($Databases['NEI'],"
        SELECT *
        FROM   nei.dbo.Connection
        WHERE  Connection.Connector = ?
               AND Connection.Hash = ?
    ;", array($_SESSION['User'],$_SESSION['Hash']));
    $Connection = sqlsrv_fetch_array($r);
    $My_User    = sqlsrv_query($Databases['NEI'],"
        SELECT Emp.*,
               Emp.fFirst AS First_Name,
               Emp.Last   AS Last_Name
        FROM   nei.dbo.Emp
        WHERE  Emp.ID = ?
    ;", array($_SESSION['User']));
    $My_User = sqlsrv_fetch_array($My_User);
    $My_Field = ($My_User['Field'] == 1 && $My_User['Title'] != "OFFICE") ? True : False;
    $r = sqlsrv_query($Databases['NEI'],"
        SELECT Privilege.Access_Table,
               Privilege.User_Privilege,
               Privilege.Group_Privilege,
               Privilege.Other_Privilege
        FROM   Portal.dbo.Privilege
        WHERE  Privilege.User_ID = ?
    ;",array($_SESSION['User']));
    $My_Privileges = array();
    while($array2 = sqlsrv_fetch_array($r)){$My_Privileges[$array2['Access_Table']] = $array2;}
    $Privileged = False;
    if( isset($My_Privileges['Admin'])
        && (
			$My_Privileges['Admin']['Other_Privilege'] >= 4
		)
	 ){
            $Privileged = True;}
    if(!isset($Connection['ID']) || !$Privileged){print json_encode(array('data'=>array()));}
    else {
        $r = sqlsrv_query($Databases['NEI'],"
            SELECT Emp.ID          AS ID,
                   Emp.fFirst      AS First_Name,
                   Emp.Last        AS Last_Name
            FROM   nei.dbo.Emp
               	   LEFT JOIN nei.dbo.tblWork ON 'A' + convert(varchar(10),Emp.ID) + ',' = tblWork.Members
            WHERE  Emp.Status='0';");
        $data = array();
        if($r){
            while($array = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC)){
                if(isset($array['ID']) && is_numeric($array['ID'])){
                  $r2 = sqlsrv_query($Databases['NEI'],
                    " SELECT Privilege.*
                      FROM   Portal.dbo.Privilege
                      WHERE  Privilege.User_ID = ?;",
                      array($array['ID']));
                  if($r2){while($array2 = sqlsrv_fetch_array($r2)){
                      if(is_array($array2)){
                        $array['Privileges'][] = $array2;
                        if($array2['Access_Table'] == 'Beta'){
                            $array['Beta'] = $array2['User_Privilege'] . $array2['Group_Privilege'] . $array2['Other_Privilege'];
                        }
                      }
                  }}
                  if(!isset($array['Beta'])){$array['Beta'] = '000';}
                  $data[] = $array;
                }
            }
        }
        print json_encode(array('data'=>$data));	}
}?>
