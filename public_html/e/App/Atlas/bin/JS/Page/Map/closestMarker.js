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
    <?php if(isset($_GET['Latitude'],$_GET['Longitude'])){?>calculateAndDisplayRoute(directionsService1, directionsDisplay1, new google.maps.LatLng(xlat, xlng), new google.maps.LatLng(<?php echo $_GET['Latitude'] . ',' . $_GET['Longitude'];?>));<?php }?>
    var service = new google.maps.DistanceMatrixService();
		service.getDistanceMatrix(
		  {
		    origins: [new google.maps.LatLng(xlat, xlng)],
		    <?php if(isset($_GET['Latitude'],$_GET['Longitude'])){?>destinations: [new google.maps.LatLng(<?php echo $_GET['Latitude'] . ',' . $_GET['Longitude'];?>)],<?php }?>
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
