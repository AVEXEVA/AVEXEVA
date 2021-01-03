var selected = [];

function initDatatable( Args ){
  $( '#' + Args.Table ).on( 'preInit.dt', function(e, settings){
    if ($(window).height() >= 1080){ $( '#' + Args.Table ).DataTable().page.len(25).draw(); }
    else { $( '#' + Args.Table ).DataTable().page.len(10).draw(); }
  });
  Args.rowCallback = 'rowCallback' in Args ? Args.rowCallback : function(){};
  var Datatable = $( '#' + Args.Table ).DataTable( {
    processing:true,
    serverSide:true,
    ajax : {
      url : Args.Ajax,
      data : function ( d ){
        var z = d.columns.length;
        for ( i = 0; i < z; i++ ){  d.columns.splice( 0, 1);  }
        return d;
      }
    },
    columns: Args.Columns,
    language:{
      'loadingRecords':"<div style='text-align:center;'><div class='sk-cube-grid' style='display:inline-block;position:relative;';><div class='sk-cube sk-cube1' style='background-color:#cc0000'></div><div class='sk-cube sk-cube2' style='background-color:#cc0000'></div><div class='sk-cube sk-cube3' style='background-color:#cc0000'></div><div class='sk-cube sk-cube4' style='background-color:#cc0000'></div><div class='sk-cube sk-cube5' style='background-color:#cc0000'></div><div class='sk-cube sk-cube6' style='background-color:#cc0000'></div><div class='sk-cube sk-cube7' style='background-color:#cc0000'></div><div class='sk-cube sk-cube8' style='background-color:#cc0000'></div><div class='sk-cube sk-cube9' style='background-color:#cc0000'></div></div><div class='sk-cube-grid' style='display:inline-block;position:relative;top:-45px;'><div class='sk-cube sk-cube1' style='background-color:#00007f'></div><div class='sk-cube sk-cube2' style='background-color:#00007f'></div><div class='sk-cube sk-cube3' style='background-color:#00007f'></div><div class='sk-cube sk-cube4' style='background-color:#00007f'></div><div class='sk-cube sk-cube5' style='background-color:#00007f'></div><div class='sk-cube sk-cube6' style='background-color:#00007f'></div><div class='sk-cube sk-cube7' style='background-color:#00007f'></div><div class='sk-cube sk-cube8' style='background-color:#00007f'></div><div class='sk-cube sk-cube9' style='background-color:#00007f'></div></div><div class='sk-cube-grid' style='display:inline-block;position:relative;top:-84px;'><div class='sk-cube sk-cube1' style='background-color:gold'></div><div class='sk-cube sk-cube2' style='background-color:gold'></div><div class='sk-cube sk-cube3' style='background-color:gold'></div><div class='sk-cube sk-cube4' style='background-color:gold'></div><div class='sk-cube sk-cube5' style='background-color:gold'></div><div class='sk-cube sk-cube6' style='background-color:gold'></div><div class='sk-cube sk-cube7' style='background-color:gold'></div><div class='sk-cube sk-cube8' style='background-color:gold'></div><div class='sk-cube sk-cube9' style='background-color:gold'></div></div></div><div style='font-size:72px;text-align:center;' class='BankGothic'>Nouveau Elevator</div><div style='font-size:42px;text-align:center;'><i>Raising Your Life</i></div>"
    },
    buttons:[
      { text:'View',
        action:function(e,dt,node,config){
          var data = dt.rows({selected:true}).data().pluck( Args.Primary_Key ).toArray();
          document.location.href = 'Object.php?Table=' + Args.Datatable + '&ID=' + data[0];
        }
      }
    ],
    select : true,
    initComplete: function(){},
    paging:true,
    dom:'Blfrtip',
    scrollY : '75vh',
    scrollX : true,
    scroller: { rowHeight: 30 },
    autoWidth : true,
    rowCallback : Args.rowCallback
  } );
  $(window).resize(function () {
    if ($(this).height() >= 1080){ $( '#' + Args.Table ).DataTable().page.len(25).draw(); }
    else { $( '#' + Args.Table ).DataTable().page.len(10).draw(); }
  });
  $('#' + Args.Table + ' tbody').on('click', 'tr', function () {
    var id = this.id;
    var index = $.inArray(id, selected);
    if ( index === -1 ) { selected.push( id ); }
    else { selected.splice( index, 1 ); }
    $(this).toggleClass('selected');
  } );
  return Datatable;
}
