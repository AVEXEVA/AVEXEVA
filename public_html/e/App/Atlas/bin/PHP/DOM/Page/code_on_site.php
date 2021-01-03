<?php
if(session_id() == '' || !isset($_SESSION)) {
    session_start();
    $_SESSION['page-target'] = 'due_violations';
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
	  		|| $My_Privileges['Location']['Group_Privilege'] < 4){}
    else {
		sqlsrv_query($NEI,"
			INSERT INTO Portal.dbo.Activity([User], [Date], [Page])
			VALUES(?,?,?)
		;",array($_SESSION['User'],date("Y-m-d H:i:s"), "locations.php"));
?><div class="panel panel-primary" style='height:100%;'>
  <div class="panel-heading" style='background-color:#1f1f1f;color:white;border-bottom:0px solid black;'>
    <h4 style='float:left;'><div onClick=""><?php $Icons->Location();?> Locations</div></h4>
    <div style='clear:both;'></div>
  </div>
  <div class="panel-body" style='height:90%;background-color:#2a2a2a'>
    <div class='row' style='padding-top:50px;'>
      <div class='col-xs-12' style='font-weight:bold;font-size:24px;'>Violation Opportunities (On Site Mechanics w/ Open Violations)</div>
      <div class='col-xs-12'>
        <table id='Opportunities' class='display' cellspacing='0' width='100%' style='font-size:12px;'>
          <thead><tr>
            <th>Location</th>
            <th>Violations</th>
            <th>Mechanic</th>
            <th>Division</th>
            <th>Level</th>
          </tr></thead>
        </table>
      </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function(){
  var Opportunities = $('table#Opportunities').DataTable( {
    'ajax':"cgi-bin/php/reports/Violation_Opportunities.php",
    'columns':[
      {
        data:"Location_Tag"
      },{
        data:"Violation_Count"
      },{
        data:"Employee_Name"
      },{
        data:"Division"
      },{
        data:"Level",
        render:function(data){
          if(data == 4){
            return 'Violations';
          } else {
            return 'Other';
          }
        }
      }
    ],
    'buttons':[
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
    'scrollY' : "600px",
    'scrollCollapse':true,
    "lengthChange": true,
    "order": [[ 1, "ASC" ]]
  });
});
</script><?php
    }
} else {}?>
