<div class='popup' id='selectLocations'>
  <div class='panel-primary'>
    <div class='panel-heading'><h4>Select Location</h4></div>
    <div class='panel-body'>
      <table id='Table_Locations' class='display' cellspacing='0' width='100%' style='font-size:12px;'>
        <thead>
          <th title="Location's ID">Loc</th>
          <th title="Location's Tag">Tag</th>
          <th>ID</th>
          <th>Maintained</th>
          <th>Latt</th>
          <th>Long</th>
          <th>Remarks</
        </thead>
      </table>
      <script>

      var Table_Locations = $('#Table_Locations').DataTable( {
        "processing":true,
        "serverSide":true,
        "ajax": "cgi-bin/php/get/lookupLocations.php",
        "order": [[ 1, "asc" ]],
        "columns": [
          {
  					"className":"hidden",
            'data':'Loc'
  				},{
            'data':'Tag'
  				},{
            'data':'ID'
          },{
            'data':'Maint',
            render:function(data, type, row){
              if(type === 'display' || type === 'filter' || type !== 'display'){
                if(data == 0){
                  return 'Inactive';
                } else {
                  return 'Maintained';
                }
              }
            }
          },{
            'data':'Latt',
            'className':''

          },{
            'data':'fLong',
            'className':''
          },{
            'data':'Remarks',
            'className':'hidden'
          }
        ],
        "language":{
  				"loadingRecords":"<div style='text-align:center;'><div class='sk-cube-grid' style='display:inline-block;position:relative;';><div class='sk-cube sk-cube1' style='background-color:#cc0000'></div><div class='sk-cube sk-cube2' style='background-color:#cc0000'></div><div class='sk-cube sk-cube3' style='background-color:#cc0000'></div><div class='sk-cube sk-cube4' style='background-color:#cc0000'></div><div class='sk-cube sk-cube5' style='background-color:#cc0000'></div><div class='sk-cube sk-cube6' style='background-color:#cc0000'></div><div class='sk-cube sk-cube7' style='background-color:#cc0000'></div><div class='sk-cube sk-cube8' style='background-color:#cc0000'></div><div class='sk-cube sk-cube9' style='background-color:#cc0000'></div></div><div class='sk-cube-grid' style='display:inline-block;position:relative;top:-45px;'><div class='sk-cube sk-cube1' style='background-color:#00007f'></div><div class='sk-cube sk-cube2' style='background-color:#00007f'></div><div class='sk-cube sk-cube3' style='background-color:#00007f'></div><div class='sk-cube sk-cube4' style='background-color:#00007f'></div><div class='sk-cube sk-cube5' style='background-color:#00007f'></div><div class='sk-cube sk-cube6' style='background-color:#00007f'></div><div class='sk-cube sk-cube7' style='background-color:#00007f'></div><div class='sk-cube sk-cube8' style='background-color:#00007f'></div><div class='sk-cube sk-cube9' style='background-color:#00007f'></div></div><div class='sk-cube-grid' style='display:inline-block;position:relative;top:-84px;'><div class='sk-cube sk-cube1' style='background-color:gold'></div><div class='sk-cube sk-cube2' style='background-color:gold'></div><div class='sk-cube sk-cube3' style='background-color:gold'></div><div class='sk-cube sk-cube4' style='background-color:gold'></div><div class='sk-cube sk-cube5' style='background-color:gold'></div><div class='sk-cube sk-cube6' style='background-color:gold'></div><div class='sk-cube sk-cube7' style='background-color:gold'></div><div class='sk-cube sk-cube8' style='background-color:gold'></div><div class='sk-cube sk-cube9' style='background-color:gold'></div></div></div><div style='font-size:72px;text-align:center;' class='BankGothic'>Nouveau Elevator</div><div style='font-size:42px;text-align:center;'><i>Raising Your Life</i></div>"
  			},
  			"paging":true,
  			"initComplete":function(){},
  			"scrollY" : "600px",
  			"scrollCollapse":true,
  			"lengthChange": false,
        "drawCallback": function ( settings ) {
          selectLocation(this.api());
        }
      });

      function selectLocation(tbl){
        $("table#Table_Locations tbody tr").each(function(){
          $(this).on('click',function(){
            if($(this).children(':nth-child(2)').html() != $("#Dispatch_Location").html()){
              $("#Dispatch_Unit").html('Select Unit');
              $("#Dispatch_Job").html('Select Job');
              $("input[name='Unit']").val('');
              $("input[name='Job']").val('');
              $('#Dispatch_Location').html($(this).children(':nth-child(2)').html());
              $('#new-dispatch-ticket input[name="Location"]').val($(this).children(':first-child').html());
              $("#Location_Remarks").html($(this).children(':nth-child(7)').html());
              var link = this;
              var latlng = {lat:parseFloat($(this).children(':nth-child(5)').html()), lng:parseFloat($(this).children(':nth-child(6)').html())};
              Latitude = parseFloat($(this).children(':nth-child(5)').html());
              Longitude = parseFloat($(this).children(':nth-child(6)').html());
              var myOptions = {
                zoom: 16,
                center: latlng
              };
              new_map = new google.maps.Map(document.getElementById("dispatch_new_map"), myOptions);
              directionsService10 = new google.maps.DirectionsService;
              directionsDisplay10 = new google.maps.DirectionsRenderer({map: new_map});
              getGPS(Latitude, Longitude);
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
              $.ajax({
                  url:'cgi-bin/php/get/lookupDivision.php?ID=' + $(link).children(':first-child').html(),
                  method:'GET',
                  success:function(code){
                    $("input[name='Division']").val(code);
                    $.ajax({
                      url:'cgi-bin/php/get/lookupRoute.php?ID=' + $(link).children(':first-child').html(),
                      method:'GET',
                      success:function(code){
                        $("input[name='Route_Mech']").val(code.split(',')[0])
                        $("input[name='Route']").val(code.split(',')[1]);
                        $('#selectLocations').remove();
                      }
                    });
                  }
                });
                $('#selectLocations').remove();
            } else {
              $('#selectLocations').remove();
            }
          });
        });
      }

      </script>
    </div>
    <div class='panel-heading'><button onClick='closePopup(this);' style='width:100%;height:50px;'>Close</button></div>
  </div>
</div>
