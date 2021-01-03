<div class='popup' id='selectWorkers'>
  <div class='panel-primary'>
    <div class='panel-heading'><h4>Select Worker</h4></div>
    <div class='panel-body'>
      <table id='Table_Workers' class='display' cellspacing='0' width='100%' style='font-size:12px;'>
        <thead>
          <th>ID</th>
          <th>First</th>
          <th>Last</th>
          <th>fWork</th>
          <th>Title</th>
        </thead>
      </table>
      <script>
      var Table_Workers = $('#Table_Workers').DataTable( {
    		"ajax": "cgi-bin/php/get/lookupWorkers.php?<?php echo isset($_GET['Location']) ? "Loc={$_GET['Location']}" : '';?>",
    		"processing":true,
    		"serverSide":true,
    		"columns": [
    			{
    				"className":"hidden"
    			},{
    			},{
    			},{
            'className':'hidden'
          },{

          }
    		],
    		"language":{
    			"loadingRecords":"<div style='text-align:center;'><div class='sk-cube-grid' style='display:inline-block;position:relative;';><div class='sk-cube sk-cube1' style='background-color:#cc0000'></div><div class='sk-cube sk-cube2' style='background-color:#cc0000'></div><div class='sk-cube sk-cube3' style='background-color:#cc0000'></div><div class='sk-cube sk-cube4' style='background-color:#cc0000'></div><div class='sk-cube sk-cube5' style='background-color:#cc0000'></div><div class='sk-cube sk-cube6' style='background-color:#cc0000'></div><div class='sk-cube sk-cube7' style='background-color:#cc0000'></div><div class='sk-cube sk-cube8' style='background-color:#cc0000'></div><div class='sk-cube sk-cube9' style='background-color:#cc0000'></div></div><div class='sk-cube-grid' style='display:inline-block;position:relative;top:-45px;'><div class='sk-cube sk-cube1' style='background-color:#00007f'></div><div class='sk-cube sk-cube2' style='background-color:#00007f'></div><div class='sk-cube sk-cube3' style='background-color:#00007f'></div><div class='sk-cube sk-cube4' style='background-color:#00007f'></div><div class='sk-cube sk-cube5' style='background-color:#00007f'></div><div class='sk-cube sk-cube6' style='background-color:#00007f'></div><div class='sk-cube sk-cube7' style='background-color:#00007f'></div><div class='sk-cube sk-cube8' style='background-color:#00007f'></div><div class='sk-cube sk-cube9' style='background-color:#00007f'></div></div><div class='sk-cube-grid' style='display:inline-block;position:relative;top:-84px;'><div class='sk-cube sk-cube1' style='background-color:gold'></div><div class='sk-cube sk-cube2' style='background-color:gold'></div><div class='sk-cube sk-cube3' style='background-color:gold'></div><div class='sk-cube sk-cube4' style='background-color:gold'></div><div class='sk-cube sk-cube5' style='background-color:gold'></div><div class='sk-cube sk-cube6' style='background-color:gold'></div><div class='sk-cube sk-cube7' style='background-color:gold'></div><div class='sk-cube sk-cube8' style='background-color:gold'></div><div class='sk-cube sk-cube9' style='background-color:gold'></div></div></div><div style='font-size:72px;text-align:center;' class='BankGothic'>Nouveau Elevator</div><div style='font-size:42px;text-align:center;'><i>Raising Your Life</i></div>"
    		},
    		"paging":true,
    		"select":true,
    		"initComplete":function(){},
    		"scrollY" : "600px",
    		"scrollCollapse":true,
    		"lengthChange": false,
    		"order": [[ 1, "ASC" ]],
        'buttons':[
        ],
        "drawCallback": function ( settings ) {
          selectWorker(this.api());
        }
    	} );
      function selectWorker(tbl){
        $("table#Table_Workers tbody tr").each(function(){
          $(this).on('click',function(){
        <?php if(isset($_GET['Assignment'])){?>
                var ID = $(this).children(':first-child').html();
                $.ajax({
                  url:'cgi-bin/php/post/assign_tickets.php',
                  method:'POST',
                  data:{
                    IDS:<?php echo json_encode($_GET['IDS']);?>,
                    Worker:ID
                  },
                  success:function(code){
                    alert('Your tickets have been assigned.');
                    $('#Table_Workers').closest('.popup').remove();
                  }
                });<?php } else {?>
        
            $('#Dispatch_Worker').html($(this).children(':nth-child(2)').html() + ' ' + $(this).children(':nth-child(3)').html());
            $('#new-dispatch-ticket input[name="Worker"]').val($(this).children(':nth-child(4)').html());
            $('#selectWorkers').remove();
          <?php }?>
          });
        });
      }
      </script>
    </div>
    <div class='panel-heading'><button onClick='closePopup(this);' style='width:100%;height:50px;'>Close</button></div>
  </div>
</div>
