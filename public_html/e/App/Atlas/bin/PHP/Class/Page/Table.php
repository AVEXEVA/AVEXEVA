<?php
namespace Page;
class Table extends \Page\Template {
  //Variables
  protected $Session = NULL;
  //SQL
  protected $Table;
  protected $Columns;
  //Functions
  public function __construct( $Args ){
    parent::__construct( $Args );
    self::SQL();
    parent::Columns();
    ?><div id='<?php echo parent::__get( 'Table' )['Plural'];?>' class='Page'><?php
      self::Table();
    ?></div><div style='clear:both;'></div><?php
  }
  private function SQL(){
    $r = sqlsrv_query(
      parent::__get( 'Session' )->__get( 'Database' )->__get( 'Resource' ),
      " SELECT  Datatable.*,
                Datacolumn.Name AS Primary_Key
        FROM    [Portal2].dbo.[Datatable]
                LEFT JOIN [Portal2].dbo.[Datacolumn] ON [Datatable].[ID] = [Datacolumn].[Table] AND [Datacolumn].[Datatype] = 'primary_key'
        WHERE   [Datatable].[ID] = ?
      ;",
      array(
        substr( parent::__get( 'Session' )->__get( 'Reference' ), 11 )
      )
    );
    parent::__set( 'Table', sqlsrv_fetch_array( $r ) );
  }
  public function Table(){
    $Table_Singular = parent::__get( 'Table' )[ 'Singular' ];
    ?><div class='card'>
      <div class='card-header'><?php \Icons::getInstance()->$Table_Singular();?> <?php echo parent::__get( 'Table' )[ 'Plural' ];?></div>
      <div class='card-body'>
        <table id='Table_<?php echo parent::__get( 'Table' )[ 'Plural' ];?>' class='display' cellspacing='0' width='100%' style=''>
          <thead><?php if(count(parent::__get( 'Columns' )) > 0){foreach(parent::__get( 'Columns' ) as $Position => $Column ){ ?><th placeholder='<?php echo $Column[ 'Name' ];?>'><?php echo $Column[ 'Name' ];?></th><?php }} ?></thead>
        </table>
      </div>
    </div>
    <script>
    initDatatable({
      Datatable : <?php echo parent::__get( 'Table' )[ 'ID' ];?>,
      Primary_Key : '<?php echo parent::__get( 'Table' )[ 'Primary_Key' ];?>',
      Table : 'Table_<?php echo parent::__get( 'Table' )[ 'Plural' ];?>',
      Ajax  : 'bin/PHP/GET/Datatable/Table.php?Name=<?php echo parent::__get( 'Table' )[ 'Name' ];?>',
			Columns: [<?php print parent::initDatatableColumns(); ?>]
    });
    </script><?php
  }
}?>
