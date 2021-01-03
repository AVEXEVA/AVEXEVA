<?php
if(session_id() == '' || !isset($_SESSION)) {
    session_start();
    $_SESSION['page-target'] = 'bugs';
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
  <div class="panel-heading"><h4><?php $Icons->Bug();?> Bugs</h4></div>
  <div class='panel-body'>
    <?php $r = sqlsrv_query($Portal,"
        SELECT
            Bug.ID,
            Bug.Name,
            Bug.Description,
            Bug.Resolution,
            Bug.Fixed,
            Bug.Suggestion,
            Severity.Name AS Severity
        FROM Bug LEFT JOIN Severity ON Bug.Severity = Severity.ID
    ;");
    if($r){while($Bug = sqlsrv_fetch_array($r)){?>
    <div class='row'>
        <div class='col-md-2'>Name:</div><div class='col-md-10'><?php echo $Bug['Name'];?></div>
        <div class='col-md-2'>Severity:</div><div class='col-md-10'><?php echo $Bug['Severity'];?></div>
        <div class='col-md-2'>Description:</div><div class='col-md-10'><?php echo $Bug['Description'];?></div>
        <div class='col-md-2'>Suggestion:</div><div class='col-md-10'><?php echo strlen($Bug['Suggestion']) > 0 ? $Bug['Suggestion'] : '&nbsp;';?></div>
        <div class='col-md-2'>Resolution:</div><div class='col-md-10'><?php echo strlen($Bug['Resolution']) > 0 ? $Bug['Resolution'] : '&nbsp;';?></div>
        <div class='col-md-2'>Fixed:</div><div class='col-md-10'><?php echo strlen($Bug['Fixed']) > 0 ? $Bug['Fixed'] : '&nbsp;';?></div>
    </div><hr /><?php }}?>
  </div>
</div>
<script>
$(document).ready(function(){
  var Table_Violations = $('#Table_Violations').DataTable( {
    "processing":true,
    "serverSide":true,
      "ajax": {
        "url":"cgi-bin/php/get/Violations.php",
      },
    "order":[[1,"desc"]],
    "columns": [
      {
        "data":"ID",
      },{
        "data":"Name"
      },{
        "data":"Tag"
      },{
        "data":"State"
      },{
        "data":"fDate",
        render: function(data){if(data.length > 0){return data.substr(5,2) + "/" + data.substr(8,2) + "/" + data.substr(0,4);}else{return null;}}
      },{
        "data":"Status"
      }
    ],
    "language":{
      "loadingRecords":"<div style='text-align:center;'><div class='sk-cube-grid' style='display:inline-block;position:relative;';><div class='sk-cube sk-cube1' style='background-color:#cc0000'></div><div class='sk-cube sk-cube2' style='background-color:#cc0000'></div><div class='sk-cube sk-cube3' style='background-color:#cc0000'></div><div class='sk-cube sk-cube4' style='background-color:#cc0000'></div><div class='sk-cube sk-cube5' style='background-color:#cc0000'></div><div class='sk-cube sk-cube6' style='background-color:#cc0000'></div><div class='sk-cube sk-cube7' style='background-color:#cc0000'></div><div class='sk-cube sk-cube8' style='background-color:#cc0000'></div><div class='sk-cube sk-cube9' style='background-color:#cc0000'></div></div><div class='sk-cube-grid' style='display:inline-block;position:relative;top:-45px;'><div class='sk-cube sk-cube1' style='background-color:#00007f'></div><div class='sk-cube sk-cube2' style='background-color:#00007f'></div><div class='sk-cube sk-cube3' style='background-color:#00007f'></div><div class='sk-cube sk-cube4' style='background-color:#00007f'></div><div class='sk-cube sk-cube5' style='background-color:#00007f'></div><div class='sk-cube sk-cube6' style='background-color:#00007f'></div><div class='sk-cube sk-cube7' style='background-color:#00007f'></div><div class='sk-cube sk-cube8' style='background-color:#00007f'></div><div class='sk-cube sk-cube9' style='background-color:#00007f'></div></div><div class='sk-cube-grid' style='display:inline-block;position:relative;top:-84px;'><div class='sk-cube sk-cube1' style='background-color:gold'></div><div class='sk-cube sk-cube2' style='background-color:gold'></div><div class='sk-cube sk-cube3' style='background-color:gold'></div><div class='sk-cube sk-cube4' style='background-color:gold'></div><div class='sk-cube sk-cube5' style='background-color:gold'></div><div class='sk-cube sk-cube6' style='background-color:gold'></div><div class='sk-cube sk-cube7' style='background-color:gold'></div><div class='sk-cube sk-cube8' style='background-color:gold'></div><div class='sk-cube sk-cube9' style='background-color:gold'></div></div></div><div style='font-size:72px;text-align:center;' class='BankGothic'>Nouveau Elevator</div><div style='font-size:42px;text-align:center;'><i>Raising Your Life</i></div>"
    },
    "paging":true,
    "select":true,
    "initComplete":function(){},
    "scrollY" : "850px",
    "scrollCollapse":true,
    "lengthChange": true
  });
});
</script><?php
    }
} else {}?>
