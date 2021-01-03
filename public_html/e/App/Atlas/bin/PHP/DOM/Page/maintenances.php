<?php
if(session_id() == '' || !isset($_SESSION)) {
    session_start();
    $_SESSION['page-target'] = 'maintenances';
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
	   	|| !isset($My_Privileges['Location'])
	  		|| $My_Privileges['Location']['User_Privilege']  < 4
	  		|| $My_Privileges['Location']['Group_Privilege'] < 4){echo 'bye world';}
    else {
		sqlsrv_query($NEI,"
			INSERT INTO Portal.dbo.Activity([User], [Date], [Page])
			VALUES(?,?,?)
		;",array($_SESSION['User'],date("Y-m-d H:i:s"), "locations.php"));
?><div class="panel panel-primary" style='height:100%;'>
  <div class="panel-heading"><h3><?php $Icons->Unit();?>Required Maintenance</h3></div>
  <div class="panel-body">
    <table id='Table_Units' class='display' cellspacing='0' width='100%'>
      <thead>
        <th title="Unit's ID">ID</th>
        <th title='Unit State ID'>State</th>
        <th title="Unit's Label">Unit</th>
        <th title="Type of Unit">Type</th>
        <th title="Unit's Location">Location</th>
        <th>Route</th>
        <th>Division</th>
        <th>Worked On Last</th>
      </thead>
    </table>
  </div>
</div>
<script>
$(document).ready(function() {
  var Table_Units = $('#Table_Units').DataTable( {
      "ajax": {
          "url":"cgi-bin/php/reports/Maintenances.php",
          "dataSrc":function(json){if(!json.data){json.data = [];}return json.data;}
      },
      "columns": [
          { "data": "ID" },
          { "data": "State"},
          { "data": "Unit"},
          { "data": "Type"},
          { "data": "Location"},
          { "data": "Route"},
          { "data": "Zone"},
          {
            "data": "Last_Date",
            render: function(data, type){
              return type === 'sort' ? data : moment(data).format('L');
            }
          }
      ],
      "order": [[1, 'asc']],
      "language":{"loadingRecords":""},
      "initComplete":function(){
          finishLoadingPage();
      },
      "lengthMenu":[[10,25,50,100,500,-1,0],[10,25,50,100,500,"All","None"]]
  } );
} );
</script><?php
    }
} else {}?>
