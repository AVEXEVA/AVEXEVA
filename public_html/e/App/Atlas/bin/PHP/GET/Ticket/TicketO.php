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
	 if( isset($My_Privileges['Ticket'],$My_Privileges['User'])
        && (
				$My_Privileges['Ticket']['User_Privilege'] >= 4
			||	$My_Privileges['Ticket']['Group_Privilege'] >= 4
			||	$My_Privileges['Ticket']['Other_Privilege'] >= 4)){
            $Privileged = True;}
    if(!isset($Connection['ID']) || !$Privileged){print json_encode(array('data'=>array()));}
	else {
    $serverName = "172.16.12.45"; //serverName\instanceName
		$connectionInfo = array(
			"Database"=>"nei",
			"UID"=>"sa",
			"PWD"=>"SQLABC!23456",
			'ReturnDatesAsStrings'=>true
		);
		$conn = sqlsrv_connect( $serverName, $connectionInfo);

		/*
		 * Script:    DataTables server-side script for PHP and MySQL
		 * Copyright: 2010 - Allan Jardine
		 * License:   GPL v2 or BSD (3-point)
		 */

		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * Easy set variables
		 */

		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		 * you want to insert a non-database field (for example a counter or static image)
		 */
		$aColumns = array( 'ID','fDesc','CDate','DDate','EDate','TimeRoute','TimeSite','TimeComp','Who','fBy','Level','Job','fWork', 'LID', 'LElev', 'Owner', 'Assigned');
		/* Indexed column (used for fast and accurate table cardinality) */
		$sIndexColumn = "Tickets.ID";

		/* DB table to use */
		$sTable = array("TicketO");


		/*
		 * Paging
		 */
		$sLimit = "";
		$_GET['iDisplayStart'] = isset($_GET['start']) ? $_GET['start'] : 0;
		$_GET['iDisplayLength'] = isset($_GET['length']) ? $_GET['length'] : '-1';

		$Start = $_GET['iDisplayStart'];
		$Length = $_GET['iDisplayLength'];

		$End = $Length == '-1' ? 9999999999 : intval($Start) + intval($Length);

		/*
		 * Ordering
		 */

		$sOrder = "";
		if ( isset( $_GET['order'][0]['column'] ) )
		{
			$sOrder = "ORDER BY  CAST(Tickets.";
			$sOrder .= $aColumns[$_GET['order'][0]['column']] . " AS NVARCHAR(100))" . $_GET['order'][0]['dir'];
			/*for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
						".addslashes( $_GET['sSortDir_'.$i] ) .", ";
				}
			}

			$sOrder = substr_replace( $sOrder, "", -2 );
			if ( $sOrder == "ORDER BY" )
			{
				$sOrder = "";
			}*/
		}


		/*
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables, and MySQL's regex functionality is very limited
		 */
		$sWhere = "";
		$_GET['sSearch'] = isset($_GET['search']['value']) ? $_GET['search']['value'] : "";
		if ( $_GET['sSearch'] != "" )
		{
			$sWhere = "WHERE (";
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				$sWhere .= $aColumns[$i]." LIKE '%".addslashes( $_GET['sSearch'] )."%' OR ";
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}

		/* Individual column filtering */
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
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
				$sWhere .= $aColumns[$i]." LIKE '%".addslashes($_GET['sSearch_'.$i])."%' ";
			}
		}


		/*
		 * SQL queries
		 * Get data to display
		 */

		$pWhere = $sWhere;
		$sWhere = !isset($sWhere) || $sWhere == '' ? "WHERE '1'='1'" : $sWhere;
		$sQuery = "SELECT *
		           FROM
                  		 (
                  			SELECT Tickets.*,
                                [Loc].Tag AS Location,
                                [OwnerWithRol].[Name] AS Customer,
                                [Elev].[State] AS Unit_State,
                                [Elev].[Unit] AS Unit_Label,
                                Emp.fFirst + ' ' + Emp.Last AS Mechanic,
                                TickOStatus.Type AS Ticket_Status,
                                ROW_NUMBER() OVER ($sOrder) AS ROW_COUNT
                  			FROM (
                  				(SELECT TicketO." . str_replace(" ,", " ", implode(", TicketO.",$aColumns)) . "
                  				FROM TicketO)
                  			) AS Tickets
                        LEFT JOIN nei.dbo.Loc          ON Tickets.LID       = Loc.Loc
                        LEFT JOIN nei.dbo.Job          ON Tickets.Job       = Job.ID
                        LEFT JOIN nei.dbo.Elev         ON Tickets.LELev     = Elev.ID
                        LEFT JOIN nei.dbo.OwnerWithRol ON Tickets.Owner     = OwnerWithRol.ID
                        LEFT JOIN nei.dbo.Emp          ON Tickets.fWork     = Emp.fWork
                        LEFT JOIN nei.dbo.JobType      ON Job.Type          = JobType.ID
                        LEFT JOIN nei.dbo.Route        ON Loc.Route         = Route.ID
                        LEFT JOIN nei.dbo.Zone         ON Loc.Zone          = Zone.ID
                        LEFT JOIN nei.dbo.TickOStatus  ON Tickets.Assigned = TickOStatus.Ref
			                  $sWhere
                      ) A

		          WHERE A.ROW_COUNT BETWEEN $Start AND $End
		";
    //echo $sQuery;
		//echo $sQuery;

		$rResult = sqlsrv_query($conn,  $sQuery ) or die(print_r(sqlsrv_errors()));

		$sWhere =$pWhere;
		/* Data set length after filtering */
		$sQueryRow = "
			SELECT ".str_replace(" , ", " ", implode(", ", $aColumns))."
			FROM   (
				(SELECT TicketO." . str_replace(", ", " ", implode(", TicketO.",$aColumns)) . "
				FROM TicketO)
			) AS Tickets
			$sWhere
		";
		$params = array();
		$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
		$stmt = sqlsrv_query( $conn, $sQueryRow , $params, $options );

		$iFilteredTotal = sqlsrv_num_rows( $stmt );


		//echo "TOTAL " . $iFilteredTotal;
		/* Total data set length */
		$sQuery = "
			SELECT COUNT(".$sIndexColumn.")
			FROM   (
				(SELECT TicketO.ID
				FROM TicketO)
			) AS Tickets
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
			/*for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				if ( $aColumns[$i] == "version" )
				{
					$row[] = ($aRow[ $aColumns[$i] ]=="0") ? '-' : $aRow[ $aColumns[$i] ];
				}
				else if ( $aColumns[$i] != ' ' )
				{
					$row[] = $aRow[ $aColumns[$i] ];
				}
			}*/
      //$output['aaData'][] = $row;
      $aRow['TimeRoute']  =   $aRow['TimeRoute']  == '1899-12-30 00:00:00.000' ? 'N/A' : date('h:i a', strtotime($aRow['TimeRoute']));
      $aRow['TimeSite']   =   $aRow['TimeSite']   == '1899-12-30 00:00:00.000' ? 'N/A' : date('h:i a', strtotime($aRow['TimeSite']));
      $aRow['TimeComp']   =   $aRow['TimeComp']   == '1899-12-30 00:00:00.000' ? 'N/A' : date('h:i a', strtotime($aRow['TimeComp']));
      $aRow['EDate']      =   $aRow['EDate']      == NULL                      ? 'N/A' : date('m/d/Y', strtotime($aRow['EDate']));
			$output['aaData'][] = $aRow;
		}

		echo json_encode( $output );
  }
}
?>
