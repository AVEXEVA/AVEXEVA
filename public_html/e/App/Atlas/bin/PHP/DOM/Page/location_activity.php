<?php
if(session_id() == '' || !isset($_SESSION)) {
    session_start();
    $_SESSION['page-target'] = 'location_activity';
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
  <div class="panel-heading"><h4><?php $Icons->Location();?> Location Activity</h4></div>
  <div class='panel-body'>
    <div>
        Start <input type='text' name='Start' value='<?php echo $_GET['Start'];?>' />
        End <input type='text' name='End' value='<?php echo $_GET['End'];?>' />
        <button onClick='refresh();'>Refresh</button>
    </div>
    <div style='text-align:right;'>
      Enter "Maintained" into the "Search" bar to view only maintained locations.
    </div>
    <table id='Table_Customers' class='display' cellspacing='0' width='100%' height='800px'>
      <thead>
        <th>Loc</th>
        <th>Customer</th>
        <th>Customer</th>
        <th>Location</th>
        <th>Tickets</th>
        <th>Shutdowns</th>
        <th>Maintenances</th>
        <th>Services</th>
        <th>Modernizations</th>
        <th>Repairs</th>
        <th></th>
      </thead>
    </table>
  </div>
</div>
<style>
.dtrg-group td {
  background-color:#f0f0f0 !important;
}
</style>
<!-- Custom Date Filters-->
<script src="../dist/js/filters.js"></script>
<style>
@media not print {
  .agood {
    color:darkgreen;
  }
  .vgood {
    color:green;
  }
  .good {
    color:lime;
  }
  .middle {
    color:gray;
  }
  .bad {
    color:pink;
  }
  .vbad {
    color:red;
  }
  .tbad {
    color:darkred;
  }
}
tr.group,
tr.group:hover {
    background-color: #ddd !important;
}
</style>
<script>
function getColor(value){
    if(value > 0){return "rgb(0, " + (255 * (value / 589)) + ", 0)";}
    if(value < 0){return "rgb(" + (-255 * (value / 589)) + ", 0, 0)";}
}
$(document).ready(function(){
  var groupColumn = 1;
  var Table_Customers = $('#Table_Customers').DataTable( {
    "ajax": {
      "url":"cgi-bin/php/reports/Activity_2019.php?Start=" + $("input[name='Start']").val() + "&End=" + $("input[name='End']").val(),
      "dataSrc":function(json){
          if(!json.data){json.data = [];}
          return json.data;}
    },
    <?php if(!isset($_GET['Print'])){?>'scrollY':'75vh',<?php }?>
    'paging':false,
    "order": [[ groupColumn, 'asc' ]],
    "columns": [
        { "data": "Location_ID", "className":"hidden"},
        { "data": "Customer_Name",  "visible": false, "targets": groupColumn },
        { "data": "Customer_N",  "visible": false},
        { "data": "Location_Name"},
        { "data": "Ticket_Count"},
        { "data": "Shutdown_Count"},
        { "data": "Maintenance_Count"},
        { "data": "Service_Count"},
        { "data": "Modernization_Count"},
        { "data": "Repair_Count"},
        { "data": "Active", "visible": false}
        //,{ "data": "Cost_Margin"}
    ],
    "drawCallback": function ( settings ) {
        var api = this.api();
        var rows = api.rows( {page:'current'} ).nodes();
        var last=null;

        api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
            if ( last !== group ) {
                $(rows).eq( i ).before(
                    '<tr class="group"><td colspan="11"><b><i>'+group+'</i></b></td></tr>'
                );

                last = group;
            }
        } );
    },
    dom: 'Bfrtip',
    buttons: [],
    "createdRow": function ( row, data, index ) {
    },
    rowGroup: {
      startRender: null,
      endRender: function ( rows, group ) {
        var Ticket_Count = rows
            .data()
            .pluck('Ticket_Count')
            .reduce( function (a, b) {
              return a + b*1;
            }, 0);
        var Shutdown_Count = rows
            .data()
            .pluck('Shutdown_Count')
            .reduce( function (a, b) {
                return a + b*1;
            }, 0);
        var Customer_ID  = rows
            .data()
            .pluck('Customer_ID').reduce( function (a, b) {
                return b*1;
            }, 0);
        var Maintenance_Count = rows
            .data()
            .pluck('Maintenance_Count')
            .reduce( function (a, b) {
                return a + b*1;
            }, 0);
        var Service_Count = rows
            .data()
            .pluck('Service_Count')
            .reduce( function (a, b) {
                return a + b*1;
            }, 0);
        var Modernization_Count = rows
            .data()
            .pluck('Modernization_Count')
            .reduce( function (a, b) {
                return a + b*1;
            }, 0);
        var Repair_Count = rows
            .data()
            .pluck('Repair_Count')
            .reduce( function (a, b) {
                return a + b*1;
            }, 0);
          return $("<tr/ class='Totals' rel='" + Customer_ID + "'>")
              .append( '<td colspan="1">Totals for '+group+'</td>' )
              .append( '<td>'+Ticket_Count+'</td>')
              .append( '<td>'+Shutdown_Count+'</td>')
              .append( '<td>'+Maintenance_Count+'</td>')
              .append( '<td>'+Service_Count+'</td>' )
              .append( '<td>'+Modernization_Count+'</td>' )
              .append( '<td>'+Repair_Count+'</td>' )
      },
      dataSrc: 'Customer_Name'
    },
    //"lengthMenu":[[-1,10,25,50,100,500],["All",10,25,50,100,500]],
    "language":{"loadingRecords":""},
    "initComplete":function(){
      $("table#Table_Customers tbody tr[role='row']>td:nth-child(3)").on("click", function(){
        $("tr.Chart").remove();
        var link = this;
        $.ajax({
          url:"cgi-bin/js/chart/location_activity.php?Fetched=1&ID=" + $(this).parent().children(':first-child').html(),
          method:"GET",
          success:function(code){
            $(link).parent().after("<tr class='Chart'><td colspan='11'><div id='flot-placeholder-profit' style='height:500px;width:100%;'><div></td></tr>");
            $(link).parent().after(code);
          }
        });
      });
      $("table#Table_Customers tbody tr[role='row']>td:nth-child(3)").on("click", function(){
        $("tr.Chart").remove();
        var link = this;
        $.ajax({
          url:"cgi-bin/js/chart/location_tickets.php?Fetched=1&ID=" + $(this).parent().children(':first-child').html(),
          method:"GET",
          success:function(code){
            $(link).parent().after("<tr class='Chart'><td colspan='11'><div id='flot-placeholder-profit' style='height:500px;width:100%;'><div></td></tr>");
            $(link).parent().after(code);
          }
        });
      });
      $("table#Table_Customers tbody tr[role='row']>td:nth-child(4)").on("click", function(){
        $("tr.Chart").remove();
        var link = this;
        $.ajax({
          url:"cgi-bin/js/chart/location_shutdowns.php?Fetched=1&ID=" + $(this).parent().children(':first-child').html(),
          method:"GET",
          success:function(code){
            $(link).parent().after("<tr class='Chart'><td colspan='11'><div id='flot-placeholder-profit' style='height:500px;width:100%;'><div></td></tr>");
            $(link).parent().after(code);
          }
        });
      });
      $("table#Table_Customers tbody tr[role='row']>td:nth-child(5)").on("click", function(){
        $("tr.Chart").remove();
        var link = this;
        $.ajax({
          url:"cgi-bin/js/chart/location_shutdowns.php?Fetched=1&ID=" + $(this).parent().children(':first-child').html(),
          method:"GET",
          success:function(code){
            $(link).parent().after("<tr class='Chart'><td colspan='11'><div id='flot-placeholder-profit' style='height:500px;width:100%;'><div></td></tr>");
            $(link).parent().after(code);
          }
        });
      });
      $("table#Table_Customers tbody tr[role='row']>td:nth-child(6)").on("click", function(){
        $("tr.Chart").remove();
        var link = this;
        $.ajax({
          url:"cgi-bin/js/chart/location_shutdowns.php?Fetched=1&ID=" + $(this).parent().children(':first-child').html(),
          method:"GET",
          success:function(code){
            $(link).parent().after("<tr class='Chart'><td colspan='11'><div id='flot-placeholder-profit' style='height:500px;width:100%;'><div></td></tr>");
            $(link).parent().after(code);
          }
        });
      });
      $("table#Table_Customers tbody tr.Totals").on("click", function(){
        $("tr.Chart").remove();
        var link = this;
        var past = this;
        $.ajax({
          url:"cgi-bin/js/chart/customer_activity.php?Fetched=1&ID=" + $(link).attr('rel'),
          method:"GET",
          success:function(code){
            $(past).after("<tr class='Chart'><td colspan='11'><div id='flot-placeholder-profit' style='height:500px;width:100%;'><div></td></tr>");
            $(past).after(code);
          }
        });
      });
    }
  } );
});
$(document).ready(function(){
  $("input[name='Start']").datepicker();
  $("input[name='End']").datepicker();
});
</script><?php
    }
} else {}?>
