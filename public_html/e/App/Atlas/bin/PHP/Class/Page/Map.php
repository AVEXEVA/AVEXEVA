<?php
namespace Page;
class Map extends \Page\Template {
  //Variables
  protected $Session = NULL;
  //Functions
  public function __construct( $Args ){
    parent::__construct( $Args );
    ?><div id='Map' class='Page'><?php
      self::CSS();
      self::HTML();

    ?></div><div style='clear:both;'></div><?php
  }
  public function CSS(){?><link rel="stylesheet" type="text/css" href="bin/Libraries/map-icons-master/dist/css/map-icons.css"><?php }
  private function HTML(){
    self::Menu();
    ?><div class='card-group'><?php
      switch( parent::__get( 'Session' )->__get( 'GET' )[ 'Module' ] ){
        case 'Timeline':self::Timeline();self::Javascript3();break;
        default: self::Map();self::Javascript2();break;
      }
    ?></div><?php
  }
  private function Menu(){
    ?><div class='card'>
      <div class='card-header'><?php \Icons::getInstance()->Location();?> <?php echo parent::__get( 'Data' )[ 'Tag' ];?></div>
      <div class='card-body'>
        <div class='Menu'>
          <div class='Button <?php echo !isset(parent::__get( 'Session' )->__get('GET')['Module']) || parent::__get( 'Session' )->__get('GET')['Module'] == 'Map' ? 'Active' : NULL;?>' onClick="document.location.href='Map.php?Module=Map';">
            <div class='Icon'><?php \Icons::getInstance()->Map(2);?></div>
            <div class='Text'>Map</div>
          </div>
          <div class='Button <?php echo isset(parent::__get( 'Session' )->__get('GET')['Module']) && parent::__get( 'Session' )->__get('GET')['Module'] == 'Timeline' ? 'Active' : NULL;?>' onClick="document.location.href='Map.php?Module=Timeline';">
		         <div class='Icon'><?php \Icons::getInstance()->Timeline(2);?></div>
             <div class='Text'>Timeline</div>
          </div>
        </div>
      </div>
    </div><?php
  }
  public function Map(){
    ?><div class='card'>
      <div class='card-header'>Map</div>
      <div id='mapCanvas' class='card-body fullSize'>&nbsp;</div>
    </div><?php }
  public function Timeline(){?><div class='card'>
    <div class='card'>
      <div class='card-header'>Timeline</div>
      <div id='Timeline' class='card-body fullSize'>&nbsp;</div>
    </div><?php }
  public function Javascript3(){
    ?><script>
      var GETTING_TIMELINE = 0;
      var REFRESH_DATETIME = '<?php echo date("Y-m-d H:i:s",strtotime('-300 minutes'));?>';
      var Timeline_Supervisor = '';
      var TIMELINE = new Array();
      function NOW() {

          var date = new Date();
          var aaaa = date.getFullYear();
          var gg = date.getDate();
          var mm = (date.getMonth() + 1);

          if (gg < 10)
              gg = "0" + gg;

          if (mm < 10)
              mm = "0" + mm;

          var cur_day = aaaa + "-" + mm + "-" + gg;

          var hours = date.getHours()
          var minutes = date.getMinutes()
          var seconds = date.getSeconds();

          if (hours < 10)
              hours = "0" + hours;

          if (minutes < 10)
              minutes = "0" + minutes;

          if (seconds < 10)
              seconds = "0" + seconds;

          return cur_day + " " + hours + ":" + minutes + ":" + seconds;

      }
      function getTimeline(){
        var TEMP_REFRESH_DATETIME = REFRESH_DATETIME;
        REFRESH_DATETIME = NOW();
        if(GETTING_TIMELINE == 0){
          GETTING_TIMELINE = 1;
          $.ajax({
            url:"bin/PHP/GET/Map/Timeline.php?REFRESH_DATETIME=" + TEMP_REFRESH_DATETIME + "&Supervisor=" + Timeline_Supervisor,
            method:"GET",
            error:function(XMLHttpRequest, textStatus, errorThrown){
              GETTING_TIMELINE = 0;
            },
            success:function(json){
              var ticketData = JSON.parse(json);
              for(i in ticketData){
                if(TIMELINE[i]){}
                else {
                  $("#Timeline").prepend("<div rel='" + ticketData[i].Entity_ID + "' class='row toolesttipster' id='timeline_" + ticketData[i].Entity + "_" + ticketData[i].Entity_ID + "'><div class='col-xs-12'>"
                    + "<div class='row'><div class='col-xs-12'>" + '<?php \Icons::getInstance()->Ticket(1);?> ' + ticketData[i].Entity + ' #' + ticketData[i].Entity_ID + "</div></div>"
                    + "<div class='row'><div class='col-xs-12'>" + '<?php \Icons::getInstance()->Calendar(1);?> ' + ticketData[i].Action + " @ " + ticketData[i].Time_Stamp + '</div></div>'
                    + "<div class='row'><div class='col-xs-12'>" + '<?php \Icons::getInstance()->Calendar(1);?> ' + ticketData[i].Location_Tag + '</div></div>'
                    + "<div class='row'><div class='col-xs-12'>"  + '<?php \Icons::getInstance()->User(1);?> ' + ticketData[i].Employee_Name + '</div></div>'
                  +  "</div></div>"
                  + "<div class='row'><div class='col-xs-12'>&nbsp;</div></div>");
                  $("#timeline_" + ticketData[i].Entity + "_" + ticketData[i].Entity_ID).on('click',function(){
                    $.ajax({
                      url:"cgi-bin/php/tooltip/Ticket.php",
                      method:"GET",
                      data:{
                        ID:$(this).attr('rel')
                      },
                      success:function(code){
                        $(".popup").remove();
                        $("body").append(code);
                      }
                    });
                  });
                  TIMELINE[i] = ticketData[i];
                }
              }
              GETTING_TIMELINE = 0;
              setTimeout(function(){
                getTimeline();
              }, 60000);
            }
          });
        }
      }
      $(document).ready(function(){
        getTimeline();
      })
    </script><?php
  }
  public function Javascript2(){
    ?><script type="text/javascript" src="bin/Libraries/map-icons-master/dist/js/map-icons.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script type="text/javascript">
    var LookUp_Address = null;
    var LookUp_User = null;
    var marker = new Array();
    var shutdowns = new Array();
    var marker_set_Division1 = new Array();
    var marker_set_Division2 = new Array();
    var marker_set_Division2c = new Array();
    var marker_set_Divison2 = new Array();
    var marker_set_Division3 = new Array();
    var marker_set_Division4 = new Array();
    var marker_set_Firemen = new Array();
    var marker_set_Repair = new Array();
    var marker_set_Escalator = new Array();
    var marker_set_Testing = new Array();
    var marker_set_Modernization = new Array();
    var marker_set_Gwz = new Array();
    var marker_set_Office = new Array();
    var marker_set_Warehouse = new Array();
    var marker_set_ = new Array();
    var Timeline_Supervisor = '';
    var TIMELINE = new Array();
    var REFRESH_DATETIME = '<?php echo date("Y-m-d H:i:s",strtotime('-300 minutes'));?>';
    var exceptions = new Array();
    function jsUcfirst(string)
    {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
    function showModernization(){
      for (i in marker){marker[i].setMap(marker_set_Modernization.includes(i) ? map : null);}
      for (i in shutdowns){shutdowns[i].setMap(null);}
      for (i in entrapments){entrapments[i].setMap(null);}
      Timeline_Supervisor = 'Modernization'
      $("#Timeline").html("");
      REFRESH_DATETIME = '<?php echo date("Y-m-d H:i:s",strtotime('-300 minutes'));?>';
      TIMELINE = new Array();
    }
    function showDivision1(){
      for (i in marker){marker[i].setMap(marker_set_Division1.includes(i) ? map : null);}
      for (i in shutdowns){
        if(shutdowns_division[i] == 'DIVISION #1'){
          shutdowns[i].setMap(map);
        }
        else {shutdowns[i].setMap(null);}
      }
      for (i in entrapments){
        if(entrapments_division[i] == 'DIVISION #1'){
          entrapments[i].setMap(map);
        }
        else {entrapments[i].setMap(null);}
      }
      Timeline_Supervisor = 'Division 1';
      $("#Timeline").html("");
      REFRESH_DATETIME = '<?php echo date("Y-m-d H:i:s",strtotime('-300 minutes'));?>';
      TIMELINE = new Array();
    }
    function showDivision2(){
      for (i in marker){marker[i].setMap(marker_set_Division2.includes(i) ? map : null);}
      for (i in shutdowns){
        if(shutdowns_division[i] == 'DIVISION #2'){
          shutdowns[i].setMap(map);
        }
        else {shutdowns[i].setMap(null);}
      }
      for (i in entrapments){
        if(entrapments_division[i] == 'DIVISION #2'){
          entrapments[i].setMap(map);
        }
        else {entrapments[i].setMap(null);}
      }
      Timeline_Supervisor = 'Division 2';
      $("#Timeline").html("");
      REFRESH_DATETIME = '<?php echo date("Y-m-d H:i:s",strtotime('-300 minutes'));?>';
      TIMELINE = new Array();
    }
    function showDivision3(){
      for (i in marker){marker[i].setMap(marker_set_Division3.includes(i) ? map : null);}
      for (i in shutdowns){
        if(shutdowns_division[i] == 'DIVISION #3'){
          shutdowns[i].setMap(map);
        }
        else {shutdowns[i].setMap(null);}
      }
      for (i in entrapments){
        if(entrapments_division[i] == 'DIVISION #3'){
          entrapments[i].setMap(map);
        }
        else {entrapments[i].setMap(null);}
      }
      Timeline_Supervisor = 'Division 3';
      $("#Timeline").html("");
      REFRESH_DATETIME = '<?php echo date("Y-m-d H:i:s",strtotime('-300 minutes'));?>';
      TIMELINE = new Array();
    }
    function showDivision4(){
      for (i in marker){marker[i].setMap(marker_set_Division4.includes(i) ? map : null);}
      for (i in shutdowns){
        if(shutdowns_division[i] == 'DIVISION #4'){
          shutdowns[i].setMap(map);
        }
        else {shutdowns[i].setMap(null);}
      }
      for (i in entrapments){
        if(entrapments_division[i] == 'DIVISION #4'){
          entrapments[i].setMap(map);
        }
        else {entrapments[i].setMap(null);}
      }
      Timeline_Supervisor = 'Division 4'
      $("#Timeline").html("");
      REFRESH_DATETIME = '<?php echo date("Y-m-d H:i:s",strtotime('-300 minutes'));?>';
      TIMELINE = new Array();
    }
    function showFiremen(){
      for (i in marker){marker[i].setMap(marker_set_Firemen.includes(i) ? map : null);}
      for (i in shutdowns){shutdowns[i].setMap(null);}
      for (i in entrapments){entrapments[i].setMap(null);}
      Timeline_Supervisor = 'Firemen';
      $("#Timeline").html("");
      REFRESH_DATETIME = '<?php echo date("Y-m-d H:i:s",strtotime('-300 minutes'));?>';
      TIMELINE = new Array();
    }
    function showTesting(){
      for (i in marker){marker[i].setMap(marker_set_Testing.includes(i) ? map : null);}
      for (i in shutdowns){shutdowns[i].setMap(null);}
      for (i in entrapments){entrapments[i].setMap(null);}
      Timeline_Supervisor = 'Testing';
      $("#Timeline").html("");
      REFRESH_DATETIME = '<?php echo date("Y-m-d H:i:s",strtotime('-300 minutes'));?>';
      TIMELINE = new Array();
    }
    function showEscalator(){
      for (i in marker){marker[i].setMap(marker_set_Escalator.includes(i) ? map : null);}
      for (i in shutdowns){shutdowns[i].setMap(null);}
      for (i in entrapments){entrapments[i].setMap(null);}
      Timeline_Supervisor = 'Escalator';
      $("#Timeline").html("");
      REFRESH_DATETIME = '<?php echo date("Y-m-d H:i:s",strtotime('-300 minutes'));?>';
      TIMELINE = new Array();
    }
    function showRepair(){
      for (i in marker){marker[i].setMap(marker_set_Repair.includes(i) ? map : null);}
      for (i in shutdowns){shutdowns[i].setMap(null);}
      for (i in entrapments){entrapments[i].setMap(null);}
      Timeline_Supervisor = 'Repair';
      $("#Timeline").html("");
      REFRESH_DATETIME = '<?php echo date("Y-m-d H:i:s",strtotime('-300 minutes'));?>';
      TIMELINE = new Array();
    }
    var map;
    var directionsDisplay1;
    var directionsService1;
    function renderMap(){
      var latlng = {lat:<?php echo isset(parent::__get('Session')->__get('GET')['Latitude']) ? parent::__get('Session')->__get('GET')['Latitude'] : 40.7831;?>, lng:<?php echo isset(parent::__get('Session')->__get('GET')['Longitude']) ? parent::__get('Session')->__get('GET')['Longitude'] : -73.9712;?>};
      var myOptions = {
        zoom: <?php echo isset(parent::__get('Session')->__get('GET')['Latitude'], parent::__get('Session')->__get('GET')['Longitude']) ? 18 : 10;?>,
        center: latlng
      };
      if(!document.body.contains(document.getElementById('mapCanvas'))){return;}
      map = new google.maps.Map(document.getElementById("mapCanvas"), myOptions);
      directionsService1 = new google.maps.DirectionsService;
      directionsDisplay1 = new google.maps.DirectionsRenderer({
        map: map
      });
      <?php if(isset(parent::__get('Session')->__get('GET')['Mechanic'])){?>geocoder2 = new google.maps.Geocoder();
      geocoder2.geocode({
          'address': '<?php echo $asdf['Latitude'] . ' ' . $asdf['Longitude'];?>'
      }, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
            var LookUp_Mechanic = new google.maps.Marker({
              map: map,
              position: new google.maps.LatLng(results[0].geometry.location.lat(),results[0].geometry.location.lng()),
              icon: {
                path:mapIcons.shapes.SQUARE_PIN,
                fillColor:'#00CCBB',
                fillOpacity:0,
                strokeColor:'green',
                strokeWeight:0
              },
              zIndex:99999999,
              id:'LookUp_Mechanic',
              icon:flagSymbol('black')
            });
            var LookUp_A = new google.maps.LatLng(<?php echo parent::__get('Session')->__get('GET')['Latitude'];?>, <?php echo parent::__get('Session')->__get('GET')['Longitude'];?>);
            var LookUp_M = new google.maps.LatLng(results[0].geometry.location.lat(),results[0].geometry.location.lng());
            calculateAndDisplayRoute(directionsService1, directionsDisplay1, LookUp_M, LookUp_A);
          }
        });
      <?php }?>
      $(document).ready(function(){
        getGPS();
        setInterval(getGPS, 15000);
        <?php if(isset(parent::__get('Session')->__get('GET')['Latitude'],parent::__get('Session')->__get('GET')['Longitude']) && isset(parent::__get('Session')->__get('GET')['Locate'])){?>codeAddress('<?php echo parent::__get('Session')->__get('GET')['Latitude'];?> <?php echo parent::__get('Session')->__get('GET')['Longitude'];?>');<?php }?>
      });
    }
    function rad(x) {return x*Math.PI/180;}
    function next_Nearest(t){
    	$('.popup').remove();
    	exceptions.push(t);
    	<?php if(isset(parent::__get('Session')->__get('GET')['Latitude'],parent::__get('Session')->__get('GET')['Longitude'])){?>find_closest_marker(<?php echo parent::__get('Session')->__get('GET')['Latitude'];?>, <?php echo parent::__get('Session')->__get('GET')['Longitude'];?>);<?php }?>
    }
    function previous_Nearest(t){
    	$('.popup').remove();
    	exceptions.pop();
    	<?php if(isset(parent::__get('Session')->__get('GET')['Latitude'],parent::__get('Session')->__get('GET')['Longitude'])){?>find_closest_marker(<?php echo parent::__get('Session')->__get('GET')['Latitude'];?>, <?php echo parent::__get('Session')->__get('GET')['Longitude'];?>);<?php }?>
    }
    var Ticket_ID;
    var EID;
    function find_closest_marker(lat, lng ) {
        var R = 6371; // radius of earth in km
        var distances = [];
        var closest = 99999999999999;
        var nearest;
        var xlat;
        var xlng;
        var t;
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
        map.setCenter(new google.maps.LatLng(xlat, xlng));
        map.setZoom(20);
        <?php if(isset(parent::__get('Session')->__get('GET')['Latitude'],parent::__get('Session')->__get('GET')['Longitude'])){?>calculateAndDisplayRoute(directionsService1, directionsDisplay1, new google.maps.LatLng(xlat, xlng), new google.maps.LatLng(<?php echo parent::__get('Session')->__get('GET')['Latitude'] . ',' . parent::__get('Session')->__get('GET')['Longitude'];?>));<?php }?>
        var service = new google.maps.DistanceMatrixService();
    		service.getDistanceMatrix(
    		  {
    		    origins: [new google.maps.LatLng(xlat, xlng)],
    		    <?php if(isset(parent::__get('Session')->__get('GET')['Latitude'],parent::__get('Session')->__get('GET')['Longitude'])){?>destinations: [new google.maps.LatLng(<?php echo parent::__get('Session')->__get('GET')['Latitude'] . ',' . parent::__get('Session')->__get('GET')['Longitude'];?>)],<?php }?>
    		    travelMode: 'WALKING',
    		    unitSystem: google.maps.UnitSystem.IMPERIAL,
    		    avoidHighways: false,
    		    avoidTolls: false,
    		  }, function(response, status){
    		  	//var response = JSON.parse(response);
    		  	if (status !== google.maps.DistanceMatrixStatus.OK) {
    	            console.log('Error:', status);
    	        } else {
                $.ajax({
                  url:"cgi-bin/php/tooltip/GPS.php",
                  method:"GET",
                  data:{
                    ID:EID,
                    popup:false
                  },
                  success:function(code){
                    var ticket = code;
                    $(".popup").remove();
                    var mech = t.split(' - ')[0];
                    var time = t.split(' - ')[1];
                    $("body").append('<div class="popup directions" style="font-size:20px !important;width:700px !important;left:unset;right:1% !important;height:auto;">' + "<div class='panel-primary'><div class='panel-heading'>Directions Information<div style='float:right;' onClick='close_this(this);'>Close</div></div><div class='panel-body'><div class='row'><div class='col-xs-3'>Mechanic:</div><div class='col-xs-9'>" + mech + "</div></div><div class='row'><div class='col-xs-3'>GPS Stamp</div><div class='col-xs-9'>" + moment(new Date(time)).format('MM/DD/YYYY hh:mm A') + "</div></div><div class='row'><div class='col-xs-3'>Duration:</div><div class='col-xs-9'>" + response.rows[0].elements[0].duration.text + "</div><div class='row'><div class='col-xs-6'><button onClick='previous_Nearest(\"" + t + "\");' style='width:100%;'>Previous Nearest</button></div><div class='col-xs-6'><button onClick='next_Nearest(\"" + t + "\");' style='width:100%;'>Next Nearest</button></div></div><div class='row'><div class='col-xs-12' style='font-size:12px !important;background-color:white !important;color:black !important;'>" + ticket + "</div></div></div></div>");
                  }
                });
    	        }
    		  });
    }
    function close_this(link){
    	$(link).parent().parent().parent().remove();
    }
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


    var GETTING_MODERNIZATIONS = 0;
    var modernizations_division = new Array();
    var modernizations = new Array();
    function getModernizations(){
      if(GETTING_MODERNIZATIONS == 0){
        GETTING_MODERNIZATIONS = 1;
        $.ajax({
          url:"bin/PHP/GET/Map/getModernizations.php",
          method:"GET",
          success:function(json){
            var GPS_Data = JSON.parse(json);
            for(i in modernizations){
              if(GPS_Data[i]){
                GPS_Data[i]['Updated'] = 1;
              } else {
                modernizations[i].setMap(null);
              }
            }
            for(i in GPS_Data){
              if(GPS_Data[i]['Updated']){}
              else {
                modernizations[i] = new google.maps.Marker({
                  map: map,
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
                  zIndex:99999999,
                  icon:flagSymbol(GPS_Data[i].Serviced == '1' ? 'green' : 'green')
                });
              }
            }
            for(i in GPS_Data){
              modernizations_division[i] = GPS_Data[i]['Division'];
              if(modernizations[i]){
                modernizations[i].setPosition(new google.maps.LatLng(GPS_Data[i].Latitude, GPS_Data[i].Longitude));
                modernizations[i].setTitle(GPS_Data[i].Title);
                modernizations[i].setIcon(flagSymbol(GPS_Data[i].Serviced == '1' ? 'green' : 'green'));
              } else {
                modernizations[i] = new google.maps.Marker({
                  map: map,
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
                  icon:flagSymbol(GPS_Data[i].Serviced == '1' ? 'cyan' : 'blue')
                });
              }
            }
            GETTING_MODERNIZATIONS = 0;
          }
        });
      }
    }
    var GETTING_SHUTDOWNS = 0;
    var shutdowns_division = new Array();
    function getShutdowns(){
      if(GETTING_SHUTDOWNS == 0){
        GETTING_SHUTDOWNS = 1;
        $.ajax({
          url:"bin/PHP/GET/Map/getShutdowns.php",
          method:"GET",
          success:function(json){
            var GPS_Data = JSON.parse(json);
            for(i in shutdowns){
              if(GPS_Data[i]){
                GPS_Data[i]['Updated'] = 1;
              } else {
                shutdowns[i].setMap(null);
              }
            }
            for(i in GPS_Data){
              if(GPS_Data[i]['Updated']){}
              else {
                shutdowns[i] = new google.maps.Marker({
                  map: map,
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
                  icon:flagSymbol(GPS_Data[i].Serviced == '1' ? 'cyan' : 'blue')
                });
              }
            }
            for(i in GPS_Data){
              shutdowns_division[i] = GPS_Data[i]['Division'];
              if(shutdowns[i]){
                shutdowns[i].setPosition(new google.maps.LatLng(GPS_Data[i].Latitude, GPS_Data[i].Longitude));
                shutdowns[i].setTitle(GPS_Data[i].Title);
                shutdowns[i].setIcon(flagSymbol(GPS_Data[i].Serviced == '1' ? 'cyan' : 'blue'));
              } else {
                shutdowns[i] = new google.maps.Marker({
                  map: map,
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
                  icon:flagSymbol(GPS_Data[i].Serviced == '1' ? 'cyan' : 'blue')
                });
              }
            }
            GETTING_SHUTDOWNS = 0;
          }
        });
      }
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
    function flagSymbol(color) {
        return {
            path: 'M 0,0 -1,-2 V -43 H 1 V -2 z M 1,-40 H 30 V -20 H 1 z',
            fillColor: color,
            fillOpacity: 1,
            strokeColor: '#000',
            strokeWeight: 2,
            scale: 1,
       };
    }
    var GETTING_ENTRAPMENTS = 0;
    var entrapments = new Array();
    var entrapments_division = new Array();
    function getEntrapments(){
      if(GETTING_ENTRAPMENTS == 0){
        GETTING_ENTRAPMENTS = 1;
        $.ajax({
          url:"bin/PHP/GET/Map/getEntrapments.php",
          method:"GET",
          success:function(json){
            var GPS_Data = JSON.parse(json);
            for(i in entrapments){
              if(GPS_Data[i]){
                GPS_Data[i]['Updated'] = 1;
              } else {
                entrapments[i].setMap(null);
              }
            }
            for(i in GPS_Data){
              entrapments_division[i] = GPS_Data[i]['Division'];
              if(GPS_Data[i]['Updated']){}
              else {
                entrapments[i] = new google.maps.Marker({
                  map: map,
                  position: new google.maps.LatLng(GPS_Data[i].Latitude, GPS_Data[i].Longitude),
                  zIndex:99999999,
                  title: GPS_Data[i].Title,
                  icon: {
                    path:mapIcons.shapes.SQUARE_PIN,
                    fillColor:'#00CCBB',
                    fillOpacity:0,
                    strokeColor:'black',
                    strokeWeight:0
                  },
                  id:i,
                  icon:flagSymbol(GPS_Data[i].Serviced == '1' ? 'purple' : 'red')
                });
              }
            }
            for(i in GPS_Data){
              if(entrapments[i]){
                entrapments[i].setPosition(new google.maps.LatLng(GPS_Data[i].Latitude, GPS_Data[i].Longitude));
                entrapments[i].setTitle(GPS_Data[i].Title);
                entrapments[i].setIcon(flagSymbol(GPS_Data[i].Serviced == '1' ? 'purple' : 'red'));
              } else {
                entrapments[i] = new google.maps.Marker({
                  map: map,
                  position: new google.maps.LatLng(GPS_Data[i].Latitude, GPS_Data[i].Longitude),
                  zIndex:99999999,
                  title: GPS_Data[i].Title,
                  icon: {
                    path:mapIcons.shapes.SQUARE_PIN,
                    fillColor:'#00CCBB',
                    fillOpacity:0,
                    strokeColor:'black',
                    strokeWeight:0
                  },
                  id:i,
                  icon:flagSymbol(GPS_Data[i].Serviced == '1' ? 'purple' : 'red')
                });
              }
            }
            GETTING_entrapments = 0;
          }
        });
      }
    }

    var GETTING_GPS = 0;
    var GOT_DIRECTIONS = 0;
    function getGPS(){
      getShutdowns();
      getEntrapments();
      getModernizations();
      if(GETTING_GPS == 0){
        GETTING_GPS = 1;
        $.ajax({
          url:"bin/PHP/GET/Map/getGPS.php",
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
                  map: map,
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
              eval("marker_set_" + jsUcfirst(GPS_Data[i]['Supervisor'].replace(/ /g,'').replace(/-/g,'').toLowerCase()) + ".push('" + i + "');");
            }
            GETTING_GPS = 0;
            if(GOT_DIRECTIONS == 0){setTimeout(function(){<?php if(isset(parent::__get('Session')->__get('GET')['Latitude'],parent::__get('Session')->__get('GET')['Longitude']) && isset(parent::__get('Session')->__get('GET')['Nearest'])){?>find_closest_marker(<?php echo parent::__get('Session')->__get('GET')['Latitude'];?>, <?php echo parent::__get('Session')->__get('GET')['Longitude'];?>);<?php }?>},100);GOT_DIRECTIONS = 1;}
          }
        });
      }
    }
    function codeAddress(address) {
        geocoder = new google.maps.Geocoder();
        geocoder.geocode({
            'address': address
        }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
              map.setCenter(results[0].geometry.location);
              map.setZoom(18);
              if(LookUp_Address != null){LookUp_Address.setMap(null);}
              LookUp_Address = new google.maps.Marker({
                map: map,
                position: new google.maps.LatLng(results[0].geometry.location.lat(),results[0].geometry.location.lng()),
                icon: {
                  path:mapIcons.shapes.SQUARE_PIN,
                  fillColor:'#00CCBB',
                  fillOpacity:0,
                  strokeColor:'black',
                  strokeWeight:0
                },
                zIndex:99999999,
                id:'LookUp_Address',
                icon:flagSymbol('black')
              });
            }
        });

    }
    function takeServiceCall(){
      $.ajax({
        url:"cgi-bin/php/element/map/Service_Call.php",
        method:"GET",
        success:function(code){
          $("body").append(code);
        }
      });
    }
    function zoomUser(link){
      var val = $(link).val();
      for ( i in marker ){
        if(marker[i].id == val){
          var latlng = new google.maps.LatLng(marker[i].getPosition().lat(), marker[i].getPosition().lng());
          map.setCenter(marker[i].getPosition());
          map.setZoom(15);
          if(LookUp_User != null){
            marker[LookUp_User].setIcon(pinSymbol(marker[LookUp_User]['Color']));
          }
          marker[i].setIcon(pinSymbol('black'));
          marker[i]['Color'] = 'black';
          LookUp_User = i;
        }
      }
    }
    function breadcrumbUser(link){
      var val = $(link).val();
      document.location.href='map3.php?ID=' + val;
    }
    var toggle = 0;
    function setMapOnAll(mapped) {
      for ( i in marker )
        marker[i].setMap(mapped);
      for ( i in shutdowns )
        shutdowns[i].setMap(mapped);
      for ( i in entrapments )
        entrapments[i].setMap(mapped);
      //marker = new Array();
    }
    function clearMarkers() {
      setMapOnAll(toggle == 0 ? null : map);
      toggle = toggle == 0 ? 1 : 0;
      Timeline_Supervisor = ''
      $("#Timeline").html("");
      REFRESH_DATETIME = '<?php echo date("Y-m-d H:i:s",strtotime('-300 minutes'));?>';
      TIMELINE = new Array();
      getTimeline();
    }
    $(document).on('click',function(e){
    	if($(e.target).closest('.popup:not([class*="directions"])').length === 0){
    		$('.popup:not([class*="directions"])').fadeOut(300);
    		$('.popup:not([class*="directions"])').remove();
    	}
    });
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAJwGnwOrNUvlYnmB5sdJGkXy8CQsTA46g&callback=renderMap"></script>
    <script type='text/javascript' src='https://maps.googleapis.com/maps/api/directions/json?origin=43.65077%2C-79.378425&destination=43.63881%2C-79.42745&key=AIzaSyAJwGnwOrNUvlYnmB5sdJGkXy8CQsTA46g'></script><?php
  }
  public function Javascript(){?>
    <script type="text/javascript" src="cgi-bin/libraries/map-icons-master/dist/js/map-icons.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script>
    function renderMap(){
      var latlng = {
        lat : 40.7831,
        lng : -73.9712
      };
      var myOptions = {
        zoom: 10,
        center: latlng
      };
      var map = new google.maps.Map(document.getElementById("mapCanvas"), myOptions);
    }
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAJwGnwOrNUvlYnmB5sdJGkXy8CQsTA46g&callback=renderMap"></script>
    <!--<script type='text/javascript' src='https://maps.googleapis.com/maps/api/directions/json?origin=43.65077%2C-79.378425&destination=43.63881%2C-79.42745&key=AIzaSyAJwGnwOrNUvlYnmB5sdJGkXy8CQsTA46g'></script>-->
  <?php }
}?>
