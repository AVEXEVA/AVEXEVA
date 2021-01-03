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
    sqlsrv_query($Portal,"INSERT INTO Activity([User], [Date], [Page]) VALUES(?,?,?);",array($_SESSION['User'],date("Y-m-d H:i:s"), "customer-unit.php"));
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
			<div class="row">
				<div class='col-md-12' >
					<div class="panel panel-primary">
						<div class = "table-responsive">
							<table id='Table_Units' class='display' cellspacing='0' width='100%' overflow-x: scroll style="font-size: 12px";>
							<thead>
								<th title="Unit's ID">ID</th>
								<th title='Unit State ID'>State</th>
								<th title="Unit's Label">Label</th>
								<th title="Type of Unit">Type</th>
								<th title="Unit's Location">Location</th>
								<th>Status</th>
							</thead>

							</table>
						</div>
					</div>
				</div>
			</div>
				<script>
			var Table_Units = $('#Table_Units').DataTable( {
				"ajax": "cgi-bin/php/get/Units_by_Customer.php?ID=<?php echo $_GET['ID'];?>",
				"autoWidth":false,
				"columns": [
					{ 
						"data": "ID",
						"className":"hidden"
					},{ 
						"data": "State"
					},{ 
						"data": "Unit"
					},{ 
						"data": "Type"
					},{ 
						"data": "Location",
						"visible":false
					},{ 
						"data": "Status",
						render:function(data){
							switch(data){
								case 0:return 'Active';
								case 1:return 'Inactive';
								case 2:return 'Demolished';
								case 3:return 'XXX';
								case 4:return 'YYY';
								case 5:return 'ZZZ';
								case 6:return 'AAA';
								default:return 'Error';
							}
						}
					}
					<?php if(count($Values) > 0){foreach($Values as $Field=>$Value){?>,{"data" :"<?php echo $Field;?>", "visible":false}<?php }}?>
				],
				"paging":false,
				"searching":false
			} );
			function hrefUnits(){hrefRow("Table_Units","unit");}
			$("Table#Table_Units").on("draw.dt",function(){hrefUnits();});
				</script>
			</div>
		</div>
	</div>

</body>
</html>
<?php
    }
} else {?><html><head><script>document.location.href="../login.php?Forward=location<?php echo (!isset($_GET['ID']) || !is_numeric($_GET['ID'])) ? "s.php" : ".php?ID={$_GET['ID']}";?>";</script></head></html><?php }?>