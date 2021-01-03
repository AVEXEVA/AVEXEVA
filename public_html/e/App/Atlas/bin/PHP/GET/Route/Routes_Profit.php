<?php
session_start();
require('../index.php');
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
  $r = sqlsrv_query($Portal,
    " SELECT Privilege.Access_Table,
             Privilege.User_Privilege,
             Privilege.Group_Privilege,
             Privilege.Other_Privilege
      FROM   Portal.dbo.Privilege
      WHERE  Privilege.User_ID = ?
  ;",array($_SESSION['User']));
  $My_Privileges = array();
  while($array2 = sqlsrv_fetch_array($r)){$My_Privileges[$array2['Access_Table']] = $array2;}
  $Privileged = False;
  if( isset($My_Privileges['Route'])
      && $My_Privileges['Route']['Other_Privilege'] >= 4){$Privileged = True;}
  if(!isset($Connection['ID']) || !$Privileged){print json_encode(array('data'=>array()));}
  else {
      $r = sqlsrv_query($NEI,
        " SELECT /*Route_Information.ID,*/
                 Route.Name AS ID,
                 '---' + Route.Name + '---' AS Route,
                 Emp.fFirst AS First_Name,
                 Emp.Last AS Last_Name,
                 Route_Information.Revenue,
                 Route_Information.Labor,
                 Route_Information.Material,
                 Route_Information.Cost,
                 Route_Information.Profit,
                 Route_Information.Elevators AS Elevators,
                 Route_Information.Escalators AS Escalators
          FROM   nei.dbo.Route
                 LEFT JOIN nei.dbo.Emp ON Route.Mech = Emp.fWork
                 LEFT JOIN (
                   SELECT  Route.ID,
                           Route.ID AS Route,
                           Elevators.Units AS Elevators,
                           Escalators.Units AS Escalators,
                           CASE WHEN Route_Revenue.Revenue IS NULL THEN 0 ELSE Route_Revenue.Revenue END AS Revenue,
                           CASE WHEN Route_TS_Labor.Labor IS NULL THEN
                                CASE WHEN Route_Material.Material IS NULL THEN
                                     0
                                ELSE Route_TS_Labor.Labor
                                END
                           ELSE CASE WHEN Route_Material.Material IS NULL THEN
                                     Route_TS_Labor.Labor
                                ELSE Route_TS_Labor.Labor + Route_Material.Material
                                END
                           END AS Cost,
                           CASE WHEN Route_TS_Labor.Labor IS NULL THEN 0 ELSE Route_TS_Labor.Labor END AS Labor,
                           CASE WHEN Route_Material.Material IS NULL THEN 0 ELSE Route_Material.Material END AS Material,
                           CASE WHEN Route_Revenue.Revenue IS NULL THEN
                                CASE WHEN Route_TS_Labor.Labor IS NULL THEN
                                     CASE WHEN Route_Material.Material IS NULL THEN
                                          0
                                     ELSE Route_Material.Material
                                     END
                                ELSE CASE WHEN Route_Material.Material IS NULL THEN
                                          -1 * Route_TS_Labor.Labor
                                     ELSE -1 * (Route_TS_Labor.Labor + Route_Material.Material)
                                     END
                                END
                           ELSE CASE WHEN Route_TS_Labor.Labor IS NULL THEN
                                     CASE WHEN Route_Material.Material IS NULL THEN
                                          Route_Revenue.Revenue
                                     ELSE Route_Revenue.Revenue - Route_Material.Material
                                     END
                                ELSE CASE WHEN Route_Material.Material IS NULL THEN
                                          Route_Revenue.Revenue - Route_TS_Labor.Labor
                                     ELSE Route_Revenue.Revenue - (Route_TS_Labor.Labor + Route_Material.Material)
                                     END
                                END
                           END AS Profit
                   FROM    nei.dbo.Route
                           LEFT JOIN nei.dbo.Emp ON Route.Mech = Emp.fWork
                           LEFT JOIN (
                             SELECT Count(Elev.ID) AS Units,
                                    Loc.Route
                             FROM   nei.dbo.Loc
                                    LEFT JOIN nei.dbo.Elev ON Loc.Loc = Elev.Loc
                             WHERE Elev.Type <> 'Escalator'
                             GROUP BY Loc.Route
                           ) AS Elevators ON Elevators.Route = Route.ID
                           LEFT JOIN (
                             SELECT Count(Elev.ID) AS Units,
                                    Loc.Route
                             FROM   nei.dbo.Loc
                                    LEFT JOIN nei.dbo.Elev ON Loc.Loc = Elev.Loc
                             WHERE  Elev.Type = 'Escalator'
                             GROUP BY Loc.Route
                           ) AS Escalators ON Escalators.Route = Route.ID
                            LEFT JOIN (
                              SELECT  Loc.Route AS Route,
               										    Sum(Invoice.Amount) AS Revenue
               								FROM    nei.dbo.Loc
               										    LEFT JOIN nei.dbo.Invoice AS Invoice  ON Invoice.Loc = Loc.Loc
                                      LEFT JOIN nei.dbo.Job AS Job          ON Job.ID = Invoice.Job
               								WHERE        Invoice.fDate >= ?
                                     AND   Job.Type = 0
               								GROUP BY Loc.Route
               						  ) AS Route_Revenue ON Route_Revenue.Route = Route.ID
                            LEFT JOIN (
                              SELECT Loc.Route AS Route,
               										   Sum(Job_Item.Amount)   AS Labor
               								FROM   nei.dbo.Loc
               										   LEFT JOIN nei.dbo.Job AS Job        ON Loc.Loc = Job.Loc AND Loc.Owner = Job.Owner
               										   LEFT JOIN nei.dbo.JobI AS Job_Item  ON Job.ID      = Job_Item.Job
               							  WHERE        Job_Item.fDate >= ?
               										   AND   Job_Item.Type  = 1
               										   AND   Job_Item.Labor = 1
                                     AND   Job.Type = 0
               								  GROUP BY Loc.Route
                            ) AS Route_TS_Labor ON Route_TS_Labor.Route = Route.ID
                            LEFT JOIN (
                              SELECT Loc.Route AS Route,
               										   Sum(Job_Item.Amount) AS Material
               								FROM   nei.dbo.Loc
               										   LEFT JOIN nei.dbo.Job AS Job        ON Loc.Loc = Job.Loc AND Loc.Owner = Job.Owner
               										   LEFT JOIN nei.dbo.JobI AS Job_Item  ON Job.ID = Job_Item.Job
               								WHERE  (Job_Item.Labor <> 1
               										  OR Job_Item.Labor = ''
               										  OR Job_Item.Labor = 0
               										  OR Job_Item.Labor = ' '
               										  OR Job_Item.Labor IS NULL)
               										 AND Job_Item.Type = 1
               										 AND Job_Item.fDate >= ?
                                   AND Job.Type = 0
               								GROUP BY Loc.Route
                            ) AS Route_Material ON Route_Material.Route = Route.ID
                ) AS Route_Information ON Route_Information.Route = Route.ID
          WHERE    Emp.fFirst <> 'D'
          GROUP BY Route.Name,
                   Route.Name,
                   Emp.fFirst,
                   Emp.Last,
                   Route_Information.Revenue,
                   Route_Information.Labor,
                   Route_Information.Material,
                   Route_Information.Cost,
                   Route_Information.Profit,
                   Route_Information.Elevators,
                   Route_Information.Escalators
          ORDER BY Route.Name DESC
	    ;", array(date('Y-m-d H:i:s', strtotime('-1 month')), date('Y-m-d H:i:s', strtotime('-1 month')), date('Y-m-d H:i:s', strtotime('-1 month'))));
    if( ($errors = sqlsrv_errors() ) != null) {
      foreach( $errors as $error ) {
          echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
          echo "code: ".$error[ 'code']."<br />";
          echo "message: ".$error[ 'message']."<br />";
      }
    }
    $data = array();
    if($r){while($array = sqlsrv_fetch_array($r)){$data[$array['ID']] = $array;}}

    $r = sqlsrv_query($NEI,
      " SELECT Route.Name AS ID,
               Loc.Tag AS Route,
               Emp.fFirst AS First_Name,
               Emp.Last AS Last_Name,
               Location_Information.Revenue,
               Location_Information.Labor,
               Location_Information.Material,
               Location_Information.Cost,
               Location_Information.Profit,
               Location_Information.Elevators,
               Location_Information.Escalators
        FROM   nei.dbo.Loc
               LEFT JOIN nei.dbo.Route ON Loc.Route = Route.ID
               LEFT JOIN nei.dbo.Emp ON Route.Mech = Emp.fWork
               LEFT JOIN (
                 SELECT  Loc.Loc AS Loc,
                         Elevators.Units AS Elevators,
                         Escalators.Units AS Escalators,
                         CASE WHEN Location_TS_Labor.Labor IS NULL THEN
                              CASE WHEN Location_Material.Material IS NULL THEN
                                   0
                              ELSE Location_TS_Labor.Labor
                              END
                         ELSE CASE WHEN Location_Material.Material IS NULL THEN
                                   Location_TS_Labor.Labor
                              ELSE Location_TS_Labor.Labor + Location_Material.Material
                              END
                         END AS Cost,
                         CASE WHEN Location_Revenue.Revenue IS NULL THEN 0 ELSE Location_Revenue.Revenue END AS Revenue,
                         CASE WHEN Location_TS_Labor.Labor IS NULL THEN 0 ELSE Location_TS_Labor.Labor END AS Labor,
                         CASE WHEN Location_Material.Material IS NULL THEN 0 ELSE Location_Material.Material END AS Material,
                         CASE WHEN Location_Revenue.Revenue IS NULL THEN
                              CASE WHEN Location_TS_Labor.Labor IS NULL THEN
                                   CASE WHEN Location_Material.Material IS NULL THEN
                                        0
                                   ELSE Location_Material.Material
                                   END
                              ELSE CASE WHEN Location_Material.Material IS NULL THEN
                                        -1 * Location_TS_Labor.Labor
                                   ELSE -1 * (Location_TS_Labor.Labor + Location_Material.Material)
                                   END
                              END
                         ELSE CASE WHEN Location_TS_Labor.Labor IS NULL THEN
                                   CASE WHEN Location_Material.Material IS NULL THEN
                                        Location_Revenue.Revenue
                                   ELSE Location_Revenue.Revenue - Location_Material.Material
                                   END
                              ELSE CASE WHEN Location_Material.Material IS NULL THEN
                                        Location_Revenue.Revenue - Location_TS_Labor.Labor
                                   ELSE Location_Revenue.Revenue - (Location_TS_Labor.Labor + Location_Material.Material)
                                   END
                              END
                         END AS Profit
                 FROM    nei.dbo.Loc
                          LEFT JOIN (
                            SELECT Count(Elev.ID) AS Units,
                                    Loc.Loc
                            FROM   nei.dbo.Loc
                                   LEFT JOIN nei.dbo.Elev ON Elev.Loc = Loc.Loc
                            WHERE Elev.Type <> 'Escalator'
                            GROUP BY Loc.Loc
                          ) AS Elevators ON Elevators.Loc = Loc.Loc
                          LEFT JOIN (
                            SELECT Count(Elev.ID) AS Units,
                                    Loc.Loc
                            FROM   nei.dbo.Loc
                                   LEFT JOIN nei.dbo.Elev ON Elev.Loc = Loc.Loc
                            WHERE Elev.Type = 'Escalator'
                            GROUP BY Loc.Loc
                          ) AS Escalators ON Escalators.Loc = Loc.Loc
                          LEFT JOIN (
                            SELECT  Loc.Loc AS ID,
                                    Sum(Invoice.Amount) AS Revenue
                            FROM    nei.dbo.Loc
                                    LEFT JOIN nei.dbo.Invoice AS Invoice  ON Invoice.Loc = Loc.Loc
                                    LEFT JOIN nei.dbo.Job AS Job          ON Invoice.Job = Job.ID
                            WHERE        Invoice.fDate >= ?
                                   AND   Job.Type = 0
                            GROUP BY Loc.Loc
                          ) AS Location_Revenue ON Location_Revenue.ID = Loc.Loc
                          LEFT JOIN (
                            SELECT Loc.Loc AS ID,
                                   Sum(Job_Item.Amount)   AS Labor
                            FROM   nei.dbo.Loc
                                   LEFT JOIN nei.dbo.Job AS Job        ON Job.Loc = Loc.Loc
                                   LEFT JOIN nei.dbo.JobI AS Job_Item  ON Job.ID      = Job_Item.Job
                            WHERE        Job_Item.fDate >= ?
                                   AND   Job_Item.Type  = 1
                                   AND   Job_Item.Labor = 1
                                   AND   Job.Type = 0
                              GROUP BY Loc.Loc
                          ) AS Location_TS_Labor ON Location_TS_Labor.ID = Loc.Loc
                          LEFT JOIN (
                            SELECT Loc.Loc AS ID,
                                   Sum(Job_Item.Amount) AS Material
                            FROM   nei.dbo.Loc
                                   LEFT JOIN nei.dbo.Job AS Job        ON Job.Loc = Loc.Loc
                                   LEFT JOIN nei.dbo.JobI AS Job_Item  ON Job.ID = Job_Item.Job
                            WHERE  (Job_Item.Labor <> 1
                                  OR Job_Item.Labor = ''
                                  OR Job_Item.Labor = 0
                                  OR Job_Item.Labor = ' '
                                  OR Job_Item.Labor IS NULL)
                                 AND Job_Item.Type = 1
                                 AND Job_Item.fDate >= ?
                                 AND Job.Type = 0
                            GROUP BY Loc.Loc
                          ) AS Location_Material ON Location_Material.ID = Loc.Loc
              ) AS Location_Information ON Location_Information.Loc = Loc.Loc
        GROUP BY Route.Name,
                 Loc.Tag,
                 Emp.fFirst,
                 Emp.Last,
                 Location_Information.Revenue,
                 Location_Information.Labor,
                 Location_Information.Material,
                 Location_Information.Cost,
                 Location_Information.Profit,
                 Location_Information.Elevators,
                 Location_Information.Escalators
    ;", array(date('Y-m-d H:i:s', strtotime('-1 month')), date('Y-m-d H:i:s', strtotime('-1 month')), date('Y-m-d H:i:s', strtotime('-1 month'))));
    if( ($errors = sqlsrv_errors() ) != null) {
      foreach( $errors as $error ) {
          echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
          echo "code: ".$error[ 'code']."<br />";
          echo "message: ".$error[ 'message']."<br />";
      }
    }
    $data2 = array();
    if($r){while($array = sqlsrv_fetch_array($r)){
      if(!isset($data2[$array['ID']])){$data2[$array['ID']] = array();}
      $data2[$array['ID']][] = $array;
    }}

    $data3 = array();
    foreach($data as $index=>$Route){
      if(isset($data2[$Route['ID']])){foreach($data2[$Route['ID']] AS $index2=>$Location){
        $data3[] = $Location;
      }}
      $data3[] = $Route;
    }

    print json_encode(array('data'=>$data3));
  }
}?>
