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
    if( isset($My_Privileges['Violation'])
        && (
				$My_Privileges['Violation']['Other_Privilege'] >= 4
			||	$My_Privileges['Violation']['Group_Privilege'] >= 4
			||	$My_Privileges['Violation']['User_Privilege'] >= 4
		)
	){
            $Privileged = True;}
	if(!isset($Connection['ID'])  || !$Privileged){print json_encode(array('data'=>array()));}
    else {
    $serverName = "172.16.12.45"; //serverName\instanceName
    $connectionInfo = array(
    	"Database"=>"nei",
    	"UID"=>"sa",
    	"PWD"=>"SQLABC!23456",
    	'ReturnDatesAsStrings'=>true
    );
    $conn = sqlsrv_connect( $serverName, $connectionInfo);
    $aColumns = array( 'Violation.ID', 'Violation.Name', 'Violation.fDate', 'Violation.Status', 'Violation.Loc' );
    $aIndexColumn = "ID";
    $aTable = "Violation";

    $bColumns = array('Loc.Tag', 'Loc.City', 'Loc.State', 'Loc.Address', 'Loc.Maint');
    $bTable = "Loc";

    $xColumns = $aColumns + $bColumns;

    $sLimit = "";

  	$_GET['iDisplayStart'] = isset($_GET['start']) ? $_GET['start'] : 0;
  	$_GET['iDisplayLength'] = isset($_GET['length']) ? $_GET['length'] : '-1';

    $Start = $_GET['iDisplayStart'];
    $Length = $_GET['iDisplayLength'];

    $End = $Length == '-1' ? 9999999999 : intval($Start) + intval($Length);

    if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
    {
      $sLimit = "OFFSET  ".$_GET['iDisplayStart']." ROWS FETCH NEXT ".$_GET['iDisplayLength']." ROWS ONLY ";
    }
    $sOrder = "";
    if ( isset( $_GET['order'][0]['column'] ) )
    {
        $sOrder = "ORDER BY  ";
        $sOrder .= $aColumns[$_GET['order'][0]['column']] . " " . $_GET['order'][0]['dir'];
    }
    $sWhere = "";
	  $_GET['sSearch'] = isset($_GET['search']['value']) ? $_GET['search']['value'] : "";
    if ( $_GET['sSearch'] != "" )
    {
        $sWhere = "WHERE (";
        for ( $i=0 ; $i<count($xColumns) ; $i++ )
        {
            $sWhere .= $xColumns[$i]." LIKE '%".addslashes( $_GET['sSearch'] )."%' OR ";
        }
        $sWhere = substr_replace( $sWhere, "", -3 );
        $sWhere .= ')';
    }
    for ( $i=0 ; $i<count($xColumns) ; $i++ )
    {
        if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
        {
            if ( $sWhere == "" )
            {
                $sWhere = "WHERE ";
            }
            else
            {
                $sWhere .= " AND ";
            }
            $sWhere .= $xColumns[$i]." LIKE '%".addslashes($_GET['sSearch_'.$i])."%' ";
        }
    }
    $sWhere = !isset($sWhere) || $sWhere == '' ? "WHERE '1'='1'" : $sWhere;
		$sQuery = "
			SELECT *
			FROM
			 (
				SELECT ROW_NUMBER() OVER ($sOrder) AS ROW_COUNT," . str_replace(" , ", " ", implode(", ", $xColumns)) . "
				FROM $aTable LEFT JOIN nei.dbo.Loc ON Loc.Loc = Violation.Loc
				$sWhere
			) A
			WHERE A.ROW_COUNT BETWEEN $Start AND $End
		";

    $rResult = sqlsrv_query($conn,  $sQuery ) or die(print_r(sqlsrv_errors()));
    /* Data set length after filtering */
		$sQueryRow = "
			SELECT ".str_replace(" , ", " ", implode(", ", $xColumns))."
			FROM   $aTable LEFT JOIN nei.dbo.Loc ON Violation.Loc = Loc.Loc
			$sWhere
		;";

    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $stmt = sqlsrv_query( $conn, $sQueryRow , $params, $options );

    $iFilteredTotal = sqlsrv_num_rows( $stmt );


    //echo "TOTAL " . $iFilteredTotal;
    /* Total data set length */
    $sQuery = "
        SELECT COUNT(".$aIndexColumn.")
        FROM   $aTable
    ";
    $rResultTotal = sqlsrv_query($conn,  $sQuery ) or die(print_r(sqlsrv_errors()));
    $aResultTotal = sqlsrv_fetch_array($rResultTotal);
    $iTotal = $aResultTotal[0];




    /*
     * Output
     */
    $output = array(
        "sEcho" => intval($_GET['sEcho']),
        "iTotalRecords" => $iTotal,
        "iTotalDisplayRecords" => $iFilteredTotal,
        "aaData" => array()
    );

    while ( $aRow = sqlsrv_fetch_array( $rResult ) )
    {
        $row = array();
        for ( $i=0 ; $i<count($xColumns) ; $i++ )
        {
            if ( $xColumns[$i] == "version" )
            {
                /* Special output formatting for 'version' column */
                $row[] = ($aRow[ $xColumns[$i] ]=="0") ? '-' : $aRow[ $xColumns[$i] ];
            }
            else if ( $xColumns[$i] != ' ' )
            {
                /* General output */
                $row[] = $aRow[ $xColumns[$i] ];
            }
        }
        $output['aaData'][] = $row;
    }

    echo json_encode( $output );
	}
}
?>
