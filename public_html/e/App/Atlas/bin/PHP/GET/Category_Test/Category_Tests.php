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
        WHERE  User_ID = ? AND Access_Table='Test'
    ;",array($_SESSION['User']));
    $My_Privileges = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC);
    if(!isset($array['ID'])){?><html><head><script>document.location.href='../login.php';</script></head></html><?php }
    else {
      $data = array();
			$r = sqlsrv_query($Portal,
        "SELECT   Category_Test.ID AS ID,
                  Category_Test.[Start] AS Date,
                  Loc.Tag AS Location_Name,
                  Category_Test.Printed AS Printed,
                  Elev.State + ' - ' + Elev.Unit AS Unit_Name/*,*/
				 FROM     Portal.dbo.Category_Test
                  LEFT JOIN [nei].dbo.[Loc] ON [Loc].[Loc] = Category_Test.Location
                  LEFT JOIN [nei].dbo.[Elev] ON [Category_Test].Unit = Elev.ID
        WHERE     Category_Test.Status = 'Complete'
                  AND Category_Test.ID IN (Select Category_Test FROM Deficiency)
			;",array());
      if( ($errors = sqlsrv_errors() ) != null) {
        foreach( $errors as $error ) {
            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".$error[ 'message']."<br />";
        }
    }
		if($r){while($array = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC)){
      $array['Date'] = date('m/d/Y',strtotime($array['Date']));
      $r2 = sqlsrv_query($Portal, "SELECT * FROM [Deficiency] WHERE [Deficiency].Category_Test = ?;", array($array['ID']));
      $Processed = FALSE;
      $Override = FALSE;
      if($r2){
        $Processed = TRUE;
        while($row2 = sqlsrv_fetch_array($r2)){
          if($row2['Processed'] == 1 && is_null($row2['Action'])){$Override = TRUE;}
          if($row2['Processed'] == 1){}
          elseif(is_null($row2['Processed'])){$Processed = FALSE;}
        }
      }
      $array['Printed'] = is_null($array['Printed']) ? 'Created' : 'Printed';
      $array['Printed'] = $Processed ? 'Processed' : $array['Printed'];
      $array['Printed'] = $Override ? 'Paperwork' : $array['Printed'];
      $data[] = $array;
    }}
    print json_encode(array('data'=>$data));
  }
}?>
