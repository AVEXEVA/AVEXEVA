<?php
if(session_id() == '' || !isset($_SESSION)) {
    session_start();
    $_SESSION['page-target'] = 'profitability';
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
?>
          <div>
            <div style='float:left;'>Overhead : 14%</div>
            <div style='float:left;'>"Bills" : 150+ Days</div>
            <div style='float:left;'>Only Completed Modernizations OR Repair</div>
            <div style='clear:both;'></div>
          </div>
          <div>
            <script src="../vendor/flot/excanvas.min.js"></script>
            <script src="../vendor/flot/jquery.flot.js"></script>
            <script src="../vendor/flot/jquery.flot.pie.js"></script>
            <script src="../vendor/flot/jquery.flot.resize.js"></script>
            <script src="../vendor/flot/jquery.flot.time.js"></script>
            <script src="../vendor/flot/jquery.flot.categories.js"></script>
            <script src="../vendor/flot-tooltip/jquery.flot.tooltip.min.js"></script>
              Start <input type='text' name='Start' value='<?php echo $_GET['Start'];?>' />
              End <input type='text' name='End' value='<?php echo $_GET['End'];?>' />
              <button onClick='refresh();' style='color:black;'>Refresh</button>
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
          <th>Revenue</th>
          <th>Material</th>
          <th>Labor</th>
          <th>Profit</th>
          <th>Profit %
					<th>Overhead</th>
          <th>Profit w/ OH</th>
          <th>Bills</th>
					<th>Profit w/ OH w/o Bills </th>
          <th>Profit Percentage</th>
          <th>Grade</th>
          <th></th>
          <!--<th>Cost Margin</th>-->
				</thead>
			</table>
        </div>
    </div>
    <style>
    .dtrg-group td {
      background-color:#f0f0f0 !important;
    }
    </style>
    <style>
    tr.Totals {
      /*background-color:#9a9a9a;*/
      color:black !important;;
    }
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
        background-color: #1d1d1d !important;
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
                    "url":"cgi-bin/php/reports/Accounts_2019.php?Start=" + $("input[name='Start']").val() + "&End=" + $("input[name='End']").val(),
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
                    { "data": "Invoices_Sum"},
                    { "data": "Materials_Sum"},
                    { "data": "Labor_Sum"},
                    { "data": "Profit"},
                    { "data": "Profit_Percentage_Raw"},
                    { "data": "Overhead"},
                    { "data": "Profit_with_Overhead"},
                    { "data": "Bills_Sum"},
                    { "data": "Profit_with_Overhead_without_Bills"},
                    { "data": "Profit_Percentage"},
                    { "data": 'Grade', 'className':'hidden'},
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
        buttons: [
            /*{
                extend: 'print',
                customize: function ( win ) {
                    $(win.document.body)
                        .css( 'font-size', '10pt' )
                        .prepend(
                        );

                    $(win.document.body).find( 'table' )
                        .addClass( 'compact' )
                        .css( 'font-size', 'inherit' );
                }
            }*/
        ],
                rowGroup: {
                      startRender: null,
                      endRender: function ( rows, group ) {
                        var Invoices_Sum = rows
                        .data()
                        .pluck('Invoices_Sum')
                        .reduce( function (a, b) {
                          return a + b.replace(/[^-\d.]/g, '')*1;
                        }, 0);
                        var I = Invoices_Sum;
                        Invoices_Sum = $.fn.dataTable.render.number(',', '.', 2, '$').display( Invoices_Sum );
                        var Materials_Sum = rows
                            .data()
                            .pluck('Materials_Sum')
                            .reduce( function (a, b) {
                                return a + b.replace(/[^-\d.]/g, '')*1;
                            }, 0);
                        Materials_Sum = $.fn.dataTable.render.number(',', '.', 2, '$').display( Materials_Sum );
                        var Customer_ID  = rows
                            .data()
                            .pluck('Customer_ID').reduce( function (a, b) {
                                return b.replace(/[^-\d.]/g, '')*1;
                            }, 0);;
                        var Labor_Sum = rows
                            .data()
                            .pluck('Labor_Sum')
                            .reduce( function (a, b) {
                                return a + b.replace(/[^-\d.]/g, '')*1;
                            }, 0);
                        Labor_Sum = $.fn.dataTable.render.number(',', '.', 2, '$').display( Labor_Sum );
                        var Profit = rows
                            .data()
                            .pluck('Profit')
                            .reduce( function (a, b) {
                                return a + b.replace(/[^-\d.]/g, '')*1;
                            }, 0);
                        var P2 = Profit;
                        Profit = $.fn.dataTable.render.number(',', '.', 2, '$').display( Profit );

                        var Overhead = rows
                            .data()
                            .pluck('Overhead')
                            .reduce( function (a, b) {
                                return a + b.replace(/[^-\d.]/g, '')*1;
                            }, 0);
                        Overhead = $.fn.dataTable.render.number(',', '.', 2, '$').display( Overhead );
                        var Profit_with_Overhead = rows
                            .data()
                            .pluck('Profit_with_Overhead')
                            .reduce( function (a, b) {
                                return a + b.replace(/[^-\d.]/g, '')*1;
                            }, 0);
                        Profit_with_Overhead = $.fn.dataTable.render.number(',', '.', 2, '$').display( Profit_with_Overhead );
                        var Bills_Sum = rows
                            .data()
                            .pluck('Bills_Sum')
                            .reduce( function (a, b) {
                                return a + b.replace(/[^-\d.]/g, '')*1;
                            }, 0);
                        Bills_Sum = $.fn.dataTable.render.number(',', '.', 2, '$').display( Bills_Sum );
                        var Profit_with_Overhead_without_Bills = rows
                            .data()
                            .pluck('Profit_with_Overhead_without_Bills')
                            .reduce( function (a, b) {
                                return a + b.replace(/[^-\d.]/g, '')*1;
                            }, 0);
                        var P = Profit_with_Overhead_without_Bills;
                        Profit_with_Overhead_without_Bills = $.fn.dataTable.render.number(',', '.', 2, '$').display( Profit_with_Overhead_without_Bills );
                        var Perc2 = P2 < 0 && I < 0 ? ((P2 / I) * -100) : ((P2 / I) * 100);
                        var Profit_Percentage = P < 0 && I < 0 ? ((P / I) * -100) : ((P / I) * 100);
                        var Grade = ((Profit_Percentage / 85) * .65);

                        Profit_Percentage = Profit_Percentage.toFixed(2) + '%';
                        Perc2 = Perc2.toFixed(2) + '%';

                        if(P > 350000){
                          Grade = Grade + .35;
                        } else {
                          Grade = Grade + ((P / 350000) * .35);
                        }
                        Grade = Grade.toFixed(2) + '%';
                        //Profit_Percentage = $.fn.dataTable.render.number(',', '.', 2, '').display( Profit_Percentage ) + '%';

                          return $("<tr/ class='Totals' rel='" + Customer_ID + "'>")
                              .append( '<td colspan="1">Totals for '+group+'</td>' )
                              .append( '<td>'+Invoices_Sum+'</td>')
                              .append( '<td>'+Materials_Sum+'</td>')
                              .append( '<td>'+Labor_Sum+'</td>')
                              .append( '<td>'+Profit+'</td>' )
                              .append( '<td>'+Perc2+'</td>' )
                              .append( '<td>'+Overhead+'</td>' )
                              .append( '<td>'+Profit_with_Overhead+'</td>' )
                              .append( '<td>'+Bills_Sum+'</td>' )
                              .append( '<td>'+Profit_with_Overhead_without_Bills+'</td>' )
                              .append( '<td>'+Profit_Percentage+'</td>' );
                              //.append( '<td>'+Grade+'</td>' );
                      },
                      dataSrc: 'Customer_Name'
                },
                "createdRow": function ( row, data, index ) {
                  //alert(data[10]);
                  /*if(data['Profit_Margin'] > 250){$('td', row).eq(11).addClass('agood');}
                  else if(data['Profit_Margin'] > 100){$('td', row).eq(11).addClass('vgood');}
                  else if(data['Profit_Margin'] > 25){$('td', row).eq(11).addClass('good');}
                  else if(data['Profit_Margin'] > -25){$('td', row).eq(11).addClass('middle');}
                  else if(data['Profit_Margin'] > -100){$('td', row).eq(11).addClass('bad');}
                  else if(data['Profit_Margin'] > -250){$('td', row).eq(11).addClass('vbad');}
                  else {$('td', row).eq(11).addClass('tbad');}*/
                  $('td', row).eq(11).css('color', getColor(data['Profit_Margin']));
                },
                //"lengthMenu":[[-1,10,25,50,100,500],["All",10,25,50,100,500]],
                "language":{"loadingRecords":""},
                "initComplete":function(){
                  finishLoadingPage();
                  $("table#Table_Customers tbody tr[role='row']").on("click", function(){
                    $("tr.Chart").remove();
                    var link = this;
                    $.ajax({
                      url:"cgi-bin/js/chart/location_profit.php?Fetched=1&ID=" + $(this).children(":first-child").html(),
                      method:"GET",
                      success:function(code){
                        $(link).after("<tr class='Chart'><td colspan='11'><div id='flot-placeholder-profit' style='height:500px;width:100%;'><div></td></tr>");
                        $(link).after(code);
                      }
                    });
                  });
                  $("table#Table_Customers tbody tr.Totals").on("click", function(){
                    $("tr.Chart").remove();
                    var link = this;
                    var past = this;
                    $.ajax({
                      url:"cgi-bin/js/chart/customer_profit.php?Fetched=1&ID=" + $(link).attr('rel'),
                      method:"GET",
                      success:function(code){
                        $(past).after("<tr class='Chart'><td colspan='11'><div id='flot-placeholder-profit' style='height:500px;width:100%;'><div></td></tr>");
                        $(past).after(code);
                      }
                    });
                  });
                  $("table#Table_Customers tbody tr.group").on("click", function(){
                    $("tr.Chart").remove();
                    var link = this;
                    var IDs = [];
                    while($(link).next().attr('role') == 'row'){
                      var link = $(link).next();
                      IDs.push($(link).children(':first-child').html());
                    }
                    $.ajax({
                      url:"cgi-bin/js/chart/customer_overall_profit.php?Fetched=1&IDs=" + IDs.join(','),
                      method:"GET",
                      success:function(code){
                        $(link).after("<tr class='Chart'><td colspan='11'><div id='flot-placeholder-profit' style='height:500px;width:100%;'><div></td></tr>");
                        $(link).after(code);
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
    </script>
    <script src="https://nightly.datatables.net/rowgroup/js/dataTables.rowGroup.min.js"></script>
</body>
</html>
<?php
    }
} else {?><html><head><script>document.location.href='../login.php?Forward=accounts_2019.php';</script></head></html><?php }?>
