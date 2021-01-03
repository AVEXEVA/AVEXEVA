<?php
if(session_id() == '' || !isset($_SESSION)) {
    session_start();
    $_SESSION['page-target'] = 'violations';
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
  <div class='panel-heading'>
    <ul class="nav nav-tabs" role="tablist">
       <li class="active"> <a href="#home" role="tab" data-toggle="tab" rel='*'>*</a></li>
       <li class="active"> <a href="#home" role="tab" data-toggle="tab" rel='All'>All</a></li>
       <li><a href="#profile" role="tab" data-toggle="tab" rel='Open'>Open</a></li>
   </ul>
  </div>
  <div id='Dispatch' class="panel-body">
    <table id='Table_Tickets' class='display' cellspacing='0' width='100%' style='font-size:12px;'>
        <thead>
          <th>ID</th>
          <th>WO</th>
          <th>Type</th>
          <th>Location</th>
          <th>Street</th>
          <th>State</th>
          <th>Zip</th>
          <th>Unit</th>
          <th>Status</th>
          <th>Division</th>
          <th>Mechanic</th>
          <th>Created</th>
          <th>Dispatch</th>
          <th>Scheduled</th>
          <th>Tags</th>
          <th>Description</th>
          <th>Latitude</th>
          <th>Longitude</th>
          <th>Employee ID</th>
        </thead>
        <tfoot>
          <th>ID</th>
          <th>WO</th>
          <th>Type</th>
          <th>Location</th>
          <th>Street</th>
          <th>State</th>
          <th>Zip</th>
          <th>Unit</th>
          <th>Status</th>
          <th>Division</th>
          <th>Mechanic</th>
          <th>Created</th>
          <th>Dispatch</th>
          <th>Scheduled</th>
          <th>Tags</th>
          <th>Description</th>
          <th>Latitude</th>
          <th>Longitude</th>
          <th>Employee ID</th>
        </tfoot>
      </table>
  </div>
  <div class="panel-heading" onClick="toggleTicket();"><h4><div><?php $Icons->Ticket();?> Ticket</div></h4></div>
  <div id='Ticket' class='panel-body'></div>
</div>
<script>
$('#Table_Tickets tfoot th').each( function () {
  var title = $(this).text();
  $(this).html( '<input type="text" placeholder="Search '+title+'" style="width:100%;" />' );
} );
var options = {
  "ajax": "cgi-bin/php/get/Dispatch.php",
  "columns": [
    {
      'data':'ID',
      'className':'hidden'
    },{
      'data':'Work_Order',
      'className':'hidden'
    },{
      'data':'Level'
    },{
      'data':'Location'
    },{
      'data':'Street'
    },{
      'data':'State',
      'visible':false
    },{
      'data':'Zip',
      'visible':false
    },{
      'data':'Unit_Name'
    },{
      'data':'Status',
      'name':'Status'
    },{
      'data':'Division'
    },{
      'data':'Worker_Name'
    },{
      'data':'Created'
    },{
      'data':'Dispatched',
      'visible':false
    },{
      'data':'Date',
      'visible':false
    },{
      'data':'Tags',
      'visible':true
    },{
      'data':'Description'
    },{
      'data':'Latitude',
      'className':'hidden'
    },{
      'data':'Longitude',
      'className':'hidden'
    },{
      'data':'Employee_ID',
      'className':'hidden'
    }
  ],
  "language":{
    "loadingRecords":"<div style='text-align:center;'><div class='sk-cube-grid' style='display:inline-block;position:relative;';><div class='sk-cube sk-cube1' style='background-color:#cc0000'></div><div class='sk-cube sk-cube2' style='background-color:#cc0000'></div><div class='sk-cube sk-cube3' style='background-color:#cc0000'></div><div class='sk-cube sk-cube4' style='background-color:#cc0000'></div><div class='sk-cube sk-cube5' style='background-color:#cc0000'></div><div class='sk-cube sk-cube6' style='background-color:#cc0000'></div><div class='sk-cube sk-cube7' style='background-color:#cc0000'></div><div class='sk-cube sk-cube8' style='background-color:#cc0000'></div><div class='sk-cube sk-cube9' style='background-color:#cc0000'></div></div><div class='sk-cube-grid' style='display:inline-block;position:relative;top:-45px;'><div class='sk-cube sk-cube1' style='background-color:#00007f'></div><div class='sk-cube sk-cube2' style='background-color:#00007f'></div><div class='sk-cube sk-cube3' style='background-color:#00007f'></div><div class='sk-cube sk-cube4' style='background-color:#00007f'></div><div class='sk-cube sk-cube5' style='background-color:#00007f'></div><div class='sk-cube sk-cube6' style='background-color:#00007f'></div><div class='sk-cube sk-cube7' style='background-color:#00007f'></div><div class='sk-cube sk-cube8' style='background-color:#00007f'></div><div class='sk-cube sk-cube9' style='background-color:#00007f'></div></div><div class='sk-cube-grid' style='display:inline-block;position:relative;top:-84px;'><div class='sk-cube sk-cube1' style='background-color:gold'></div><div class='sk-cube sk-cube2' style='background-color:gold'></div><div class='sk-cube sk-cube3' style='background-color:gold'></div><div class='sk-cube sk-cube4' style='background-color:gold'></div><div class='sk-cube sk-cube5' style='background-color:gold'></div><div class='sk-cube sk-cube6' style='background-color:gold'></div><div class='sk-cube sk-cube7' style='background-color:gold'></div><div class='sk-cube sk-cube8' style='background-color:gold'></div><div class='sk-cube sk-cube9' style='background-color:gold'></div></div></div><div style='font-size:72px;text-align:center;' class='BankGothic'>Nouveau Elevator</div><div style='font-size:42px;text-align:center;'><i>Raising Your Life</i></div>"
  },
  "dom":"Bfrtip",
  "select":true,
  "initComplete":function(){
    Table_Tickets.on('select', function(e, dt, type, indexes){
      var rowData = Table_Tickets.rows( indexes ).data().toArray()[0];
      setTimeout(function(){render_Ticket(rowData['ID'], 0);},250);
    });
    Table_Tickets.columns().every( function () {
        var that = this;

        $( 'input', this.footer() ).on( 'keyup change clear', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    } );
  },
  'createdRow':function( row, data, dataIndex){
    if( data['Tags'] ==  'Entrapment'){
                  $(row).addClass('redClass');
              } else if(data['Tags'] == 'Shutdown'){
                $(row).addClass('blueClass');
              } else if(data['Tags'] == 'Maintenance'){
                $(row).addClass('greenClass');
              }
  },
  "scrollY" : "425px",
  "scrollCollapse":true,
  'paging':false,
  'select':true,
  "lengthChange": false,
  //"order": [[ 1, "ASC" ]],
  "search":{},
  'buttons':['copy', 'csv', 'excel', 'pdf', 'print',
    {
      text: 'New',
      action: function ( e, dt, node, config ) {
        var Layout = $(".layout.active").attr('rel');
        $.ajax({
          url:'new-dispatch-ticket.php',
          success:function(code){
            $('body').append("<div class='popup'>" + code + "</div>");
          }
        });
      }
    },
    {
      text: 'Locate',
      action: function ( e, dt, node, config ) {
        openInNewTab('map.php?Type=Live&Locate=1&Latitude=' + $("div.Active tr.selected").children(':nth-child(13)').html() + '&Longitude=' + $("div.Active tr.selected").children(':nth-child(14)').html());
      }
    },
    {
      text: 'Nearest Mechanic',
      action: function ( e, dt, node, config ) {
        openInNewTab('map.php?Type=Live&Nearest=1&Latitude=' + $("div.Active tr.selected").children(':nth-child(13)').html() + '&Longitude=' + $("div.Active tr.selected").children(':nth-child(14)').html());
      }
    },
    {
      text: 'Route',
      action: function ( e, dt, node, config ) {
        openInNewTab('map.php?Type=Live&Latitude=' + $("div.Active tr.selected").children(':nth-child(13)').html() + '&Longitude=' + $("div.Active tr.selected").children(':nth-child(14)').html() + '&Mechanic=' + $("div.Active tr.selected").children(':nth-child(15)').html());
      }
    }
  ]
};
var Table_Tickets = $('#Table_Tickets').DataTable( options );
$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
  if($(this).attr('rel') == 'Open'){
    Table_Tickets.columns(8).className('hidden');
    Table_Tickets.columns(8).search('Open').draw();
  } else if($(this).attr('rel') == 'All'){
    Table_Tickets.search('');
    Table_Tickets.columns().search('');
    Table_Tickets.draw();
  } else {
    Table_Tickets.ajax.reload();
  }
  $($.fn.dataTable.tables(true)).DataTable()
     .columns.adjust()
     .responsive.recalc();
});
function render_Ticket(ID, check){
  var i = 0;
  $('div.Active table[class*="dataTable"] tr.selected').each(function(){
    i++;
  });
  if(i == 1 || true){
    $('div#Ticket').html("<div style='text-align:center;'><div class='sk-cube-grid' style='display:inline-block;position:relative;';><div class='sk-cube sk-cube1' style='background-color:#cc0000'></div><div class='sk-cube sk-cube2' style='background-color:#cc0000'></div><div class='sk-cube sk-cube3' style='background-color:#cc0000'></div><div class='sk-cube sk-cube4' style='background-color:#cc0000'></div><div class='sk-cube sk-cube5' style='background-color:#cc0000'></div><div class='sk-cube sk-cube6' style='background-color:#cc0000'></div><div class='sk-cube sk-cube7' style='background-color:#cc0000'></div><div class='sk-cube sk-cube8' style='background-color:#cc0000'></div><div class='sk-cube sk-cube9' style='background-color:#cc0000'></div></div><div class='sk-cube-grid' style='display:inline-block;position:relative;top:-45px;'><div class='sk-cube sk-cube1' style='background-color:#00007f'></div><div class='sk-cube sk-cube2' style='background-color:#00007f'></div><div class='sk-cube sk-cube3' style='background-color:#00007f'></div><div class='sk-cube sk-cube4' style='background-color:#00007f'></div><div class='sk-cube sk-cube5' style='background-color:#00007f'></div><div class='sk-cube sk-cube6' style='background-color:#00007f'></div><div class='sk-cube sk-cube7' style='background-color:#00007f'></div><div class='sk-cube sk-cube8' style='background-color:#00007f'></div><div class='sk-cube sk-cube9' style='background-color:#00007f'></div></div><div class='sk-cube-grid' style='display:inline-block;position:relative;top:-84px;'><div class='sk-cube sk-cube1' style='background-color:gold'></div><div class='sk-cube sk-cube2' style='background-color:gold'></div><div class='sk-cube sk-cube3' style='background-color:gold'></div><div class='sk-cube sk-cube4' style='background-color:gold'></div><div class='sk-cube sk-cube5' style='background-color:gold'></div><div class='sk-cube sk-cube6' style='background-color:gold'></div><div class='sk-cube sk-cube7' style='background-color:gold'></div><div class='sk-cube sk-cube8' style='background-color:gold'></div><div class='sk-cube sk-cube9' style='background-color:gold'></div></div></div><div style='font-size:72px;text-align:center;' class='BankGothic'>Nouveau Elevator</div><div style='font-size:42px;text-align:center;'><i>Raising Your Life</i></div>")
    $.ajax({
      url:'dispatch_ticket.php?ID=' + ID,
      method:'GET',
      success:function(code){
        $('div#Ticket').html(code);
        if(check == 1){openRelatedTickets(ID);}
      }
    });
  } else if(i == 0) {
    $('div#Ticket').html('NO TICKET AVAILABLE');
  } else {
    $('div#Ticket').html(MULTI_TICKET_PAGE);
    if(check == 1){openRelatedTickets(ID);}
  }
}
</script><?php
    }
} else {}?>
