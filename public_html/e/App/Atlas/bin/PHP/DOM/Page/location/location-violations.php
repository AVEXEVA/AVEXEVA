<?php
session_start();

require('../../../../cgi-bin/php/index.php');
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
        if(isset($My_Privileges['Location']) && $My_Privileges['Location']['User_Privilege'] >= 4 && $My_Privileges['Location']['Group_Privilege'] >= 4 && $My_Privileges['Location']['Other_Privilege'] >= 4){$Privileged = TRUE;}
        elseif($My_Privileges['Location']['User_Privilege'] >= 4 && is_numeric($_GET['ID'])){
            $r = sqlsrv_query(  $NEI,"
			SELECT 	*
			FROM 	nei.dbo.TicketO
			WHERE 	TicketO.LID='{$_GET['ID']}'
					AND fWork='{$My_User['fWork']}'");
            $r2 = sqlsrv_query( $NEI,"
			SELECT 	*
			FROM 	nei.dbo.TicketD
			WHERE 	TicketD.Loc='{$_GET['ID']}'
					AND fWork='{$My_User['fWork']}'");
            $r3 = sqlsrv_query( $NEI,"
			SELECT 	*
			FROM 	nei.dbo.TicketDArchive
			WHERE 	TicketDArchive.Loc='{$_GET['ID']}'
					AND fWork='{$My_User['fWork']}'");
            $r = sqlsrv_fetch_array($r);
            $r2 = sqlsrv_fetch_array($r2);
			$r3 = sqlsrv_fetch_array($r3);
            $Privileged = (is_array($r) || is_array($r2) || is_array($r3)) ? TRUE : FALSE;
        }
    } elseif($_SESSION['Branch'] == 'Customer' && is_numeric($_GET['ID'])){
        $SQL_Result = sqlsrv_query($NEI,"
            SELECT Loc.Owner
            FROM Loc
            WHERE Loc.Loc='{$_GET['ID']}' AND Loc.Owner='{$_SESSION['Branch_ID']}'
        ;");
        if($SQL_Result){
            $sql = sqlsrv_fetch_array($SQL_Result);
            if($sql){
                $Privileged = true;
            }
        }
    }
    sqlsrv_query($Portal,"INSERT INTO Activity([User], [Date], [Page]) VALUES(?,?,?);",array($_SESSION['User'],date("Y-m-d H:i:s"), "location.php"));
    if(!isset($array['ID'])  || !$Privileged || !is_numeric($_GET['ID'])){
      ?><html><head><script>document.location.href="../login.php?Forward=location<?php echo (!isset($_GET['ID']) || !is_numeric($_GET['ID'])) ? "s.php" : ".php?ID={$_GET['ID']}";?>";</script></head></html><?php }
    else {
        $ID = $_GET['ID'];
        $r = sqlsrv_query($NEI,
            "SELECT TOP 1
                    Loc.Loc              AS Location_ID,
                    Loc.ID               AS Name,
                    Loc.Tag              AS Tag,
                    Loc.Address          AS Street,
                    Loc.City             AS City,
                    Loc.State            AS State,
                    Loc.Zip              AS Zip,
                    Loc.Balance          as Location_Balance,
                    Zone.Name            AS Zone,
                    Loc.Route            AS Route_ID,
                    Emp.ID               AS Route_Mechanic_ID,
                    Emp.fFirst           AS Route_Mechanic_First_Name,
                    Emp.Last             AS Route_Mechanic_Last_Name,
                    Loc.Owner            AS Customer_ID,
                    OwnerWithRol.Name    AS Customer_Name,
                    OwnerWithRol.Balance AS Customer_Balance,
                    Terr.Name            AS Territory_Domain/*,
                    Sum(SELECT Location.ID FROM Loc AS Location WHERE Location.Owner='Loc.Owner') AS Customer_Locations*/
            FROM    Loc
                    LEFT JOIN nei.dbo.Zone         ON Loc.Zone   = Zone.ID
                    LEFT JOIN nei.dbo.Route        ON Loc.Route  = Route.ID
                    LEFT JOIN nei.dbo.Emp          ON Route.Mech = Emp.fWork
                    LEFT JOIN nei.dbo.OwnerWithRol ON Loc.Owner  = OwnerWithRol.ID
                    LEFT JOIN Terr         		   ON Terr.ID    = Loc.Terr
            WHERE
                    Loc.Loc = ?
        ;",array($_GET['ID']));
        $Location = sqlsrv_fetch_array($r);
        $data = $Location;
        $job_result = sqlsrv_query($NEI,"
            SELECT Job.ID AS ID
            FROM   Job
            WHERE  Job.Loc = ?
        ;",array($_GET['ID']));
        if($job_result){
            $Jobs = array();
            $dates = array();
            $totals = array();
            while($array = sqlsrv_fetch_array($job_result)){$Jobs[] = "[JOBLABOR].[JOB #]='{$array['ID']}'";}
            $SQL_Jobs = implode(" OR ",$Jobs);
        }?>
<div class="panel panel-primary">
	<!--<div class="panel-heading"><h3><i class="fa fa-bell fa-fw"></i> Violations Table</h3></div>-->
	<div class="panel-body  BankGothic shadow">
		<table id='Table_Violations' class='display' cellspacing='0' width='100%' style="font-size:12px;">
			<thead>
				<th title='ID of the Violation'>ID</th>
				<th title='Name of the Violation'>Name</th>
				<th title="Date of the Violation">Date</th>
				<th title='Status of the Violation'>Status</th>
				<th title='Description of the Violation'>Description</th>
			</thead>
		</table>
	</div>
</div>
<script>
var Editor_Violations = new $.fn.dataTable.Editor({
	ajax: "cgi-bin/php/get/Locations_by_Customer.php?ID=<?php echo $_GET['ID'];?>",
	table: "#Table_Violations",
	idSrc: "ID",
	fields : [{
		label: "ID",
		name: "ID"
	},{
		label: "Name",
		name: "Name"
	},{
		label: "Date",
		name: "Date",
		type: "datetime",
		def:function(){return new Date();}
	},{
		label: "Status",
		name: "Status",
		type: "select",
		options: [<?php
			$r = sqlsrv_query($NEI,"
				SELECT   VioStatus.Type,
					     VioStatus.ID
				FROM     nei.dbo.VioStatus
				ORDER BY VioStatus.Type ASC
			;");
			$Types = array();
			if($r){while($Type = sqlsrv_fetch_array($r)){$Types[] = '{' . "label: '{$Type['Type']}', value:'{$Type['ID']}'" . '}';}}
			echo implode(",",$Types);
		?>]
	},{
		label: "Description",
		name: "Description",
		type:"textarea"
	}]
});
Editor_Violations.field('ID').disable();
var Table_Violations = $('#Table_Violations').DataTable( {
	"ajax": {
		"url":"cgi-bin/php/get/Violations_by_Location.php?ID=<?php echo $_GET['ID'];?>",
		"dataSrc":function(json){if(!json.data){json.data = [];}return json.data;}
	},
	"columns": [
		{
			"data": "ID",
			"className":"hidden"
		},{
			"data": "Name"
		},{
			"data": "Date",
		 	render: function(data){return data.substr(5,2) + "/" + data.substr(8,2) + "/" + data.substr(0,4);}
		},{
			"data": "Status"
		},{
			"data": "Description"
		}
	],
	"buttons":[/*
		'copy',
		'csv',
		'excel',
		'pdf',
		'print',
		/*{ extend: "create", editor: Editor_Violations },
		{ extend: "edit",   editor: Editor_Violations },
		{ extend: "remove", editor: Editor_Violations },
		{ text:"View",
		  action:function(e,dt,node,config){
			  document.location.href = 'job.php?ID=' + $("#Table_Violations tbody tr.selected td:first-child").html();
		  }
		}
	*/ ],
	<?php require('../../../js/datatableOptions.php');?>
} );
function hrefViolations(){hrefRow("Table_Violations","violation");}
$("Table#Table_Violations").on("draw.dt",function(){hrefViolations();});
</script>

<?php
    }
} else {?><html><head><script>document.location.href="../login.php?Forward=location<?php echo (!isset($_GET['ID']) || !is_numeric($_GET['ID'])) ? "s.php" : ".php?ID={$_GET['ID']}";?>";</script></head></html><?php }?>
