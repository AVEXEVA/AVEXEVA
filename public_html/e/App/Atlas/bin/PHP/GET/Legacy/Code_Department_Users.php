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
    if( isset($My_Privileges['Code'])
        && (
    				$My_Privileges['Code']['Other_Privilege'] >= 4
    			||	$My_Privileges['Code']['Group_Privilege'] >= 4
    			||	$My_Privileges['Code']['User_Privilege'] >= 4
		    )){ $Privileged = True; }
    //Category Tests Privileges
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
                  AND Admin.Category_Test = 1
                  AND (
                    Admin.Category_Test = 1
                    OR Division_1.Category_test = 1
                    OR Division_2.Category_test = 1
                    OR Division_3.Category_test = 1
                    OR Division_4.Category_test = 1
                  )
      ;", array( $_SESSION['User'] ));
    $Code_Department_User = sqlsrv_fetch_array($r);
    $Privileged = $Privileged && is_array($Code_Department_User) ? true : false;

	if( !isset($Connection['ID']) || !$Privileged ){print json_encode(array('data'=>array()));}
    else {
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
                    AND Emp.[ID] IN (SELECT [Code_Department].[User] FROM [Portal].dbo.[Code_Department])
                    AND (
                      Admin.Category_Test = 1
                      OR Division_1.Category_test = 1
                      OR Division_2.Category_test = 1
                      OR Division_3.Category_test = 1
                      OR Division_4.Category_test = 1
                    )
        ;");
      $data = array();
      if($r){while($array = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC)){$data[] = $array;}}
      print json_encode(array('data'=>$data));	}
}?>
