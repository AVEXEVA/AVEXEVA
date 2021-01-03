<?php
if(session_id() == '' || !isset($_SESSION)) {
    session_start();
    $_SESSION['page-target'] = 'tickets';
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
	   	|| !isset($My_Privileges['Ticket'])
	  		|| $My_Privileges['Ticket']['User_Privilege']  < 4
	  		|| $My_Privileges['Ticket']['Group_Privilege'] < 4
        || $My_Privileges['Ticket']['Other_Privilege'] < 4){}
    else {
		sqlsrv_query($NEI,"
			INSERT INTO Portal.dbo.Activity([User], [Date], [Page])
			VALUES(?,?,?)
		;",array($_SESSION['User'],date("Y-m-d H:i:s"), "locations.php"));
?><div class="panel panel-primary" style='height:100%;'>
  <div class="panel-heading"><h4><?php $Icons->Ticket();?> Tickets</h4></div>
  <div class='panel-body'>
    <table id='Table_Tickets' class='display' cellspacing='0' width='100%' style='color:white !important;'>
      <thead><tr>
        <th>Ticket_ID</th>
        <th>Ticket_fDesc</th>
        <th>Ticket_DescRes</th>
        <th>Ticket_CDate</th>
        <th>Ticket_DDate</th>
        <th>Ticket_EDate</th>
        <th>Ticket_TimeRoute</th>
        <th>Ticket_TimeSite</th>
        <th>Ticket_TimeComp</th>
        <th>Ticket_Who</th>
        <th>Ticket_fBy</th>
        <th>Ticket_Level</th>
        <th>Ticket_Owner</th>
        <th>Ticket_fWork</th>
        <th>Ticket_Reg</th>
        <th>Ticket_OT</th>
        <th>Ticket_DT</th>
        <th>Ticket_NT</th>
        <th>Ticket_Total</th>
        <th>Ticket_ClearPR</th>
        <th>Ticket_Assigned</th>
        <th>Loc_Tag
        <th>Loc_Loc</th></td>
        <th>Loc_Address</th>
        <th>Loc_City</th>
        <th>Loc_State</th>
        <th>Loc_Zip</th>
        <th>Job_ID</th>
        <th>Job_fDesc</th>
        <th>OwnerWithRol_I</th>D
        <th>OwnerWithRol_Name</th>
        <th>Elev_State</th>
        <th>Elev_Unit</th>
        <th>Emp_fFirst</th>
        <th>Emp_Last</th>
        <th>JobType_Type</th>
      </tr></thead>
      <tfoot><tr>
        <th>Ticket_ID</th>
        <th>Ticket_fDesc</th>
        <th>Ticket_DescRes</th>
        <th>Ticket_CDate</th>
        <th>Ticket_DDate</th>
        <th>Ticket_EDate</th>
        <th>Ticket_TimeRoute</th>
        <th>Ticket_TimeSite</th>
        <th>Ticket_TimeComp</th>
        <th>Ticket_Who</th>
        <th>Ticket_fBy</th>
        <th>Ticket_Level</th>
        <th>Ticket_Owner</th>
        <th>Ticket_fWork</th>
        <th>Ticket_Reg</th>
        <th>Ticket_OT</th>
        <th>Ticket_DT</th>
        <th>Ticket_NT</th>
        <th>Ticket_Total</th>
        <th>Ticket_ClearPR</th>
        <th>Ticket_Assigned</th>
        <th>Loc_Tag
        <th>Loc_Loc</th></td>
        <th>Loc_Address</th>
        <th>Loc_City</th>
        <th>Loc_State</th>
        <th>Loc_Zip</th>
        <th>Job_ID</th>
        <th>Job_fDesc</th>
        <th>OwnerWithRol_I</th>D
        <th>OwnerWithRol_Name</th>
        <th>Elev_State</th>
        <th>Elev_Unit</th>
        <th>Emp_fFirst</th>
        <th>Emp_Last</th>
        <th>JobType_Type</th>
      </tr></tfoot>
    </table>
  </div>
</div>
<script>
$(document).ready(function(){
  $('#Table_Tickets tfoot th').each( function () {
    var title = $(this).text();
    if(title == 'CDate' || title == 'DDate' || title == 'EDate' || title == 'TimeRoute' || title == 'TimeSite' || title == 'TimeComp'){
      $(this).html( '<input currency="min" format="date" type="text" placeholder="Min '+title+'" style="width:100%;" />' + '<input currency="max" format="date" type="text" placeholder="Max '+title+'" style="width:100%;" />' );
    } else if(title == 'Reg' || title == 'OT' || title == 'DT' || title == 'NT' || title == 'Total'){
      $(this).html( '<input currency="min" format="number" type="text" placeholder="Min '+title+'" style="width:100%;" />' + '<input currency="max" format="date" type="text" placeholder="Max '+title+'" style="width:100%;" />' );
    } else {
      $(this).html( '<input type="text" placeholder="Search '+title+'" style="width:100%;" />' );
    }
  } );
  var Table_Tickets = $('#Table_Tickets').DataTable( {
    'processing':true,
    'serverSide':true,
      'ajax': {
        'url':'cgi-bin/php/get/Tickets.php',
        'type': 'GET',
        'data' : function( d ) {
          d.columns[4]['search']['min'] = $('div.dataTables_scrollFootInner table.dataTable tfoot tr th:nth-child(5) input:nth-child(1)').val();
          d.columns[4]['search']['max'] = $('div.dataTables_scrollFootInner table.dataTable tfoot tr th:nth-child(5) input:nth-child(2)').val();
          d.columns[12]['search']['min'] = $('div.dataTables_scrollFootInner table.dataTable tfoot tr th:nth-child(13) input:nth-child(1)').val();
          d.columns[12]['search']['max'] = $('div.dataTables_scrollFootInner table.dataTable tfoot tr th:nth-child(13) input:nth-child(2)').val();
          d.columns[13]['search']['min'] = $('div.dataTables_scrollFootInner table.dataTable tfoot tr th:nth-child(14) input:nth-child(1)').val();
          d.columns[13]['search']['max'] = $('div.dataTables_scrollFootInner table.dataTable tfoot tr th:nth-child(14) input:nth-child(2)').val();
        }
      },
    'order':[[0,'desc']],
    'columns': [
      {
        'data'  :   'Ticket_ID',
      },{
        'data'  :   'Ticket_fDesc'
      },{
        'data'  :   'Ticket_DescRes'
      },{
        'data'  :   'Ticket_CDate',
        render: function(data, type){return type === 'sort' ? data : moment(data).format('L');}
      },{
        'data'  :   'Ticket_DDate',
        render: function(data, type){return type === 'sort' ? data : moment(data).format('L');}
      },{
        'data'  :   'Ticket_EDate'
      },{
        'data'  :   'Ticket_TimeRoute'
      },{
        'data'  :   'Ticket_TimeSite'
      },{
        'data'  :   'Ticket_TimeComp'
      },{
        'data'  :   'Ticket_Who'
      },{
        'data'  :   'Ticket_fBy'
      },{
        'data'  :   'Ticket_Level'
      },{
        'data'  :   'Ticket_Owner'
      },{
        'data'  :   'Ticket_fWork'
      },{
        'data'  :   'Ticket_Reg'
      },{
        'data'  :   'Ticket_OT'
      },{
        'data'  :   'Ticket_DT'
      },{
        'data'  :   'Ticket_NT'
      },{
        'data'  :   'Ticket_Total'
      },{
        'data'  :   'Ticket_ClearPR'
      },{
        'data'  :   'Ticket_Assigned'
      },{
        'data'  :   'Loc_Loc'
      },{
        'data'  :   'Loc_Tag'
      },{
        'data'  :   'Loc_Address'
      },{
        'data'  :   'Loc_City'
      },{
        'data'  :   'Loc_State'
      },{
        'data'  :   'Loc_Zip'
      },{
        'data'  :   'Job_ID'
      },{
        'data'  :   'Job_fDesc'
      },{
        'data'  :   'OwnerWithRol_ID'
      },{
        'data'  :   'OwnerWithRol_Name'
      },{
        'data'  :   'Elev_State'
      },{
        'data'  :   'Elev_Unit'
      },{
        'data'  :   'Emp_fFirst'
      },{
        'data'  :   'Emp_Last'
      },{
        'data'  :   'JobType_Type'
      }
    ],
    'buttons':['copy','csv','excel','pdf','print','pageLength'],
    'language':{
      "loadingRecords":"<div style='text-align:center;'><div class='sk-cube-grid' style='display:inline-block;position:relative;';><div class='sk-cube sk-cube1' style='background-color:#cc0000'></div><div class='sk-cube sk-cube2' style='background-color:#cc0000'></div><div class='sk-cube sk-cube3' style='background-color:#cc0000'></div><div class='sk-cube sk-cube4' style='background-color:#cc0000'></div><div class='sk-cube sk-cube5' style='background-color:#cc0000'></div><div class='sk-cube sk-cube6' style='background-color:#cc0000'></div><div class='sk-cube sk-cube7' style='background-color:#cc0000'></div><div class='sk-cube sk-cube8' style='background-color:#cc0000'></div><div class='sk-cube sk-cube9' style='background-color:#cc0000'></div></div><div class='sk-cube-grid' style='display:inline-block;position:relative;top:-45px;'><div class='sk-cube sk-cube1' style='background-color:#00007f'></div><div class='sk-cube sk-cube2' style='background-color:#00007f'></div><div class='sk-cube sk-cube3' style='background-color:#00007f'></div><div class='sk-cube sk-cube4' style='background-color:#00007f'></div><div class='sk-cube sk-cube5' style='background-color:#00007f'></div><div class='sk-cube sk-cube6' style='background-color:#00007f'></div><div class='sk-cube sk-cube7' style='background-color:#00007f'></div><div class='sk-cube sk-cube8' style='background-color:#00007f'></div><div class='sk-cube sk-cube9' style='background-color:#00007f'></div></div><div class='sk-cube-grid' style='display:inline-block;position:relative;top:-84px;'><div class='sk-cube sk-cube1' style='background-color:gold'></div><div class='sk-cube sk-cube2' style='background-color:gold'></div><div class='sk-cube sk-cube3' style='background-color:gold'></div><div class='sk-cube sk-cube4' style='background-color:gold'></div><div class='sk-cube sk-cube5' style='background-color:gold'></div><div class='sk-cube sk-cube6' style='background-color:gold'></div><div class='sk-cube sk-cube7' style='background-color:gold'></div><div class='sk-cube sk-cube8' style='background-color:gold'></div><div class='sk-cube sk-cube9' style='background-color:gold'></div></div></div><div style='font-size:72px;text-align:center;' class='BankGothic'>Nouveau Elevator</div><div style='font-size:42px;text-align:center;'><i>Raising Your Life</i></div>"
    },
    'paging':true,
    'dom':'Blfrtip',
    'select':true,
    'initComplete':function(){
      Table_Tickets.columns().every( function () {
        var that = this;
        $( 'input', this.footer() ).on( 'keyup change clear click', function () {
          if($(this).attr('format') == 'currency' || $(this).attr('format') == 'date' || $(this).attr('format') == 'number'){
            that.draw();
          } else {
            if ( that.search() !== this.value ) {
            that
              .search( this.value )
              .draw();
            }
          }
        } );
        $( 'select', this.footer() ).on( 'keyup change clear click', function () {
          var vals = [];
          $(this).children(':selected').each(function(){
            vals.push($(this).val());
          });
          that.search( vals.join('|'), true, false );
          that.draw();
        } );
      });
    },
    'scrollY' : '800px',
    'scrollCollapse':true,
    'lengthMenu': [[20, 50, 100, -1], [20, 50, 100, 'All']]
  });
});
</script><?php
    }
} else {}?>
