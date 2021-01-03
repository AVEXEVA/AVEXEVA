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
        if(isset($My_Privileges['Job']) && $My_Privileges['Job']['User_Privilege'] >= 4 && $My_Privileges['Job']['Group_Privilege'] >= 4 && $My_Privileges['Job']['Other_Privilege'] >= 4){$Privileged = TRUE;}
        elseif($My_Privileges['Job']['User_Privilege'] >= 4 && is_numeric($_GET['ID'])){
			$a = sqlsrv_query($NEI,"
				SELECT Job.Loc
				FROM nei.dbo.Job
				WHERE Job.ID = ?
			;",array($_GET['ID']));
			$loc = sqlsrv_fetch_array($a)['Loc'];
            $r = sqlsrv_query(  $NEI,"
				SELECT *
				FROM 		nei.dbo.Job
				LEFT JOIN 	nei.dbo.TicketO ON Job.ID = TicketO.Job
				WHERE 		TicketO.LID= ?
					AND 	TicketO.fWork= ?
			;",array($loc,$My_User['fWork']));
            $r2 = sqlsrv_query( $NEI,"
				SELECT *
				FROM 		nei.dbo.Job
				LEFT JOIN 	nei.dbo.TicketD ON Job.ID = TicketD.Job
				WHERE 		TicketD.Loc= ?
							AND TicketD.fWork= ? 
			;",array($loc,$My_User['fWork']));
			$r3 = sqlsrv_query( $NEI,"
				SELECT *
				FROM 		nei.dbo.Job
				LEFT JOIN 	nei.dbo.TicketDArchive ON Job.ID = TicketDArchive.Loc
				WHERE 		TicketDArchive.Loc= ?
							AND TicketDArchive.fWork= ?
			;",array($loc,$My_User['fWork']));
            $r = sqlsrv_fetch_array($r);
            $r2 = sqlsrv_fetch_array($r2);
			$r3 = sqlsrv_fetch_array($r3);
            $Privileged = (is_array($r) || is_array($r2) || is_array($r3)) ? TRUE : FALSE;
		}
    }
    //
    if(!isset($array['ID'])  || !is_numeric($_GET['ID']) || !$Privileged ){require("401.html");}
    else {
       $r = sqlsrv_query($NEI,"
			SELECT TOP 1
                Job.ID                AS Job_ID,
                Job.fDesc             AS Job_Name,
                Job.fDate             AS Job_Start_Date,
                Job.BHour             AS Job_Budgeted_Hours,
                JobType.Type          AS Job_Type,
				Job.Remarks 		  AS Job_Remarks,
                Loc.Loc               AS Location_ID,
                Loc.ID                AS Location_Name,
                Loc.Tag               AS Location_Tag,
                Loc.Address           AS Location_Street,
                Loc.City              AS Location_City,
                Loc.State             AS Location_State,
                Loc.Zip               AS Location_Zip,
                Loc.Route             AS Route,
                Zone.Name             AS Division,
                OwnerWithRol.ID       AS Customer_ID,
                OwnerWithRol.Name     AS Customer_Name,
                OwnerWithRol.Status   AS Customer_Status,
                OwnerWithRol.Elevs    AS Customer_Elevators,
                OwnerWithRol.Address  AS Customer_Street,
                OwnerWithRol.City     AS Customer_City,
                OwnerWithRol.State    AS Customer_State,
                OwnerWithRol.Zip      AS Customer_Zip,
                OwnerWithRol.Contact  AS Customer_Contact,
                OwnerWithRol.Remarks  AS Customer_Remarks,
                OwnerWithRol.Email    AS Customer_Email,
                OwnerWithRol.Cellular AS Customer_Cellular,
                Elev.ID               AS Unit_ID,
                Elev.Unit             AS Unit_Label,
                Elev.State            AS Unit_State,
                Elev.Cat              AS Unit_Category,
                Elev.Type             AS Unit_Type,
                Emp.fFirst            AS Mechanic_First_Name,
                Emp.Last              AS Mechanic_Last_Name,
                Route.ID              AS Route_ID,
				Violation.ID          AS Violation_ID,
				Violation.fdate       AS Violation_Date,
				Violation.Status      AS Violation_Status,
				Violation.Remarks     AS Violation_Remarks,
				Loc.Route            AS Route_ID,
				Emp.ID               AS Route_Mechanic_ID,
				Emp.fFirst           AS Route_Mechanic_First_Name,
				Emp.Last             AS Route_Mechanic_Last_Name,
				Rol.Phone            AS Route_Mechanic_Phone_Number,
				Portal.Email         AS Route_Mechanic_Email
            FROM 
                Job 
                LEFT JOIN nei.dbo.Loc           ON Job.Loc      = Loc.Loc
                LEFT JOIN nei.dbo.Zone          ON Loc.Zone     = Zone.ID
                LEFT JOIN nei.dbo.JobType       ON Job.Type     = JobType.ID
                LEFT JOIN nei.dbo.OwnerWithRol  ON Job.Owner    = OwnerWithRol.ID
                LEFT JOIN nei.dbo.Elev          ON Job.Elev     = Elev.ID
                LEFT JOIN nei.dbo.Route         ON Loc.Route    = Route.ID
                LEFT JOIN nei.dbo.Emp           ON Emp.fWork    = Route.Mech
				LEFT JOIN nei.dbo.Violation     ON Job.ID       = Violation.Job
				LEFT JOIN Portal.dbo.Portal     ON Emp.ID       = Portal.Branch_ID AND Portal.Branch = 'Nouveau Elevator'
				LEFT JOIN nei.dbo.Rol          ON Emp.Rol    = Rol.ID 
            WHERE
                Job.ID = ?
        ;",array($_GET['ID']));
        $Job = sqlsrv_fetch_array($r);?>
<div class="panel panel-primary">
    <!--<div class="panel-heading BankGothic"><h3>Customer Information</h3></div>-->
    <div class='panel-body'>			
		<div class='row shadower' style='border-bottom:3px;padding-top:10px;padding-bottom:10px;'>
			<div class='col-xs-4'><?php $Icons->Job(1);?> Job ID</div>
			<div class='col-xs-8'><?php echo $Job['Job_ID'];?></div>
			<div class='col-xs-4'><?php $Icons->Blank(1);?> Name</div>
			<div class='col-xs-8'><?php echo $Job['Job_Name'];?></div>
			<div class='col-xs-4'><?php $Icons->Blank(1);?> Start Date</div>
			<div class='col-xs-8'><?php echo date("m/d/Y",strtotime($Job['Job_Start_Date']));?></div>
			<div class='col-xs-4'><?php $Icons->Blank(1);?> B. Hours</div>
			<div class='col-xs-8'><?php echo strlen($Job['Job_Budgeted_Hours']) > 0 ? $Job['Job_Budgeted_Hours'] : "&nbsp;";?></div>
			<div class='col-xs-4'><?php $Icons->Blank(1);?> Type</div>
			<div class='col-xs-8'><?php echo $Job['Job_Type'];?></div>
			<div class='col-xs-4'><?php $Icons->Blank(1);?> Notes</div>
			<div class='col-xs-8'><pre><?php echo $Job['Job_Remarks'];?></pre></div>
		</div>
		<div class='row shadower' style='border-bottom:3px ;padding-top:10px;padding-bottom:10px;'>
			<div class='col-xs-4'><?php $Icons->Location(1);?> Location:</div>
			<div class='col-xs-8'><?php echo $Job['Location_Name'];?></div>
			<div class='col-xs-4'><?php $Icons->Blank(1);?> Street:</div>
			<div class='col-xs-8'><?php echo $Job['Location_Street'];?></div>
			<div class='col-xs-4'><?php $Icons->Blank(1);?> City:</div>
			<div class='col-xs-8'><?php echo $Job['Location_City'];?></div>
			<div class='col-xs-4'><?php $Icons->Blank(1);?> State:</div>
			<div class='col-xs-8'><?php echo $Job['Location_State'];?></div>
			<div class='col-xs-4'><?php $Icons->Blank(1);?> Zip:</div>
			<div class='col-xs-8'><?php echo $Job['Location_Zip'];?></div>
		</div>
		<div class='row shadower' style='border-bottom:3px ;padding-top:10px;padding-bottom:10px;'>
			<div class='col-xs-4'><?php $Icons->Customer(1);?> Customer </div>
			<div class='col-xs-8'><?php echo $Job['Customer_Street'];?></div>
			<div class='col-xs-4'><?php $Icons->Blank(1);?> Address</div>
			<div class='col-xs-8'><?php echo $Job['Customer_Name'];?></div>
			<div class='col-xs-4'><?php $Icons->Blank(1);?> City</div>
			<div class='col-xs-8'><?php echo $Job['Customer_City'];?></div>
			<div class='col-xs-4'><?php $Icons->Blank(1);?> State</div>
			<div class='col-xs-8'><?php echo $Job['Customer_State'];?></div>
			<div class='col-xs-4'><?php $Icons->Blank(1);?> Zip</div>
			<div class='col-xs-8'><?php echo $Job['Customer_Zip'];?></div>
			<div class='col-xs-4'><?php $Icons->Blank(1);?> Status</div>
			<div class='col-xs-8'><?php echo $Job['Customer_Status'] == 0 ? "Active" : "Unactive";?></div>
			<?php if(isset($Job['Customer_Website']) && strlen($Job['Customer_Website']) > 0){?><div class='col-xs-4'><?php $Icons->Customer(1);?> Website</div>
			<div class='col-xs-8'><?php echo $Job['Customer_Website'];?></div><?php }?>
		</div>
    </div>
</div>

<?php
    }
} else {?><html><head><script>document.location.href="../login.php?Forward=location<?php echo (!isset($_GET['ID']) || !is_numeric($_GET['ID'])) ? "s.php" : ".php?ID={$_GET['ID']}";?>";</script></head></html><?php }?>