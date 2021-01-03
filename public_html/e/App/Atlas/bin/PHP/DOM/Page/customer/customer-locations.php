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
    sqlsrv_query($Portal,"INSERT INTO Activity([User], [Date], [Page]) VALUES(?,?,?);",array($_SESSION['User'],date("Y-m-d H:i:s"), "customer-locations.php"));
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
	<!--<div class="panel-heading"><h3><i class="fa fa-bell fa-fw"></i> Locations Table</h3></div>-->
	<div class="panel-body white-background BankGothic shadow">
		<div id='Form_Location'>
			<div class="panel panel-primary">
				<div class="panel-heading" style='position:fixed;width:750px;z-index:999;'><h2 style='display:block;'>Location Form</h2></div>
				<div class="panel-body white-background BankGothic shadow" style='padding-top:100px;'>
					<div style='display:block !important;'>
						<fieldset >
							<legend>Names</legend>
							<editor-field name='ID'></editor-field>
							<editor-field name='Name'></editor-field>
							<editor-field name='Tag'></editor-field>
						</fieldset>
						<fieldset>
							<legend>Address</legend>
							<editor-field name='Street'></editor-field>
							<editor-field name='City'></editor-field>
							<editor-field name='State'></editor-field>
							<editor-field name='Zip'></editor-field>
							<editor-field name='Latitude'></editor-field>
							<editor-field name='Longitude'></editor-field>
						</fieldset>
						<fieldset>
							<legend>Contact</legend>
							<editor-field name='Contact_Name'></editor-field>
							<editor-field name='Contact_Phone'></editor-field>
							<editor-field name='Contact_Fax'></editor-field>
							<editor-field name='Contact_Cellular'></editor-field>
							<editor-field name='Contact_Email'></editor-field>
							<editor-field name='Contact_Website'></editor-field>
						</fieldset>
						<fieldset>
							<legend>Maintenance</legend>
							<editor-field name='Route'></editor-field>
							<editor-field name='Division'></editor-field>
							<editor-field name='Maintenance'></editor-field>
						</fieldset>
						<fieldset>
							<legend>Financials</legend>
							<editor-field name='Sales_Tax'></editor-field>
							<editor-field name='Collector'></editor-field>
						</fieldset>
						<fieldset>
							<legend>Sales</legend>
							<editor-field name='Territory'></editor-field>
						</fieldset>
					</div>
				</div>
			</div>
		</div>
		<table id='Table_Locations' class='display' cellspacing='0' width='100%'>
			<thead>
				<th title="Location's ID"></th>
				<th title="Location's Name State ID"></th>
				<th title="Location's Tag">Name</th>
				<th title="Location's Street"></th>
				<th title="Location's City"></th>
				<th title="Location's State"></th>
				<th title="Location's Zip"></th>
				<th title="Location's Route">Route</th>
				<th title="Location's Zone">Division</th>
				<th title="Location's Mainteniance"></th>
			</thead>
		</table>
	</div>
</div>
</div>
<script>
var Editor_Locations = new $.fn.dataTable.Editor({
ajax: "php/post/Location.php?ID=<?php echo $_GET['ID'];?>",
table: "#Table_Locations",
template: '#Form_Location',
formOptions: {
	inline: {
		submit: "allIfChanged"
	}
},
idSrc: "ID",
fields : [{
	label: "Loc",
	name: "ID"
},{
	label: "ID",
	name: "Name"
},{
	label: "Tag",
	name: "Tag"
},{
	label: "Street",
	name: "Street"
},{
	label: "City",
	name: "City",
	type: "select",
	options: [<?php
		$r = sqlsrv_query($NEI,"
			SELECT   Loc.City
			FROM     nei.dbo.Loc
			WHERE    Loc.City <> ''
			         AND Loc.City <> ?
			GROUP BY Loc.City
			ORDER BY Loc.City ASC
		;",array("DON'T USE THIS CODE"));
		$Cities = array();
		if($r){while($City = sqlsrv_fetch_array($r)){$Cities[] = '{' . "label: '{$City['City']}', value:'{$City['City']}'" . '}';}}
		echo implode(",",$Cities);
	?>]
},{
	label: "State",
	name: "State",
	type: "select",
	options: [<?php
		$r = sqlsrv_query($NEI,"
			SELECT   Loc.State
			FROM     nei.dbo.Loc
			WHERE    Loc.State <> ''
			GROUP BY Loc.State
			ORDER BY Loc.State ASC
		;");
		$States = array();
		if($r){while($State = sqlsrv_fetch_array($r)){$States[] = '{' . "label: '{$State['State']}', value:'{$State['State']}'" . '}';}}
		echo implode(",",$States);
	?>]
},{
	label: "Zip",
	name: "Zip"
},{
	label: "Route",
	name: "Route",
	type: "select",
	options: [<?php
		$r = sqlsrv_query($NEI,"
			SELECT   Route.Name
			FROM     nei.dbo.Route
			GROUP BY Route.Name
			ORDER BY Route.Name ASC
		;");
		$States = array();
		if($r){while($State = sqlsrv_fetch_array($r)){$States[] = '{' . "label: '{$State['Name']}', value:'{$State['Name']}'" . '}';}}
		echo implode(",",$States);
	?>]
},{
	label: "Division",
	name: "Division",
	type: "select",
	options: [<?php
		$r = sqlsrv_query($NEI,"
			SELECT   Zone.Name
			FROM     nei.dbo.Zone
			GROUP BY Zone.Name
			ORDER BY Zone.Name ASC
		;");
		$States = array();
		if($r){while($State = sqlsrv_fetch_array($r)){$States[] = '{' . "label: '{$State['Name']}', value:'{$State['Name']}'" . '}';}}
		echo implode(",",$States);
	?>]
},{
	label:"Maintenance",
	name:"Maintenance",
	type:"radio",
	options: [
		{label: "Not Maintained", value:0},
		{label: "Maintained", value:1}
	]
},{
	label:"Territory",
	name:"Territory",
	type:"select",
	options: [<?php 
		$r = sqlsrv_query($NEI,"
			SELECT Terr.Name 
			FROM   nei.dbo.Terr
			GROUP BY Terr.Name
			ORDER BY Terr.Name ASC
		;");
		$Territories = array();
		if($r){while($Territory = sqlsrv_fetch_array($r)){$Territories[] = '{' . "label: '{$Territory['Name']}', value:'{$Territory['Name']}'" . '}';}}
		echo implode(",",$Territories);
	?>]
},{
	label:"Longitude",
	name:"Longitude"
},{
	label:"Latitude",
	name:"Latitude"
},{
	label:"Sales Tax",
	name:"Sales_Tax",
	type:"select",
	options: [<?php 
		$r = sqlsrv_query($NEI,"
			SELECT Loc.sTax AS Sales_Tax
			FROM   nei.dbo.Loc
			GROUP BY Loc.sTax
			ORDER BY Loc.sTax
		;");
		$Sales_Taxes = array();
		if($r){while($Sales_Tax = sqlsrv_fetch_array($r)){$Sales_Taxes[] = '{' . "label: '{$Sales_Tax['Sales_Tax']}', value:'{$Sales_Tax['Sales_Tax']}'" . '}';}}
		echo implode(",",$Sales_Taxes);
	?>]
},{
	label:"Collector",
	name:"Collector",
	type:"select",
	options:['',<?php 
		$r = sqlsrv_query($NEI,"
			SELECT Emp.fFirst + ' ' + Emp.Last AS Name
			FROM   nei.dbo.Emp
			WHERE  Emp.Custom3 = 'COLLECTOR'
				   And Emp.Status = 0
		;");
		$People = array();
		if($r){while($Person = sqlsrv_fetch_array($r)){$People[] = '{' . "label: " . '"' . "{$Person['Name']}" . '"' . ", value:" . '"' . "{$Person['Name']}" . '"' . '}';}}
		echo implode(",",$People);
	?>]
},{
	label:"Name",
	name:"Contact_Name"
},{
	label:"Phone",
	name:"Contact_Phone"
},{
	label:"Fax",
	name:"Contact_Fax"
},{
	label:"Cellular",
	name:"Contact_Cellular"
},{
	label:"Email",
	name:"Contact_Email"
},{
	label:"Website",
	name:"Contact_Website"
}]
});
Editor_Locations.field('ID').disable();
/*$('#Table_Locations').on( 'click', 'tbody td:not(:first-child)', function (e) {
Editor_Locations.inline( this );
} );*/
var Table_Locations = $('#Table_Locations').DataTable( {
"ajax": "cgi-bin/php/get/Locations_by_Customer.php?ID=<?php echo $_GET['ID'];?>",
"columns": [
	{ 
		"data": "ID",
		"className":"hidden"
	},{ 
		"data": "Name",
		"visible":false
	},{ 
		"data": "Tag"
	},{ 
		"data": "Street",
		"visible":false
	},{ 
		"data": "City",
		"visible":false
	},{ 
		"data": "State",
		"visible":false
	},{ 
		"data": "Zip",
		"visible":false
	},{ 
		"data": "Route"
	},{ 
		"data": "Division"
	},{ 
		"data": "Maintenance",
	  	"render":function(data){
		  	if(data == '1'){return "Maintained";}
		  	else {return "Not Maintained";}
	  	},
		"visible":false
	}
],
"buttons":[
	<?php if(isset($My_Privleges['Export'])){?>{
		extend: 'collection',
		text: 'Export',
		buttons: [
			'copy',
			'excel',
			'csv',
			'pdf',
			'print'
		]
	},<?php }?>
	/*{ extend: "create", editor: Editor_Locations },
	{ extend: "edit",   editor: Editor_Locations },
	{ 
		extend: "remove", 
	 	editor: Editor_Locations, 
	 	formButtons: [
			'Delete',
			{ text: 'Cancel', action: function () { this.close(); } }
		]
	},*/
],
"searching":false,
<?php require('../../../js/datatableOptions.php');?>
} );
function hrefLocations(){hrefRow("Table_Locations","location");}
$("Table#Table_Locations").on("draw.dt",function(){hrefLocations();});
</script>
<?php
    }
} else {?><html><head><script>document.location.href="../login.php?Forward=location<?php echo (!isset($_GET['ID']) || !is_numeric($_GET['ID'])) ? "s.php" : ".php?ID={$_GET['ID']}";?>";</script></head></html><?php }?>