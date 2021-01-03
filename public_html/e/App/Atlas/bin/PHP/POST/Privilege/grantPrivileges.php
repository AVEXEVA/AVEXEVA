<?php 
session_start();
require('../get/index.php');
if(isset($_SESSION['User'],$_SESSION['Hash'])){
    $r = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.Connection WHERE Connector = ? AND Hash = ?;",array($_SESSION['User'],$_SESSION['Hash']));
    $array = sqlsrv_fetch_array($r);
    $User = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.Emp WHERE ID = ?",array($_GET['User']));
    $User = sqlsrv_fetch_array($User);
    $Field = ($User['Field'] == 1 && $User['Title'] != "OFFICE") ? True : False;
    $r = sqlsrv_query($Portal,"
        SELECT Privilege.*
        FROM   Portal.dbo.Privilege
        WHERE 
            User_ID='{$_SESSION['User']}'
            AND Access_Table='Admin'
            AND User_Privilege='7'
            AND Group_Privilege='7'
            AND Other_Privilege='7'
    ;");
    $Admin = sqlsrv_fetch_array($r);
    if(!isset($array['ID'])  || !is_array($Admin)){?><html><head><script>document.location.href='../login.php';</script></head></html><?php }
    else {
        $r = sqlsrv_query($Portal,"
            SELECT Privilege.*
            FROM   Portal.dbo.Privilege
            WHERE  User_ID='{$_POST['User_ID']}'
                AND Access_Table='{$_POST['Access_Table']}'
        ;");
        $array = sqlsrv_fetch_array($r);
        if(isset($array['ID']) && $array['ID'] > 0){
            $r = sqlsrv_query($Portal,"
                UPDATE Portal.dbo.Privilege
                SET 
                    User_Privilege='{$_POST['User_Privilege']}',
                    Group_Privilege='{$_POST['Group_Privilege']}',
                    Other_Privilege='{$_POST['Other_Privilege']}'
                WHERE 
                    User_ID='{$_POST['User_ID']}'
                    AND Access_Table='{$_POST['Access_Table']}'
            ;");
        } else {
            $r = sqlsrv_query($Portal,"
                INSERT INTO Portal.dbo.Privilege(User_ID,Access_Table,User_Privilege,Group_Privilege,Other_Privilege)
                VALUES({$_POST['User_ID']},'{$_POST['Access_Table']}',{$_POST['User_Privilege']},{$_POST['Group_Privilege']},{$_POST['Other_Privilege']})
            ;");
        }
        //print json_encode(array('data'=>$data));   
    }
}?>