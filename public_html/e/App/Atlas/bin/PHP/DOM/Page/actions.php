<?php
if(session_id() == '' || !isset($_SESSION)) {
    session_start();
    $_SESSION['page-target'] = 'actions';
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
  <div class="panel-heading"><h3><?php $Icons->Action();?> Actions</h3></div>
  <div class="panel-body">

    <div id="flot-actions" style="width:100%;height:500px;display:inline-block;"></div>
    <script src="../vendor/flot/excanvas.min.js"></script>
    <script src="../vendor/flot/jquery.flot.js"></script>
    <script src="../vendor/flot/jquery.flot.pie.js"></script>
    <script src="../vendor/flot/jquery.flot.resize.js"></script>
    <script src="../vendor/flot/jquery.flot.time.js"></script>
    <script src="../vendor/flot/jquery.flot.symbol.js"></script>
    <script src="../vendor/flot/jquery.flot.axislabels.js"></script>
    <script src="../vendor/flot-tooltip/jquery.flot.tooltip.min.js"></script>
    <?php if($_GET['Preloaded'] == 1){require('cgi-bin/js/chart/actions.php');} else {require('../../../cgi-bin/js/chart/actions.php');}?>
    <script>
    $(document).ready(function(){
      $('.flot-tick-label').css('color','white');
    });
    </script>
  </div>
</div>
<?php
    }
} else {}?>
