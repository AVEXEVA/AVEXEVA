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
				Violation.Remarks     AS Violation_Remarks
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
            WHERE
                Job.ID = ?
        ;",array($_GET['ID']));
        $Job = sqlsrv_fetch_array($r);?>
	<script src="cgi-bin/js/function/formatTicket.js"></script>
						<div class='col-md-12' >
							<div class="panel panel-primary">
								<div class="panel-body ">
									<table id='Table_Worker_Feed' class='display' cellspacing='0' width='100%' style="font-size:12px">
										<thead><tr>
											<th></th>
											<th>Created</th>
											<th>Scheduled</th>
											<th>Mechanic</th>
											<th>Unit</th>
											<th>Details</th>
											<th>Status</th>
										</tr></thead>
									</table>
									<script>
									var Table_Worker_Feed = $('#Table_Worker_Feed').DataTable( {
										"ajax": {
												"url": "cgi-bin/php/reports/Worker_Feed_by_Job.php?ID=<?php echo $_GET['ID'];?>",
												"dataSrc":function(json){if(!json.data){json.data = [];}return json.data;}
										},
										"columns": [
											{
												"className":      'details-control',
												"orderable":      false,
												"data":           null,
												"defaultContent": ''
											},{
												"data" : "Created",
						 						render: function(data){return data.substr(5,2) + "/" + data.substr(8,2) + "/" + data.substr(0,4);}
											},{
												"data" : "Scheduled",
						 						render: function(data){return data.substr(5,2) + "/" + data.substr(8,2) + "/" + data.substr(0,4);}
											},{
												"data" : "Mechanic"
											},{
												"data" : "Unit"
											},{
												"data" : "Description"
											},{
												"data" : "Status"
											}
										],
										"lengthMenu":[[10,25,50,100,500,-1],[10,25,50,100,500,"All"]],
										"order": [[1, 'asc']],
										"language":{"loadingRecords":""},
										"initComplete":function(){finishLoadingPage();},
										"paging":false,
										"scrollY" : "300px",
										"scrollCollapse":true

									} );
									<?php if(!isMobile()){?>$('#Table_Worker_Feed tbody').on('click', 'td.details-control', function () {
										var tr = $(this).closest('tr');
										var row = Table_Worker_Feed.row( tr );

										if ( row.child.isShown() ) {
											row.child.hide();
											tr.removeClass('shown');
										}
										else {
											row.child( formatTicket(row.data()) ).show();
											tr.addClass('shown');
										}
									} );<?php } else {?>
									 $('#Table_Worker_Feed tbody').on('click', 'td', function () {
										var tr = $(this).closest('tr');
										var row = Table_Worker_Feed.row( tr );

										if ( row.child.isShown() ) {
											row.child.hide();
											tr.removeClass('shown');
										}
										else {
											row.child( formatTicket(row.data()) ).show();
											tr.addClass('shown');
										}
									} );
									<?php }?>
									</script>
								</div>
							</div>
						</div>

<?php
    }
} else {?><html><head><script>document.location.href="../login.php?Forward=location<?php echo (!isset($_GET['ID']) || !is_numeric($_GET['ID'])) ? "s.php" : ".php?ID={$_GET['ID']}";?>";</script></head></html><?php }?>
