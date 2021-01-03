<div class='popup' id='selectUnits'>
  <div class='panel-primary'>
    <div class='panel-heading'><h4>Select Unit</h4></div>
    <div class='panel-body'>
      <table id='Table_Units' class='display' cellspacing='0' width='100%' style='font-size:12px;'>
        <thead>
          <th></th>
          <th>State</th>
          <th>CU #</th>
          <th>Label</th>
          <th>Type</th>
          <th>Status</th>
        </thead>
        <tfoot>
          <th></th>
          <th>State</th>
          <th>CU #</th>
          <th></th>
          <th></th>
          <th></th>
        </tfoot>
      </table>
      <style>
      </style>
      <script>
      $('#Table_Units tfoot th').each( function () {
    	  	var title = $(this).text();
    	  	$(this).html( '<input type="text" placeholder="Search '+title+'" style="width:100%;" />' );
    	} );
      var Table_Units123 = $('#Table_Units').DataTable( {
    		"ajax": "cgi-bin/php/get/lookupUnits.php?<?php echo isset($_GET['Location']) ? "Loc={$_GET['Location']}" : '';?>",
    		"processing":true,
    		"serverSide":true,
    		"columns": [
    			{
    				"className":"hidden"
    			},{
            "data":"State",
    				label: "State",
    				name: "State",
            render:function(data){
              if(data == ''){return 'N/A';}
              return data;
            }
    			},{
            "data":"fDesc"
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
    			}
    		],
    		"language":{
    			"loadingRecords":"<div style='text-align:center;'><div class='sk-cube-grid' style='display:inline-block;position:relative;';><div class='sk-cube sk-cube1' style='background-color:#cc0000'></div><div class='sk-cube sk-cube2' style='background-color:#cc0000'></div><div class='sk-cube sk-cube3' style='background-color:#cc0000'></div><div class='sk-cube sk-cube4' style='background-color:#cc0000'></div><div class='sk-cube sk-cube5' style='background-color:#cc0000'></div><div class='sk-cube sk-cube6' style='background-color:#cc0000'></div><div class='sk-cube sk-cube7' style='background-color:#cc0000'></div><div class='sk-cube sk-cube8' style='background-color:#cc0000'></div><div class='sk-cube sk-cube9' style='background-color:#cc0000'></div></div><div class='sk-cube-grid' style='display:inline-block;position:relative;top:-45px;'><div class='sk-cube sk-cube1' style='background-color:#00007f'></div><div class='sk-cube sk-cube2' style='background-color:#00007f'></div><div class='sk-cube sk-cube3' style='background-color:#00007f'></div><div class='sk-cube sk-cube4' style='background-color:#00007f'></div><div class='sk-cube sk-cube5' style='background-color:#00007f'></div><div class='sk-cube sk-cube6' style='background-color:#00007f'></div><div class='sk-cube sk-cube7' style='background-color:#00007f'></div><div class='sk-cube sk-cube8' style='background-color:#00007f'></div><div class='sk-cube sk-cube9' style='background-color:#00007f'></div></div><div class='sk-cube-grid' style='display:inline-block;position:relative;top:-84px;'><div class='sk-cube sk-cube1' style='background-color:gold'></div><div class='sk-cube sk-cube2' style='background-color:gold'></div><div class='sk-cube sk-cube3' style='background-color:gold'></div><div class='sk-cube sk-cube4' style='background-color:gold'></div><div class='sk-cube sk-cube5' style='background-color:gold'></div><div class='sk-cube sk-cube6' style='background-color:gold'></div><div class='sk-cube sk-cube7' style='background-color:gold'></div><div class='sk-cube sk-cube8' style='background-color:gold'></div><div class='sk-cube sk-cube9' style='background-color:gold'></div></div></div><div style='font-size:72px;text-align:center;' class='BankGothic'>Nouveau Elevator</div><div style='font-size:42px;text-align:center;'><i>Raising Your Life</i></div>"
    		},
    		"paging":true,
    		"select":true,
    		"initComplete":function(){
          Table_Units123.columns().every( function () {
    			  var that = this;

    			  $( 'input', this.footer() ).on( 'keyup change clear click', function () {
    			  		if ( that.search() !== this.value ) {
    							that
    								.search( this.value )
    								.draw();
    						}
    			  } );
          });
        },
    		"scrollY" : "600px",
    		"scrollCollapse":true,
    		"lengthChange": false,
    		"order": [[ 1, "ASC" ]],
        "drawCallback": function ( settings ) {
          selectUnit(this.api());
        }
    	} );
      function selectUnit(tbl){
        $("table#Table_Units tbody tr").each(function(){
          $(this).on('click',function(){
            $('#Dispatch_Unit').html($(this).children(':nth-child(3)').html() + ' - ' + $(this).children(':nth-child(4)').html());
            $('#new-dispatch-ticket input[name="Unit"]').val($(this).children(':first-child').html());
            $('#selectUnits').remove();
            $.ajax({
              url:'cgi-bin/php/get/getUnitGPS.php?ID=' + $(this).children(":first-child").html(),
              method:"GET",
              success:function(Coords){
                Coords = JSON.parse(Coords);
		if(Coords.Latitude == null || Coords.Longitude == null){return;}
                var latlng = {lat:parseFloat(Coords.Latitude), lng:parseFloat(Coords.Longitude)};
                Latitude = parseFloat(Coords.Latitude);
                Longitude = parseFloat(Coords.Longitude);
                /*var myOptions = {
                  zoom: 16,
                  center: latlng
                };
                var new_map;
                new_map = new google.maps.Map(document.getElementById("dispatch_new_map"), myOptions);*/
                new_map.setCenter(new google.maps.LatLng(Latitude, Longitude));
                new_map.setZoom(18);
                directionsService101 = new google.maps.DirectionsService;
                directionsDisplay101 = new google.maps.DirectionsRenderer({map: new_map});
                getGPS(Latitude, Longitude, true);
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
            })
          });
        });
      }
      </script>
    </div>
    <div class='panel-heading'><button onClick='closePopup(this);' style='width:100%;height:50px;'>Close</button></div>
  </div>
</div>
