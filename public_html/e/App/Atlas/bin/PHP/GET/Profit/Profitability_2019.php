<?php
session_start();
require('index.php');
if(isset($_SESSION['User'],$_SESSION['Hash'])){
    $r = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.Connection WHERE Connector = ? AND Hash = ?;",array($_SESSION['User'],$_SESSION['Hash']));
    $array = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC);
    $User = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.Emp WHERE ID = ?",array($_GET['User']));
    $User = sqlsrv_fetch_array($User);
    $Field = ($User['Field'] == 1 && "OFFICE" != $User['Title']) ? True : False;
    $r = sqlsrv_query($Portal,"
            SELECT Access_Table, User_Privilege, Group_Privilege, Other_Privilege
            FROM   Portal.dbo.Privilege
            WHERE  User_ID = ?
        ;",array($_SESSION['User']));
    $My_Privileges = array();
    while($array2 = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC)){$My_Privileges[$array2['Access_Table']] = $array2;}
    $Privileged = false;
    if(isset($My_Privileges['Invoice']) && $My_Privileges['Invoice']['User_Privilege'] >= 4){$Privileged = true;}
    if(isset($_SESSION['Branch_ID']) && $_SESSION['Branch_ID'] == $_GET['ID']){$Privileged = true;}
    if(!isset($array['ID']) || !$Privileged){?><html><head><script>document.location.href='../login.php';</script></head></html><?php }
    else {
		$data = array();
        $r = sqlsrv_query($NEI,"
        SELECT *,
                Customers.Profit_without_OpenAR * .86 AS True_Profit,
                CASE WHEN Customers.Profit_Percentage_without_OpenAR = 0
                THEN 0
                ELSE Customers.Profit_Percentage_without_OpenAR - 14 END AS True_Profit_Percentage
        FROM (
			SELECT Customer.*,
        Rol.Name AS Name,
        CASE WHEN Customer.Revenue = 0
						THEN 0
						ELSE
							CASE WHEN Customer.Profit < 0 AND Customer.Revenue < 0
								THEN ((Customer.Profit / Customer.Revenue) * -1)
								ELSE (Customer.Profit / Customer.Revenue)
							END
					END * 100 AS Profit_Percentage,
          CASE WHEN Customer.Revenue = 0
  						THEN 0
  						ELSE
  							CASE WHEN Customer.Profit_without_OpenAR < 0 AND Customer.Revenue < 0
  								THEN ((Customer.Profit_without_OpenAR / Customer.Revenue) * -1)
  								ELSE (Customer.Profit_without_OpenAR / Customer.Revenue)
  							END
  					END * 100 AS Profit_Percentage_without_OpenAR
			FROM (SELECT  *,
				    Customer.Revenue - (Customer.Labor + Customer.Material) AS Profit,
            Customer.Revenue - (Customer.Labor + Customer.Material + Customer.OpenAR) AS Profit_without_OpenAR
				FROM (SELECT Customer.Name AS Customer,
              Customer.ID AS ID,
              Customer_OpenAR.OpenAR AS OpenAR,
					   CASE WHEN Customer_Revenue.Revenue IS NULL
							THEN 0
							ELSE Customer_Revenue.Revenue END
							AS Revenue,
					   CASE WHEN Customer_Material.Material IS NULL
							THEN 0
							ELSE Customer_Material.Material END
							AS Material,
					   CASE WHEN Customer_Paradox_Labor.Paradox_Labor IS NULL
							THEN CASE WHEN Customer_TS_Labor.TS_Labor  IS NULL
								THEN 0
								ELSE Customer_TS_Labor.TS_Labor END
							ELSE CASE WHEN Customer_TS_Labor.TS_Labor IS NULL
								THEN Customer_Paradox_Labor.Paradox_Labor
								ELSE Customer_Paradox_Labor.Paradox_Labor + Customer_TS_Labor.TS_Labor END END AS Labor
				FROM   nei.dbo.OwnerWithRol AS Customer
        LEFT JOIN (SELECT Customer.ID AS ID,
                  Sum(OpenAR.Balance) AS OpenAR
                  FROM nei.dbo.OwnerWithRol AS Customer
                  LEFT JOIN nei.dbo.Loc ON Loc.Owner = Customer.ID
                  LEFT JOIN nei.dbo.OpenAR ON OpenAR.Loc = Loc.Loc
                  LEFT JOIN nei.dbo.Invoice ON Invoice.Ref = OpenAR.Ref
                  LEFT JOIN nei.dbo.Job ON Invoice.Job = Job.ID
                  WHERE Invoice.fDate < GETDATE() - 150
                  AND ((Job.Type = 2 AND Job.Status <> 0) OR (Job.Type <> 2))
                  GROUP BY Customer.ID
                  ) AS Customer_OpenAR ON Customer_OpenAR.ID = Customer.ID
					   LEFT JOIN (SELECT Customer.ID AS ID,
										 Sum(Invoice.Amount) AS Revenue
								  FROM   nei.dbo.OwnerWithRol      AS Customer
										 LEFT JOIN nei.dbo.Loc     AS Location ON Customer.ID = Location.Owner
										 LEFT JOIN nei.dbo.Invoice AS Invoice  ON Invoice.Loc = Location.Loc
                     LEFT JOIN nei.dbo.Job ON Job.ID = Invoice.Job
								  WHERE  Invoice.fDate >= '2017-03-30 00:00:00.000'
                  AND ((Job.Type = 2 AND Job.Status <> 0) OR (Job.Type <> 2))
								  GROUP BY Customer.ID
								 ) AS Customer_Revenue ON Customer_Revenue.ID = Customer.ID
					   LEFT JOIN (SELECT Customer.ID                  AS ID,
										 Sum(Job_Labor.[TOTAL COST])  AS Paradox_Labor
								  FROM   nei.dbo.OwnerWithRol           AS Customer
										 LEFT JOIN nei.dbo.Job          AS Job       ON Customer.ID       = Job.Owner
										 LEFT JOIN Paradox.dbo.JOBLABOR AS Job_Labor ON Job.ID            = Job_Labor.[JOB #]
								  WHERE  Job_Labor.[WEEK ENDING] >= '2017-03-30 00:00:00.000'
								  		 AND Job_Labor.jobAlpha <> '9999'
										 AND Job_Labor.jobAlpha <> '8888'
										 AND Job_Labor.jobAlpha <> '7777'
										 AND Job_Labor.jobAlpha <> '6666'
										 AND Job_Labor.jobAlpha <> '5555'
										 AND Job_Labor.jobAlpha <> '4444'
										 AND Job_Labor.jobAlpha <> '3333'
										 AND Job_Labor.jobAlpha <> '2222'
										 AND Job_Labor.jobAlpha <> '1111'
										 AND Job_Labor.jobAlpha <> '0000'
                     AND ((Job.Type = 2 AND Job.Status <> 0) OR (Job.Type <> 2))
								  GROUP BY Customer.ID) AS Customer_Paradox_Labor ON Customer_Paradox_Labor.ID = Customer.ID
					   LEFT JOIN (SELECT Customer.ID AS ID,
										 Sum(Job_Item.Amount)   AS TS_Labor
								  FROM   nei.dbo.OwnerWithRol   AS Customer
										 LEFT JOIN nei.dbo.Job  AS Job      ON Customer.ID = Job.Owner
										 LEFT JOIN nei.dbo.JobI AS Job_Item ON Job.ID      = Job_Item.Job
								  WHERE  Job_Item.fDate >= '2017-03-30 00:00:00.000'
										 AND Job_Item.Type  = 1
										 AND Job_Item.Labor = 1
                     AND ((Job.Type = 2 AND Job.Status <> 0) OR (Job.Type <> 2))
								  GROUP BY Customer.ID) AS Customer_TS_Labor ON Customer_TS_Labor.ID = Customer.ID
					   LEFT JOIN (SELECT Customer.ID AS ID,
										 Sum(Job_Item.Amount) AS Material
								  FROM   nei.dbo.OwnerWithRol AS Customer
										 LEFT JOIN nei.dbo.Job AS Job ON Customer.ID = Job.Owner
										 LEFT JOIN nei.dbo.JobI AS Job_Item ON Job.ID = Job_Item.Job
								  WHERE  (Job_Item.Labor <> 1
										  OR Job_Item.Labor = ''
										  OR Job_Item.Labor = 0
										  OR Job_Item.Labor = ' '
										  OR Job_Item.Labor IS NULL)
										 AND Job_Item.Type = 1
										 AND Job_Item.fDate >= '2017-03-30 00:00:00.000'
                     AND ((Job.Type = 2 AND Job.Status <> 0) OR (Job.Type <> 2))
								  GROUP BY Customer.ID) AS Customer_Material ON Customer_Material.ID = Customer.ID
						) AS Customer
				) AS Customer
        LEFT JOIN nei.dbo.Owner ON Customer.ID = Owner.ID
        LEFT JOIN nei.dbo.Rol ON Rol.ID = Owner.Rol
			WHERE Customer.Profit <> 0
    ) AS Customers
		;");
		if( ($errors = sqlsrv_errors() ) != null) {
			foreach( $errors as $error ) {
				echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
				echo "code: ".$error[ 'code']."<br />";
				echo "message: ".$error[ 'message']."<br />";
			}
		}
		if($r){while($Customer = sqlsrv_fetch_array($r)){$data[] = $Customer;}}
		print json_encode(array('data'=>$data));
	}
}?>
