<?php
session_start();
set_time_limit (60);
require('../../index.php');
function Check_Date_Time($date_time){
 if (preg_match("/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2}).(\d{3})/", $date_time)){return true;}
 else {return false;}
}
if(isset($_SESSION['User'],$_SESSION['Hash'])){
  $r = sqlsrv_query($Databases['Default'],"SELECT * FROM Connection WHERE Connector = ? AND Hash = ?;",array($_SESSION['User'],$_SESSION['Hash']));
  $array = sqlsrv_fetch_array($r);
  $Privileged = FALSE;
  $r = sqlsrv_query($Databases['Default'],"SELECT * FROM Emp WHERE ID = ?",array($_SESSION['User']));
  $My_User = sqlsrv_fetch_array($r);
  $Field = ($User['Field'] == 1 && $User['Title'] != "OFFICE") ? True : False;
  $r = sqlsrv_query($Databases['Default'],"
      SELECT Access_Table, User_Privilege, Group_Privilege, Other_Privilege
      FROM   Portal.dbo.Privilege
      WHERE  User_ID = ?
  ;",array($_SESSION['User']));
  $My_Privileges = array();
  while($array2 = sqlsrv_fetch_array($r)){$My_Privileges[$array2['Access_Table']] = $array2;}
  $Privileged = FALSE;
  if(isset($My_Privileges['Map']) && $My_Privileges['Map']['User_Privilege'] >= 4 && $My_Privileges['Map']['User_Privilege'] >= 4 && $My_Privileges['Map']['User_Privilege'] >= 4){$Privileged = TRUE;}
  if(!$Privileged){?><html><head><script>document.location.href='../login.php';</script></head></html><?php }
  else {
    //Get Timeline//
    $rows = array();
    $Ticket_ID = 0;

    if(isset($_GET['REFRESH_DATETIME'])){
      $r = sqlsrv_query( $Databases['Portal_44'],
        " SELECT  *
          FROM    Portal.dbo.Timeline
          WHERE   Timeline.Time_Stamp > ?
                  AND Timeline.Time_stamp <= ?
          ORDER BY Timeline.Time_Stamp ASC
        ;",array(date("Y-m-d H:i:s",strtotime("-15 minutes",strtotime($_GET['REFRESH_DATETIME']))),date("Y-m-d H:i:s",strtotime('+15 minutes'))));
      sqlErrors();
      $pSQL = sqlsrv_prepare($Databases['Default'],
        " SELECT  Emp.fFirst + ' ' + Emp.Last AS Employee_Name,
                  Ticket.ID,
                  Loc.Tag AS Location_Tag
          FROM    (
            (
              SELECT  TicketO.fWork,
                      TicketO.ID,
                      TicketO.LID AS Loc
              FROM    TicketO
              WHERE   TicketO.ID = ?
            )
            UNION ALL
            (
              SELECT  TicketD.fWork,
                      TicketD.ID,
                      TicketD.Loc AS Loc
              FROM    TicketD
              WHERE   TicketD.ID = ?
            )
          ) AS Ticket
                  LEFT JOIN Emp ON Emp.fWork = Ticket.fWork
                  LEFT JOIN tblWork ON 'A' + convert(varchar(10),Emp.ID) + ',' = tblWork.Members
                  LEFT JOIN Loc ON Ticket.Loc = Loc.Loc
          WHERE   tblWork.Super LIKE '%' + ? + '%' OR ? IS NULL
        ;",array(&$Ticket_ID, &$Ticket_ID, &$_GET['Supervisor'], &$_GET['Supervisor']));
      if($r){while($row = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC)){
        if($row['Entity'] == 'Ticket'){
          $Ticket_ID = $row['Entity_ID'];
          sqlsrv_execute($pSQL);
          sqlErrors();
          $pRow = sqlsrv_fetch_array($pSQL);
          if($pSQL && is_array($pRow)){
            $row['Time_Stamp'] = date("m/d/Y h:i A",strtotime($row['Time_Stamp']));
            $row['Employee_Name'] = $pRow['Employee_Name'];
            $row['Location_Tag'] = $pRow['Location_Tag'];
            $rows[$row['ID']] = $row;
          }
        } else {
          $row['Time_Stamp'] = date("m/d/Y h:i A",strtotime($row['Time_Stamp']));
          $rows[$row['ID']] = $row;
        }
      }}
    }
    print json_encode($rows);
  }
}?>
