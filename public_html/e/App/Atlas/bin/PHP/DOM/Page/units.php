<?php
if(session_id() == '' || !isset($_SESSION)) {
    session_start();
    $_SESSION['page-target'] = 'units';
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
    <h4 style='float:left;'><div onClick=""><?php $Icons->Location();?> Units</div></h4>
    <div style='clear:both;'></div>
  </div>
  <div class="panel-body" style='height:90%;background-color:#2a2a2a'>
    <table id='Table_Units' class='display' cellspacing='0' width='100%' style='font-size:12px;'>
      <thead>
        <th></th>
        <th></th>
        <th>State</th>
        <th>Label</th>
        <th>Type</th>
        <th>Status</th>
        <th></th>
        <th></th>
      </thead>
    </table>
    <script>
    var Table_Units = $('#Table_Units').DataTable( {
  		"ajax": "cgi-bin/php/get/Units.php",
  		"processing":true,
  		"serverSide":true,
  		"columns": [
  			{
  				"className":"hidden"
  			},{
  				"className":"hidden"
  			},{
  				label: "State",
  				name: "State",
          render:function(data){
            if(data == ''){return 'N/A';}
            return data;
          }
  			},{
  			},{
  			},{
  				render:function(data){
  					switch(data){
  						case 0:return 'Active';
  						case 1:return 'Inactive';
  						case 2:return 'Demolished';
  						case 3:return 'Dismantled';
  						case 4:return 'Removed';
  						case 5:return 'No Jurisdiction';
  						default:return 'Error';
  					}
  				}
  			},{
          "className":"hidden"
        },{

        }
  		],
  		<?php /*"buttons":[
  			{
  				extend: 'collection',
  				text: 'Export',
  				buttons: [
  					'copy',
  					'excel',
  					'csv',
  					'pdf',
  					'print'
  				]
  			},
  			{ text:"View",
  			  action:function(e,dt,node,config){
  				  var data = Table_Units.rows({selected:true}).data()[0];
  				  document.location.href = 'unit.php?ID=' + data.ID;//$("#Table_Units tbody tr.selected td:first-child").html();
  			  }
  			}
  		],*/?>
  		"language":{
  			"loadingRecords":"<div style='text-align:center;'><div class='sk-cube-grid' style='display:inline-block;position:relative;';><div class='sk-cube sk-cube1' style='background-color:#cc0000'></div><div class='sk-cube sk-cube2' style='background-color:#cc0000'></div><div class='sk-cube sk-cube3' style='background-color:#cc0000'></div><div class='sk-cube sk-cube4' style='background-color:#cc0000'></div><div class='sk-cube sk-cube5' style='background-color:#cc0000'></div><div class='sk-cube sk-cube6' style='background-color:#cc0000'></div><div class='sk-cube sk-cube7' style='background-color:#cc0000'></div><div class='sk-cube sk-cube8' style='background-color:#cc0000'></div><div class='sk-cube sk-cube9' style='background-color:#cc0000'></div></div><div class='sk-cube-grid' style='display:inline-block;position:relative;top:-45px;'><div class='sk-cube sk-cube1' style='background-color:#00007f'></div><div class='sk-cube sk-cube2' style='background-color:#00007f'></div><div class='sk-cube sk-cube3' style='background-color:#00007f'></div><div class='sk-cube sk-cube4' style='background-color:#00007f'></div><div class='sk-cube sk-cube5' style='background-color:#00007f'></div><div class='sk-cube sk-cube6' style='background-color:#00007f'></div><div class='sk-cube sk-cube7' style='background-color:#00007f'></div><div class='sk-cube sk-cube8' style='background-color:#00007f'></div><div class='sk-cube sk-cube9' style='background-color:#00007f'></div></div><div class='sk-cube-grid' style='display:inline-block;position:relative;top:-84px;'><div class='sk-cube sk-cube1' style='background-color:gold'></div><div class='sk-cube sk-cube2' style='background-color:gold'></div><div class='sk-cube sk-cube3' style='background-color:gold'></div><div class='sk-cube sk-cube4' style='background-color:gold'></div><div class='sk-cube sk-cube5' style='background-color:gold'></div><div class='sk-cube sk-cube6' style='background-color:gold'></div><div class='sk-cube sk-cube7' style='background-color:gold'></div><div class='sk-cube sk-cube8' style='background-color:gold'></div><div class='sk-cube sk-cube9' style='background-color:gold'></div></div></div><div style='font-size:72px;text-align:center;' class='BankGothic'>Nouveau Elevator</div><div style='font-size:42px;text-align:center;'><i>Raising Your Life</i></div>"
  		},
  		"paging":true,
  		<?php if(!isMobile() && false){?>"dom":"Bfrtip",<?php }?>
  		"select":true,
  		"initComplete":function(){
  		},
  		"scrollY" : "600px",
  		"scrollCollapse":true,
  		"lengthChange": false,
  		"order": [[ 1, "ASC" ]],
  		"search":{
  			"search":"<?php echo isset($_SESSION['Forward-Backward'],$_SESSION['Forward-Backward']['Units']) ? $_SESSION['Forward-Backward']['Units'] : '';?>"
  		}
  	} );
    function hrefUnits(){
      $('Table#Table_Units tr').on('dblclick', function(){
        open_page("<div page-target='unit'></div>", {ID:$(this).children(':first-child').html()});
      });
    }
    $("Table#Table_Units").on("draw.dt",function(){hrefUnits();});
    </script>
  </div>
</div>
</script><?php
    }
} else {}?>
