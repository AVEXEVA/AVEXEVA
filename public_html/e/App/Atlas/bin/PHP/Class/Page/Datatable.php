<?php
namespace Page;
class Datatable extends \Page\Template {
  //Variables
  protected $Session = NULL;
  //SQL
  protected $Columns;
  //Functions
  public function __construct( $Args ){
    parent::__construct( $Args );
    if( is_numeric(parent::__get( 'Session' )->__get( 'GET' )[ 'ID' ] ) ){
      $Table_ID = parent::__get( 'Session' )->__get( 'GET' )[ 'ID' ];
    } else {
      $Table_ID = 1;
    }
    //MutliDatabaseBug
    $Resource = sqlsrv_query(
      parent::__get( 'Session' )->__get( 'Database' )->__get( 'Resource' ),
      " SELECT  *
        FROM    [Portal2].dbo.[Datatable]
        WHERE   [Datatable].[ID] = ?
      ;",
      array( $Table_ID )
    );
    if( $Resource ){
      $this->Session->__set(
        'GET',
        array_merge(
          parent::__get( 'Session' )->__get( 'GET' ),
          array(
            'Datatable' => sqlsrv_fetch_array($Resource)['ID']
          )
        )
      );
    }
    self::Columns();
    ?><div id='Admin' class='Page'><?php
      self::Admin();
    ?></div><div style='clear:both;'></div><?php
  }
  public function Admin(){
    if( is_numeric(parent::__get( 'Session' )->__get( 'GET' )[ 'ID' ] ) ){
      $Table_ID = parent::__get( 'Session' )->__get( 'GET' )[ 'ID' ];
    } else {
      $Table_ID = 1;
    }
    ?><div class='card'>
      <div class='card-header'><?php \Icons::getInstance()->Admin();?></div>
      <div class='card-body'>
        <div class='row'>
          <div class='col'>&nbsp;</div>
          <div class='col'><button onClick='scanTables();'>Scan Tables</button></div>
          <div class='col'>&nbsp;</div>
        </div>
        <div class='row'>
          <div class='col'>Table:</div>
          <div class='col'><select name='Table' onChange='changeTable(this);'>
            <?php
              $Resource = sqlsrv_query(
                parent::__get( 'Session' )->__get( 'Database' )->__get( 'Resource' ),
                " SELECT    *
                  FROM      [Portal2].dbo.[Datatable]
                  ORDER BY  [Datatable].[Name] ASC
                ;",
                array( )
              );
              if( $Resource ){while ( $Row = sqlsrv_fetch_array( $Resource ) ){?><option <?php echo $Table_ID == $Row['ID'] ? 'selected' : null; ?> value='<?php echo $Row['ID'];?>'><?php echo $Row['Name'];?></option><?php }}
            ?>
          </select></div>
          <script> function changeTable( link ){ document.location.href='Datatable.php?ID=' + $( link ).val(); } </script>
        </div>
        <div class='row'>
          <?php
            $r = sqlsrv_query(
              parent::__get( 'Session' )->__get( 'Database' )->__get( 'Resource' ),
              " SELECT  *
                FROM    [Portal2].dbo.[Datatable]
                WHERE   [Datatable].[ID] = ?
              ;",
              array( $Table_ID )
            );
            $Table = sqlsrv_fetch_array($r);
            $Table_Name = $Table['Name'];
          ?>
          <div class='col'>Menu:</div>
          <div class='col'><select name='Menu' onChange='updateTableMenu(this);'>
            <option value='null'>Select</option>
            <option value='0' <?php echo $Table['Menu'] == 0 ? 'selected' : null;?>>False</option>
            <option value='1' <?php echo $Table['Menu'] == 1 ? 'selected' : null;?>>True</option>
          </select></div>
          <script>function updateTableMenu( link ){
            $.ajax({
              url:'bin/PHP/POST/Datatable/Menu.php',
              data: {
                ID : <?php echo $Table['ID'];?>,
                Menu : $(link).val()
              },
              method:'POST',
              success:function(){ location.reload(); }
            });
          }</script>
        </div>
      </div>
    </div><?php
    ?><div class='card'>
      <div class='card-header'><?php \Icons::getInstance()->$Table_Name()?> <?php echo $Table;?></div>
      <div class='card-body'>
        <table id='Table_Datatable' class='display' cellspacing='0' width='100%' style=''>
          <thead>
            <th name='Database' placeholder='Database'>Database</th>
            <th name='Datatable' placeholder='Datatable'>Datatable</th>
            <th name='Datacolumn' placeholder='Datacolumn'>Datacolumn</th>
            <th name='Ordinal' placeholder='Ordinal'>Ordinal</th>
            <th name='Column' placeholder='Column' >Column</th>
            <th name='Display' placeholder='Display'>Display</th>
            <th name='Datatype' placeholder='Datatype'>Datatype</th>
            <th name='Position' placeholder='Position'>Position</th>
          </thead>
        </table>
      </div>
    </div>
    <script>
    var Page_Datatable = false;
    Page_Datatable = initDatatable({
      Table : 'Table_Datatable',
      Ajax  : 'bin/PHP/GET/Datatable/Datacolumns.php?Datatable=<?php echo parent::__get( 'Session' )->__get( 'GET' )[ 'Datatable' ];?>',
			Columns: [
        {
          data : 'Database'
        },{
          data : 'Datatable'
        },{
          data : 'Datacolumn'
        },{
          data : 'Ordinal'
        },{
          data : 'Name'
        },{
          data : 'Display',
          render : function( d, type, row){
            switch( type ){
              case 'filter' :
                return d == 0
                  ? 'No'
                  : 'Yes';
              case 'display': return '<button onClick="columnUpdate(this);">' + ( d == 1 ? 'Yes' : 'No' ) + '</button>';
              default : return d;
            }
          }
        },{
          data : 'Datatype',
          render : function( d, type, row ){
            switch( type ){
              case 'filter' : return d;
              case 'display': return columnDatatypeRender( d );
              default : return d;
            }
          }
        },{
          data : 'Position',
          render: function( d, type, row){ return d; }
        }
      ],
      rowCallback : function( row, data, displayNum, displayIndex, dataIndex ){
        var length = Page_Datatable.page.info().recordsTotal;
        var options = [ '<option value="9000">Select</option>' ];
        var selected;
        var position = data.Position;
        for( i = 0; i < length; i++ ){
          selected = i == parseInt( position ) ? 'selected' : null;
          options = options + "<option value='" + i + "' " + selected + ">" + i + "</option>";
        }
        $('td:eq(7)', row).html( '<select name="Position" onChange="columnUpdate(this);">' + options + '</select>' );
      }
    });
    function DatatableArray( Table ){
      var array = [];
      var headers = [];
      $('#' + Table + ' th').each(function(index, item) {
          headers[index] = $(item).children('div').html();
      });
      var i = 0;
      $('#' + Table + ' tr').has('td').each(function() {
          var arrayItem = {};
          $('td', $(this)).each(function(index, item) {
            switch(headers[index]){
              case 'Database':arrayItem[headers[index]] = $(item).html();break;
              case 'Datatable':arrayItem[headers[index]] = $(item).html();break;
              case 'Datacolumn':arrayItem[headers[index]] = $(item).html();break;
              case 'Column':arrayItem[headers[index]] = $(item).html();break;
              case 'Display':arrayItem[headers[index]] = $($(item).html()).html();break;
              case 'Datatype':arrayItem[headers[index]] = $('option:selected', item).html();break;
              case 'Position':arrayItem[headers[index]] = $('option:selected', item).html();break;
            }
          });
          array[i] = arrayItem;
          i++;
      });
      return array;
    }
    function columnDatatypeRender( d ){
      var Datatypes = [ 'string', 'primary_key', 'mm/dd/yyyy', 'bit-yn', 'bit-ny', 'currency' ];
      var HTML = "<select name='Datatype' onChange='columnDatatype(this);'>";
      for( i in Datatypes ){
        var isSelected = Datatypes[i] == d ? 'selected' : null;
        HTML = HTML + "<option value='" + Datatypes[i] + "' " + isSelected + ">" + Datatypes[i] + "</option>";
      }
      HTML = HTML + "</select>";
      return HTML;
    }
    function updateAdminTable(){
      $.ajax({
        url : 'bin/PHP/POST/Datatable/Datacolumns.php',
        method : 'POST',
        data : { Datatable : DatatableArray( 'Table_Datatable' ), },
        success : function( Result ){ $( '#' + 'Table_Datatable' ).dataTable( ).fnStandingRedraw( ); }
      });
    }
    function columnDatatype(link){
      $(link).prop('disabled', true);
      updateAdminTable();
    }
    function columnUpdate(link){
      $(link).prop('disabled', true);
      if($(link).html() == 'Yes'){ $(link).html('No'); }
      else if($(link).html() == 'No'){ $(link).html('Yes'); }
      updateAdminTable();
    }
    </script><?php
  }
  public function Columns(){
    $Table = parent::__get( 'Session' )->__get( 'Table' );
    $Resource = sqlsrv_query(
      parent::__get( 'Session' )->__get( 'Database' )->__get( 'Resource' ),
      " SELECT  *
        FROM    information_schema.columns
                LEFT JOIN Portal2.dbo.Datatable ON columns.table_name = Datatable.Name
                LEFT JOIN Portal2.dbo.Datacolumn ON columns.column_name = Datacolumn.Name
        WHERE   columns.table_name = ?
      ;",
      array( $Table )
    );
    $Columns = array();
    while( $Row = sqlsrv_fetch_array( $Resource ) ){ $Columns[ $Row['COLUMN_NAME'] ] = array(
      'Display' => $Row['Display']
    );}
    parent::__set( 'Columns', $Columns );
  }
}?>
