<?php
if(session_id() == '' || !isset($_SESSION)) {
    session_start();
    $_SESSION['page-target'] = 'paid_time_off';
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
?><div class='panel panel-primary'>
  <div class='panel-heading'>Paid Time Off Report</div>
  <div class='panel-body'>
		<table id='Table_Paid_Time_Off' class='display' cellspacing='0' width='100%' height='800px'>
			<thead>
        <th>Employee ID</th>
				<th>First Name</th>
        <th>Last Name</th>
        <th>Sick 2014</th>
        <th>Vacation 2014</th>
        <th>Unpaid 2014</th>
        <th>Lieu 2014</th>
        <th>Medical 2014</th>
        <th>Total 2014</th>
        <th>Sick 2015</th>
        <th>Vacation 2015</th>
        <th>Unpaid 2015</th>
        <th>Lieu 2015</th>
        <th>Medical 2015</th>
        <th>Total 2015</th>
        <th>Sick 2016</th>
        <th>Vacation 2016</th>
        <th>Unpaid 2016</th>
        <th>Lieu 2016</th>
        <th>Medical 2016</th>
        <th>Total 2016</th>
        <th>Sick 2017</th>
        <th>Vacation 2017</th>
        <th>Unpaid 2017</th>
        <th>Lieu 2017</th>
        <th>Medical 2017</th>
        <th>Total 2017</th>
			</thead>
		</table>
  </div>
</div>
<script>
$(document).ready(function() {
  var Table_Paid_Time_Off = $('#Table_Paid_Time_Off').DataTable( {
      "ajax": {
          "url":"cgi-bin/php/reports/Paid_Time_Off.php",
          "dataSrc":function(json){if(!json.data){json.data = [];}return json.data;}
      },
      "columns": [
          { "data": "Employee_ID"},
          { "data": "Last_Name"},
          { "data": "First_Name"},
          { "data": "Sick_2014"},
          { "data": "Vacation_2014"},
          { "data": "Unpaid_2014"},
          { "data": "Lieu_2014"},
          { "data": "Medical_2014"},
          { "data": "Total_2014"},
          { "data": "Sick_2015"},
          { "data": "Vacation_2015"},
          { "data": "Unpaid_2015"},
          { "data": "Lieu_2015"},
          { "data": "Medical_2015"},
          { "data": "Total_2015"},
          { "data": "Sick_2016"},
          { "data": "Vacation_2016"},
          { "data": "Unpaid_2016"},
          { "data": "Lieu_2016"},
          { "data": "Medical_2016"},
          { "data": "Total_2016"},
          { "data": "Sick_2017"},
          { "data": "Vacation_2017"},
          { "data": "Unpaid_2017"},
          { "data": "Lieu_2017"},
          { "data": "Medical_2017"},
          { "data": "Total_2017"}
      ],
      "dom":"Bfrtip",
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
      'paging':false,
      "order": [[1, 'asc']],
      "language":{
          "loadingRecords":""
      },
      "initComplete":function(){
      }
  } );
} );
</script>
<?php
    }
} else {?><html><head><script>document.location.href='../login.php?Forward=accounts_2019.php';</script></head></html><?php }?>
