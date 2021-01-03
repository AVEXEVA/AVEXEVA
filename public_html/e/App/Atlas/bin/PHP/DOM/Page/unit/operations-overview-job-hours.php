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
        $data = $Unit;
        $r2 = sqlsrv_query($NEI,"
            SELECT *
            FROM   ElevTItem
            WHERE  ElevTItem.ElevT    = 1
                   AND ElevTItem.Elev = ?
        ;",array($_GET['ID']));
        if($r2){while($array = sqlsrv_fetch_array($r2)){$Unit[$array['fDesc']] = $array['Value'];}}
?>
<style>
table#Location_Hours tbody tr td, table#Location_Hours thead tr th {
	border:1px solid black;
	padding:3px;
}
</style>
<table width="100%" class="" id="Location_Hours">
<thead>
	<tr>
		<th>Weeks</th>
		<th>Thu</th>
		<th>Fri</th>
		<th>Sat</th>
		<th>Sun</th>
		<th>Mon</th>
		<th>Tue</th>
		<th>Wed</th>
		<th>Total</th>
	</tr>
</thead>
<style>
.hoverGray:hover {
	background-color:#dfdfdf !important;
}
</style>
<tbody>
	<tr><td colspan='9'><?php require('../../../php/element/loading-active.php');?></td></tr>
	<tr style='cursor:pointer;' class="odd gradeX hoverGray">
		<?php $Today = date('l');
		$Date = date('Y-m-d');
		if($Today == 'Thursday'){$WeekOf = date('Y-m-d');}
		elseif($Today == 'Friday'){$WeekOf = date('Y-m-d', strtotime($Date . ' -1 days'));}
		elseif($Today == 'Saturday'){$WeekOf = date('Y-m-d', strtotime($Date . ' -2 days'));}
		elseif($Today == 'Sunday'){$WeekOf = date('Y-m-d', strtotime($Date . ' -3 days'));}
		elseif($Today == 'Monday'){$WeekOf = date('Y-m-d', strtotime($Date . ' -4 days'));}
		elseif($Today == 'Tuesday'){$WeekOf = date('Y-m-d', strtotime($Date . ' -5 days'));}
		elseif($Today == 'Wednesday'){$WeekOf = date('Y-m-d', strtotime($Date . ' -6 days'));}
		$WeekOf = date('Y-m-d',strtotime($WeekOf . ' +6 days'));?>
		<td class='WeekOf' rel='<?php echo $WeekOf;?>' onClick="refresh_this(this);"><?php
			echo $WeekOf;
		?></td><?php 
		$Today = date('l');
		if($Today == 'Thursday'){$Thursday = date('Y-m-d');}
		elseif($Today == 'Friday'){$Thursday = date('Y-m-d', strtotime($Date . ' -1 days'));}
		elseif($Today == 'Saturday'){$Thursday = date('Y-m-d', strtotime($Date . ' -2 days'));}
		elseif($Today == 'Sunday'){$Thursday = date('Y-m-d', strtotime($Date . ' -3 days'));}
		elseif($Today == 'Monday'){$Thursday = date('Y-m-d', strtotime($Date . ' -4 days'));}
		elseif($Today == 'Tuesday'){$Thursday = date('Y-m-d', strtotime($Date . ' -5 days'));}
		elseif($Today == 'Wednesday'){$Thursday = date('Y-m-d', strtotime($Date . ' -6 days'));}
		$r = sqlsrv_query($NEI,"
			SELECT Sum(Total) as Summed 
			FROM nei.dbo.TicketD LEFT JOIN nei.dbo.Job ON TicketD.Job = Job.ID 
			WHERE TicketD.Elev='" . $_GET['ID'] . "' and EDate >= '" . $Thursday . " 00:00:00.000' AND EDate <= '" . $Thursday . " 23:59:59.999'");?>
		<td class='Thursday' rel='<?php echo $Thursday;?>' onClick="refresh_this(this);"><?php
			echo sqlsrv_fetch_array($r)['Summed'];
		?></td>
		<?php 
		if($Today == 'Friday'){$Friday = date('Y-m-d');}
		elseif($Today == 'Saturday'){$Friday = date('Y-m-d', strtotime($Date . ' -1 days'));}
		elseif($Today == 'Sunday'){$Friday = date('Y-m-d', strtotime($Date . ' -2 days'));}
		elseif($Today == 'Monday'){$Friday = date('Y-m-d', strtotime($Date . ' -3 days'));}
		elseif($Today == 'Tuesday'){$Friday = date('Y-m-d', strtotime($Date . ' -4 days'));}
		elseif($Today == 'Wednesday'){$Friday = date('Y-m-d', strtotime($Date . ' -5 days'));}
		elseif($Today == 'Thursday'){$Friday = date('Y-m-d', strtotime($Date . ' +1 days'));}
		$r = sqlsrv_query($NEI,"
			SELECT Sum(Total) AS Summed 
			FROM nei.dbo.TicketD
			WHERE TicketD.Elev='" . $_GET['ID'] . "' and EDate >= '" . $Friday . " 00:00:00.000' AND EDate <= '" . $Friday . " 23:59:59.999'");?>
		<td class='Friday' rel='<?php echo $Friday;?>' onClick="refresh_this(this);"><?php
			echo sqlsrv_fetch_array($r)['Summed'];
		?></td>
		<?php
		if($Today == 'Satuday'){$Saturday = date('Y-m-d');}
		elseif($Today == 'Sunday'){$Saturday = date('Y-m-d', strtotime($Date . ' -1 days'));}
		elseif($Today == 'Monday'){$Saturday = date('Y-m-d', strtotime($Date . ' -2 days'));}
		elseif($Today == 'Tuesday'){$Saturday = date('Y-m-d', strtotime($Date . ' -3 days'));}
		elseif($Today == 'Wednesday'){$Saturday = date('Y-m-d', strtotime($Date . ' -4 days'));}
		elseif($Today == 'Thursday'){$Saturday = date('Y-m-d', strtotime($Date . ' +2 days'));}
		elseif($Today == 'Friday'){$Saturday = date('Y-m-d', strtotime($Date . ' +1 days'));}
		$r = sqlsrv_query($NEI,"
			SELECT Sum(Total) AS Summed
			FROM nei.dbo.TicketD
			WHERE TicketD.Elev='" . $_GET['ID'] . "' and EDate >= '" . $Saturday . " 00:00:00.000' AND EDate <= '" . $Saturday . " 23:59:59.999'");?>
		<td class='Saturday' rel='<?php echo $Saturday;?>' onClick="refresh_this(this);"><?php
			echo sqlsrv_fetch_array($r)['Summed'];
		?></td>
		<?php 
		if($Today == 'Sunday'){$Sunday = date('Y-m-d');}
		elseif($Today == 'Monday'){$Sunday = date('Y-m-d', strtotime($Date . ' -1 days'));}
		elseif($Today == 'Tuesday'){$Sunday = date('Y-m-d', strtotime($Date . ' -2 days'));}
		elseif($Today == 'Wednesday'){$Sunday = date('Y-m-d', strtotime($Date . ' -3 days'));}
		elseif($Today == 'Thursday'){$Sunday = date('Y-m-d', strtotime($Date . ' +3 days'));}
		elseif($Today == 'Friday'){$Sunday = date('Y-m-d', strtotime($Date . ' +2 days'));}
		elseif($Today == 'Saturday'){$Sunday = date('Y-m-d', strtotime($Date . ' +1 days'));}
		$r = sqlsrv_query($NEI,"
			SELECT Sum(Total) AS Summed 
			FROM nei.dbo.TicketD LEFT JOIN nei.dbo.Job ON TicketD.Job = Job.ID 
			WHERE TicketD.Elev='" . $_GET['ID'] . "' and EDate >= '" . $Sunday . " 00:00:00.000' AND EDate <= '" . $Sunday . " 23:59:59.999'");?>
		<td class='Sunday' rel='<?php echo $Sunday;?>' onClick="refresh_this(this);"><?php
			echo sqlsrv_fetch_array($r)['Summed'];
		?></td>
		<?php
		if($Today == 'Monday'){$Monday = date('Y-m-d');}
		elseif($Today == 'Tuesday'){$Monday = date('Y-m-d', strtotime($Date . ' -1 days'));}
		elseif($Today == 'Wednesday'){$Monday = date('Y-m-d', strtotime($Date . ' -2 days'));}
		elseif($Today == 'Thursday'){$Monday = date('Y-m-d', strtotime($Date . ' +4 days'));}
		elseif($Today == 'Friday'){$Monday = date('Y-m-d', strtotime($Date . ' +3 days'));}
		elseif($Today == 'Saturday'){$Monday = date('Y-m-d', strtotime($Date . ' +2 days'));}
		elseif($Today == 'Sunday'){$Monday = date('Y-m-d', strtotime($Date . ' +1 days'));}
		$r = sqlsrv_query($NEI,"
			SELECT Sum(Total) AS Summed
			FROM nei.dbo.TicketD
			WHERE TicketD.Elev='" . $_GET['ID'] . "' and EDate >= '" . $Monday . " 00:00:00.000' AND EDate <= '" . $Monday . " 23:59:59.999'");?>
		<td class='Monday' rel='<?php echo $Monday;?>' onClick="refresh_this(this);"><?php
			echo sqlsrv_fetch_array($r)['Summed'];
		?></td>
		<?php 
		if($Today == 'Tuesday'){$Tuesday = date('Y-m-d');}
		elseif($Today == 'Wednesday'){$Tuesday = date('Y-m-d', strtotime($Date . ' -1 days'));}
		elseif($Today == 'Thursday'){$Tuesday = date('Y-m-d', strtotime($Date . ' +5 days'));}
		elseif($Today == 'Friday'){$Tuesday = date('Y-m-d', strtotime($Date . ' +4 days'));}
		elseif($Today == 'Saturday'){$Tuesday = date('Y-m-d', strtotime($Date . ' +3 days'));}
		elseif($Today == 'Sunday'){$Tuesday = date('Y-m-d', strtotime($Date . ' +2 days'));}
		elseif($Today == 'Monday'){$Tuesday = date('Y-m-d', strtotime($Date . ' +1 days'));}
		$r = sqlsrv_query($NEI,"
			SELECT Sum(Total) AS Summed
			FROM nei.dbo.TicketD
			WHERE TicketD.Elev='" . $_GET['ID'] . "' and EDate >= '" . $Tuesday . " 00:00:00.000' AND EDate <= '" . $Tuesday . " 23:59:59.999'");?>
		<td class='Tuesday' rel='<?php echo $Tuesday;?>' onClick="refresh_this(this);"><?php
			echo sqlsrv_fetch_array($r)['Summed'];
		?></td>
		<?php 
		if($Today == 'Wednesday'){$Wednesday = date('Y-m-d');}	
		elseif($Today == 'Thursday'){$Wednesday = date('Y-m-d', strtotime($Date . ' +6 days'));}
		elseif($Today == 'Friday'){$Wednesday = date('Y-m-d', strtotime($Date . ' +5 days'));}
		elseif($Today == 'Saturday'){$Wednesday = date('Y-m-d', strtotime($Date . ' +4 days'));}
		elseif($Today == 'Sunday'){$Wednesday = date('Y-m-d', strtotime($Date . ' +3 days'));}
		elseif($Today == 'Monday'){$Wednesday = date('Y-m-d', strtotime($Date . ' +2 days'));}
		elseif($Today == 'Tuesday'){$Wednesday = date('Y-m-d', strtotime($Date . ' +1 days'));}
		$r = sqlsrv_query($NEI,"
			SELECT Sum(Total) AS Summed
			FROM nei.dbo.TicketD LEFT JOIN nei.dbo.Job ON TicketD.Job = Job.ID
			WHERE TicketD.Elev='" . $_GET['ID'] . "' and EDate >= '" . $Wednesday . " 00:00:00.000' AND EDate <= '" . $Wednesday . " 23:59:59.999'");?>
		<td class='Wednesday' rel='<?php echo $Wednesday;?>' onClick="refresh_this(this);"><?php
			echo sqlsrv_fetch_array($r)['Summed'];
		?></td>
		<td><?php
			$r = sqlsrv_query($NEI,"SELECT Sum(Total) AS Summed FROM nei.dbo.TicketD LEFT JOIN nei.dbo.Job ON TicketD.Job = Job.ID WHERE TicketD.Elev='" . $_GET['ID'] . "' and EDate >= '" . $Thursday . " 00:00:00.000' AND EDate <= '" . $Wednesday . " 23:59:59.999'");
			echo sqlsrv_fetch_array($r)['Summed'];
		?></td>
	</tr>
	<?php while($WeekOf > "2017-03-08 00:00:00.000"){?><tr style='cursor:pointer;' class="odd gradeX hoverGray">
		<?php $WeekOf = date('Y-m-d',strtotime($WeekOf . '-7 days')); ?>
		<td class='WeekOf' rel='<?php echo $WeekOf;?>' onClick="refresh_this(this);"><?php 
			echo $WeekOf;
		?></td>
		<?php 
		$Thursday = date('Y-m-d',strtotime($Thursday . '-7 days'));
			$r = sqlsrv_query($NEI,"SELECT Sum(Total) AS Summed FROM nei.dbo.TicketD LEFT JOIN nei.dbo.Job ON TicketD.Job = Job.ID WHERE TicketD.Elev='" . $_GET['ID'] . "' and EDate >= '" . $Thursday . " 00:00:00.000' AND EDate <= '" . $Thursday . " 23:59:59.999'");?>
		<td class='Thursday' rel='<?php echo $Thursday;?>' onClick="refresh_this(this);"><?php 
			echo sqlsrv_fetch_array($r)['Summed'];
		?></td>
		<?php $Friday = date('Y-m-d',strtotime($Friday . '-7 days'));
			$r = sqlsrv_query($NEI,"SELECT Sum(Total) AS Summed FROM nei.dbo.TicketD LEFT JOIN nei.dbo.Job ON TicketD.Job = Job.ID WHERE TicketD.Elev='" . $_GET['ID'] . "' and EDate >= '" . $Friday . " 00:00:00.000' AND EDate <= '" . $Friday . " 23:59:59.999'");?>
		<td class='Friday' rel='<?php echo $Friday;?>' onClick="refresh_this(this);"><?php 
			echo sqlsrv_fetch_array($r)['Summed'];
		?></td>
		<?php $Saturday = date('Y-m-d',strtotime($Saturday . '-7 days'));
			$r = sqlsrv_query($NEI,"SELECT Sum(Total) AS Summed FROM nei.dbo.TicketD LEFT JOIN nei.dbo.Job ON TicketD.Job = Job.ID WHERE TicketD.Elev='" . $_GET['ID'] . "' and EDate >= '" . $Saturday . " 00:00:00.000' AND EDate <= '" . $Saturday . " 23:59:59.999'");?>
		<td class='Saturday' rel='<?php echo $Saturday;?>' onClick="refresh_this(this);"><?php 
			echo sqlsrv_fetch_array($r)['Summed'];
		?></td>
		<?php $Sunday = date('Y-m-d',strtotime($Sunday . '-7 days'));
			$r = sqlsrv_query($NEI,"SELECT Sum(Total) AS Summed FROM nei.dbo.TicketD LEFT JOIN nei.dbo.Job ON TicketD.Job = Job.ID WHERE TicketD.Elev='" . $_GET['ID'] . "' and EDate >= '" . $Sunday . " 00:00:00.000' AND EDate <= '" . $Sunday . " 23:59:59.999'");?>
		<td class='Sunday' rel='<?php echo $Sunday;?>' onClick="refresh_this(this);"><?php 
			echo sqlsrv_fetch_array($r)['Summed'];
		?></td>
		<?php $Monday = date('Y-m-d',strtotime($Monday . '-7 days'));
			$r = sqlsrv_query($NEI,"SELECT Sum(Total) AS Summed FROM nei.dbo.TicketD LEFT JOIN nei.dbo.Job ON TicketD.Job = Job.ID WHERE TicketD.Elev='" . $_GET['ID'] . "' and EDate >= '" . $Monday . " 00:00:00.000' AND EDate <= '" . $Monday . " 23:59:59.999'");?>
		<td class='Monday' rel='<?php echo $Monday;?>' onClick="refresh_this(this);"><?php 
			echo sqlsrv_fetch_array($r)['Summed'];
		?></td>
		<?php $Tuesday = date('Y-m-d',strtotime($Tuesday . '-7 days'));
			$r = sqlsrv_query($NEI,"SELECT Sum(Total) AS Summed FROM nei.dbo.TicketD LEFT JOIN nei.dbo.Job ON TicketD.Job = Job.ID WHERE TicketD.Elev='" . $_GET['ID'] . "' and EDate >= '" . $Tuesday . " 00:00:00.000' AND EDate <= '" . $Tuesday . " 23:59:59.999'");?>
		<td class='Tuesday' rel='<?php echo $Tuesday;?>' onClick="refresh_this(this);"><?php 
			echo sqlsrv_fetch_array($r)['Summed'];
		?></td>
		<?php $Wednesday = date('Y-m-d',strtotime($Wednesday . '-7 days'));
			$r = sqlsrv_query($NEI,"SELECT Sum(Total) AS Summed FROM nei.dbo.TicketD LEFT JOIN nei.dbo.Job ON TicketD.Job = Job.ID WHERE TicketD.Elev='" . $_GET['ID'] . "' and EDate >= '" . $Wednesday . " 00:00:00.000' AND EDate <= '" . $Wednesday . " 23:59:59.999'");?>
		<td class='Wednesday' rel='<?php echo $Wednesday;?>' onClick="refresh_this(this);"><?php
			echo sqlsrv_fetch_array($r)['Summed'];
		?></td>
		<td><?php
			$r = sqlsrv_query($NEI,"SELECT Sum(Total) AS Summed FROM nei.dbo.TicketD LEFT JOIN nei.dbo.Job ON TicketD.Job = Job.ID WHERE TicketD.Elev='" . $_GET['ID'] . "' and EDate >= '" . $Thursday . " 00:00:00.000' AND EDate <= '" . $Wednesday . " 23:59:59.999'");
			echo sqlsrv_fetch_array($r)['Summed'];
		?></td>
	</tr><?php }?>
</tbody>
</table>
<script>$(document).ready(function(){$("table#Location_Hours>tbody>tr:first-child").remove();});</script>
<?php
    }
} else {?><html><head><script>document.location.href="../login.php?Forward=location<?php echo (!isset($_GET['ID']) || !is_numeric($_GET['ID'])) ? "s.php" : ".php?ID={$_GET['ID']}";?>";</script></head></html><?php }?>