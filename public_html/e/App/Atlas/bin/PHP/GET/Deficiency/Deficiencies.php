<?php
session_start();
require('index.php');
if(isset( $_SESSION['User'], $_SESSION['Hash']) ){
  $r = sqlsrv_query($NEI,
    " SELECT  *
	    FROM    nei.dbo.Connection
	    WHERE   Connection.Connector = ?
	            AND Connection.Hash  = ?
    ;",array( $_SESSION['User'], $_SESSION['Hash'] ));
  $My_Connection = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC);

  $r = sqlsrv_query($NEI,
    " SELECT *,
  		       Emp.fFirst AS First_Name,
  			     Emp.Last   AS Last_Name
  		FROM   nei.dbo.Emp
  		WHERE  Emp.ID = ?
  	;", array( $_SESSION['User']) );
  $My_User = sqlsrv_fetch_array($r);

  $r = sqlsrv_query($NEI,
    " SELECT *
	    FROM   Portal.dbo.Privilege
	    WHERE  Privilege.User_ID = ?
    ;", array( $_SESSION['User']) );
  $My_Privileges = array();

  $r = sqlsrv_query($NEI,
    " SELECT    Emp.ID                   AS ID,
                Emp.fFirst               AS First_Name,
                Emp.Last                 AS Last_Name,
                Admin.Category_Test      AS Admin,
                Division_1.Category_Test AS Division_1,
                Division_2.Category_Test AS Division_2,
                Division_3.Category_Test AS Division_3,
                Division_4.Category_Test AS Division_4
      FROM      nei.dbo.Emp
                LEFT JOIN Portal.dbo.Code_Department AS Admin       ON Admin.[User]      = Emp.ID AND Admin.[Division]       = 0
                LEFT JOIN Portal.dbo.Code_Department AS Division_1  ON Division_1.[User] = Emp.ID AND Division_1.[Division]  = 2
                LEFT JOIN Portal.dbo.Code_Department AS Division_2  ON Division_2.[User] = Emp.ID AND Division_2.[Division]  = 3
                LEFT JOIN Portal.dbo.Code_Department AS Division_3  ON Division_3.[User] = Emp.ID AND Division_3.[Division]  = 4
                LEFT JOIN Portal.dbo.Code_Department AS Division_4  ON Division_4.[User] = Emp.ID AND Division_4.[Division]  = 5
      WHERE     Emp.Status = 0
                AND Emp.ID = ?
                AND (
                  Admin.Category_Test = 1
                  OR Division_1.Category_test = 1
                  OR Division_2.Category_test = 1
                  OR Division_3.Category_test = 1
                  OR Division_4.Category_test = 1
                )
    ;", array( $_SESSION['User'] ));
  $Code_Department_User = sqlsrv_fetch_array($r);

	if($r){while($My_Privilege = sqlsrv_fetch_array($r)){$My_Privileges[$My_Privilege['Access_Table']] = $My_Privilege;}}
  if(	!isset($My_Connection['ID']) || !isset($Code_Department_User['ID'])){?><?php require('../404.html');?><?php }
  else {
    $data = array();
    $r = sqlsrv_query($Portal,
      " SELECT  Deficiency.ID,
                Deficiency.Comments,
                Deficiency.Processed,
                Deficiency.Action,
                Deficiency.Ticket,
                Deficiency.Notes,
                Category_Elevator_Part.Name AS Part,
                Category_Elevator_Part.External_ID AS Part_ID,
                Category_Violation_Condition.Name AS Condition,
                Category_Violation_Condition.External_ID AS Condition_ID,
                Category_Remedy.Name AS Remedy,
                Category_Remedy.External_ID AS Remedy_ID
        FROM    [Portal].dbo.[Deficiency]
                LEFT JOIN Portal.dbo.Category_Elevator_Part ON Deficiency.Elevator_Part = Category_Elevator_Part.ID
                LEFT JOIN Portal.dbo.Category_Violation_Condition ON Category_Violation_Condition.ID = Deficiency.[Condition]
                LEFT JOIN Portal.dbo.Category_Remedy ON Deficiency.Remedy = Category_Remedy.ID
        WHERE   Deficiency.Category_Test = ?
      ;", array($_GET['ID']));
    if($r){while($row = sqlsrv_fetch_array($r)){
      $row['Notes'] = is_null($row['Notes']) ? '' : $row['Notes'];
      $data[] = $row;
    }}
    print json_encode(array('data'=>$data));
  }
}?>
