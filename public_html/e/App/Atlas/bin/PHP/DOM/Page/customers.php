<?php
if(session_id() == '' || !isset($_SESSION)) {
    session_start();
    $_SESSION['page-target'] = 'customers';
    unset($_SESSION['page-id']);
    require('../../../cgi-bin/php/index.php');
}
if(isset($_SESSION['User'],$_SESSION['Hash'])){
    $r = sqlsrv_query($NEI,"
		SELECT *
		FROM   nei.dbo.Connection
		WHERE  Connection.Connector = ?
		       AND Connection.Hash  = ?
	;",array($_SESSION['User'],$_SESSION['Hash']));
    $My_Connection = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC);
    $r = sqlsrv_query($NEI,"
		SELECT *,
		       Emp.fFirst AS First_Name,
			   Emp.Last   AS Last_Name
		FROM   nei.dbo.Emp
		WHERE  Emp.ID = ?
	;",array($_SESSION['User']));
    $My_User = sqlsrv_fetch_array($r);
	$r = sqlsrv_query($NEI,"
		SELECT *
		FROM   Portal.dbo.Privilege
		WHERE  Privilege.User_ID = ?
	;",array($_SESSION['User']));
	$My_Privileges = array();
	if($r){while($My_Privilege = sqlsrv_fetch_array($r)){$My_Privileges[$My_Privilege['Access_Table']] = $My_Privilege;}}
    if(	!isset($My_Connection['ID'])
	   	|| !isset($My_Privileges['Customer'])
	  		|| $My_Privileges['Customer']['User_Privilege']  < 4
	  		|| $My_Privileges['Customer']['Group_Privilege'] < 4
	  		|| $My_Privileges['Customer']['Other_Privilege'] < 4){}
    else {
		sqlsrv_query($NEI,"INSERT INTO Portal.dbo.Activity([User], [Date], [Page]) VALUES(?,?,?) ;",array($_SESSION['User'],date("Y-m-d H:i:s"), "customers.php"));
?><div class="panel panel-primary" style='height:100%;'>
  <div class="panel-heading" style='background-color:#1f1f1f;color:white;border-bottom:0px solid black;'>
    <h4 style='float:left;'><div onClick=""><?php $Icons->Customer();?> Customers</div></h4>
    <div style='clear:both;'></div>
  </div>
  <div class="panel-body" style='height:90%;background-color:#2a2a2a'>
    <table id='Table_Customers' class='display' cellspacing='0' width='100%'>
      <thead>
        <th title="Customer's ID">ID</th>
        <th title='Customer Name'>Name</th>
        <th title='Customer Status'>Status</th>
      </thead>
    </table>
    <script>
   	function hrefCustomers(){
      $('Table#Table_Customers tr').on('dblclick', function(){
        open_page("<div page-target='customer'></div>", {ID:$(this).children(':first-child').html()});
      });
    }
    var Table_Customers = $('#Table_Customers').DataTable( {
		"ajax": {
			"url":"cgi-bin/php/get/Customers.php"
		},
		"processing":true,
		"serverSide":true,
		"order": [[ 1, "asc" ]],
		"columns": [
			{},
			{},
			{render:function(data){if(data == 0){return 'Active';}else{return 'Inactive';}}}
		],
		"buttons":[
			{
				extend: 'collection',
				text: 'Export',
				buttons: [
					'copy',
					'excel',
					'csv',
					'pdf',
					'print'
				]
			}
		],
		"language":{
			"loadingRecords":"<div style='text-align:center;'><div class='sk-cube-grid' style='display:inline-block;position:relative;';><div class='sk-cube sk-cube1' style='background-color:#cc0000'></div><div class='sk-cube sk-cube2' style='background-color:#cc0000'></div><div class='sk-cube sk-cube3' style='background-color:#cc0000'></div><div class='sk-cube sk-cube4' style='background-color:#cc0000'></div><div class='sk-cube sk-cube5' style='background-color:#cc0000'></div><div class='sk-cube sk-cube6' style='background-color:#cc0000'></div><div class='sk-cube sk-cube7' style='background-color:#cc0000'></div><div class='sk-cube sk-cube8' style='background-color:#cc0000'></div><div class='sk-cube sk-cube9' style='background-color:#cc0000'></div></div><div class='sk-cube-grid' style='display:inline-block;position:relative;top:-45px;'><div class='sk-cube sk-cube1' style='background-color:#00007f'></div><div class='sk-cube sk-cube2' style='background-color:#00007f'></div><div class='sk-cube sk-cube3' style='background-color:#00007f'></div><div class='sk-cube sk-cube4' style='background-color:#00007f'></div><div class='sk-cube sk-cube5' style='background-color:#00007f'></div><div class='sk-cube sk-cube6' style='background-color:#00007f'></div><div class='sk-cube sk-cube7' style='background-color:#00007f'></div><div class='sk-cube sk-cube8' style='background-color:#00007f'></div><div class='sk-cube sk-cube9' style='background-color:#00007f'></div></div><div class='sk-cube-grid' style='display:inline-block;position:relative;top:-84px;'><div class='sk-cube sk-cube1' style='background-color:gold'></div><div class='sk-cube sk-cube2' style='background-color:gold'></div><div class='sk-cube sk-cube3' style='background-color:gold'></div><div class='sk-cube sk-cube4' style='background-color:gold'></div><div class='sk-cube sk-cube5' style='background-color:gold'></div><div class='sk-cube sk-cube6' style='background-color:gold'></div><div class='sk-cube sk-cube7' style='background-color:gold'></div><div class='sk-cube sk-cube8' style='background-color:gold'></div><div class='sk-cube sk-cube9' style='background-color:gold'></div></div></div><div style='font-size:72px;text-align:center;' class='BankGothic'>Nouveau Elevator</div><div style='font-size:42px;text-align:center;'><i>Raising Your Life</i></div>"
		},
		"paging":true,
		"dom":"Bfrtip",
		"select":true,
		"initComplete":function(){
			
		},
		"scrollY" : "750px",
		"scrollCollapse":true,
		"lengthChange": false
		} );
    $(document).ready(function(){
		$("Table#Table_Customers").on("draw.dt",function(){hrefCustomers();});
    });
    </script>
  </div>
</div>
</script><?php
    }
} else {}?>
