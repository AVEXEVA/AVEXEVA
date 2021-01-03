<?php
namespace Page;
class Privileges extends \Page\Template {
  //Variables
  protected $Session = NULL;
  //Functions
  public function __construct( $Args ){
    parent::__construct( $Args );
    ?><div id='Privileges' class='Page'><?php
      self::Card();
    ?></div><div style='clear:both;'></div><?php
  }
  public function Card(){
    ?><div class='card'>
      <div class='card-header'><?php \Icons::getInstance()->Privilege();?></div>
      <div class='card-body'><?php self::Table();?></div>
    </div><?php
  }
  public function Table(){
    ?><table id='Table_Privileges' class='display' cellspacing='0' width='100%'>
        <thead>
            <th title='ID'>ID</th>
            <th title='Last Name'>Last Name</th>
            <th title='First Name'>First Name</th>
        </thead>
       <tfooter>
            <th title='ID'>ID</th>
            <th title='Last Name'>Last Name</th>
            <th title='First Name'>First Name</th>
        </tfooter>
    </table>
    <script>
        function hrefEmployees(){$('#Table_Privileges tbody tr').each(function(){$(this).on('click',function(){document.location.href='Privilege.php?ID=' + $(this).children(':first-child').html();});});}
        $(document).ready(function() {
            var table = $('#Table_Privileges').DataTable( {
                'ajax': {
                    'url':'bin/PHP/GET/Privilege/Privileges.php',
                    'dataSrc':function(json){if(!json.data){json.data = [];}return json.data;}
                },
                'columns': [
                    { data : 'ID'},
                    { data : 'Last_Name'},
                    { data : 'First_Name'}
                ],
                'order': [[1, 'asc']],
                'language':{
                    'loadingRecords':''
                },
                'initComplete':function(){
                    hrefEmployees();
                    $("input[type='search'][aria-controls='Table_Privileges']").on('keyup',function(){hrefEmployees();});
                    $('#Table_Privileges').on( 'page.dt', function () {setTimeout(function(){hrefEmployees();},100);});
                    $('#Table_Privileges th').on('click',function(){setTimeout(function(){hrefEmployees();},100);});
                }

            } );
        } );
    </script><?php
  }
}?>
