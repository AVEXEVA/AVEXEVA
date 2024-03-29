<?php
session_start();

require('../../../php/index.php');
setlocale(LC_MONETARY, 'en_US');
if(isset($_SESSION['User'],$_SESSION['Hash'])){
    $r = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.Connection WHERE Connector = ? AND Hash = ?;",array($_SESSION['User'],$_SESSION['Hash']));
    $array = sqlsrv_fetch_array($r);
    if(!isset($_SESSION['Branch']) || $_SESSION['Branch'] == 'Nouveau Elevator'){
        $My_User = sqlsrv_query($NEI,"SELECT *, fFirst AS First_Name, Last as Last_Name FROM Emp WHERE ID = ?",array($_SESSION['User']));
        $My_User = sqlsrv_fetch_array($My_User);
        $Field = ($My_User['Field'] == 1 && $My_User['Title'] != "OFFICE") ? True : False;
        $r = sqlsrv_query($Portal,"
            SELECT Access_Table, User_Privilege, Group_Privilege, Other_Privilege
            FROM   Portal.dbo.Privilege
            WHERE  User_ID = ?
        ;",array($_SESSION['User']));
        $My_Privileges = array();
        while($array2 = sqlsrv_fetch_array($r)){$My_Privileges[$array2['Access_Table']] = $array2;}
        $Privileged = FALSE;
        if(isset($My_Privileges['Customer']) && $My_Privileges['Customer']['Other_Privilege'] >= 4){$Privileged = TRUE;}
    }
    sqlsrv_query($Portal,"INSERT INTO Activity([User], [Date], [Page]) VALUES(?,?,?);",array($_SESSION['User'],date("Y-m-d H:i:s"), "customer-information.php"));
    if(!isset($array['ID'])  || !$Privileged || !is_numeric($_GET['ID'])){?><html><head><script>document.location.href="../login.php?Forward=location<?php echo (!isset($_GET['ID']) || !is_numeric($_GET['ID'])) ? "s.php" : ".php?ID={$_GET['ID']}";?>";</script></head></html><?php }
    else {
        $r = sqlsrv_query($NEI,
            "SELECT TOP 1
                    OwnerWithRol.ID      AS Customer_ID,
                    OwnerWithRol.Name    AS Customer_Name,
                    OwnerWithRol.Address AS Customer_Street,
                    OwnerWithRol.City    AS Customer_City,
                    OwnerWithRol.State   AS Customer_State,
                    OwnerWithRol.Zip     AS Customer_Zip,
                    OwnerWithRol.Status  AS Customer_Status,
                    OwnerWithRol.Website AS Customer_Website
            FROM    nei.dbo.OwnerWithRol
            WHERE   OwnerWithRol.ID = ?
        ;",array($_GET['ID']));
        $Customer = sqlsrv_fetch_array($r);?>
<div class="panel panel-primary">
	<div class='panel-body' style='font-size:16px;padding:5px;'>
		<div class='row shadower' style='padding-top:10px;padding-bottom:10px;'>
			<div class='col-xs-4'><?php $Icons->Customer(1);?> Name:</div>
			<div class='col-xs-8'><?php echo $Customer['Customer_Name'];?></div>
			<div class='col-xs-4'><?php $Icons->Blank(1);?> Status:</div>
			<div class='col-xs-8'><?php echo isset($Customer['Customer_Status']) && $Customer['Customer_Status'] == 0? "Active" : "Inactive";?></div>
        </div>
        <div class='row shadower' style='padding-top:10px;padding-bottom:10px;'>
			<div class='col-xs-4'><?php $Icons->Address(1);?> Street:</div>
			<div class='col-xs-8'><?php echo $Customer['Customer_Street'];?></div>
			<div class='col-xs-4'><?php $Icons->Blank(1);?> City:</div>
			<div class='col-xs-8'><?php echo $Customer['Customer_City'];?></div>
			<div class='col-xs-4'><?php $Icons->Blank(1);?> State:</div>
			<div class='col-xs-8'><?php echo $Customer['Customer_State'];?></div>
			<div class='col-xs-4'><?php $Icons->Blank(1);?> Zip:</div>
			<div class='col-xs-8'><?php echo $Customer['Customer_Zip'];?></div>
			<div class='col-xs-4'><?php $Icons->Web(1);?> Website:</div>
			<div class='col-xs-8'><?php echo strlen($Customer['Customer_Website']) > 0 ?  $Customer['Customer_Website'] : "&nbsp;";?></div>
        </div>
        <div class='row shadower' style='padding-top:10px;padding-bottom:10px;'>
            <div class='col-xs-4'><?php $Icons->Unit(1);?> Units</div>
            <div class='col-xs-8'><?php
				$r = sqlsrv_query($NEI,"
					SELECT Count(Elev.ID) AS Count_of_Elevators
					FROM   nei.dbo.Elev
						   LEFT JOIN nei.dbo.Loc ON Elev.Loc = Loc.Loc
					WHERE  Loc.Owner = ?
				;",array($_GET['ID']));
				echo $r ? sqlsrv_fetch_array($r)['Count_of_Elevators'] : 0;
			?></div>
            <div class='col-xs-4'><?php $Icons->Job(1);?> Jobs</div>
            <div class='col-xs-8'>&nbsp;
				<?php
				$r = sqlsrv_query($NEI,"
					SELECT Count(Job.ID) AS Count_of_Jobs
					FROM   nei.dbo.Job
						   LEFT JOIN nei.dbo.Loc ON Job.Loc = Loc.Loc
					WHERE  Loc.Owner = ? AND Job.Status = 1
				;",array($_GET['ID']));
			echo $r ? sqlsrv_fetch_array($r)['Count_of_Jobs'] : 0;?>
			</div>
            <div class='col-xs-4'><?php $Icons->Violation(1);?> Violations</div>
            <div class='col-xs-8'>&nbsp;
			<?php
				$r = sqlsrv_query($NEI,"
					SELECT Count(Violation.ID) AS Count_of_Violations
					FROM   nei.dbo.Violation
						   LEFT JOIN nei.dbo.Loc ON Violation.Loc = Loc.Loc
					WHERE  Loc.Owner = ?
				;",array($_GET['ID']));
				echo $r ? sqlsrv_fetch_array($r)['Count_of_Violations'] : 0;?>
			</div>
            <div class='col-xs-4'><?php $Icons->Ticket(1);?> Tickets</div>
            <div class='col-xs-8'>
			<?php
				$r = sqlsrv_query($NEI,"
					SELECT Count(Tickets.ID) AS Count_of_Tickets
					FROM   (
								(
									SELECT TicketO.ID AS ID
									FROM   nei.dbo.TicketO
										   LEFT JOIN nei.dbo.Loc ON TicketO.LID = Loc.Loc
									WHERE  Loc.Owner = ?
								)
								UNION ALL
								(
									SELECT TicketD.ID AS ID
									FROM   nei.dbo.TicketD
										   LEFT JOIN nei.dbo.Loc ON TicketD.Loc = Loc.Loc
									WHERE  Loc.Owner = ?
								)
								UNION ALL
								(
									SELECT TicketDArchive.ID AS ID
									FROM   nei.dbo.TicketDArchive
										   LEFT JOIN nei.dbo.Loc ON TicketDArchive.Loc = Loc.Loc
									WHERE  Loc.Owner = ?
								)
							) AS Tickets
				;",array($_GET['ID'],$_GET['ID'],$_GET['ID']));
				echo $r ? sqlsrv_fetch_array($r)['Count_of_Tickets'] : 0;?>
			</div>
            <div class='col-xs-4'><?php $Icons->Proposal(1);?> Proposals</div>
            <div class='col-xs-8'>
				<?php
				$r = sqlsrv_query($NEI,"
					SELECT Count(Estimate.ID) AS Count_of_Estimates
					FROM   nei.dbo.Estimate
						   LEFT JOIN nei.dbo.Loc ON Estimate.LocID = Loc.Loc
					WHERE  Loc.Owner = ?
				;",array($_GET['ID']));
				echo $r ? sqlsrv_fetch_array($r)['Count_of_Estimates'] : 0;?>
			</div>
            <div class='col-xs-4'><?php $Icons->Invoice(1);?> Invoices</div>
            <div class='col-xs-8'>
				<?php
				$r = sqlsrv_query($NEI,"
					SELECT Count(Invoice.Ref) AS Count_of_Invoices
					FROM   nei.dbo.Invoice
						   LEFT JOIN nei.dbo.Loc ON Invoice.Loc = Loc.Loc
					WHERE  Loc.Owner = ? AND Invoice.Status = 1;
				;",array($_GET['ID']));
				echo $r ? sqlsrv_fetch_array($r)['Count_of_Invoices'] : 0;?>
			</div>
            <div class='col-xs-4'><?php $Icons->Legal(1);?> Lawsuits</div>
            <div class='col-xs-8'>
				<?php
				$r = sqlsrv_query($NEI,"
					SELECT Count(Job.ID) AS Count_of_Lawsuits
					FROM   nei.dbo.Job
						   LEFT JOIN nei.dbo.Loc ON Job.Loc = Loc.Loc
					WHERE  Loc.Owner = ?
						   AND (Job.Type = 9
							 OR Job.Type = 12)
				;",array($_GET['ID']));
				echo $r ? sqlsrv_fetch_array($r)['Count_of_Lawsuits'] : 0;?>
			</div>
		</div>
		<?php if(isset($My_Privileges['Finances']) && $My_Privileges['Finances']['User_Privilege'] >= 4) {?>
		<div class='row shadower' style='padding-top:10px;padding-bottom:10px;'>
            <div class='col-xs-4'><?php $Icons->Payroll(1);?> Balance</div>
            <div class='col-xs-8'><?php
				$r = sqlsrv_query($NEI,"
					SELECT Sum(OpenAR.Balance) AS Balance
					FROM   nei.dbo.OpenAR
						   LEFT JOIN nei.dbo.Loc ON OpenAR.Loc = Loc.Loc
					WHERE  Loc.Owner = ?
				;",array($_GET['ID']));
				$Balance = $r ? sqlsrv_fetch_array($r)['Balance'] : 0;
				echo money_format('%(n',$Balance);
			?></div>
		</div> <?php } ?>
	</div>
</div>

<?php
    }
} else {?><html><head><script>document.location.href="../login.php?Forward=location<?php echo (!isset($_GET['ID']) || !is_numeric($_GET['ID'])) ? "s.php" : ".php?ID={$_GET['ID']}";?>";</script></head></html><?php }?>
