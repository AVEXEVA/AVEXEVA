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
    if( isset($My_Privileges['Job'],$My_Privileges['Invoice']) 
        && $My_Privileges['Job']['Other_Privilege'] >= 4
	  	&& $My_Privileges['Invoice']['Other_Privilege'] >= 4){
            $Privileged = True;}
    if(!isset($Connection['ID'])  || !is_numeric($_GET['ID']) || !$Privileged){print json_encode(array('data'=>array()));}
    else {
        $r = sqlsrv_query($NEI,"
            SELECT OpenAR.Ref      AS  Invoice,
                   OpenAR.fDate    AS  Dated,
                   OpenAR.Due      AS  Due,
                   OpenAR.fDesc    AS  Description,
                   OpenAR.Original AS  Original,
                   OpenAR.Balance  AS  Balance,
                   Loc.Tag         AS  Tag
            FROM   nei.dbo.OpenAR
                   LEFT JOIN nei.dbo.Loc     ON OpenAR.Loc = Loc.Loc
				   LEFT JOIN nei.dbo.Invoice ON OpenAR.Ref = Invoice.Ref
            WHERE  Invoice.Job = ?
        ;",array($_GET['ID']));
        $data = array();
        if($r){while($array = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC)){$data[] = $array;}}
        print json_encode(array('data'=>$data));   }
}?>