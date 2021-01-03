<?php
require('../../index.php');
$Session = new Session( array( 'Reference' => 'PHP/GET/Customer/Customers' ) );
if( $Session->__validate() ){
  new Log( array( 'Session' => $Session ) );
  $conn = $Session->__get('Database');
  $aColumns = array( 'ID', 'Name', 'Status' );
  $sColumns = str_replace(' , ', ' ', implode(', ', $aColumns));
  $sIndexColumn = 'ID';
  $sTable = 'OwnerWithRol';
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

  $sQuery = " SELECT  Customers.*
              FROM (
                SELECT  ROW_NUMBER() OVER ({$sOrder}) AS ROW_COUNT,
                        {$sColumns},
                        Locations.Count   AS Locations,
                        Elevators.Count   AS Elevators,
                        Escalators.Count  AS Escalators
                FROM    {$sTable}
                        LEFT JOIN (
                          SELECT    Loc.Owner AS Customer_ID,
                                    Count(Loc.Loc) AS Count
                          FROM      Loc
                          GROUP BY  Loc.Owner
                        ) AS Locations ON Locations.Customer_ID = OwnerWithRol.ID
                        LEFT JOIN (
                          SELECT    Elev.Owner AS Customer_ID,
                                    Count(Elev.ID) AS Count
                          FROM      Elev
                          WHERE     Elev.Type <> 'Escalator'
                          GROUP BY  Elev.Owner
                        ) AS Elevators ON Elevators.Customer_ID = OwnerWithRol.ID
                        LEFT JOIN (
                          SELECT    Elev.Owner AS Customer_ID,
                                    Count(Elev.ID) AS Count
                          FROM      Elev
                          WHERE     Elev.Type = 'Escalator'
                          GROUP BY  Elev.Owner
                        ) AS Escalators ON Escalators.Customer_ID = OwnerWithRol.ID
                {$sWhere}
                        AND OwnerWithRol.ID IN (
                          SELECT Tickets.Owner_ID
                          FROM (
                            (
                              SELECT    Emp.ID AS Employee_ID,
                                        Job.Owner AS Owner_ID
                              FROM      TicketO
                                        LEFT JOIN Emp ON TicketO.fWork = Emp.fWork
                                        LEFT JOIN Job ON Job.ID = TicketO.Job
                              GROUP BY  Emp.ID,
                                        Job.Owner
                            ) UNION ALL (
                              SELECT    Emp.ID AS Employee_ID,
                                        Job.Owner AS Owner_ID
                              FROM      TicketD
                                        LEFT JOIN Emp ON TicketD.fWork = Emp.fWork
                                        LEFT JOIN Job ON Job.ID = TicketD.Job
                              GROUP BY  Emp.ID,
                                        Job.Owner
                            )
                          ) AS Tickets
                          WHERE    Tickets.Employee_ID = ?
                          GROUP BY Tickets.Owner_ID
                      )
              ) AS Customers
    WHERE Customers.ROW_COUNT BETWEEN ? AND ?";
  //echo $sQuery;
  //var_dump($parameters);

  $fParameters = $parameters;
  $fParameters[] = $Start;
  $fParameters[] = $End;

  $rResult = sqlsrv_query($conn,  $sQuery, $fParameters ) or die(print_r(sqlsrv_errors()));

  $sQueryRow = "  SELECT {$sColumns}
                  FROM   {$sTable}
                  {$sWhere}
                          AND OwnerWithRol.ID IN (
                            SELECT Tickets.Owner_ID
                            FROM (
                              (
                                SELECT    Emp.ID AS Employee_ID,
                                          Job.Owner AS Owner_ID
                                FROM      TicketO
                                          LEFT JOIN Emp ON TicketO.fWork = Emp.fWork
                                          LEFT JOIN Job ON Job.ID = TicketO.Job
                                GROUP BY  Emp.ID,
                                          Job.Owner
                              ) UNION ALL (
                                SELECT    Emp.ID AS Employee_ID,
                                          Job.Owner AS Owner_ID
                                FROM      TicketD
                                          LEFT JOIN Emp ON TicketD.fWork = Emp.fWork
                                          LEFT JOIN Job ON Job.ID = TicketD.Job
                                GROUP BY  Emp.ID,
                                          Job.Owner
                              )
                            ) AS Tickets
                            WHERE    Tickets.Employee_ID = ?
                            GROUP BY Tickets.Owner_ID
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
      $row = $aRow;
      $row['version'] = $aRow['version'] == '0' ? '-' : $aRow['version'];
      $output[ 'aaData' ][] = $row;
  }
  echo json_encode( $output );
}
?>
