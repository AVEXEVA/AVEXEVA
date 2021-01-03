<?php
if(session_id() == '' || !isset($_SESSION)) {
    session_start();
    $_SESSION['page-target'] = 'job_hours';
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
  <div class="panel-heading"><h3><?php $Icons->Job();?> Job Review Modernization Overtime</h3></div>
  <div class='panel-heading'><div class='row'><div class='col-xs-3'><button onClick="viewTickets();" style='color:Black !important;'>View Tickets</button></div></div></div>
  <div class='panel-heading'>
    <script>

    function refresh(){
      open_page("<div page-target='job_overtime'></div>", {
        'Start'       : $("input[name='Start']").val(),
        'End'         : $("input[name='End']").val(),
        'Supervisor'  : $("select[name='Supervisor']").val()
      });
      //document.location.href='job_review_mod_supervisors.php?Start=' + $("input[name='Start']").val() + "&End=" + $("input[name='End']").val() + "&Supervisor=" + $("select[name='Supervisor']").val();
    }
    </script>
    <input type='text' name='Start' style='color:black;' value='<?php echo $_GET['Start'];?>' style='color:black;' />
    <input type='text' name='End' style='color:black;' value='<?php echo $_GET['End'];?>' style='color:black;'  />
    <button onClick='refresh();' style='color:black;' >Refresh</button>
  </div>
  <div class="panel-body">
    <table id='Table_Jobs' class='display' cellspacing='0' width='100%'>
      <thead>
        <th>Job ID</th>
        <th>Class</th>
        <th>Supervisor/Employee</th>
        <th>ID</th>
        <th>Date Created</th>
        <th>Description</th>
        <th>Account/Resolution</th>
        <th>OT</th>
        <th>DT</th>
      </thead>
    </table>
  </div>
</div>
<style>
tr.selected[role='row'], div table#Table_Jobs tbody tr.selected[role='row'] td[class^="sorting"] {
  background-color:yellow !important;
  color:black !important;
}
div table#Table_Jobs tbody tr.white-or-gray {
  background-color:lightgray !important;;
  color:black !important;
}
div table#Table_Jobs tbody tr[role='row'].white-or-gray.selected {
  background-color:gold !important;;
  color:black !important;
}
</style>
<script>
var Table_Tickets;
$(document).ready(function() {
  $("input[name='Start']").datepicker();
  $("input[name='End']").datepicker();
   $.fn.dataTable.moment( 'MM/DD/YYYY' );
   var groupColumn = 0;
   Table_Tickets = $('#Table_Jobs').DataTable( {
      "ajax": {
          "url":"cgi-bin/php/reports/Job_Review_Mod_Supervisors.php?Start=<?php echo isset($_GET['Start']) ? $_GET['Start'] : null;?>&End=<?php echo isset($_GET['End']) ? $_GET['End'] : null;?>&Supervisor=<?php echo isset($_GET['Supervisor']) ? $_GET['Supervisor'] : null;?>",
          "dataSrc":function(json){if(!json.data){json.data = [];}return json.data;}
      },
      "rowGroup":{
          dataSrc: 'Supervisor'
      },
      "select":true,
      'createdRow':function(row, data, dataIndex){
        if($(row).find('td:eq(1)').html() == 'Ticket'){
          $(row).addClass('white-or-gray');
        }
      },
      "columns": [
          { "data":"Job_ID", "className":"hidden"},
          { "data":"Class", "className":"hidden"},
          { "data": "Supervisor", "targets": groupColumn},
          { "data": "ID"},
          { "data": "Date_Created"},
          { "data": "Description"},
          { "data": "Account"},
          { "data": "OT"},
          { "data": "DT"}
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
function viewTickets(){
  $("tr.selected").each(function(){
    var link = this;
    $.ajax({
      method:"GET",
      url:"cgi-bin/php/reports/Job_Review_Mod_Supervisors_Tickets.php",
      data:{'ID':$(link).children("td:first-child").html(), 'Start':$('input[name="Start"]').val(), 'End':$("input[name='End']").val()},
      success:function(code){
        var jsonData = JSON.parse(code);
        Table_Tickets.rows.add(jsonData.data);
        Table_Tickets.draw();
      //{$(link).after(code);

      }
    });
  });
}
</script>
<?php
    }
} else {?><html><head><script>document.location.href='../login.php?Forward=accounts_2019.php';</script></head></html><?php }?>
