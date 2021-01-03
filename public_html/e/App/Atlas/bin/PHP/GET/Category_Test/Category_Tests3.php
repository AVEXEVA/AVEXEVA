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
    //SQL Server
    $serverName = '172.16.12.45';
    $connectionInfo = array(
    	'Database'=>'nei',
    	'UID'=>'sa',
    	'PWD'=>'SQLABC!23456',
      'ReturnDatesAsStrings'=>true,
      "CharacterSet" => "UTF-8"
    );
    $conn = sqlsrv_connect( $serverName, $connectionInfo);

    //CHECK
    if(   !is_int((int)$_GET['order'][0]['column'])
      ||  !in_array($_GET['order'][0]['dir'], array('ASC', 'asc', 'DESC', 'desc'))
      ||  !is_int((int)$_GET['start'])
      ||  !is_int((int)$_GET['length'])
    ){exit;}

    //GET
    ////Order
    $dOrder  = strtoupper($_GET['order'][0]['dir']);
  	$Columns = array(
      'Category_Test.ID',
      'Loc.Tag',
      'Elev.Name',
      'Category_Test.Start',
      'Violation.ID',
      'Violation.Status',
      'Category_Test.Printed',
      'Zone.Name',
      'Job.ID'
  	);
    $cOrder = isset($Columns[$_GET['order'][0]['column']]) ? $Columns[$_GET['order'][0]['column']] : $Columns[0];
    $sOrder = $cOrder == 'Elev.Name' ? "Elev.State {$dOrder}, Elev.Unit {$dOrder}" : $cOrder . ' ' . $dOrder;

    if($_GET['Status_Order'] == 'true'){ $sOrder = $_GET['Status_Order_By'] == '' ? $sOrder : in_array($_GET['Status_Order_By'], array('asc', 'desc')) ? 'Violation.[Status] ' . $_GET['Status_Order_By'] . ', ' . $sOrder : $sOrder; }
    else { $sOrder = $_GET['Status_Order'] == '' ? $sOrder : in_array($_GET['Status_Order_By'], array('asc', 'desc')) ? $sOrder . ', Violation.[Status] ' . $_GET['Status_Order_By'] : $sOrder; }

    if($_GET['Division_Order'] == 'true'){ $sOrder = $_GET['Division_Order_By'] == '' ? $sOrder : in_array($_GET['Division_Order_By'], array('asc', 'desc')) ? 'Zone.[ID] ' . $_GET['Division_Order_By'] . ', ' . $sOrder : $sOrder; }
    else { $sOrder = $_GET['Division_Order'] == '' ? $sOrder : in_array($_GET['Division_Order_By'], array('asc', 'desc')) ? $sOrder . ', Zone.[ID] ' . $_GET['Division_Order_By'] : $sOrder; }

    if($_GET['Date_Order'] == 'true'){ $sOrder = $_GET['Date_Order_By'] == '' ? $sOrder : in_array($_GET['Date_Order_By'], array('asc', 'desc')) ? 'Category_Test.Start ' . $_GET['Date_Order_By'] . ', ' . $sOrder : $sOrder; }
    else { $sOrder = $_GET['Date_Order_By'] == '' ? $sOrder : in_array($_GET['Date_Order_By'], array('asc', 'desc')) ? $sOrder . ', Category_Test.Start ' . $_GET['Date_Order_By'] : $sOrder; }

    ////Paging
    $Start  = is_integer( ( int ) $_GET['start'])  ? $_GET['start']  : 0;
    $Length = is_integer( ( int ) $_GET['length']) ? $_GET['length'] : 10;
    $End    = $Start + $Length;

    ////Search
    $search = isset($_GET['search']['value']) ? $_GET['search']['value'] : '';

    $_GET['Date_Start'] = (bool)strtotime($_GET['Date_Start'])  ? date('Y-m-d 00:00:00.000', strtotime( $_GET['Date_Start'] ))  : '2000-01-01 00:00:00.000';
    $_GET['Date_End']   = (bool)strtotime($_GET['Date_End'])    ? date('Y-m-d 00:00:00.000', strtotime( $_GET['Date_End'] ))    : '2050-01-01 00:00:00.000';

    //Prams
    $params = array(
      $_SESSION['User'],
      $_SESSION['User'],
      $_GET['Category_Test'],
      $_GET['Category_Test_ID'],
      $_GET['Category_Test'],
      $search,
      $search,
      $search,
      $search,
      $search,
      $search,
      $search,
      $search,
      $search,
      $_GET['Date_Start'],
      $_GET['Date_Start'],
      $_GET['Date_End'],
      $_GET['Date_End']
    );

    //Statuses
    $Statuses = array();
    $r = sqlsrv_query($NEI, "SELECT Violation.Status FROM [nei].dbo.[Violation] GROUP BY Violation.Status;");
    if($r){while($row = sqlsrv_fetch_array($r)){$Statuses[] = $row['Status'];}}
    $sStatuses = array();
    if(count($_GET['Statuses']) > 0){foreach($_GET['Statuses'] as $k=>$v){if(in_array($v, $Statuses)){$sStatuses[] = $v;}}}
    $sStatuses = count($sStatuses) > 0 ? "('" . implode("', '", $sStatuses) . "')" : "('0')";

    //Divisions
    $Divisions = array();
    $r = sqlsrv_query($NEI, "SELECT Zone.ID FROM [nei].dbo.[Zone];");
    if($r){while($row = sqlsrv_fetch_array($r)){$Divisions[] = $row['ID'];}}
    $sDivisions = array();
    if(count($_GET['Divisions']) > 0){foreach($_GET['Divisions'] as $k=>$v){if(in_array($v, $Divisions)){$sDivisions[] = $v;}}}
    $sDivisions = count($sDivisions) > 0 ? "('" . implode("', '", $sDivisions) . "')" : "('99999999999')";

    //Select
    $sQuery = "  SELECT Tbl.*
			           FROM  (
                   SELECT  ROW_NUMBER() OVER (ORDER BY {$sOrder}) AS ROW_COUNT,
                           Category_Test.ID AS ID,
                           Category_Test.[Start] AS Date,
                           Category_Test.Void AS Void,
                           Category_Test.Final AS Finalized,
                           Loc.Tag AS Location_Name,
                           Category_Test.Printed AS Printed,
                           Category_Test.Violation AS Violation,
                           Violation.Status AS Violation_Status,
                           Zone.Name AS Division,
                           Violation.Job,
                           Elev.State + ' - ' + Elev.Unit AS Unit_Name
                   FROM    Portal.dbo.Category_Test
                           LEFT JOIN [nei].dbo.[Loc] ON [Loc].[Loc] = Category_Test.Location
                           LEFT JOIN [nei].dbo.[Elev] ON [Category_Test].Unit = Elev.ID
                           LEFT JOIN [nei].dbo.[Violation] ON [Violation].ID = Category_Test.Violation
                           LEFT JOIN [nei].dbo.[Zone] ON [Loc].[Zone] = [Zone].ID
                           LEFT JOIN [nei].dbo.[Job] ON [Violation].[Job] = [Job].ID
                   WHERE   Category_Test.Status = 'Complete'
                           AND Category_Test.ID IN (Select Deficiency.Category_Test FROM Portal.dbo.Deficiency)
                           AND ([Category_Test].[Start] <= '2019-11-11' OR ([Category_Test].[Final] = 1 AND [Category_Test].[Parent] > 0))
                           AND (     Zone.ID IN (SELECT Code_Department.Division FROM Portal.dbo.Code_Department WHERE Code_Department.[User] = ? AND Code_Department.Category_Test = 1 )
                                OR  (Zone.ID = 1 AND 0 IN (SELECT Code_Department.Division FROM Portal.dbo.Code_Department WHERE Code_Department.[User] = ?)))
                           AND (  (? = 'true' AND Category_Test.ID = ?)
                                  OR (? <> 'true' AND ((
                                           Category_Test.ID              LIKE '%' + ? + '%'
                                       OR  Loc.Tag                       LIKE '%' + ? + '%'
                                       OR  Elev.State + ' ' + Elev.Unit  LIKE '%' + ? + '%'
                                       OR  Violation.ID                  LIKE '%' + ? + '%'
                                       OR  Violation.Status              LIKE '%' + ? + '%'
                                       OR  Category_Test.Printed         LIKE '%' + ? + '%'
                                       OR  Zone.Name                     LIKE '%' + ? + '%'
                                       OR  Job.ID                        LIKE '%' + ? + '%'
                                     ) OR  ? = '')
                                     AND (
                                      ( ? = '' OR Category_Test.Start >= ? )
                                      AND (? = '' OR Category_Test.Start <= ? ))
                                      AND (Violation.Status IN {$sStatuses} OR '0' IN {$sStatuses})
                                      AND (Zone.ID IN {$sDivisions} OR '99999999999' IN {$sDivisions})))

      			           ) AS Tbl
			           WHERE Tbl.ROW_COUNT BETWEEN {$Start} AND {$End};";


    //Test SQL
    ////echo $sQuery;

    $rResult = sqlsrv_query($conn,  $sQuery, $params ) or die(print_r(sqlsrv_errors()));

    //FilteredTotal
		$sQueryRow = "  SELECT  Category_Test.ID
                    FROM    Portal.dbo.Category_Test
                            LEFT JOIN [nei].dbo.[Loc] ON [Loc].[Loc] = Category_Test.Location
                            LEFT JOIN [nei].dbo.[Elev] ON [Category_Test].Unit = Elev.ID
                            LEFT JOIN [nei].dbo.[Violation] ON [Violation].ID = Category_Test.Violation
                            LEFT JOIN [nei].dbo.[Zone] ON [Loc].[Zone] = [Zone].ID
                            LEFT JOIN [nei].dbo.[Job] ON [Violation].[Job] = [Job].ID
                    WHERE   Category_Test.Status = 'Complete'
                            AND Category_Test.ID IN (Select Deficiency.Category_Test FROM Portal.dbo.Deficiency)
                            AND ([Category_Test].[Start] <= '2019-11-11' OR ([Category_Test].[Final] = 1 AND [Category_Test].[Parent] > 0))
                            AND (     Zone.ID IN (SELECT Code_Department.Division FROM Portal.dbo.Code_Department WHERE Code_Department.[User] = ? AND Code_Department.Category_Test = 1 )
                                 OR  (Zone.ID = 1 AND 0 IN (SELECT Code_Department.Division FROM Portal.dbo.Code_Department WHERE Code_Department.[User] = ?)))
                            AND (  (? = 'true' AND Category_Test.ID = ?)
                                   OR (? <> 'true' AND ((
                                            Category_Test.ID              LIKE '%' + ? + '%'
                                        OR  Loc.Tag                       LIKE '%' + ? + '%'
                                        OR  Elev.State + ' ' + Elev.Unit  LIKE '%' + ? + '%'
                                        OR  Violation.ID                  LIKE '%' + ? + '%'
                                        OR  Violation.Status              LIKE '%' + ? + '%'
                                        OR  Category_Test.Printed         LIKE '%' + ? + '%'
                                        OR  Zone.Name                     LIKE '%' + ? + '%'
                                        OR  Job.ID                        LIKE '%' + ? + '%'
                                      ) OR  ? = '')
                                      AND (
                                       ( ? = '' OR Category_Test.Start >= ? )
                                       AND (? = '' OR Category_Test.Start <= ? ))
                                       AND (Violation.Status IN {$sStatuses} OR '0' IN {$sStatuses})
                                       AND (Zone.ID IN {$sDivisions} OR '99999999999' IN {$sDivisions})));";
    $options =  array( 'Scrollable' => SQLSRV_CURSOR_KEYSET );
    $stmt = sqlsrv_query( $conn, $sQueryRow, $params, $options );

    $iFilteredTotal = sqlsrv_num_rows( $stmt );

    //Total
    $sQuery = "SELECT COUNT(Category_Test.ID) FROM Portal.dbo.Category_Test;";
    $rResultTotal = sqlsrv_query($conn,  $sQuery, array( $_SESSION['Customer'] ) ) or die(print_r(sqlsrv_errors()));
    $aResultTotal = sqlsrv_fetch_array($rResultTotal);
    $iTotal = $aResultTotal[0];

    //Template
    $output = array(
        'sEcho'                 => intval($_GET['sEcho']),
        'iTotalRecords'         => $iTotal,
        'iTotalDisplayRecords'  => $iFilteredTotal,
        'aaData'                => array()
    );

    //SQL Buffer
    while ( $aRow = sqlsrv_fetch_array( $rResult ) ){
      $aRow['Date'] = date('m/d/Y h:i A', strtotime($aRow['Date']));

      $array['Date'] = date('m/d/Y',strtotime($array['Date']));
      $r2 = sqlsrv_query($Portal, "SELECT * FROM [Deficiency] WHERE [Deficiency].Category_Test = ?;", array($array['ID']));
      $Processed = FALSE;
      $Override = FALSE;
      if($array['Void'] == 1){$Void = TRUE;} else {$Void = FALSE;}
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
      $array['Printed'] = $Void ? 'Void' : $array['Printed'];
      $data[] = $array;
		  $output['aaData'][] = $aRow;
    }

    //Output
    echo json_encode( $output );
  }
}?>
