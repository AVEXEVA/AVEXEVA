<?php
if(session_id() == '' || !isset($_SESSION)) {
    session_start();
    $_SESSION['page-target'] = 'job_review';
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
  <div class="panel-heading"><h3><?php $Icons->Job();?> Job Review With Open Ticket</h3></div>
  <div class='panel-heading'>
    <script>
    function refresh(){
      //document.location.href='job_review_with_open_ticket.php?Start=' + $("input[name='Start']").val() + "&End=" + $("input[name='End']").val() + "&Supervisor=" + $("select[name='Supervisor']").val();
      open_page($('a[page-target="last_invoice_review"]'), {Start:$("input[name='Start']").val(), End:$("input[name='End']").val(), Supervisor:$("select[name='Supervisor']").val()});
    }
    </script>
    <input type='text' name='Start' style='color:black;' value='<?php echo $_GET['Start'];?>' />
    <input type='text' name='End' style='color:black;' value='<?php echo $_GET['End'];?>'  />
    <select name='Supervisor' style='color:black;'>
      <?php
      $r = sqlsrv_query($NEI,"SELECT Job.Custom1 FROM nei.dbo.Job GROUP BY Job.Custom1;");
      if($r){while($row = sqlsrv_fetch_array($r)){
        ?><option value='<?php echo $row['Custom1'];?>' <?php echo isset($_GET['Supervisor']) && $_GET['Supervisor'] == $row['Custom1'] ? 'selected' : null;?>><?php echo $row['Custom1'];?></option><?php
      }}
      ?>
    </select>
    <button onClick='refresh();' style='color:black;' >Refresh</button>
  </div>
  <div class="panel-body">
    <table id='Table_Last_Invoice_Review' class='display' cellspacing='0' width='100%'>
      <thead>
        <th>Job</th>
        <th>Date Created</th>
        <th>Description</th>
        <th>Account</th>
        <th>Territory</th>
        <th>Contract Amount</th>
        <th>Billing Amount</th>
        <th>Supervisor</th>
        <th>Hours Charged</th>
        <th>Open Ticket</th>
      </thead>
    </table>
  </div>
</div>
<script>
$(document).ready(function() {
  $("input[name='Start']").datepicker();
  $("input[name='End']").datepicker();
   $.fn.dataTable.moment( 'MM/DD/YYYY' );
   var groupColumn = 3;
  var Table_Last_Invoice_Review = $('#Table_Last_Invoice_Review').DataTable( {
      "ajax": {
          "url":"cgi-bin/php/reports/Job_Review_With_Open_Ticket.php?Start=<?php echo isset($_GET['Start']) ? $_GET['Start'] : null;?>&End=<?php echo isset($_GET['End']) ? $_GET['End'] : null;?>&Supervisor=<?php echo isset($_GET['Supervisor']) ? $_GET['Supervisor'] : null;?>",
          "dataSrc":function(json){if(!json.data){json.data = [];}return json.data;}
      },
      "columns": [
          { "data": "Job_ID"},
          { "data": "Date_Created"},
          { "data": "Description"},
          { "data": "Account"},
          { "data": "Territory"},
          { "data": "Contract_Amount"},
          { "data": "Billing_Amount"},
          { "data": "Supervisor"},
          { "data": "Hours_Charged"},
          { "data": "Open_Ticket"}
      ]
  } );
} );
    </script>
</body>
</html>
<?php
    }
} else {?><html><head><script>document.location.href='../login.php?Forward=accounts_2019.php';</script></head></html><?php }?>
