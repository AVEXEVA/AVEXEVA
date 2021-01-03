<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
			||	$My_Privileges['Unit']['Group_Privilege'] >= 4
			||  $My_Privileges['Unit']['User_Privilege'] >= 4
		)
	 ){
            $Privileged = True;}
    if(!isset($Connection['ID']) || !$Privileged){print json_encode(array('data'=>array()));}
    else {
      $serverName = "172.16.12.45"; //serverName\instanceName
      $connectionInfo = array(
      	"Database"=>"nei",
      	"UID"=>"sa",
      	"PWD"=>"SQLABC!23456"
      );
      $conn = sqlsrv_connect( $serverName, $connectionInfo);

      $sLimit = "";
    	$_GET['iDisplayStart'] = isset($_GET['start']) ? $_GET['start'] : 0;
    	$_GET['iDisplayLength'] = isset($_GET['length']) ? $_GET['length'] : '-1';

      $Start = $_GET['iDisplayStart'];
      $Length = $_GET['iDisplayLength'];
      $End = $Length == '-1' ? 9999999999 : intval($Start) + intval($Length);

      $pWhere = $sWhere;
      $sWhere = !isset($sWhere) || $sWhere == '' ? "WHERE '1'='1'" : $sWhere;
      $sql_leftTables = implode(" ",$leftTables);
      $params = array();


      $Estimate_ID =          isset($_GET['columns'][0]['search']['value'])  ? $_GET['columns'][0]['search']['value']  : '';
      $Estimate_Name =        isset($_GET['columns'][1]['search']['value'])  ? $_GET['columns'][1]['search']['value']  : '';
      $Estimate_fDesc =       isset($_GET['columns'][2]['search']['value'])  ? $_GET['columns'][2]['search']['value']  : '';
      $Loc_Tag =              isset($_GET['columns'][3]['search']['value'])  ? $_GET['columns'][3]['search']['value']  : '';
      $OwnerWithRol_Name =    isset($_GET['columns'][4]['search']['min'])    ? $_GET['columns'][4]['search']['min']    : '';
      $Estimate_Quoted_Min =  isset($_GET['columns'][5]['search']['max'])    ? $_GET['columns'][5]['search']['max']    : '';
      $Estimate_Quoted_Max =  isset($_GET['columns'][5]['search']['min'])    ? $_GET['columns'][5]['search']['min']    : '';
      $Estimate_fDate_Min =   isset($_GET['columns'][6]['search']['max'])    ? $_GET['columns'][6]['search']['max']    : '';
      $Estimate_fDate_Max =   isset($_GET['columns'][6]['search']['min'])    ? $_GET['columns'][6]['search']['min']    : '';
      $Estimate_BDate_Min =   isset($_GET['columns'][7]['search']['max'])    ? $_GET['columns'][7]['search']['max']    : '';
      $Estimate_BDate_Max =   isset($_GET['columns'][7]['search']['value'])  ? $_GET['columns'][7]['search']['value']  : '';
      $Estimate_Type =        isset($_GET['columns'][8]['search']['value'])  ? $_GET['columns'][8]['search']['value']  : '';
      $Estimate_Status =      isset($_GET['columns'][9]['search']['value'])  ? $_GET['columns'][9]['search']['value']  : '';
      $Estimate_Remarks =     isset($_GET['columns'][10]['search']['value']) ? $_GET['columns'][10]['search']['value'] : '';
      $Search =            isset($_GET['search']['value']) ? $_GET['search']['value'] : '';
      $Sort =  isset($_GET['order'][0]['column']) && is_numeric($_GET['order'][0]['column']) && $_GET['order'][0]['column'] >= 0 && $_GET['order'][0]['column'] <= 5
                && isset($_GET['columns'][$_GET['order'][0]['column']]['data'])
                && in_array($_GET['columns'][$_GET['order'][0]['column']]['data'], array('Emp_ID', 'Emp_fFirst', 'Emp_Last', 'Emp_Email', 'Emp_Title')) ?
                  $_GET['columns'][$_GET['order'][0]['column']]['data'] : 'Emp_ID';
      $Sort = str_replace('_', '.', $Sort);
      $Direction = isset($_GET['order'][0]['dir']) && in_array($_GET['order'][0]['dir'], array('asc', 'desc', 'ASC', 'DESC')) ? $_GET['order'][0]['dir'] : 'ASC';
      $Order = 'ORDER BY ' . $Sort . ' ' .  $Direction;
      $params = array_fill(0, 5, $Search);
      array_push($params, $Emp_ID, $Emp_fFirst, $Emp_Last, $Emp_Email, $Emp_Title, $Start, $End);
      //var_dump($params);
  		$sQuery = "" .
          " SELECT  *
  			  FROM    (
                    SELECT  ROW_NUMBER() OVER ({$Order}) AS ROW_COUNT,
                            Estimate.ID AS Emp_ID,
                            Estimate.Name AS Emp_fFirst,
                            Estimate.fDesc AS Emp_Last,
                            Loc.Tag AS Loc_Tag,
                            OwnerWithRol.Name AS OwnerWithRol_Name,
                            Estimate.Quoted AS Estimate_Quoted,
                            Estimate.fDate Estimate_fDate,
                            Estimate.BDate Estimate_BDate,
                            Estimate.Type Estimate_Type,
                            Estimate.Status Estiamte_Status,
                            Estimate.Remarks Estimate_Remarks
                    FROM    nei.dbo.Estimate
                            LEFT JOIN nei.dbo.Loc ON Estimate.LocID = Loc.Loc
                            LEFT JOIN nei.dbo.OwnerWithRol ON Loc.Owner = OwnerWithRol.Owner
                    WHERE   (
                                Estimate.ID LIKE '%' + ? + '%'
                                OR Estimate.Name LIKE '%' + ? + '%'
                                OR Estimate.fDesc LIKE '%' + ? + '%'
                                OR Loc.Tag LIKE '%' + ? + '%'
                                OR OwnerWithRol.Name LIKE '%' + ? + '%'
                                OR Estimate.Remarks LIKE '%' + ? + '%'
                            )
                            AND Estimate.ID LIKE '%' + ? + '%'
                            AND Estimate.Name LIKE '%' + ? + '%'
                            AND Estimate.fDesc LIKE '%' + ? + '%'
                            AND Loc.Tag LIKE '%' + ? + '%'
                            AND OwnerWithRol.Name LIKE '%' + ? + '%'
                            AND Estimate.Quoted >= ?
                            AND Estimate.Quoted <= ?
                            AND Estimate.fDate >= ?
                            AND Estimate.fDate <= ?
                            AND Estimate.BDate >= ?
                            AND Estimate.BDate <= ?
                            AND Estimate.Type LIKE '%' + ? + '%'
                            AND Estimate.Status LIKE '%' + ? + '%'
                            AND Estimate.Remarks LIKE '%' + ? + '%'
                  ) as A
  			  WHERE A.ROW_COUNT BETWEEN ? AND ?
  		";
      //echo $sQuery;
      $rResult = sqlsrv_query($conn,  $sQuery, $params ) or die(print_r(sqlsrv_errors()));
  		$sQueryRow = "" .
  			"  SELECT Count(Emp.ID) AS Rows
  			   FROM   nei.dbo.Emp
                   LEFT JOIN Portal.dbo.Portal ON Portal.Branch_ID = Emp.ID AND Portal.Branch = 'Nouveau Elevator'
  			   WHERE  (
                     Emp.ID LIKE '%' + ? + '%'
                     OR Emp.fFirst LIKE '%' + ? + '%'
                     OR Emp.Last LIKE '%' + ? + '%'
                     OR Emp.Title LIKE '%' + ? + '%'
                     OR Portal.Email LIKE '%' + ? + '%'
                   )
                   AND Emp.ID LIKE '%' + ? + '%'
                   AND Emp.fFirst LIKE '%' + ? + '%'
                   AND Emp.Last LIKE '%' + ? + '%'
                   AND Emp.Title LIKE '%' + ? + '%'
                   AND Portal.Email LIKE '%' + ? + '%'
  	;";
    array_pop($params);//unset Start
    array_pop($params);//unset End
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $stmt = sqlsrv_query( $conn, $sQueryRow , $params ) or die(print_r(sqlsrv_errors()));
    $iFilteredTotal = sqlsrv_fetch_array($stmt)['Rows'];
    $sQuery = "" .
      " SELECT COUNT(Emp.ID)
        FROM   nei.dbo.Emp
    ;";
    $rResultTotal = sqlsrv_query($conn,  $sQuery ) or die(print_r(sqlsrv_errors()));
    $aResultTotal = sqlsrv_fetch_array($rResultTotal);
    $iTotal = $aResultTotal[0];

    $output = array(
        "sEcho" => intval($_GET['sEcho']),
        "iTotalRecords" => $iTotal,
        "iTotalDisplayRecords" => $iFilteredTotal,
        "aaData" => array()
    );

    while ( $aRow = sqlsrv_fetch_array( $rResult ) )
    {
    	unset($aRow['ROW_COUNT']);
		  $output['aaData'][] = $aRow;
    }

    echo json_encode( $output );
}}?>
