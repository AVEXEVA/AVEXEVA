<?php
if(session_id() == '' || !isset($_SESSION)) {
    session_start();
    $_SESSION['page-target'] = 'collections';
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
	   	|| !isset($My_Privileges['Executive'])
	  		|| $My_Privileges['Executive']['User_Privilege']  < 4
	  		|| $My_Privileges['Executive']['Group_Privilege'] < 4
	  	    || $My_Privileges['Executive']['Other_Privilege'] < 4){
				?><?php require('../404.html');?><?php }
    else {
		sqlsrv_query($NEI,"
			INSERT INTO Portal.dbo.Activity([User], [Date], [Page])
			VALUES(?,?,?)
		;",array($_SESSION['User'],date("Y-m-d H:i:s"), "accounts_2019.php"));
?><div class="panel panel-primary">
  <div class="panel-heading"><h3><?php $Icons->Job();?> Accounts' Collections</h3></div>
  <div class="panel-body">
    <table id='Table_Collections' class='display' cellspacing='0' width='100%'>
      <thead>
        <th>Customer</th>
        <th>Account</th>
        <th>Collector</th>
        <th>Route</th>
        <th>Territory</th>
        <th>Balance</th>
        <th>90 Days</th>
        <th>180 Days</th>
        <th>365 Days</th>
        <th># Unpaid Inv.</th>
        <th>Last Payment Date</th>
        <!--<th>Last Payment Amount</th>-->
        <th># of New Inv. Since Last Payment</th>
        <th>Average Days Paid</th>
        <th>Payment History (180 Days)</th>
        <th>Count History (180 Days)</th>
      </thead>
    </table>
  </div>
</div>
<script>
$(document).ready(function() {
  $("input[name='Start']").datepicker();
  $("input[name='End']").datepicker();
   $.fn.dataTable.moment( 'MM/DD/YYYY' );
   var groupColumn = 0;
  var Table_Collections = $('#Table_Collections').DataTable( {
      "ajax": {
          "url":"cgi-bin/php/reports/Account_Collections.php?Start=<?php echo isset($_GET['Start']) ? $_GET['Start'] : null;?>&End=<?php echo isset($_GET['End']) ? $_GET['End'] : null;?>&Supervisor=<?php echo isset($_GET['Supervisor']) ? $_GET['Supervisor'] : null;?>",
          "dataSrc":function(json){if(!json.data){json.data = [];}return json.data;}
      },
      "columns": [
        { "data": "Customer"},
        { "data": "Account"},
        { "data": "Collector"},
        { "data": "Route"},
        { "data": "Territory"},
        { "data": "Balance"},
        { "data": "Days_90"},
        { "data": "Days_180"},
        { "data": "Days_365"},
        { "data": "No_Unpaid_Invoices"},
        { "data": "Last_Payment_Date"},
        //{ "data": "Last_Payment_Amount"},
        { "data": "No_New_Invoices"},
        { "data": "Average_Days_Paid", "className":"hidden"},
        { "data": "Sum_Payments"},
        { "data": "Count_Payments"}
      ],
      "fixedHeader": {
        header:true,
        headerOffset: 55
      },
      "language":{
        "loadingRecords":"<div style='text-align:center;'><div class='sk-cube-grid' style='display:inline-block;position:relative;';><div class='sk-cube sk-cube1' style='background-color:#cc0000'></div><div class='sk-cube sk-cube2' style='background-color:#cc0000'></div><div class='sk-cube sk-cube3' style='background-color:#cc0000'></div><div class='sk-cube sk-cube4' style='background-color:#cc0000'></div><div class='sk-cube sk-cube5' style='background-color:#cc0000'></div><div class='sk-cube sk-cube6' style='background-color:#cc0000'></div><div class='sk-cube sk-cube7' style='background-color:#cc0000'></div><div class='sk-cube sk-cube8' style='background-color:#cc0000'></div><div class='sk-cube sk-cube9' style='background-color:#cc0000'></div></div><div class='sk-cube-grid' style='display:inline-block;position:relative;top:-45px;'><div class='sk-cube sk-cube1' style='background-color:#00007f'></div><div class='sk-cube sk-cube2' style='background-color:#00007f'></div><div class='sk-cube sk-cube3' style='background-color:#00007f'></div><div class='sk-cube sk-cube4' style='background-color:#00007f'></div><div class='sk-cube sk-cube5' style='background-color:#00007f'></div><div class='sk-cube sk-cube6' style='background-color:#00007f'></div><div class='sk-cube sk-cube7' style='background-color:#00007f'></div><div class='sk-cube sk-cube8' style='background-color:#00007f'></div><div class='sk-cube sk-cube9' style='background-color:#00007f'></div></div><div class='sk-cube-grid' style='display:inline-block;position:relative;top:-84px;'><div class='sk-cube sk-cube1' style='background-color:gold'></div><div class='sk-cube sk-cube2' style='background-color:gold'></div><div class='sk-cube sk-cube3' style='background-color:gold'></div><div class='sk-cube sk-cube4' style='background-color:gold'></div><div class='sk-cube sk-cube5' style='background-color:gold'></div><div class='sk-cube sk-cube6' style='background-color:gold'></div><div class='sk-cube sk-cube7' style='background-color:gold'></div><div class='sk-cube sk-cube8' style='background-color:gold'></div><div class='sk-cube sk-cube9' style='background-color:gold'></div></div></div><div style='font-size:72px;text-align:center;' class='BankGothic'>Nouveau Elevator</div><div style='font-size:42px;text-align:center;'><i>Raising Your Life</i></div>"
      },
      "paging":false,
      "dom":"Bfrtip",
      "select":true,
      "initComplete":function(){
      },
      "scrollY" : "600px",
      "scrollCollapse":true
  } );
} );
    </script>
</body>
</html>
<?php
    }
} else {?><html><head><script>document.location.href='../login.php?Forward=accounts_2019.php';</script></head></html><?php }?>
