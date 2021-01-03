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
                        Job.fDate       AS Date_Created,
                        Job.fDesc       AS Description,
                        Loc.Tag         AS Account,
                        Job.BRev AS Contract_Amount,
                        Job_Revenue.Sum   AS Billing_Amount,
                        Job.Custom1     AS Supervisor,
			Job.Status,
                        Hours_Charged.Hours AS Hours_Charged,
                        Terr.Name AS Territory,
                        CASE WHEN Job.ID IN (
                          SELECT  TicketO.Job
                          FROM    nei.dbo.TicketO
                                  LEFT JOIN nei.dbo.Job ON TicketO.Job = Job.ID
                          WHERE Job.fDate >= ? AND Job.fDate <= ? AND (Job.Status = 0) AND TicketO.Assigned < 4
                          GROUP BY TicketO.Job
                        ) THEN 'Yes' ELSE 'No' END AS Open_Ticket
                  FROM  nei.dbo.Job
                        LEFT JOIN nei.dbo.Loc ON Loc.Loc = Job.Loc
                        LEFT JOIN nei.dbo.Terr ON Terr.ID = Loc.Terr
                        LEFT JOIN (
                          SELECT    Job.ID,
                                    SUM(Invoice.Amount) AS Sum
                          FROM      nei.dbo.Invoice
                                    LEFT JOIN nei.dbo.Job ON Invoice.Job = Job.ID
                          WHERE     Job.fDate >= ? AND Job.fDate <= ? AND (Job.Status = 0)
                          GROUP BY  Job.ID
                        ) AS Job_Revenue ON Job_Revenue.ID = Job.ID
                        LEFT JOIN (
                          SELECT   Job.ID AS Job,
                                   Sum(TicketD.Total) AS Hours
                          FROM     nei.dbo.Job
                                   LEFT JOIN nei.dbo.TicketD ON TicketD.Job = Job.ID
                                   LEFT JOIN (
                                     SELECT  Job.ID AS Job,
                                             Max(Invoice.fDate) AS Last_Invoice_Date
                                     FROM    nei.dbo.Job
                                             LEFT JOIN nei.dbo.Invoice ON Invoice.Job = Job.ID
                                     GROUP BY Job.ID
                                   ) AS Invoiced ON Job.ID = Invoiced.Job
                          WHERE    (TicketD.EDate >= Invoiced.Last_Invoice_Date OR Invoiced.Last_Invoice_Date IS NULL) AND Job.fDate >= ? AND Job.fDate <= ? AND (Job.Status = 0)
                          GROUP BY Job.ID
                        ) AS Hours_Charged ON Hours_Charged.Job = Job.ID
                  WHERE  Job.fDate >= ? AND Job.fDate <= ? AND (Job.Status = 0) AND Job.Type <> 0 AND Job.Custom1 = ?
    			;", array($Start, $End, $Start, $End, $Start, $End, $Start, $End, $Supervisor ));
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
