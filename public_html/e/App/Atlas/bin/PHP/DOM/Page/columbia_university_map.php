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
?><div class='panel panel-primary'>
  <div class='panel-heading'><h2>Columbia University Vertical Transportation Map</h2></div>
  <div class="panel-body">
    <div class='row'>
      <div class='col-xs-12'><div id="map" style='height:800px;width:100%;'></div></div>
    </div>
  </div>
</div>
<link rel="stylesheet" type="text/css" href="cgi-bin/libraries/map-icons-master/dist/css/map-icons.css">
<script type="text/javascript" src="cgi-bin/libraries/map-icons-master/dist/js/map-icons.js"></script>
<script>
var gettingMarkers = 0;
var customerMarkers = [];
function pinSymbol(color) {return {path: 'M 0,0 C -2,-20 -10,-22 -10,-30 A 10,10 0 1,1 10,-30 C 10,-22 2,-20 0,0 z M -2,-30 a 2,2 0 1,1 4,0 2,2 0 1,1 -4,0',fillColor: color,fillOpacity: 1,strokeColor: '#000',strokeWeight: 2,scale: 1,};}
function flagSymbol(color) {return {path: 'M 0,0 -1,-2 V -43 H 1 V -2 z M 1,-40 H 30 V -20 H 1 z',fillColor: color,fillOpacity: 1,strokeColor: '#000',strokeWeight: 2,scale: 1,};}
function renderMap(){
      var latlng = {lat:40.8075, lng:-73.9626};
      var myOptions = {
        zoom: 18,
        center: latlng
      };
      map = new google.maps.Map(document.getElementById("map"), myOptions);
      renderMarkers();
  }
function renderMarkers(){
  if(gettingMarkers == 0){
    gettingMarkers = 1;
    $.ajax({
      url:'cgi-bin/php/get/columbiaMarkers.php',
      method:'GET',
      success:function(jsonData){
        var jD = JSON.parse(jsonData);
        for(i in jD){
          customerMarkers[i] = new google.maps.Marker({
          map: map,
          position: new google.maps.LatLng(jD[i].Latitude, jD[i].Longitude),
          title: jD[i].Unit_Name,
          icon: {
            path:mapIcons.shapes.SQUARE_PIN,
            fillColor:'#00CCBB',
            fillOpacity:0,
            strokeColor:'black',
            strokeWeight:0
          },
          id:i,
          icon:pinSymbol(jD[i].Running == 'Healthy' ? 'green' : jD[i].Running == 'Modernizing' ? 'blue' : jD[i].Running == 'Repairing' ? 'orange' : 'red')
          });
        }
      }
    });
  }
}
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAJwGnwOrNUvlYnmB5sdJGkXy8CQsTA46g&callback=renderMap"></script>
<script type='text/javascript' src='https://maps.googleapis.com/maps/api/directions/json?origin=43.65077%2C-79.378425&destination=43.63881%2C-79.42745&key=AIzaSyAJwGnwOrNUvlYnmB5sdJGkXy8CQsTA46g'></script>
<?php
    }
} else {}?>
