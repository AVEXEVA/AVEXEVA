<?php 
session_start();
require('index.php');
setlocale(LC_MONETARY, 'en_US');
if(isset($_SESSION['User'],$_SESSION['Hash'])){
    $r = sqlsrv_query($NEI,"
        SELECT * 
        FROM   nei.dbo.Connection 
        WHERE  Connection.Connector = ? 
               AND Connection.Hash = ?
    ;", array($_SESSION['User'],$_SESSION['Hash']));
    $Connection = sqlsrv_fetch_array($r);
    $My_User    = sqlsrv_query($NEI,"
        SELECT Emp.*, 
               Emp.fFirst AS First_Name, 
               Emp.Last   AS Last_Name 
        FROM   nei.dbo.Emp
        WHERE  Emp.ID = ?
    ;", array($_SESSION['User']));
    $My_User = sqlsrv_fetch_array($My_User); 
    $My_Field = ($My_User['Field'] == 1 && $My_User['Title'] != "OFFICE") ? True : False;
    $r = sqlsrv_query($Portal,"
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
    if( isset($My_Privileges['Lead']) 
        && (
				$My_Privileges['Lead']['Other_Privilege'] >= 4
		)
	 ){
            $Privileged = True;}
    if(!isset($Connection['ID']) || !$Privileged){print json_encode(array('data'=>array()));}
    else {
        $r = sqlsrv_query($NEI,"
			SELECT   Lead.ID           AS ID,
				     Lead.fDesc        AS Name,
				     Lead.Address      AS Street,
				     Lead.City         AS City,
				     Lead.State        AS State,
				     Lead.Zip          AS Zip,
				     OwnerWithRol.Name AS Customer
			FROM     nei.dbo.Lead
					 LEFT JOIN nei.dbo.OwnerWithRol ON OwnerWithRol.ID = Lead.Owner
			ORDER BY Lead.fDesc ASC
		",array(),array("Scrollable"=>SQLSRV_CURSOR_KEYSET));
		$data = array();
		$row_count = sqlsrv_num_rows( $r );
		if($r){
			while($i < $row_count){
				$Lead = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC);
				if(is_array($Lead) && $Lead != array()){
					$data[] = $Lead;
				}
				$i++;
			}
		}
		print json_encode(array('data'=>$data)); 
	}
}?>