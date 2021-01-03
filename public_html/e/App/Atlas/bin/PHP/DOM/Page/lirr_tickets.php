<?php
if(session_id() == '' || !isset($_SESSION)) {
    session_start();
    $_SESSION['page-target'] = 'map';
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
?><div class="panel panel-primary">
  <div class="panel-heading"><h3><?php $Icons->Ticket();?> LIRR Tickets</h3></div>
  <div class="panel-body">
    <div class='row'>
      <div class='col-xs-2'>Start:</div>
      <div class='col-xs-10'><input type='text' name='Start' /></div>
      <div class='col-xs-2'>End:</div>
      <div class='col-xs-10'><input type='text' name='End' /></div>
      <div class='col-xs-3'><button onClick='getTickets();'>Get Tickets</button></div>
    </div>
  </div>
  <div class='panel-body' id='lirr-tickets'></div>
</div>
<link rel="stylesheet" type="text/css" href="cgi-bin/libraries/map-icons-master/dist/css/map-icons.css">
<script type="text/javascript" src="cgi-bin/libraries/map-icons-master/dist/js/map-icons.js"></script>
<script>
function getTickets(){
  open_page("<div page-target='lirr_tickets'></div>", {'Start' : $("input[name='Start']").val(), 'End' : $("input[name='End']").val()});
}
$(document).ready(function(){
  $("input[name='Start']").datepicker();
  $("input[name='End']").datepicker();
});
<?php
$_GET['Start'] = date("Y-m-d 00:00:00.000",strtotime($_GET['Start']));
$_GET['End'] = date("Y-m-d 00:00:00.000",strtotime($_GET['End']));
$r = sqlsrv_query($NEI,"SELECT TicketD.ID AS ID FROM nei.dbo.TicketD LEFT JOIN nei.dbo.Job ON TicketD.Job = Job.ID WHERE TicketD.EDate >= ? AND TicketD.EDate < ? AND Job.Owner = ? AND (Job.ID = 90174 OR Job.ID = 102928 OR Job.ID = 125536) ORDER BY Job.ID ASC, TicketD.EDate ASC;",array($_GET['Start'],$_GET['End'],2126));
if($r){
  while($row = sqlsrv_fetch_array($r)){
    ?>$(document).ready(function(){
      $.ajax({
        url:"short-ticket.php?ID=<?php echo $row['ID'];?>",
        method:"GET",
        success:function(code){$("#lirr-tickets").append(code);}
      })
    });<?php
  }
}
?>
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAJwGnwOrNUvlYnmB5sdJGkXy8CQsTA46g&callback=renderMap"></script>
<script type='text/javascript' src='https://maps.googleapis.com/maps/api/directions/json?origin=43.65077%2C-79.378425&destination=43.63881%2C-79.42745&key=AIzaSyAJwGnwOrNUvlYnmB5sdJGkXy8CQsTA46g'></script>
<?php
    }
} else {}?>
