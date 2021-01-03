<?php
require('../../index.php');
$Session = new Session( array( 'Reference' => 'PHP/GET/Location/Locations' ) );
if( $Session->__validate() ){
  new Log( array( 'Session' => $Session ) );
  $conn = $Session->__get('Database');
  $aColumns = array( 'Loc', 'Tag', 'Address', 'City', 'State', 'Zip', 'Maint' );
  $sColumns = str_replace(' , ', ' ', implode(', ', $aColumns));
  $sIndexColumn = 'Loc';
  $sTable = 'Loc';
  $parameters = array();

  $Start  = isset( $_GET[ 'start' ])  && is_integer( intval( $_GET[ 'start' ] ) ) ? $_GET['start'] : 0;
  $Length = isset( $_GET[ 'length' ]) && is_integer( intval( $_GET[ 'length' ] ) ) ? $_GET['length'] : 10;
  $End = $Length == '-1' ? 9999999999 : intval($Start) + intval($Length);
  $sLimit = 'OFFSET  ? ROWS FETCH NEXT ? ROWS ONLY ';

  $sOrder = 'ORDER BY ';
  $sOrder .= isset($_GET[ 'order' ][ 0 ][ 'column' ]) ? $aColumns[$_GET[ 'order' ][ 0 ][ 'column' ]] : $aColumns[1];
  $sOrder .= ' ';
  $sOrder .= isset($_GET[ 'order' ][ 0 ][ 'dir' ])  ? $_GET[ 'order' ][ 0 ][ 'dir' ] : 'ASC';

  $Search = isset($_GET['search']['value']) ? $_GET['search']['value'] : '';
  $aWhere = array();
  if ( $Search != '' ){
      for ( $i=0 ; $i<count($aColumns) ; $i++ ){
        $aWhere[] = $aColumns[$i]." LIKE '%' + ? + '%'";
        $parameters[] = $Search;
      }
  } else {
    $aWhere[] = "'1' = '1'";
  }
  $sWhere = 'WHERE  (' . implode(' OR ', $aWhere) . ')';

  $parameters[] = $Session->__get('User')->__get('Employee_ID');

	$sQuery = " SELECT  Locations.*
              FROM (
                SELECT  ROW_NUMBER() OVER ({$sOrder}) AS ROW_COUNT, {$sColumns}
                FROM    {$sTable}
                {$sWhere}
                        AND Loc.Loc IN (
                          SELECT Tickets.Location_ID
                          FROM (
                            (
                              SELECT    Emp.ID AS Employee_ID,
                                        TicketO.LID AS Location_ID
                              FROM      TicketO
                                        LEFT JOIN Emp ON TicketO.fWork = Emp.fWork
                              GROUP BY  Emp.ID,
                                        TicketO.LID
                            ) UNION ALL (
                              SELECT    Emp.ID AS Employee_ID,
                                        TicketD.Loc AS Location_ID
                              FROM      TicketD
                                        LEFT JOIN Emp ON TicketD.fWork = Emp.fWork
                              GROUP BY  Emp.ID,
                                        TicketD.Loc
                            )
                          ) AS Tickets
                          WHERE    Tickets.Employee_ID = ?
                          GROUP BY Tickets.Location_ID
      				        )
              ) AS Locations
		WHERE Locations.ROW_COUNT BETWEEN ? AND ?";
  //echo $sQuery;
  //var_dump($parameters);
  $fParameters = $parameters;
  $fParameters[] = $Start;
  $fParameters[] = $End;

  $rResult = sqlsrv_query($conn,  $sQuery, $fParameters ) or die(print_r(sqlsrv_errors()));

	$sQueryRow = "  SELECT {$sColumns}
              		FROM   {$sTable}
              		{$sWhere}
                          AND Loc.Loc IN (
                            SELECT Tickets.Location_ID
                            FROM (
                              (
                                SELECT    Emp.ID AS Employee_ID,
                                          TicketO.LID AS Location_ID
                                FROM      TicketO
                                          LEFT JOIN Emp ON TicketO.fWork = Emp.fWork
                                GROUP BY  Emp.ID,
                                          TicketO.LID
                              ) UNION ALL (
                                SELECT    Emp.ID AS Employee_ID,
                                          TicketD.Loc AS Location_ID
                                FROM      TicketD
                                          LEFT JOIN Emp ON TicketD.fWork = Emp.fWork
                                GROUP BY  Emp.ID,
                                          TicketD.Loc
                              )
                            ) AS Tickets
                            WHERE    Tickets.Employee_ID = ?
                            GROUP BY Tickets.Location_ID
                        )
	";
  $params = array();
  $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
  $stmt = sqlsrv_query( $conn, $sQueryRow , $parameters, $options );

  $iFilteredTotal = sqlsrv_num_rows( $stmt );

  $sQuery = "SELECT COUNT(".$sIndexColumn.") FROM   $sTable";
  $rResultTotal = sqlsrv_query($conn,  $sQuery ) or die(print_r(sqlsrv_errors()));
  $aResultTotal = sqlsrv_fetch_array($rResultTotal);
  $iTotal = $aResultTotal[0];

  $output = array(
      "sEcho" => intval($_GET['sEcho']),
      "iTotalRecords" => $iTotal,
      "iTotalDisplayRecords" => $iFilteredTotal,
      "aaData" => array()
  );

  while ( $aRow = sqlsrv_fetch_array( $rResult ) ){
      $row = array();
      for ( $i=0 ; $i<count($aColumns) ; $i++ ){
          if ( $aColumns[$i] == 'version' ){ $row[ $aColumns[ $i ] ] = $aRow[ $aColumns[$i] ] == '0' ? '-' : $aRow[ $aColumns[$i] ]; }
          else if ( $aColumns[$i] != ' ' ) { $row[ $aColumns[ $i ] ] = $aRow[ $aColumns[ $i ] ]; }
      }
      $output[ 'aaData' ][] = $row;
  }
  echo json_encode( $output );
}
?>
