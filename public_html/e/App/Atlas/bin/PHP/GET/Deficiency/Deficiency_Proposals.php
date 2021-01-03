<?php
session_start();
require('../index.php');
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
    if( isset($My_Privileges['Violation'])
        && $My_Privileges['Violation']['Other_Privilege'] >= 4){
            $Privileged = True;}
    if(!isset($Connection['ID'])  || !$Privileged){print json_encode(array('data'=>array()));}
    else {
        $r = sqlsrv_query($Portal,
          " SELECT  Deficiency.ID,
                    Deficiency.Percentage,
                    Category_Test.Start AS Date,
                    Category_Test.ID AS Category_ID,
                    Loc.Loc AS Location_ID,
                    Loc.Tag AS Location_Name,
                    Elev.State AS Unit_Name,
                    Category_Elevator_Part.Name AS Part,
                    Category_Elevator_Part.ID AS Part_ID,
                    Category_Violation_Condition.Name AS Condition,
                    Category_Violation_Condition.ID AS Condition_ID,
                    Category_Remedy.Name AS Remedy,
                    Category_Remedy.ID AS Remedy_ID,
                    Violation.Name AS Violation_Name,
                    Violation.Status AS Violation_Status
            FROM    Portal.dbo.Deficiency
                    LEFT JOIN Portal.dbo.Category_Test ON Deficiency.Category_Test = Category_Test.ID
                    LEFT JOIN nei.dbo.Elev ON Category_Test.Unit = Elev.ID
                    LEFT JOIN nei.dbo.Loc ON Loc.Loc = Category_Test.Location
                    LEFT JOIN Portal.dbo.Category_Elevator_Part ON Deficiency.Elevator_Part = Category_Elevator_Part.ID
                    LEFT JOIN Portal.dbo.Category_Violation_Condition ON Category_Violation_Condition.ID = Deficiency.[Condition]
                    LEFT JOIN Portal.dbo.Category_Remedy ON Deficiency.Remedy = Category_Remedy.ID
                    LEFT JOIN nei.dbo.Violation ON Category_Test.Violation = Violation.ID
            WHERE   Deficiency.Action = 'P'
          ;", array());
          if( ($errors = sqlsrv_errors() ) != null) {
          foreach( $errors as $error ) {
              echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
              echo "code: ".$error[ 'code']."<br />";
              echo "message: ".$error[ 'message']."<br />";
          }
      }
        $data = array();
        if($r){while($row = sqlsrv_fetch_array($r)){
          $row['Date'] = date('m/d/Y', strtotime($row['Date']));
          $data[] = $row;
        }}
        print json_encode(array('data'=>$data));
    }
}?>
