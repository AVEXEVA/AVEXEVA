<?php
session_start();
require('../../../php/index.php');
if(isset($_SESSION['User'],$_SESSION['Hash'])){
    $r = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.Connection WHERE Connector = ? AND Hash = ?;",array($_SESSION['User'],$_SESSION['Hash']));
    $array = sqlsrv_fetch_array($r);
    if(!isset($_SESSION['Branch']) || $_SESSION['Branch'] == 'Nouveau Elevator'){
        sqlsrv_query($Portal,"INSERT INTO Activity([User], [Date], [Page]) VALUES(?,?,?);",array($_SESSION['User'],date("Y-m-d H:i:s"), "unit.php"));
        $r= sqlsrv_query($NEI,"SELECT *, fFirst AS First_Name, Last as Last_Name FROM Emp WHERE ID= ?",array($_SESSION['User']));
        $My_User = sqlsrv_fetch_array($r);
        $Field = ($My_User['Field'] == 1 && $My_User['Title'] != "OFFICE") ? True : False;
        $r = sqlsrv_query($Portal,"
            SELECT Access_Table, User_Privilege, Group_Privilege, Other_Privilege
            FROM   Portal.dbo.Privilege
            WHERE  User_ID = ?
        ;",array($_SESSION['User']));
        $My_Privileges = array();
        while($My_Privilege = sqlsrv_fetch_array($r)){$My_Privileges[$My_Privilege['Access_Table']] = $My_Privilege;}
        $Privileged = FALSE;
        if(isset($My_Privileges['Unit']) && $My_Privileges['Unit']['User_Privilege'] >= 4 && $My_Privileges['Unit']['Group_Privilege'] >= 4 && $My_Privileges['Unit']['Other_Privilege'] >= 4){$Privileged = TRUE;}
        elseif(isset($My_Privileges['Unit']) && $My_Privileges['Unit']['User_Privilege'] >= 4 && $My_Privileges['Unit']['Group_Privilege'] >= 4){
			$r = sqlsrv_query($NEI,"
				SELECT Elev.Loc AS Location_ID
				FROM   nei.dbo.Elev
				WHERE  Elev.ID = ?
			;",array($_GET['ID'] ));
			$Location_ID = sqlsrv_fetch_array($r)['Location_ID'];
            $r = sqlsrv_query($NEI,"
			SELECT Tickets.*
			FROM 
			(
				(
					SELECT TicketO.ID
					FROM   nei.dbo.TicketO 
						   LEFT JOIN nei.dbo.Loc  ON TicketO.LID   = Loc.Loc
						   LEFT JOIN nei.dbo.Elev ON Loc.Loc       = Elev.Loc
						   LEFT JOIN nei.dbo.Emp  ON TicketO.fWork = Emp.fWork
					WHERE  Emp.ID      = ?
						   AND Loc.Loc = ?
				)
				UNION ALL
				(
					SELECT TicketD.ID
					FROM   nei.dbo.TicketD 
						   LEFT JOIN nei.dbo.Loc  ON TicketD.Loc   = Loc.Loc
						   LEFT JOIN nei.dbo.Elev ON Loc.Loc       = Elev.Loc
						   LEFT JOIN nei.dbo.Emp  ON TicketD.fWork = Emp.fWork
					WHERE  Emp.ID      = ?
						   AND Loc.Loc = ?
				)
				UNION ALL
				(
					SELECT TicketDArchive.ID
					FROM   nei.dbo.TicketDArchive
						   LEFT JOIN nei.dbo.Loc  ON TicketDArchive.Loc   = Loc.Loc
						   LEFT JOIN nei.dbo.Elev ON Loc.Loc              = Elev.Loc
						   LEFT JOIN nei.dbo.Emp  ON TicketDArchive.fWork = Emp.fWork
					WHERE  Emp.ID      = ?
						   AND Loc.Loc = ?
				)
			) AS Tickets
           	;", array($_SESSION['User'], $Location_ID, $_SESSION['User'], $Location_ID, $_SESSION['User'], $Location_ID));
            $r = sqlsrv_fetch_array($r);
            $Privileged = is_array($r) ? TRUE : FALSE;
        }
    }
    if(!isset($array['ID'])  || !$Privileged || !is_numeric($_GET['ID'])){?><html><head><script>document.location.href="../login.php?Forward=unit<?php echo (!isset($_GET['ID']) || !is_numeric($_GET['ID'])) ? "s.php" : ".php?ID={$_GET['ID']}";?>";</script></head></html><?php }
    else {
        if(count($_POST) > 0){
            fixArrayKey($_POST);
            foreach($_POST as $key=>$value){
				if($key == 'Price'){continue;}
				if($key == 'Type'){continue;}
                sqlsrv_query($NEI,"
                    UPDATE ElevTItem
                    SET    ElevTItem.Value     = ?
                    WHERE  ElevTItem.Elev      = ?
                           AND ElevTItem.ElevT = 1
                           AND ElevTItem.fDesc = ?
                ;",array($value,$_GET['ID'],$key));
            }
			if(isset($_POST['Price'])){
				sqlsrv_query($NEI,"
					UPDATE Elev
					SET    Elev.Price = ?
					WHERE  Elev.ID    = ?
				;",array($_POST['Price'],$_GET['ID']));
			}
			if(isset($_POST['Type'])){
				sqlsrv_query($NEI,"
					UPDATE Elev
					SET    Elev.Type = ?
					WHERE  Elev.ID    = ?
				;",array($_POST['Type'],$_GET['ID']));
			}
        }
        $r = sqlsrv_query($NEI,
            "SELECT TOP 1
                Elev.ID,
                Elev.Unit           AS Unit,
                Elev.State          AS State,
                Elev.Cat            AS Category,
                Elev.Type           AS Type,
                Elev.Building       AS Building,
                Elev.Since          AS Since,
                Elev.Last           AS Last,
                Elev.Price          AS Price,
                Elev.fDesc          AS Description,
                Loc.Loc             AS Location_ID,
                Loc.ID              AS Name,
                Loc.Tag             AS Tag,
                Loc.Tag             AS Location_Tag,
                Loc.Address         AS Street,
                Loc.City            AS City,
                Loc.State           AS Location_State,
                Loc.Zip             AS Zip,
                Loc.Route           AS Route,
                Zone.Name           AS Zone,
                OwnerWithRol.Name   AS Customer_Name,
                OwnerWithRol.ID     AS Customer_ID,
                Emp.ID AS Route_Mechanic_ID,
                Emp.fFirst AS Route_Mechanic_First_Name,
                Emp.Last AS Route_Mechanic_Last_Name
            FROM 
                Elev
                LEFT JOIN nei.dbo.Loc           ON Elev.Loc = Loc.Loc
                LEFT JOIN nei.dbo.Zone          ON Loc.Zone = Zone.ID
                LEFT JOIN nei.dbo.OwnerWithRol  ON Loc.Owner = OwnerWithRol.ID
                LEFT JOIN nei.dbo.Route ON Loc.Route = Route.ID
                LEFT JOIN nei.dbo.Emp ON Route.Mech = Emp.fWork
            WHERE
                Elev.ID = ?
		;",array($_GET['ID']));
        $Unit = sqlsrv_fetch_array($r);
        $unit = $Unit;
        $data = $Unit;
        $r2 = sqlsrv_query($NEI,"
            SELECT *
            FROM   ElevTItem
            WHERE  ElevTItem.ElevT    = 1
                   AND ElevTItem.Elev = ?
        ;",array($_GET['ID']));
        if($r2){while($array = sqlsrv_fetch_array($r2)){$Unit[$array['fDesc']] = $array['Value'];}}?>
	<div class="panel panel-primary">
		<div class="panel-heading"><h4>Job Count</h4></div>
				<div class='panel-body'>
					<div class='row' style='border-bottom:3px dashed black;padding-top:10px;padding-bottom:10px;'>
						<div class='col-xs-4'><?php $Icons->Unit(1);?> Maintenance </div>
						<div class='col-xs-8'><?php
						$Year = date("Y-01-01 00:00:00.000");
						$r = sqlsrv_query($NEI,"
							SELECT Count(Job.ID) AS Jobs
							FROM   nei.dbo.Job 
							WHERE  Job.Loc = ?
								   AND Job.Type = 0
						;",array($_GET['ID']));
						echo $r ? sqlsrv_fetch_array($r)['Jobs'] : 0;?> 
						</div>
						<div class='col-xs-4'><?php $Icons->Unit(1);?> Modernization</div>
						<div class='col-xs-8'><?php
						$Year = date("Y-01-01 00:00:00.000");
						$r = sqlsrv_query($NEI,"
							SELECT Count(Job.ID) AS Jobs
							FROM   nei.dbo.Job 
							WHERE  Job.Loc = ?
								   AND Job.Type = 2
						;",array($_GET['ID']));
						echo $r ? sqlsrv_fetch_array($r)['Jobs'] : 0;?> 
						</div>
						<div class='col-xs-4'><?php $Icons->Unit(1);?> Repair  </div>
						<div class='col-xs-8'><?php
						$Year = date("Y-01-01 00:00:00.000");
						$r = sqlsrv_query($NEI,"
							SELECT Count(Job.ID) AS Jobs
							FROM   nei.dbo.Job 
							WHERE  Job.Loc = ?
								   AND Job.Type = 6
						;",array($_GET['ID']));
						echo $r ? sqlsrv_fetch_array($r)['Jobs'] : 0;?> 
						</div>
						<div class='col-xs-4'><?php $Icons->Unit(1);?> Testing </div>
						<div class='col-xs-8'><?php
						$Year = date("Y-01-01 00:00:00.000");
						$r = sqlsrv_query($NEI,"
							SELECT Count(Job.ID) AS Jobs
							FROM   nei.dbo.Job 
							WHERE  Job.Loc = ?
								   AND Job.Type = 8
						;",array($_GET['ID']));
						echo $r ? sqlsrv_fetch_array($r)['Jobs'] : 0;?>
						</div>
						<div class='col-xs-4'><?php $Icons->Unit(1);?> Violation  </div>
						<div class='col-xs-8'> <?php
						$Year = date("Y-01-01 00:00:00.000");
						$r = sqlsrv_query($NEI,"
							SELECT Count(Job.ID) AS Jobs
							FROM   nei.dbo.Job 
							WHERE  Job.Loc = ?
								   AND Job.Type = 19
						;",array($_GET['ID']));
						echo $r ? sqlsrv_fetch_array($r)['Jobs'] : 0;?>
						</div>
						<div class='col-xs-4'><?php $Icons->Unit(1);?> Other  </div>
						<div class='col-xs-8'><?php
						$Year = date("Y-01-01 00:00:00.000");
						$r = sqlsrv_query($NEI,"
							SELECT Count(Job.ID) AS Jobs
							FROM   nei.dbo.Job 
							WHERE  Job.Loc = ?
								   AND Job.Type = 4
						;",array($_GET['ID']));
						echo $r ? sqlsrv_fetch_array($r)['Jobs'] : 0;?> 
						</div>
					</div>
				</div>
</div>
	<div class='col-md-12' style=''>
	<div class="panel panel-primary">
	<div class="panel-heading"><h4> Jobs</h4></div>
	<div class='panel-body white-background shadow'>
	<table id='Table_Jobs' class='display' cellspacing='0' width='100%'>
		<thead>
			<th>ID</th>
			<th>Name</th>
			<th>Type</th>
			<th>Status</th>
		</thead>
	</table>	
	</div>
	</div>
	<script>
	var Table_Jobs = $('#Table_Jobs').DataTable( {
	"ajax": "cgi-bin/php/get/Jobs_by_Unit.php?ID=<?php echo $_GET['ID'];?>",
	"columns": [
	{ "data": "ID"},
	{ "data": "Name" },
	{ "data": "Type"},
	{ "data": "Status"}
	],
	"order": [[3, 'dsc']],
	"language":{
	"loadingRecords":""
	},
	"initComplete":function(){
	}
	} );
	function hrefJobs(){hrefRow("Table_Jobs","job");}
	$("Table#Table_Jobs").on("draw.dt",function(){hrefJobs();});
	</script>
	</div>
	</div>
<?php
    }
} else {?><html><head><script>document.location.href="../login.php?Forward=location<?php echo (!isset($_GET['ID']) || !is_numeric($_GET['ID'])) ? "s.php" : ".php?ID={$_GET['ID']}";?>";</script></head></html><?php }?>