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
	<div class="panel panel-primary" style='margin-bottom:0px;'>
		<div class="panel-body">
			<div class="row">
				<div class='col-md-12' >
					<div class="panel panel-primary">
						<div class="panel-body white-background BankGothic shadow">
							<table id='Table_Invoices' class='display' cellspacing='0' width='100%' style='font-size:8px'>
								<thead>
									<th title='ID of the Invoice'>ID</th>
									<th title='Job of the Invoice'>Job</th>
									<th title='Location of the Invoice'>Location</th>
									<th title='Date of the Invoice'>Date</th>
									<th title='Description of the Invoice'>Description</th>
									<th title='Total Amount of Invoice'>Amount</th>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>	
			<!-- /.panel -->
	</div>
	</div>
	<style>
		.border-seperate {
			border-bottom:3px solid #333333;
		}
	</style>

	<script>
				var Table_Invoices = $('#Table_Invoices').DataTable( {
					"ajax": "cgi-bin/php/get/Invoices_by_Customer.php?ID=<?php echo $_GET['ID'];?>",
					"columns": [
						{ 
							"data" : "ID" ,
							"visible":false
						},{ 
							"data" : "Job",
							"width":"50px"
						},{ 
							"data" : "Location",
							"visible": false
						},{ 
							"data" : "fDate",
							"width":"50px",
							render: function(data){return data.substr(5,2) + "/" + data.substr(8,2) + "/" + data.substr(0,4);}
						},{ 
							"data" : "Description",
							"width":"50px"
						},{ 
							"data" : "Total",
							"width":"50px",
							render:function(data){return "$" + parseFloat(data).toLocaleString();}
						}
					],
					"buttons":[],
					"bAutoWidth":false,
					"searching":true,
					"fixedHeader": {
						header:true
					},
					"language":{
						"loadingRecords":"<div style='text-align:center;'><div class='sk-cube-grid' style='display:inline-block;position:relative;';><div class='sk-cube sk-cube1' style='background-color:#cc0000'></div><div class='sk-cube sk-cube2' style='background-color:#cc0000'></div><div class='sk-cube sk-cube3' style='background-color:#cc0000'></div><div class='sk-cube sk-cube4' style='background-color:#cc0000'></div><div class='sk-cube sk-cube5' style='background-color:#cc0000'></div><div class='sk-cube sk-cube6' style='background-color:#cc0000'></div><div class='sk-cube sk-cube7' style='background-color:#cc0000'></div><div class='sk-cube sk-cube8' style='background-color:#cc0000'></div><div class='sk-cube sk-cube9' style='background-color:#cc0000'></div></div><div class='sk-cube-grid' style='display:inline-block;position:relative;top:-45px;'><div class='sk-cube sk-cube1' style='background-color:#00007f'></div><div class='sk-cube sk-cube2' style='background-color:#00007f'></div><div class='sk-cube sk-cube3' style='background-color:#00007f'></div><div class='sk-cube sk-cube4' style='background-color:#00007f'></div><div class='sk-cube sk-cube5' style='background-color:#00007f'></div><div class='sk-cube sk-cube6' style='background-color:#00007f'></div><div class='sk-cube sk-cube7' style='background-color:#00007f'></div><div class='sk-cube sk-cube8' style='background-color:#00007f'></div><div class='sk-cube sk-cube9' style='background-color:#00007f'></div></div><div class='sk-cube-grid' style='display:inline-block;position:relative;top:-84px;'><div class='sk-cube sk-cube1' style='background-color:gold'></div><div class='sk-cube sk-cube2' style='background-color:gold'></div><div class='sk-cube sk-cube3' style='background-color:gold'></div><div class='sk-cube sk-cube4' style='background-color:gold'></div><div class='sk-cube sk-cube5' style='background-color:gold'></div><div class='sk-cube sk-cube6' style='background-color:gold'></div><div class='sk-cube sk-cube7' style='background-color:gold'></div><div class='sk-cube sk-cube8' style='background-color:gold'></div><div class='sk-cube sk-cube9' style='background-color:gold'></div></div></div><div style='font-size:72px;text-align:center;' class='BankGothic'>Nouveau Elevator</div><div style='font-size:42px;text-align:center;'><i>Raising Your Life</i></div>"
					},
					"paging":false,
					"dom":"Bfrtip",
					"select":true,
					"initComplete":function(){},
					"scrollY" : "600px",
					"scrollCollapse":true,
					"searching" : true,
				} );
				</script> 

<?php
    }
} else {?><html><head><script>document.location.href="../login.php?Forward=location<?php echo (!isset($_GET['ID']) || !is_numeric($_GET['ID'])) ? "s.php" : ".php?ID={$_GET['ID']}";?>";</script></head></html><?php }?>