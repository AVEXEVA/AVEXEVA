<?php
if(session_id() == '' || !isset($_SESSION)) {
    session_start();
    $_SESSION['page-target'] = 'privileges';
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
  <div class="panel-heading" style='background-color:#1f1f1f;color:white;border-bottom:0px solid black;'>
    <h4 style='float:left;'><div onClick=""><?php $Icons->Privilege();?> Privileges</div></h4>
    <div style='clear:both;'></div>
  </div>
  <div class="panel-body" style='height:90%;background-color:#2a2a2a'>
    <table id='Table_Privileges' class='display' cellspacing='0' width='100%'>
        <thead>
            <th title="Employee Work ID">Work ID</th>
            <th title="Employee's First Name">Last Name</th>
            <th title="Employee's First Name">First Name</th>
            <th title="Employee's Beta Privelege">Beta Access</th>
        </thead>
    </table>
    <script>
    $(document).ready(function() {
        var Table_Privileges = $('#Table_Privileges').DataTable( {
            "ajax": {
                "url":"cgi-bin/php/get/Privileges.php",
                "dataSrc":function(json){if(!json.data){json.data = [];}return json.data;}
            },
            "columns": [
                { "data": "ID"},
                { "data": "Last_Name"},
                { "data": "First_Name"},
                { "data": "Beta"}
            ],
            "order": [[1, 'asc']],
            "language":{
                "loadingRecords":""
            },
            "initComplete":function(){
                hrefEmployees();
                $("input[type='search'][aria-controls='Table_Privileges']").on('keyup',function(){hrefEmployees();});
                $('#Table_Privileges').on( 'page.dt', function () {setTimeout(function(){hrefEmployees();},100);});
                $("#Table_Privileges th").on("click",function(){setTimeout(function(){hrefEmployees();},100);});
                finishLoadingPage();
            }

        } );
    } );
    </script>
  </div>
</div>
</script><?php
    }
} else {}?>
