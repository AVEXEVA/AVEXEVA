<?php
if(session_id() == '' || !isset($_SESSION)) {
    session_start();
    $_SESSION['page-target'] = 'code_tests';
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
      ;",array($_SESSION['User'],date("Y-m-d H:i:s"), "category_tests2.php"));
?><div class="panel panel-primary">
  <div class="panel-heading"><h4><div style='float:left;' onClick="document.location.href='home.php';"><?php $Icons->Proposal();?> Proposals</div><div style='clear:both;'></div></h4></div>
  <div class="panel-body">
    <table id='Table_Deficiencies' class='display' cellspacing='0' width='100%' style='font-size:12px;'>
      <thead>
        <th>ID</th>
        <th>Category ID</th>
        <th>Violation</th>
        <th>V. Status</th>
        <th>Cat Test Date</th>
        <th>Location</th>
        <th>Unit</th>
        <th>Part</th>
        <th>Condition</th>
        <th>Remedy</th>
        <th>Percentage</th>
      </thead>
    </table>
  </div>
</div>
<script>
var Table_Deficiencies = $('#Table_Deficiencies').DataTable( {
  "ajax": "cgi-bin/php/reports/Deficiency_Proposals.php",
  "columns": [
    {
      "data":"ID"
    },{
      "data":"Category_ID",
    },{
      "data":"Violation_Name",
    },{
      "data":"Violation_Status",
    },{
      "data":"Date",
    },{
      "data":"Location_Name"
    },{
      "data":"Unit_Name",
    },{
      "data":"Part"
    },{
      "data":"Condition"
    },{
      "data":"Remedy"
    },{
      "data":"Percentage"
    }
  ],
  "language":{
    "loadingRecords":"<div style='text-align:center;'><div class='sk-cube-grid' style='display:inline-block;position:relative;';><div class='sk-cube sk-cube1' style='background-color:#cc0000'></div><div class='sk-cube sk-cube2' style='background-color:#cc0000'></div><div class='sk-cube sk-cube3' style='background-color:#cc0000'></div><div class='sk-cube sk-cube4' style='background-color:#cc0000'></div><div class='sk-cube sk-cube5' style='background-color:#cc0000'></div><div class='sk-cube sk-cube6' style='background-color:#cc0000'></div><div class='sk-cube sk-cube7' style='background-color:#cc0000'></div><div class='sk-cube sk-cube8' style='background-color:#cc0000'></div><div class='sk-cube sk-cube9' style='background-color:#cc0000'></div></div><div class='sk-cube-grid' style='display:inline-block;position:relative;top:-45px;'><div class='sk-cube sk-cube1' style='background-color:#00007f'></div><div class='sk-cube sk-cube2' style='background-color:#00007f'></div><div class='sk-cube sk-cube3' style='background-color:#00007f'></div><div class='sk-cube sk-cube4' style='background-color:#00007f'></div><div class='sk-cube sk-cube5' style='background-color:#00007f'></div><div class='sk-cube sk-cube6' style='background-color:#00007f'></div><div class='sk-cube sk-cube7' style='background-color:#00007f'></div><div class='sk-cube sk-cube8' style='background-color:#00007f'></div><div class='sk-cube sk-cube9' style='background-color:#00007f'></div></div><div class='sk-cube-grid' style='display:inline-block;position:relative;top:-84px;'><div class='sk-cube sk-cube1' style='background-color:gold'></div><div class='sk-cube sk-cube2' style='background-color:gold'></div><div class='sk-cube sk-cube3' style='background-color:gold'></div><div class='sk-cube sk-cube4' style='background-color:gold'></div><div class='sk-cube sk-cube5' style='background-color:gold'></div><div class='sk-cube sk-cube6' style='background-color:gold'></div><div class='sk-cube sk-cube7' style='background-color:gold'></div><div class='sk-cube sk-cube8' style='background-color:gold'></div><div class='sk-cube sk-cube9' style='background-color:gold'></div></div></div><div style='font-size:72px;text-align:center;' class='BankGothic'>Nouveau Elevator</div><div style='font-size:42px;text-align:center;'><i>Raising Your Life</i></div>"
  },
  "paging":false,
  "dom":"Bfrtip",
  "select":true,
  "initComplete":function(){},
  "scrollY" : "900px",
  "scrollCollapse":true,
  "lengthChange": false,
  "order": [[ 1, "ASC" ]]
} );
</script><?php
    }
} else {}?>
