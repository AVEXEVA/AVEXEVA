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
    if( isset($My_Privileges['Unit']) 
        && (
				$My_Privileges['Unit']['Other_Privilege'] >= 4
			||	$My_Privileges['Unit']['Group_Privlege'] >= 4
			||  $My_Privileges['Unit']['User_Privilege'] >= 4
		)
	 ){
            $Privileged = True;}
    if(!isset($Connection['ID']) || !$Privileged){print json_encode(array('data'=>array()));}
    else {
        $data = array();
        $Keyword = addslashes($_GET['Keyword']);
        if($My_Privileges['User_Privilege'] > 4 && $My_Privileges['Group_Privilege'] > 4 && $My_Privileges['Other_Privilege'] > 4){
            $r = sqlsrv_query($NEI,"
                SELECT DISTINCT
                    Elev.ID    AS  ID,
                    Elev.State AS  State, 
                    Elev.Unit  AS  Unit,
                    Elev.Type  AS  Type,
                    Loc.Tag    AS  Location
                FROM 
                    nei.dbo.Elev
                    LEFT JOIN nei.dbo.Loc          ON Elev.Loc  = Loc.Loc
                    LEFT JOIN nei.dbo.OwnerWithRol ON Loc.Owner = OwnerWithRol.ID
                WHERE
                    Elev.ID              LIKE '%{$Keyword}%'
                    OR Elev.State        LIKE '%{$Keyword}%'
                    OR Elev.Unit         LIKE '%{$Keyword}%'
                    OR Elev.Type         LIKE '%{$Keyword}%'
                    OR Elev.Loc          LIKE '%{$Keyword}%'
                    OR Loc.Tag           LIKE '%{$Keyword}%'
                    OR OwnerWithRol.Name LIKE '%{$Keyword}%'
            ;");
            while($array = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC)){$data[] = $array;}
        } else {
            $SQL_Units = array();
            if($My_Privileges['Group_Privilege'] >= 4){
                $r = sqlsrv_query($NEI,"
                    SELECT LElev AS Unit
                    FROM   TicketO
                           LEFT JOIN Emp ON TicketO.fWork = Emp.fWork
                    WHERE  Emp.ID = ?
                ;",array($_SESSION['User']));
                if($r){while($array = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC)){$SQL_Units[] = "Elev.ID='{$array['Unit']}'";}}
                $r = sqlsrv_query($NEI,"
                    SELECT Elev AS Unit
                    FROM   nei.dbo.TicketD
                           LEFT JOIN nei.dbo.Emp ON TicketD.fWork = Emp.fWork
                    WHERE  Emp.ID = ?
                ;",array($_SESSION['User']));
                if($r){while($array = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC)){$SQL_Units[] = "Elev.ID='{$array['Unit']}'";}}
            }
            if($My_Privileges['User_Privilege'] >= 4){
                $r = sqlsrv_query($NEI,"
                    SELECT Elev.ID AS Unit
                    FROM   nei.dbo.Elev
                           LEFT JOIN nei.dbo.Loc   ON Elev.Loc   = Loc.Loc
                           LEFT JOIN nei.dbo.Route ON Loc.Route  = Route.ID
                           LEFT JOIN nei.dbo.Emp   ON Route.Mech = Emp.fWork
                    WHERE  Emp.ID = ?
                ;",array($_SESSION['User']);
                if($r){while($array = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC)){$SQL_Units[] = "Elev.ID='{$array['Unit']}'";}}
            }
            $SQL_Units = array_unique($SQL_Units);
            if(count($SQL_Units) > 0){
                $SQL_Units = implode(' OR ',$SQL_Units);
                $r = sqlsrv_query($NEI,"
                    SELECT DISTINCT
                        Elev.ID         AS  ID,
                        Elev.State      AS  State, 
                        Elev.Unit       AS  Unit,
                        Elev.Type       AS  Type,
                        Loc.Tag         AS  Location
                    FROM 
                        nei.dbo.Elev
                        LEFT JOIN nei.dbo.Loc   ON  Elev.Loc = Loc.Loc
                        LEFT JOIN nei.dbo.OwnerWithRol ON Loc.Owner = OwnerWithRol.ID
                    WHERE
                        (Elev.ID                 LIKE '%{$Keyword}%'
                        	OR Elev.State        LIKE '%{$Keyword}%'
                        	OR Elev.Unit         LIKE '%{$Keyword}%'
                        	OR Elev.Type         LIKE '%{$Keyword}%'
                        	OR Elev.Loc          LIKE '%{$Keyword}%'
                        	OR Loc.Tag           LIKE '%{$Keyword}%'
                        	OR OwnerWithRol.Name LIKE '%{$Keyword}%')
                        AND {$SQL_Units}
                ;");
                if($r){while($array = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC)){$data[] = $array;}}
            }
        }
        print json_encode(array('data'=>$data));   }
}?>