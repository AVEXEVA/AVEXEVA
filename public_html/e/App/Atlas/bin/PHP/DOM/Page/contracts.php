<?php
if(session_id() == '' || !isset($_SESSION)) {
    session_start();
    $_SESSION['page-target'] = 'contracts';
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
  <div class="panel-heading"><h4><?php $Icons->Contract();?> Contracts</h4></div>
  <div class="panel-body">
    <table id='Table_Contracts' class='display' cellspacing='0' width='100%' style='font-size:12px;'>
      <thead>
        <th></th>
        <th>Customer</th>
        <th>Location</th>
        <th>Start Date</th>
        <th>Amount</th>
        <th>Length</th>
        <th>End Date</th>
        <th>Review Date</th>
        <th>Escalation</th>
        <th>Escalation Date</th>
        <th>Escalation Type</th>
        <th>Escalation Cycle</th>
        <th>Link</th>
        <th>Remarks</th>
      </thead>
    </table>
  </div>
</div>
<script>
var isChromium = window.chrome,
  winNav = window.navigator,
  vendorName = winNav.vendor,
  isOpera = winNav.userAgent.indexOf("OPR") > -1,
  isIEedge = winNav.userAgent.indexOf("Edge") > -1,
  isIOSChrome = winNav.userAgent.match("CriOS");
$(document).ready(function() {
  var Table_Contracts = $('#Table_Contracts').DataTable( {
    "ajax": {
      "url":"cgi-bin/php/get/Contracts.php",
      "dataSrc":function(json){if(!json.data){json.data = [];}return json.data;}
    },
    "columns": [
      {
        "data": "Contract_Job",
        "visible":false
      },
      { "data": "Customer_Name"},
      { "data": "Location_Name"},
      { "data": "Contract_Start_Date",
          render: function(data) {return data.substr(5,2) + "/" + data.substr(8,2) + "/" + data.substr(0,4);}},
      { "data": "Contract_Amount"},
      { "data": "Contract_Length"},
      { "data": "Contract_End_Date",
          render: function(data) {return data.substr(5,2) + "/" + data.substr(8,2) + "/" + data.substr(0,4);}},
      { "data": "Contract_Review_Date",
          render: function(data) {return data.substr(5,2) + "/" + data.substr(8,2) + "/" + data.substr(0,4);}},
      { "data": "Contract_Escalation_Factor"},
      { "data": "Contract_Escalation_Date",
          render: function(data) {return data.substr(5,2) + "/" + data.substr(8,2) + "/" + data.substr(0,4);}},
      { "data": "Contract_Escalation_Type",
        "visible":false},
      { "data": "Contract_Escalation_Cycle"},
      { "data": "Link"},
      { "data": "Job_Remarks"}
    ],
    "fixedHeader": {
      header:true,
      headerOffset: 55
    },
    "language":{
      "loadingRecords":"<div style='text-align:center;'><div class='sk-cube-grid' style='display:inline-block;position:relative;';><div class='sk-cube sk-cube1' style='background-color:#cc0000'></div><div class='sk-cube sk-cube2' style='background-color:#cc0000'></div><div class='sk-cube sk-cube3' style='background-color:#cc0000'></div><div class='sk-cube sk-cube4' style='background-color:#cc0000'></div><div class='sk-cube sk-cube5' style='background-color:#cc0000'></div><div class='sk-cube sk-cube6' style='background-color:#cc0000'></div><div class='sk-cube sk-cube7' style='background-color:#cc0000'></div><div class='sk-cube sk-cube8' style='background-color:#cc0000'></div><div class='sk-cube sk-cube9' style='background-color:#cc0000'></div></div><div class='sk-cube-grid' style='display:inline-block;position:relative;top:-45px;'><div class='sk-cube sk-cube1' style='background-color:#00007f'></div><div class='sk-cube sk-cube2' style='background-color:#00007f'></div><div class='sk-cube sk-cube3' style='background-color:#00007f'></div><div class='sk-cube sk-cube4' style='background-color:#00007f'></div><div class='sk-cube sk-cube5' style='background-color:#00007f'></div><div class='sk-cube sk-cube6' style='background-color:#00007f'></div><div class='sk-cube sk-cube7' style='background-color:#00007f'></div><div class='sk-cube sk-cube8' style='background-color:#00007f'></div><div class='sk-cube sk-cube9' style='background-color:#00007f'></div></div><div class='sk-cube-grid' style='display:inline-block;position:relative;top:-84px;'><div class='sk-cube sk-cube1' style='background-color:gold'></div><div class='sk-cube sk-cube2' style='background-color:gold'></div><div class='sk-cube sk-cube3' style='background-color:gold'></div><div class='sk-cube sk-cube4' style='background-color:gold'></div><div class='sk-cube sk-cube5' style='background-color:gold'></div><div class='sk-cube sk-cube6' style='background-color:gold'></div><div class='sk-cube sk-cube7' style='background-color:gold'></div><div class='sk-cube sk-cube8' style='background-color:gold'></div><div class='sk-cube sk-cube9' style='background-color:gold'></div></div></div><div style='font-size:72px;text-align:center;' class='BankGothic'>Nouveau Elevator</div><div style='font-size:42px;text-align:center;'><i>Raising Your Life</i></div>"
    },
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
      },
    ],
    "dom":"Bfrtip",
    "select":true,
    "initComplete":function(){
    },
    "scrollY" : "600px",
    "scrollCollapse":true,
    "paging":true,
    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]/*,
    "changeLength":false*/
  } );
} );
</script>
<?php
    }
} else {?><html><head><script>document.location.href='../login.php?Forward=accounts_2019.php';</script></head></html><?php }?>
