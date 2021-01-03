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
    				"    SELECT OwnerWithRol.Name AS Customer,
                        Loc.Tag AS Account,
                        Route.Name AS Route,
			Loc.Custom1 AS Collector,
                        Terr.Name AS Territory,
                        Loc.Balance AS Balance,
                        Collections_90.Balance AS Days_90,
                        Collections_180.Balance AS Days_180,
                        Collections_365.Balance AS Days_365,
                        Collections_Unpaid.No_Unpaid_Invoices AS No_Unpaid_Invoices,
                        Last_Payment.fDate AS Last_Payment_Date,
                        /*Abs(Last_Payment2.Amount) AS Last_Payment_Amount,*/
                        (SELECT Count(*) FROM [nei].dbo.Invoice WHERE [Invoice].[fDate] > Last_Payment.fDate AND [Invoice].[Loc] = [Loc].[Loc]) AS No_New_Invoices,
			Abs(Sum_Payments.Amount) AS Sum_Payments,
                        ABS(DATEDIFF(day, (SELECT Max([Trans].[fDate]) FROM nei.dbo.Trans WHERE Trans.AcctSub = Loc.Loc AND [Trans].[Type] = 6 AND [Trans].fDate >= '2017-03-30 00:00:00.000'), (SELECT Min([Trans].[fDate]) FROM nei.dbo.Trans WHERE Trans.AcctSub = Loc.Loc AND [Trans].[Type] = 6 AND [Trans].fDate >= '2017-03-30 00:00:00.000')) / (SELECT Count([Trans].[fDate]) FROM nei.dbo.Trans WHERE Trans.AcctSub = Loc.Loc AND [Trans].[Type] = 6 AND [Trans].fDate >= '2017-03-30 00:00:00.000')) AS Average_Days_Paid,
			Count_Payments.Amount AS Count_Payments
		FROM   nei.dbo.Loc
                        LEFT JOIN nei.dbo.OwnerWithRol ON Loc.Owner = OwnerWithRol.ID
                        LEFT JOIN nei.dbo.Route ON Loc.Route = Route.ID
                        LEFT JOIN nei.dbo.Terr ON Terr.ID = Loc.Terr
                        LEFT JOIN (
                          SELECT  Invoice.Loc,
                                  Sum(OpenAR.Balance) AS Balance
                          FROM    nei.dbo.OpenAR
                                  LEFT JOIN nei.dbo.Invoice ON OpenAR.Ref = Invoice.Ref
                          GROUP BY Invoice.Loc
                        ) AS Collections ON Collections.Loc = Loc.Loc
                        LEFT JOIN (
                          SELECT  Invoice.Loc,
                                  Sum(OpenAR.Balance) AS Balance
                          FROM    nei.dbo.OpenAR
                                  LEFT JOIN nei.dbo.Invoice ON OpenAR.Ref = Invoice.Ref
                          WHERE   Invoice.fDate <= ?
                          GROUP BY Invoice.Loc
                        ) AS Collections_90 ON Collections_90.Loc = Loc.Loc
                        LEFT JOIN (
                          SELECT  Invoice.Loc,
                                  Sum(OpenAR.Balance) AS Balance
                          FROM    nei.dbo.OpenAR
                                  LEFT JOIN nei.dbo.Invoice ON OpenAR.Ref = Invoice.Ref
                          WHERE   Invoice.fDate <= ?
                          GROUP BY Invoice.Loc
                        ) AS Collections_180 ON Collections_180.Loc = Loc.Loc
                        LEFT JOIN (
                          SELECT  Invoice.Loc,
                                  Sum(OpenAR.Balance) AS Balance
                          FROM    nei.dbo.OpenAR
                                  LEFT JOIN nei.dbo.Invoice ON OpenAR.Ref = Invoice.Ref
                          WHERE   Invoice.fDate <= ?
                          GROUP BY Invoice.Loc
                        ) AS Collections_365 ON Collections_365.Loc = Loc.Loc
                        LEFT JOIN (
                          SELECT  Invoice.Loc,
                                  Count(Invoice.Ref) AS No_Unpaid_Invoices
                          FROM    nei.dbo.OpenAR
                                  LEFT JOIN nei.dbo.Invoice ON OpenAR.Ref = Invoice.Ref
                          GROUP BY Invoice.Loc
                        ) AS Collections_Unpaid ON Collections_Unpaid.Loc = Loc.Loc
                        LEFT JOIN (
                          SELECT  Max([Trans].fDate) as fDate,
                                  [Trans].AcctSub
                          FROM    nei.dbo.Trans
                          WHERE   [Trans].[Type] = 6
                          GROUP BY AcctSub
                        ) AS Last_Payment ON Last_Payment.AcctSub = Loc.Loc
                        /*LEFT JOIN (
                          SELECT  [Trans].[Amount],
                                  Max([Trans].fDate) as fDate,
                                  [Trans].AcctSub
                          FROM    nei.dbo.Trans
                          WHERE   [Trans].[Type] = 6
                          GROUP BY AcctSub, Amount
                        ) AS Last_Payment2 ON Last_Payment2.AcctSub = Loc.Loc AND Last_Payment2.fDate = Last_Payment.fDate*/
			LEFT JOIN (
                          SELECT  [Trans].AcctSub,
				  Sum([Trans].Amount) as Amount
                          FROM    nei.dbo.Trans
                          WHERE   [Trans].[Type] = 6 AND [Trans].[fDate] >= ?
                          GROUP BY AcctSub
                        ) AS Sum_Payments ON Sum_Payments.AcctSub = Loc.Loc
			LEFT JOIN (
                          SELECT  [Trans].AcctSub,
				  Count([Trans].ID) as Amount
                          FROM    nei.dbo.Trans
                          WHERE   [Trans].[Type] = 6 AND [Trans].[fDate] >= ?
                          GROUP BY AcctSub
                        ) AS Count_Payments ON Count_Payments.AcctSub = Loc.Loc

                      WHERE Loc.Status = 0
    			;", array(date('Y-m-d H:i:s', strtotime('-90 days')), date('Y-m-d H:i:s', strtotime('-180 days')), date("Y-m-d H:i:s", strtotime("-365 days")), date('Y-m-d H:i:s', strtotime('-180 days')),date("Y-m-d H:i:s", strtotime("-180 days"))));
          if( ($errors = sqlsrv_errors() ) != null) {
              foreach( $errors as $error ) {
                  echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
                  echo "code: ".$error[ 'code']."<br />";
                  echo "message: ".$error[ 'message']."<br />";
              }
          }
          $data = array();
    			if($r){while($row = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC)){
            $row['Balance'] = "$" . number_format($row['Balance'], 2);
            $row['Days_90'] = "$" . number_format($row['Days_90'], 2);
            $row['Days_180'] = "$" . number_format($row['Days_180'], 2);

            $row['Days_365'] = "$" . number_format($row['Days_365'], 2);
            $row['Last_Payment_Amount'] = "$" . number_format($row['Last_Payment_Amount'], 2);
            $row['Last_Payment_Date'] = is_null($row['Last_Payment_Date']) ? '' : date("m/d/Y", strtotime($row['Last_Payment_Date']));
            $row['Sum_Payments'] = "$" . number_format($row['Sum_Payments'], 2); 
    				$data[] = $row;
    			}}
        }
        print json_encode(array('data'=>$data));
    }
}
