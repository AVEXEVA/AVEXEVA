<?php
if(session_id() == '' || !isset($_SESSION)) {
    session_start();
    $_SESSION['page-target'] = 'routes';
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
  <div class="panel-heading"><h3><?php $Icons->Route();?> Routes</h3></div>
  <div class="panel-body">
      <table id='Table_Routes' class='display' cellspacing='0' width='100%'>
          <thead>
              <th title="Route's ID"></th>
              <th title='Route Name'></th>
              <th title="Route Mechanic's Last Name"></th>
              <th title="Route Mechanic's First Name"></th>
          </thead>
      </table>
  </div>
</div>
<script>
    $(document).ready(function() {
      function hrefRoutes(){
        $('Table#Table_Routes tr').on('dblclick', function(){
          open_page("<div page-target='route'></div>", {ID:$(this).children(':first-child').html()});
        });
      }
        var Table_Routes = $('#Table_Routes').DataTable( {
            "ajax": {
                "url": "cgi-bin/php/get/Routes.php",
                "dataSrc":function(json){
                    if(!json.data){json.data = [];}
                    return json.data;}
            },
            "columns": [
                { "data": "ID" },
                { "data": "Route"},
                { "data": "First_Name"},
                { "data": "Last_Name"}
            ],
            "order": [[1, 'asc']],
            "language":{"loadingRecords":""},
            "initComplete":function(){
                finishLoadingPage();
            }

        } );
        $("Table#Table_Routes").on("draw.dt",function(){hrefRoutes();});
    } );


</script>
<?php
    }
} else {}?>
