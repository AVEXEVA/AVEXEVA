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
							<table id='Table_Jobs' class='display' cellspacing='0' width='100%' style="font-size: 9px">
								<thead>
									<th>ID</th>
									<th>Name</th>
									<th>Type</th>
								</thead>
							</table>
						</div>
					</div>
				</div>
				<script>
				var Table_Jobs = $('#Table_Jobs').DataTable( {
					"ajax": {
						"url":"cgi-bin/php/get/Jobs_by_Customer.php?ID=<?php echo $_GET['ID'];?>",
						"dataSrc":function(json){
							if(!json.data){json.data = [];}
							return json.data;}
					},
					"columns": [
						{ 
							"data": "ID" 
						},{ 
							"data": "Name"
						},{ 
							"data": "Type"
						}
					],
					"buttons":[],
					<?php require('../../../js/datatableOptions.php');?>
				} );
	function hrefJobs(){hrefRow("Table_Jobs","job");}
	$("Table#Table_Jobs").on("draw.dt",function(){hrefJobs();});
				</script>
			</div>
		</div>
	</div>

<?php
    }
} else {?><html><head><script>document.location.href="../login.php?Forward=location<?php echo (!isset($_GET['ID']) || !is_numeric($_GET['ID'])) ? "s.php" : ".php?ID={$_GET['ID']}";?>";</script></head></html><?php }?>