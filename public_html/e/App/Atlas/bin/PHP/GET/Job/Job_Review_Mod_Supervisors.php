<?php
session_start();
require('index.php');
if(isset($_SESSION['User'],$_SESSION['Hash'])){
    $r = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.Connection WHERE Connector = ? AND Hash = ?;",array($_SESSION['User'],$_SESSION['Hash']));
    $array = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC);
    $r = sqlsrv_query($Portal,"
        SELECT User_Privilege, Group_Privilege, Other_Privilege
        FROM   Portal.dbo.Privilege
        WHERE User_ID = ? AND Access_Table='Job'
    ;",array($_SESSION['User']));
    $My_Privileges = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC);
    if(!isset($array['ID']) || !is_array($My_Privileges)){?><html><head><script>document.location.href='../login.php';</script></head></html><?php }
    else {
        if($My_Privileges['User_Privilege'] >= 4 && $My_Privileges['Group_Privilege'] >= 4 && $My_Privileges['Other_Privilege'] >= 4){
          $Start = is_null($_GET['Start']) ? "2019-01-01 00:00:00.000" : date("Y-m-d 00:00:00.000", strtotime($_GET['Start']));
          $End =  is_null($_GET['End']) ? "2022-01-01 00:00:00.000" : date("Y-m-d 00:00:00.000", strtotime($_GET['End']));
          $Supervisor = is_null($_GET['Supervisor']) ? '' : $_GET['Supervisor'];
    			$r = sqlsrv_query($NEI,
    				"    SELECT Job.ID          AS Job_ID,
                        Job.ID AS ID,
                        Job.fDate       AS Date_Created,
                        Job.fDesc       AS Description,
                        Job.Custom1     AS Supervisor,
                        Loc.Tag         AS Account,
                        Overtime_Tickets.OT AS OT,
                        Overtime_Tickets.DT AS DT,
                        'Job' AS Class
                  FROM  nei.dbo.Job
                        LEFT JOIN nei.dbo.Loc ON Loc.Loc = Job.Loc
                        LEFT JOIN (
                          SELECT Sum(TicketD.OT) AS OT, Sum(TicketD.DT) AS DT, TicketD.Job FROM TicketD WHERE TicketD.EDate >= ? AND TicketD.EDate < ? AND (TicketD.OT > 0 OR TicketD.DT > 0) GROUP BY TicketD.Job
                        ) AS Overtime_Tickets ON Overtime_Tickets.Job = Job.ID
                  WHERE  (Job.Status = 0) AND Job.Type = 2 AND (Overtime_Tickets.OT > 0 OR Overtime_Tickets.DT > 0)
    			;", array($Start, $End));
          if( ($errors = sqlsrv_errors() ) != null) {
        foreach( $errors as $error ) {
            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".$error[ 'message']."<br />";
        }
    }
          $data = array();
    			if($r){while($row = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC)){
            $row['Date_Created'] = is_null($row['Date_Created']) ? '' : date('m/d/Y', strtotime($row['Date_Created']));
            $row['Last_Invoice_Date'] = is_null($row['Last_Invoice_Date']) ? '' : date('m/d/Y', strtotime($row['Last_Invoice_Date']));
            $row['Billing_Amount'] = "$" . number_format($row['Billing_Amount'], 2);
            $row['Contract_Amount'] = "$" . number_format($row['Contract_Amount'], 2);
            $row['Labor_Costs'] = "$" . number_format($row['Labor_Costs'], 2);
            $row['Material_Costs'] = "$" . number_format($row['Material_Costs'], 2);
            $row['Total_Costs'] = "$" . number_format($row['Total_Costs'], 2);
            $row['Last_Ticket_Date'] = is_null($row['Last_Ticket_Date']) ? '' : date("m/d/Y", strtotime($row['Last_Ticket_Date']));
    				$data[] = $row;
    			}}
        }
        print json_encode(array('data'=>$data));
    }
}
