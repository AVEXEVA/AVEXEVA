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
    sqlsrv_query($Portal,"INSERT INTO Activity([User], [Date], [Page]) VALUES(?,?,?);",array($_SESSION['User'],date("Y-m-d H:i:s"), "customer-violations.php"));
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
<div class="panel panel-primary" style='margin-bottom:0px;'>
		<div class="panel-body">			
			<div class="panel-body white-background BankGothic shadow">
				<table id='Table_Violations' class='display' cellspacing='0' width='100%' style='font-size:3px;'>
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
					ajax: "php/get/Locations_by_Customer.php?ID=<?php echo $_GET['ID'];?>",
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
						"url":"cgi-bin/php/get/Violations_by_Customer.php?ID=<?php echo $_GET['ID'];?>",
						"dataSrc":function(json){if(!json.data){json.data = [];}return json.data;}
					},
					"columns": [
						{ "data": "ID" },
						{ "data": "Name"},
						{ "data": "Date"},
						{ "data": "Status"},
						{ "data": "Description"}
					],
					"buttons":[
						/*'copy',
						'csv',
						'excel',
						'pdf',
						'print',
						{ extend: "create", editor: Editor_Violations },
						{ extend: "edit",   editor: Editor_Violations },
						{ extend: "remove", editor: Editor_Violations },*/
						
					]
					,
					<?php require('../../../js/datatableOptions.php');?>
				} );
				function hrefViolations(){hrefRow("Table_Violations","job");}
		$("Table#Table_Violations").on("draw.dt",function(){hrefViolations();});
				</script>
		</div>
	</div>
			

<?php
    }
} else {?><html><head><script>document.location.href="../login.php?Forward=location<?php echo (!isset($_GET['ID']) || !is_numeric($_GET['ID'])) ? "s.php" : ".php?ID={$_GET['ID']}";?>";</script></head></html><?php }?>