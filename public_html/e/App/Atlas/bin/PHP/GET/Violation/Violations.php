<?php
require('../../index.php');
$Session = new Session( array( 'Reference' => 'PHP/GET/Location/Locations' ) );
if( $Session->__validate() ){
  new Log( array( 'Session' => $Session ) );
  $conn = $Session->__get('Database');
  $aColumns = array( 
    'ID',
    'Loc',
    'Elev',
    'fdate',
    'Status',
    'Quote',
    'Job',
    'Ticket',
    'Remarks',
    'Custom1',
    'Custom2',
    'Custom3',
    'Custom4',
    'Custom5',
    'Custom6',
    'Custom7',
    'Custom8',
    'Custom9',
    'Custom10',
    'Custom11',
    'Custom12',
    'Custom13',
    'Custom14',
    'Custom15',
    'Custom16',
    'Custom17',
    'Custom18',
    'Custom19',
    'Custom20',
    'Custom21',
    'Custom22',
    'Custom23',
    'Custom24',
    'Custom25',
    'Custom26',
    'Custom27',
    'Custom28',
    'Custom29',
    'Custom30',
    'Name',
    'Estimate',
    'Remarks2',
    'idTestItem',
    'Price'
  );
  $sColumns = str_replace(' , ', ' ', implode(', ', $aColumns));
  $sIndexColumn = 'ID';
  $sTable = 'Violation';
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

  $sQuery = " 
    SELECT  Violations.*
    FROM (
      SELECT  ROW_NUMBER() OVER ({$sOrder}) AS ROW_COUNT, {$sColumns}
      FROM    {$sTable}
      {$sWhere}
    ) AS Violations
    WHERE Violations.ROW_COUNT BETWEEN ? AND ?";
  //echo $sQuery;
  //var_dump($parameters);
  $fParameters = $parameters;
  $fParameters[] = $Start;
  $fParameters[] = $End;

  $rResult = sqlsrv_query($conn,  $sQuery, $fParameters ) or die(print_r(sqlsrv_errors()));

  $sQueryRow = "  
    SELECT {$sColumns}
    FROM   {$sTable}
    {$sWhere}
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
