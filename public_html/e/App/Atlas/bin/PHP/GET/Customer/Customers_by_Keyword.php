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
    if( isset($My_Privleges['Customer']) 
	  	&& $My_Privileges['Customer']['Other_Privilege'] >= 4){
            $Privileged = True;}
    if(!isset($Connection['ID'])  || !is_numeric($_GET['ID']) || !$Privileged){print json_encode(array('data'=>array()));}
    else {
        $Keyword = addslashes($_GET['Keyword']);
        $r = sqlsrv_query($NEI,"
            SELECT DISTINCT
                OwnerWithRol.ID                                                                                                         AS ID,
                OwnerWithRol.Name                                                                                                       AS Name,
                OwnerWithRol.Status                                                                                                     AS Status,
                (SELECT Count(Loc.Loc) FROM nei.dbo.Loc WHERE Loc.Owner = OwnerWithRol.ID)                                              AS Locations,
                (SELECT Count(Elev.ID) FROM nei.dbo.Elev LEFT JOIN nei.dbo.Loc ON Loc.Loc = Elev.Loc WHERE Loc.Owner = OwnerWithRol.ID) AS Units
            FROM 
                nei.dbo.OwnerWithRol
                LEFT JOIN nei.dbo.Loc ON OwnerWithRol.ID = Loc.Owner
                LEFT JOIN nei.dbo.Elev ON Loc.Loc = Elev.Loc
            WHERE
                Elev.ID              LIKE '%{$Keyword}%'
                OR Elev.State        LIKE '%{$Keyword}%'
                OR Elev.Unit         LIKE '%{$Keyword}%'
                OR Elev.Type         LIKE '%{$Keyword}%'
                OR Elev.Loc          LIKE '%{$Keyword}%'
                OR Loc.Tag           LIKE '%{$Keyword}%'
                OR OwnerWithRol.Name LIKE '%{$Keyword}%';");
        $Customers = array();
        if($r){while($array = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC)){$Customers[] = $array;}}
        print json_encode(array('data'=>$Customers));  
	}
}