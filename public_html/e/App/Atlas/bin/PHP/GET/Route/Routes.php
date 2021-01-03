<?php
require('../../index.php');
$Session = new Session();
if($Session->__validate() && $Session->access( 'Route' )){
  $r = sqlsrv_query( $Session->__get( 'Database' ),
    " SELECT  Route.ID   AS ID,
              Route.Name AS Route,
              Emp.fFirst AS First_Name,
              Emp.Last	  AS Last_Name,
              CASE WHEN Locations.Count       IS NULL THEN 0 ELSE Locations.Count END AS Locations,
              CASE WHEN Locations.Elevators   IS NULL THEN 0 ELSE Locations.Elevators END AS Elevators,
              CASE WHEN Locations.Escalators  IS NULL THEN 0 ELSE Locations.Escalators END AS Escalators
      FROM    Route
              LEFT JOIN Emp ON Route.Mech = Emp.fWork
              LEFT JOIN (
                SELECT    Loc.Route,
                          Count(Loc.Loc) AS Count,
                          Sum(Elevators.Count) AS Elevators,
                          Sum(Escalators.Count) AS Escalators
                FROM      Loc
                          LEFT JOIN (
                            SELECT    Elev.Loc,
                                      Count(Elev.ID) AS Count
                            FROM      Elev
                            WHERE     Elev.Type <> 'Escalator'
                            GROUP BY  Elev.Loc
                          ) AS Elevators ON Elevators.Loc = Loc.Loc
                          LEFT JOIN (
                            SELECT    Elev.Loc,
                                      Count(Elev.ID) AS Count
                            FROM      Elev
                            WHERE     Elev.Type = 'Escalator'
                            GROUP BY  Elev.Loc
                          ) AS Escalators ON Escalators.Loc = Loc.Loc
                GROUP BY  Loc.Route
              ) AS Locations ON Locations.Route = Route.ID
      WHERE   Route.ID <> 76;");
  $data = array();
  if($r){while($array = sqlsrv_fetch_array($r)){
    $array[ 'First_Name' ] = proper( $array[ 'First_Name' ] );
    $array[ 'Last_Name' ]  = proper( $array[ 'Last_Name' ] );
    $data[] = $array;
  }}
  print json_encode( array( 'data' => $data ) );
}?>
