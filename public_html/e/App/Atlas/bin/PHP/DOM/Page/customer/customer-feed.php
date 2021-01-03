<?php
session_start();

require('../../../php/index.php');
setlocale(LC_MONETARY, 'en_US');
if(isset($_SESSION['User'],$_SESSION['Hash'])){
    $r = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.Connection WHERE Connector = ? AND Hash = ?;",array($_SESSION['User'],$_SESSION['Hash']));
    $array = sqlsrv_fetch_array($r);
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
    sqlsrv_query($Portal,"INSERT INTO Activity([User], [Date], [Page]) VALUES(?,?,?);",array($_SESSION['User'],date("Y-m-d H:i:s"), "customer.php?ID=".$_GET['ID']));
    if(!isset($array['ID'])  || !$Privileged || !is_numeric($_GET['ID'])){?><html><head><script>document.location.href="../login.php?Forward=customer<?php echo (!isset($_GET['ID']) || !is_numeric($_GET['ID'])) ? "s.php" : ".php?ID={$_GET['ID']}";?>";</script></head></html><?php }
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
		<div class="panel-body white-background">
			<table id='Table_Worker_Feed' class='display' cellspacing='0' width='100%' style='font-size:12px;'>
				<thead><tr>
					<th>Status</th>
					<th>Created</th>
					<th>Scheduled</th>
					<th>Mechanic</th>
				</tr></thead>
			</table>
		</div>
	</div>
</div>
	<style>
		.border-seperate {
			border-bottom:3px solid #333333;
		}
	</style>
	<script>
	var Table_Worker_Feed = $('#Table_Worker_Feed').DataTable( {
		"ajax": {
				"url": "cgi-bin/php/reports/Worker_Feed_by_Customer.php?ID=<?php echo $_GET['ID'];?>",
				"dataSrc":function(json){if(!json.data){json.data = [];}return json.data;}
		},
		"columns": [
			{
				"data" : "Status"
			},{
				"data" : "Created",
				render: function(data){if(!data){return null;}else{return data.substr(5,2) + "/" + data.substr(8,2) + "/" + data.substr(0,4);}}
			},{
				"data" : "Scheduled",
				render: function(data){if(!data){return null;}else{return data.substr(5,2) + "/" + data.substr(8,2) + "/" + data.substr(0,4);}}
			},{
				"data" : "Mechanic"
			}
		],
		"scrollY" : "300px",
		"scrollCollapse":true,
		"lengthChange":false,
		"searching":false

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
<?php
    }
} else {?><html><head><script>document.location.href="../login.php?Forward=customer<?php echo (!isset($_GET['ID']) || !is_numeric($_GET['ID'])) ? "s.php" : ".php?ID={$_GET['ID']}";?>";</script></head></html><?php }?>
