<?php
require('../../index.php');
$Session = new Session( array( 'Reference' => 'PHP/GET/Unit/Units' ) );
if( $Session->__validate() ){
  $Tables = array(
      "Elev"=>array(
          'ID',
          'State',
          'Unit',
          'Type',
          'Status',
          'Loc'
      ),
      "Loc"=>array(
          'Loc',
          'Tag'
      )
  );
  $sIndexColumn = 'Elev.Loc';
  $aColumns   = array();
  $aTables    = array();
  $leftTables = array();
  $i = 0;
  foreach($Tables as $Table=>$Columns){
    $aTables[] = $Table;
    if($i == 0){$sTable = $Table;}
    elseif($i != 0){$leftTables[] = "LEFT JOIN {$Table} ON Elev.Loc = Loc.Loc";}
    foreach($Columns as $index=>$Column){
        if(in_array("Elev." . $Column,$aColumns) || in_array("Loc." . $Column,$aColumns)){continue;}
        $aColumns[] = $Table . "." . $Column;
    }
    $i++;
  }

  $sLimit = "";
  $Start = isset($_GET['start']) ? $_GET['start'] : 0;
  $Length = isset($_GET['length']) ? $_GET['length'] : '-1';
  $End = $Length == '-1' ? 9999999999 : intval($Start) + intval($Length);

  if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' ){ $sLimit = "OFFSET {$_GET['iDisplayStart']} ROWS FETCH NEXT {$_GET['iDisplayLength']} ROWS ONLY "; }

  $sOrder = "";
  if ( isset( $_GET['order'][0]['column'] ) ){
    $sOrder = "ORDER BY  ";
    $sOrder .= $aColumns[$_GET['order'][0]['column']] . " " . $_GET['order'][0]['dir'];
  }

  $sWhere = "";
  $_GET['sSearch'] = isset($_GET['search']['value']) ? $_GET['search']['value'] : "";
  if ( $_GET['sSearch'] != "" ){
    $sWhere = "WHERE (";
    for ( $i=0 ; $i<count($aColumns) ; $i++ ){ $sWhere .= $aColumns[$i]." LIKE '%".addslashes( $_GET['sSearch'] )."%' OR "; }
    $sWhere = substr_replace( $sWhere, "", -3 );
    $sWhere .= ')';
  }

  for ( $i=0 ; $i<count($aColumns) ; $i++ ){
    if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' ){
      if ( $sWhere == "" ){ $sWhere = "WHERE "; }
      else { $sWhere .= " AND "; }
      $sWhere .= $aColumns[$i]." LIKE '%".addslashes($_GET['sSearch_'.$i])."%' ";
    }
  }

  $pWhere = $sWhere;
  $sWhere = !isset($sWhere) || $sWhere == '' ? "WHERE '1'='1'" : $sWhere;
  $sql_leftTables = implode(" ",$leftTables);
  $params = array();
  if(isset($_GET['Loc'])){
    $sWhere .= " AND Elev.Loc = ?";
    array_push($params, $_GET['Loc']);
  }
  $sColumns = str_replace(" , ", " ", implode(", ", $aColumns));
  $sQuery = " SELECT Tbl.*
              FROM  (
                SELECT  ROW_NUMBER() OVER ($sOrder) AS ROW_COUNT, {$sColumns}
                FROM    {$aTables[0]}
                        {$sql_leftTables}
                $sWhere
                        AND Elev.Loc IN
                            (
                                SELECT Tickets.Location_ID
                                FROM
                                (
                                    (
                                        SELECT    TicketO.LID AS Location_ID
                                        FROM      TicketO
                                                  LEFT JOIN Emp ON TicketO.fWork = Emp.fWork
                                        WHERE     Emp.ID = {$Session->__get('User')->__get('Employee_ID')}
                                        GROUP BY  TicketO.LID
                                    ) UNION ALL (
                                        SELECT    TicketD.Loc AS Location_ID
                                        FROM      TicketD
                                                  LEFT JOIN Emp ON TicketD.fWork = Emp.fWork
                                        WHERE     Emp.ID = {$Session->__get('User')->__get('Employee_ID')}
                                        GROUP BY  TicketD.Loc
                                    )
                              ) AS Tickets
                              GROUP BY Tickets.Location_ID
                          )
             ) AS Tbl
            WHERE Tbl.ROW_COUNT BETWEEN $Start AND $End;";

  $rResult = sqlsrv_query($Session->__get('Database'),  $sQuery, $params ) or die(print_r(sqlsrv_errors()));


  $sQueryRow = "  SELECT  {$sColumns}
                  FROM    {$aTables[0]}
                          {$sql_leftTables}
                  $sWhere
                          AND  Elev.Loc IN
                          (
                            SELECT Tickets.Location_ID
                            FROM
                            (
                                (
                                    SELECT   TicketO.LID AS Location_ID
                                    FROM     TicketO
                                             LEFT JOIN Emp ON TicketO.fWork = Emp.fWork
                                    WHERE    Emp.ID = {$Session->__get('User')->__get('Employee_ID')}
                                    GROUP BY TicketO.LID
                                )
                                UNION ALL
                                (
                                    SELECT   TicketD.Loc AS Location_ID
                                    FROM     TicketD
                                             LEFT JOIN Emp ON TicketD.fWork = Emp.fWork
                                    WHERE    Emp.ID = {$Session->__get('User')->__get('Employee_ID')}
                                    GROUP BY TicketD.Loc
                                )
                            ) AS Tickets
                            GROUP BY Tickets.Location_ID
                        )";

  $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
  $stmt = sqlsrv_query( $Session->__get('Database'), $sQueryRow , $params, $options );

  $iFilteredTotal = sqlsrv_num_rows( $stmt );

  $sQuery = "SELECT COUNT({$sIndexColumn}) FROM  $sTable;";
  $rResultTotal = sqlsrv_query($Session->__get('Database'),  $sQuery ) or die(print_r(sqlsrv_errors()));
  $aResultTotal = sqlsrv_fetch_array($rResultTotal);
  $iTotal = $aResultTotal[0];

  $output = array(
      "sEcho" => intval($_GET['sEcho']),
      "iTotalRecords" => $iTotal,
      "iTotalDisplayRecords" => $iFilteredTotal,
      "aaData" => array()
  );
  while ( $aRow = sqlsrv_fetch_array( $rResult ) ){ $output['aaData'][] = $aRow; }
  echo json_encode( $output );
}
?>
