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
    if( isset($My_Privileges['Proposal']) 
        && (
			$My_Privileges['Proposal']['Other_Privilege'] >= 4
		)
	 ){
            $Privileged = True;}
    if(!isset($Connection['ID']) || !$Privileged){print json_encode(array('data'=>array()));}
    else {
        $Keyword = addslashes($_GET['Keyword']);
        $r = sqlsrv_query($NEI,"
            SELECT Estimate.ID       AS ID,
                   Estimate.Name     AS Contact,
                   Loc.Tag           AS Location,
                   Estimate.fDesc    AS Title,
                   Estimate.fDate    AS fDate,
                   Estimate.Cost     AS Cost,
                   Estimate.Price    AS Price,
                   OwnerWithRol.Name AS Customer
            FROM   nei.dbo.Estimate
                   LEFT JOIN nei.dbo.Loc          ON Estimate.LocID  = Loc.Loc
                   LEFT JOIN nei.dbo.OwnerWithRol ON OwnerWithRol.ID = Loc.Owner
            WHERE  Estimate.ID          LIKE '%{$Keyword}%'
                   OR Estimate.Name     LIKE '%{$Keyword}%'
                   OR Loc.Tag           LIKE '%{$Keyword}%'
                   OR Estimate.fDesc    LIKE '%{$Keyword}%'
                   OR Estimate.fDate    LIKE '%{$Keyword}%'
                   OR Estimate.Cost     LIKE '%{$Keyword}%'
                   OR Estimate.Price    LIKE '%{$Keyword}%'
                   OR OwnerWithRol.Name LIKE '%{$Keyword}%'
        ");
        $data = array();
        if($r){while($array = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC)){$data[] = $array;}}
        print json_encode(array('data'=>$data));   }
}?>