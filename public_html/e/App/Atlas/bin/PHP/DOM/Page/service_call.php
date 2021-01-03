<?php
if(session_id() == '' || !isset($_SESSION)) {
    session_start();
    $_SESSION['page-target'] = 'service_call';
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
?><div class="panel panel-primary" style='height:100%;'><form id='new-dispatch-ticket'>
  <script>
  function close_popup(link){
    if(confirm('Are you sure you want to delete this new ticket?')){$(link).closest('.popup').remove();}
  }
  </script>
  <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAJwGnwOrNUvlYnmB5sdJGkXy8CQsTA46g&callback="></script>
  <script type='text/javascript' src='https://maps.googleapis.com/maps/api/directions/json?origin=43.65077%2C-79.378425&destination=43.63881%2C-79.42745&key=AIzaSyAJwGnwOrNUvlYnmB5sdJGkXy8CQsTA46g'></script>
  <script type="text/javascript" src="cgi-bin/libraries/map-icons-master/dist/js/map-icons.js"></script>
  <style>
    div.popup {
      z-index:999;
      padding:5%;
      background-color:rgba(0,0,0,.9);
      position:absolute;
      left:0%;
      top:0%;
      height:100%;
      width:100%;
    }
    .row.shadower {
      box-shadow:0px 0px 0px 0px black !important;
      -webkit-box-shadow:0px 0px 0px 0px black !important;
    }
    .Home-Screen-Option.col-lg-1 {
      width:16% !important;
    }
    .panel-primary>.panel-heading {
      background-color:#efefef !important;
      color:black !important;
    }
  </style>
  <style>

  form#new-dispatch-ticket div.col-xs-6 div.col-xs-4 {
    text-align:right;
  }
  form#new-dispatch-ticket select {
    padding:5px;
  }

  </style>
  <div class='panel-heading'style='background-color:#efefef;padding:15px;font-size:14px;color:black !important;'><h4 ><?php $Icons->Ticket();?> Ticket: New</h4></div>
  <div class='panel-body'>
    <div class='row'>
      <div class='col-xs-1'>&nbsp;</div>
      <div class='col-xs-3'>
        <div class='row'>
            <div class='col-xs-12'>&nbsp;</div>
            <div class='col-xs-12'>&nbsp;</div>
        </div>
        <div class='row'>
            <div class='col-xs-4'><?php $Icons->User(1);?> Creator:</div>
            <div class='col-xs-8'><input name='My_User' value='<?php echo $My_User['fFirst'] . ' ' . $My_User['Last'];?>' disabled /></div>
          </div>
        <div class='row'>
            <div class='col-xs-4'><?php $Icons->Calendar(1);?> Created:</div>
            <div class='col-xs-8'><input name='Date' value='<?php echo date('m/d/Y h:i A');?>' disabled /></div>
          </div>
        <div class='row'>
          <div class='col-xs-4'><?php $Icons->User(1);?> Caller:</div>
          <div class='col-xs-8'><input type='text' name='Caller' style='width:100%;' /></div>
        </div>
        <div class='row'>
          <div class='col-xs-4'><?php $Icons->Email(1);?> Email:</div>
          <div class='col-xs-8'><input type='text' name='CallEmail' style='width:100%;' /></div>
        </div>
        <div class='row'>
          <div class='col-xs-4'><?php $Icons->Phone(1);?> Phone:</div>
          <div class='col-xs-8'><input type='text' name='CallPhone' style='width:100%;' /></div>
        </div>
        <div class='row'>
          <div class='col-xs-4'><?php $Icons->Email(1);?>Auto Email:</div>
          <div class='col-xs-8'><button type='button' onClick="auto_emailer(this);" style='width:100%;height:35px;'>Off</button></div>
          <input type='hidden' name='AutoEmail' value='0' />
          <script>
            function auto_emailer(link){
              if($("input[name='AutoEmail']").val() == '0'){
                $("input[name='AutoEmail']").val(1);
                $(link).html('On');
                $(link).css('background-color', '#ffd700!important');
                $(link).css('color','black');
              } else {
                $("input[name='AutoEmail']").val(0);
                $(link).html('Off');
                $(link).css('background-color', 'rgb(211, 211, 211) !important');
                $(link).css('color','black');
              }
            }
          </script>
        </div>
          <div class='row'>
            <div class='col-xs-4'><?php $Icons->Location(1);?> Location:</div>
            <div class='col-xs-8'><button type='button' id='Dispatch_Location' onClick='selectLocations(this);' style='width:100%;height:35px;'><?php
            $pass = false;
            if(isset($_GET['Location']) && is_numeric($_GET['Location'])){
              $r = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.Loc WHERE Loc.Loc = ?;",array($_GET['Location']));
              if($r){
                $row = sqlsrv_fetch_array($r);
                if(is_array($row)){
                  $pass = True;
                  echo $row['Tag'];
                }
              }
            }
            if(!$pass){?>Select Location<?php }?></button></div>
            <script>
              function selectLocations(link){
                $.ajax({
                  url:"cgi-bin/php/element/dispatch/selectLocations.php",
                  method:"GET",
                  success:function(code){
                    $("body").append(code);
                  }
                });
              }
            </script>
            <input type='hidden' name='Location' />
        </div>

          <div class='row'>
            <div class='col-xs-4'><?php $Icons->Unit(1);?> Unit:</div>
            <div class='col-xs-8'><button type='button' id='Dispatch_Unit' onClick='selectUnits(this);' style='width:100%;height:35px;'><?php
            $pass = false;
            if(isset($_GET['Unit']) && is_numeric($_GET['Unit'])){
              $r = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.Elev WHERE Elev.ID = ?;",array($_GET['Unit']));
              if($r){
                $row = sqlsrv_fetch_array($r);
                if(is_array($row)){
                  $pass = True;
                  echo isset($row['State']) ? $row['State'] . ' - ' . $row['Unit'] : $row['Unit'];
                }
              }
            }
            if(!$pass){?>Select Unit<?php }?></button></div>
            <script>
              function selectUnits(link){
                $.ajax({
                  url:"cgi-bin/php/element/dispatch/selectUnits.php?Location=" + $('form#new-dispatch-ticket input[name="Location"]').val(),
                  method:"GET",
                  success:function(code){
                    $("body").append(code);
                  }
                });
              }
            </script>
            <input type='hidden' name='Unit' />
        </div>
        <div class='row'>
            <div class='col-xs-4'><?php $Icons->Job(1);?> Job:</div>
            <div class='col-xs-8'><button type='button' id='Dispatch_Job' onClick='selectJobs(this);' style='width:100%;height:35px;'><?php
            $pass = false;
            if(isset($_GET['Job']) && is_numeric($_GET['Job'])){
              $r = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.Job WHERE Job.ID = ?;",array($_GET['Job']));
              if($r){
                $row = sqlsrv_fetch_array($r);
                if(is_array($row)){
                  $pass = True;
                  echo $row['fDesc'];
                }
              }
            }
            if(!$pass){?>Select Job<?php }?></button></div>
            <script>
              function selectJobs(link){
                $.ajax({
                  url:"cgi-bin/php/element/dispatch/selectJobs.php?Location=" + $('form#new-dispatch-ticket input[name="Location"]').val(),
                  method:"GET",
                  success:function(code){
                    $("body").append(code);
                  }
                });
              }
            </script>
            <input type='hidden' name='Job' />
        </div>
          <div class='row'>
            <div class='col-xs-4'><?php $Icons->Admin(1);?> Level:</div>
            <div class='col-xs-8'><select name='Level'>
              <option value='1'>Service Call</option>
              <option value='2'>Trucking</option>
              <option value='3'>Modernization</option>
              <option value='4'>Violations</option>
              <option value='5'>Door Lock Monitoring</option>
              <option value='6'>Repair</option>
              <option value='7'>Annual Test</option>
              <option value='10'>Preventative Maintenance</option>
              <option value='11'>Survey</option>
              <option value='12'>Engineering</option>
              <option value='13'>Support</option>
              <option value='14'>M&R</option>'
            </select></div>
        </div>
        <div class='row'>
         <div class='col-xs-4'><?php $Icons->Division(1);?> Division:</div>
         <div class='col-xs-8'><input type='text' disabled name='Division' style='width:100%;' /></div>
       </div>
       <div class='row'>
         <div class='col-xs-4'><?php $Icons->Route(1);?> Route:</div>
         <div class='col-xs-8'><input type='text' disabled name='Route' style='width:100%;' /></div>
         <input type='hidden' name='Route_Mech' />
       </div>
        <div class='row'>
          <div class='col-xs-4'><?php $Icons->Description(1);?> Notes:</div>
          <div class='col-xs-8'><pre id='Location_Remarks' style='font-size:10px;'></pre></div>
        </div>
        <div class='row'>
            <div class='col-xs-12'>&nbsp;</div>
            <div class='col-xs-12'>&nbsp;</div>
        </div>
      </div>
      <div class='col-xs-4'>
          <div class='row'>
            <div class='col-xs-12'>&nbsp;</div>
            <div class='col-xs-12'>&nbsp;</div>
        </div>

        <div class='row'>
          <div class='col-xs-4'><?php $Icons->Connection(1);?> Quick Buttons</div>
          <div class='col-xs-4'><button type='button' onClick='add_quick_info();' style='width:100%;height:35px;'>Quick Info</button></div>
          <script>function add_quick_info(){
            if(	$("#Dispatch_Location").html() != 'Select Location' &&
              $("#Dispatch_Unit").html() != 'Select Unit' &&
              $("#Dispatch_Job").html() != 'Select Job'){
              $("textarea[name='Description']").val($("textarea[name='Description']").val() == '' ? "Location: " + $('#Dispatch_Location').html() + '\nUnit: ' + $("#Dispatch_Unit").html() + "\nJob: " + $("#Dispatch_Job").html() + "\n" : $("textarea[name='Description']").val() + "\nLocation: " + $('#Dispatch_Location').html() + '\nUnit: ' + $("#Dispatch_Unit").html() + "\nJob: " + $("#Dispatch_Job").html() + "\n");
            }
          }</script>
        </div>
        <div class='row'>
          <div class='col-xs-4'>&nbsp;</div>
          <div class='col-xs-4'><button type='button' onClick='add_shutdown();' style='width:100%;height:35px;'>Shutdown</button></div>
          <script>function add_shutdown(){$("textarea[name='Description']").val($("textarea[name='Description']").val() == '' ? 'Shutdown' : $("textarea[name='Description']").val() + '\nShutdown');}</script>
          <div class='col-xs-4'><button type='button' onClick='add_entrapment();' style='width:100%;height:35px;'>Entrapment</button></div>
          <script>function add_entrapment(){$("textarea[name='Description']").val($("textarea[name='Description']").val() == '' ? 'Entrapment' : $("textarea[name='Description']").val() + '\nEntrapment');}</script>
        </div>
        <div class='row'>
          <div class='col-xs-4'>&nbsp;</div>
          <div class='col-xs-4'><button type='button' onClick='add_oos();' style='width:100%;height:35px;'>Out of Service</button></div>
          <script>function add_oos(){$("textarea[name='Description']").val($("textarea[name='Description']").val() == '' ? 'Out of Service' : $("textarea[name='Description']").val() + '\nOut of Service');}</script>
          <div class='col-xs-4'><button type='button' onClick='clear_description();' style='width:100%;height:35px;'>Clear</button></div>
          <script>function clear_description(){$("textarea[name='Description']").val('');}</script>
        </div>
        <div class='row'>
          <div class='col-xs-4'><?php $Icons->Description(1);?> Description:</div>
          <div class='col-xs-8'><textarea style='width:100%;margin-top:5px;' rows='8' name='Description'></textarea></div>
        </div>
          <div class='row'>
          <div class='col-xs-4'><?php $Icons->User(1);?> Worker:</div>
          <div class='col-xs-8'><button type='button' id='Dispatch_Worker' onClick='selectWorkers(this);' style='width:100%;height:35px;'>Select Worker</button></div>
            <script>
              function selectWorkers(link){
                $.ajax({
                  url:"cgi-bin/php/element/dispatch/selectWorkers.php",
                  method:"GET",
                  success:function(code){
                    $("body").append(code);
                  }
                });
              }
            </script>
            <input type='hidden' name='Worker' />
        </div>
        <div class='row'>
          <div class='col-xs-4'>&nbsp;</div>
          <div class='col-xs-4'><button type='button' onClick='assign_to_route();' style='width:100%;height:35px;'>Assign to Route</button></div>
          <script>function assign_to_route(){$("#Dispatch_Worker").html($("input[name='Route']").val());$("input[name='Worker']").val($("input[name='Route_Mech']").val());}</script>
          <div class='col-xs-4'><button type='button' onClick='assign_to_closest();' style='width:100%;height:35px;'>Assign to Closest (Selected)</button></div>
          <script>function assign_to_closest(){$("#Dispatch_Worker").html($("#Closest_Worker").html());$("input[name='Worker']").val($("input[name='Closest_Worker']").val());}</script>
        </div>
        <div class='row'>
          <div class='col-xs-4'>&nbsp;</div>
          <div class='col-xs-4'><button type='button' onClick='clear_assignment();' style='width:100%;height:35px;'>Clear Assignment</button></div>
          <script>function clear_assignment(){$("#Dispatch_Worker").html('Select Worker');$("input[name='Worker']").val(null);}</script>
        </div>
        <div class='row'>
            <div class='col-xs-12'>&nbsp;</div>
            <div class='col-xs-12'>&nbsp;</div>
          </div>
      </div>
      <div class='col-xs-3'>
        <div class='row'><div class='col-xs-12'>&nbsp;</div></div>
        <div class='row'><div class='col-xs-12'>&nbsp;</div></div>
        <div class='row'>
          <div id='Closest_Worker' class='col-xs-6'></div>
          <div id='Closest_Worker_Title' class='col-xs-6'></div>
          <input type='hidden' name='Closest_Worker' />
        </div>
        <div class='row'>
          <div class='col-xs-6'>Duration:</div>
          <div class='col-xs-6' id='Duration'>&nbsp;</div>
        </div>
        <div class='row'><div class='col-xs-6' onClick='dispatch_new_select_map();'><button type='button' style='width:100%;height:35px;'>Map</button></div><div class='col-xs-6' onClick='dispatch_new_select_ticket();'><button type='button' style='width:100%;height:35px;'>Ticket</button></div></div>
        <script>
          function dispatch_new_select_map(){
            $('#dispatch_new_map').show();
            $('#dispatch_new_ticket').hide();
          }
          function dispatch_new_select_ticket(){
            $('#dispatch_new_map').hide();
            $('#dispatch_new_ticket').show();
          }
        </script>
        <div class='row'><div class='col-xs-12'>
          <div id="dispatch_new_map" style='height:300px;width:100%;'></div>
          <div id="dispatch_new_ticket" style='height:500px;width:100%;display:none;background-color:white !important;overflow-y:scroll;color:black !important;font-size:8px;'></div>
        </div></div>
        <div class='row'>
          <div class='col-xs-6'><button type='button' style='width:100%;height:35px;' onClick='previous_Nearest();'>Previous</button></div>
          <div class='col-xs-6'><button type='button' style='width:100%;height:35px;' onClick='next_Nearest();'>Next</button></div>
        </div>
      </div>
      <div class='col-xs-1'>&nbsp;</div>
</div>
</div>
  <div class='panel-body'>
    <div class='row'>
      <div class='col-xs-12'>&nbsp;</div>
    </div>
    <div class='row'>
      <div class='col-xs-6'><button type='button' id='saver_dispatch_ticket' onClick='save_dispatch_ticket(this);' style='width:100%;height:50px;' >Save</button></div>
      <script>
      function closePopup(link){$(link).closest(".popup").remove();}
        function save_dispatch_ticket(link){
          if(confirm('Are you sure you want to save this ticket?')){
            $('button#saver_dispatch_ticket').html("Saving <img src='media/images/spinner.gif' height='25px' width='auto' />");
            $('button#saver_dispatch_ticket').attr('disabled', 'disabled');
            var ticketData = new FormData();
            ticketData.append('My_User', 		$("input[name='My_User']").val());
            ticketData.append('Date', 			$("input[name='Date']").val());
            ticketData.append('Caller', 		$("input[name='Caller']").val());
            ticketData.append('CallPhone',  	$("input[name='CallPhone']").val());
            ticketData.append('CallEmail',  	$("input[name='CallEmail']").val());
            ticketData.append('AutoEmail',      $("input[name='AutoEmail']").val());
            ticketData.append('Location', 		$("input[name='Location']").val());
            ticketData.append('Unit', 			$("input[name='Unit']").val());
            ticketData.append('Job', 			$("input[name='Job']").val());
            ticketData.append('Level', 			$("select[name='Level']").val());
            ticketData.append('Description', 	$("textarea[name='Description']").val());
            ticketData.append('Worker', 		$("input[name='Worker']").val());
            $.ajax({
              url:'cgi-bin/php/post/save_dispatch_ticket.php',
              data:ticketData,
              cache: false,
                  processData: false,
                  contentType: false,
              method:'POST',
              success:function(code){
                alert('Your ticket has been saved');
                document.location.href='new-dispatcher-ticket.php';
              }
            });
          }
        }
        var new_map;
var Latitude;
var Longitude;
var marker = [];
var GETTING_GPS = 0;
var GOT_DIRECTIONS = 0;

function calculateAndDisplayRoute(directionsService2, directionsDisplay2, pointA, pointB) {
  directionsService2.route({
    origin: pointA,
    destination: pointB,
    travelMode: google.maps.TravelMode.DRIVING
  }, function(response, status) {
    if (status == 'OK') {
      directionsDisplay2.setDirections(response);
    } else {
      window.alert('Directions request failed due to ' + status);
    }
  });
}
var directionsService10;
var directionsDisplay10;
function getGPS(lat, lng, pass = false){
  Latitude = lat;
  Longitude = lng;
if(GETTING_GPS == 0 || pass){
GETTING_GPS = 1;
$.ajax({
url:"cgi-bin/php/get/getGPS.php",
method:"GET",
success:function(json){
  var GPS_Data = JSON.parse(json);
  for(i in GPS_Data){
    if(marker[i] && marker[i]['Color'] && marker[i]['Color'] == 'black'){
      var Color = 'black';
    } else if(moment().diff(moment(GPS_Data[i].Time_Stamp,'YYYY-MM-DD HH:mm:ss'), 'minutes') < 30){
      var ClassName = 'New-GPS';
      var Color = 'green';
    } else if(moment().diff(moment(GPS_Data[i].Time_Stamp,'YYYY-MM-DD HH:mm:ss'), 'minutes') < 120) {
      var ClassName = 'Old-GPS';
      var Color = 'yellow';
    } else if(moment().diff(moment(GPS_Data[i].Time_Stamp,'YYYY-MM-DD HH:mm:ss'), 'minutes') < 550) {
      var ClassName = 'Ancient-GPS';
      var Color = 'orange';
    } else {
      var ClassName ='Dead-GPS';
      var Color = 'brown';
    }
    if(marker[i]){
      marker[i].setPosition(new google.maps.LatLng(GPS_Data[i].Latitude, GPS_Data[i].Longitude));
      marker[i].setTitle(GPS_Data[i].Title);
      marker[i].setIcon(pinSymbol(Color));
      marker[i]['Color'] = Color;
      marker[i]['Employee_ID'] = i;
      marker[i]['Ticket_ID'] = GPS_Data[i].Ticket_ID;
    } else {
      marker[i] = new google.maps.Marker({
        map: new_map,
        position: new google.maps.LatLng(GPS_Data[i].Latitude, GPS_Data[i].Longitude),
        title: GPS_Data[i].Title,
        icon: {
          path:mapIcons.shapes.SQUARE_PIN,
          fillColor:'#00CCBB',
          fillOpacity:0,
          strokeColor:'black',
          strokeWeight:0
        },
        id:i,
        Color:Color,
        Employee_ID:i,
        Ticket_ID:GPS_Data[i].Ticket_ID,
        icon:pinSymbol(Color)
      });
    }
    marker[i].addListener('dblclick', function() {
      $.ajax({
        url:"cgi-bin/php/tooltip/GPS.php",
        method:"GET",
        data:{
          ID:this['Employee_ID']
        },
        success:function(code){
          $(".popup").remove();
          $("body").append(code);
        }
      });
    });
    //eval("marker_set_" + jsUcfirst(GPS_Data[i]['Supervisor'].replace(/ /g,'').toLowerCase()) + ".push('" + i + "');");
  }
  GETTING_GPS = 0;
  if(GOT_DIRECTIONS == 0){setTimeout(function(){find_closest_marker();},2500);}
}
});
}
}
var exceptions = [];
var EID;
var t;
var Ticket_ID;
function next_Nearest(t){
find_closest_marker(Latitude, Longitude);
}
function previous_Nearest(t){
exceptions.pop();
find_closest_marker(Latitude, Longitude);
}
function rad(x) {return x*Math.PI/180;}
function find_closest_marker() {
var lat = Latitude;
var lng = Longitude;
var R = 6371; // radius of earth in km
var distances = [];
var closest = 99999999999999;
var nearest;
var xlat;
var xlng;
if(Latitude == 0 || Longitude == 0){return;}
marker.forEach(function(item){
if(!exceptions.includes(item.title)){
    var mlat = item.position.lat();
    var mlng = item.position.lng();
    var title = item.title;
    var dLat  = rad(mlat - lat);
    var dLong = rad(mlng - lng);
    var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(rad(lat)) * Math.cos(rad(lat)) * Math.sin(dLong/2) * Math.sin(dLong/2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    var d = R * c;
    if (d < closest && d >= 0 && d != NaN) {
        closest = d;
        xlat = mlat;
        xlng = mlng;
        t = title;
        Ticket_ID = item.Ticket_ID;
        EID = item.Employee_ID;
    }
}
});
exceptions.push(t);
new_map.setCenter(new google.maps.LatLng(xlat, xlng));
new_map.setZoom(18);
calculateAndDisplayRoute(directionsService10, directionsDisplay10, new google.maps.LatLng(xlat, xlng), new google.maps.LatLng(Latitude, Longitude));
var service = new google.maps.DistanceMatrixService();
service.getDistanceMatrix(
{
  origins: [new google.maps.LatLng(xlat, xlng)],
  destinations: [new google.maps.LatLng(Latitude, Longitude)],
  travelMode: 'DRIVING',
  unitSystem: google.maps.UnitSystem.IMPERIAL,
  avoidHighways: false,
  avoidTolls: false
}, function(response, status){
  //var response = JSON.parse(response);
  if (status !== google.maps.DistanceMatrixStatus.OK) {
        console.log('Error:', status);
    } else {
      $.ajax({
        url:"cgi-bin/php/get/lookupWorker.php",
        method:"GET",
        data:{
          ID:EID
        },
        success:function(code){
          var parsed = JSON.parse(code);
          $('#Closest_Worker').html(parsed['data'][0].First_Name + ' ' + parsed['data'][0].Last_Name);
          $('#Closest_Worker_Title').html(parsed['data'][0].Title);
          $("input[name='Closest_Worker']").val(parsed['data'][0].fWork);
          $("#Duration").html(response.rows[0].elements[0].duration.text);

          /*$("#Dispatch_Worker").html(parsed['data'][0].First_Name + ' ' + parsed['data'][0].Last_Name);
          $("input[name='Worker']").val(parsed['data'][0].fWork);*/
        }
      });
      $.ajax({
        url:"cgi-bin/php/tooltip/GPS.php",
        method:"GET",
        data:{
          ID:EID,
          popup:false
        },
        success:function(code){
          $('#dispatch_new_ticket').html(code);
        }
      });
    }
});
}
function pinSymbol(color) {
return {
  path: 'M 0,0 C -2,-20 -10,-22 -10,-30 A 10,10 0 1,1 10,-30 C 10,-22 2,-20 0,0 z M -2,-30 a 2,2 0 1,1 4,0 2,2 0 1,1 -4,0',
  fillColor: color,
  fillOpacity: 1,
  strokeColor: '#000',
  strokeWeight: 2,
  scale: 1,
};
}
function jsUcfirst(string)
{
return string.charAt(0).toUpperCase() + string.slice(1);
}
      </script>
      <div class='col-xs-6'><button type='button' onClick='close_popup(this);' style='width:100%;height:50px;'>Close</button></div>
      <script>
      </script>
    </div>

    <div class='row'>
      <div class='col-xs-12'>&nbsp;</div>
    </div>
  </div>
</form></div>
<style>
.ui-autocomplete {
max-height: 100px;
overflow-y: auto;
/* prevent horizontal scrollbar */
overflow-x: hidden;
}
/* IE 6 doesn't support max-height
* we use height instead, but this forces the menu to always be this tall
*/
* html .ui-autocomplete {
height: 100px;
}
</style>
</script><?php
    }
} else {}?>
