<?php
session_start();
require('index.php');
if(isset($_SESSION['User'],$_SESSION['Hash'])){
    $r = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.Connection WHERE Connector = ? AND Hash = ?;",array($_SESSION['User'],$_SESSION['Hash']));
    $array = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC);
    $User = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.Emp WHERE ID = ?",array($_GET['User']));
    $User = sqlsrv_fetch_array($User);
    $Field = ($User['Field'] == 1 && $User['Title'] != "OFFICE") ? True : False;
    $r = sqlsrv_query($Portal,"
        SELECT User_Privilege, Group_Privilege, Other_Privilege
        FROM   Portal.dbo.Privilege
        WHERE  User_ID = ? AND Access_Table='Testing_Admin'
    ;",array($_SESSION['User']));
    $My_Privileges = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC);
    if(!isset($array['ID'])){?><html><head><script>document.location.href='../login.php';</script></head></html><?php }
    else {
      $data = array();
			$r = sqlsrv_query($Portal,
        "SELECT   Category_Test.ID AS ID,
                  Category_Test.[Start] AS Date,
                  Category_Test.Void AS Void,
                  Category_Test.Final AS Finalized,
                  Loc.Tag AS Location_Name,
                  Category_Test.Printed AS Printed,
                  Category_Test.Violation AS Violation,
                  Violation.Status AS Violation_Status,
                  Zone.Name AS Division,
                  Elev.State + ' - ' + Elev.Unit AS Unit_Name
				 FROM     Portal.dbo.Category_Test
                  LEFT JOIN [nei].dbo.[Loc] ON [Loc].[Loc] = Category_Test.Location
                  LEFT JOIN [nei].dbo.[Elev] ON [Category_Test].Unit = Elev.ID
                  LEFT JOIN [nei].dbo.[Violation] ON [Violation].ID = Category_Test.Violation
                  LEFT JOIN [nei].dbo.[Zone] ON [Loc].[Zone] = [Zone].ID
     	WHERE     [Category_Test].[Start] >= '2019-11-12'
		  AND [Category_Test].[Final] IS NULL;",array());
      if( ($errors = sqlsrv_errors() ) != null) {
        foreach( $errors as $error ) {
            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".$error[ 'message']."<br />";
        }
    }
		if($r){while($array = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC)){
      $array['Date'] = date('m/d/Y',strtotime($array['Date']));
      $data[] = $array;
    }}
    print json_encode(array('data'=>$data));
  }
}?>
