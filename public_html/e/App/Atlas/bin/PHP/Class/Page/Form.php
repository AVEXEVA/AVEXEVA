<?php
namespace Page;
class Form extends \Page\Template {
  //Variables
  ///Session
  protected $Session = NULL;
  //SQL
  protected $Table = NULL;
  protected $Columns = NULL;
  protected $Primary_Key = NULL;
  protected $Data = NULL;
  //Functions
  public function __construct( $Args ){
    parent::__construct( $Args );
    self::SQL();
    ?><div id='<?php echo parent::__get( 'Table' )[ 'Name' ];?>' class='Page Object'><?php
      self::HTML();
    ?></div><div style='clear:both;'></div><?php
  }
  private function Javascript(){}
  private function SQL(){
    self::sqlTable();
    self::sqlColumns();
    self::sqlData();
  }
  private function sqlTable(){
    $r = sqlsrv_query(
      parent::__get( 'Session' )->__get( 'Database' )->__get( 'Resource' ),
      " SELECT  *
      FROM    [Portal2].dbo.[Datatable]
      WHERE   [Datatable].[ID] = ?
      ;",
      array(
        parent::__get( 'Session' )->__get( 'GET' )[ 'Table' ]
      )
    );
    parent::__set( 'Table', sqlsrv_fetch_array( $r ) );
  }
  private function sqlColumns(){
    $r = sqlsrv_query(
      parent::__get( 'Session' )->__get( 'Database' )->__get( 'Resource' ),
      " SELECT  *
        FROM    [Portal2].dbo.[Datacolumn]
        WHERE   [Datacolumn].[Table] = ?
      ;",
      array(
        parent::__get( 'Session' )->__get( 'GET' )[ 'Table' ]
      )
    );
    $Columns = array();
    if( $r ){ while( $row = sqlsrv_fetch_array( $r ) ){
      if( $row[ 'Datatype' ] == 'primary_key' ){ parent::__set( 'Primary_Key', $row[ 'Name' ] ); }
      $Columns[] = $row;
    } }
    parent::__set( 'Columns', $Columns );
  }
  private function sqlData(){
    if( is_null( parent::__get( 'Primary_Key' ) ) ){return;}
    $Table = parent::__get( 'Table' )[ 'Name' ];
    $Primary_Key = parent::__get( 'Primary_Key' );
    $r = sqlsrv_query(
      parent::__get( 'Session' )->__get( 'Database' )->__get( 'Resource' ),
      " SELECT  *
        FROM    {$Table}
        WHERE   {$Table}.{$Primary_Key} = ?
      ;",
      array(
        parent::__get( 'Session' )->__get( 'GET' )[ 'ID' ]
      )
    );
    parent::__set( 'Data', sqlsrv_fetch_array( $r ) );
  }
  private function HTML(){
    self::Menu();
    switch( parent::__get( 'Session' )->__get( 'GET' )[ 'Tab' ] ){
      default: self::Default();break;
    }
  }
  private function Menu(){}
  private function Default(){
    $Singular = parent::__get( 'Table' )[ 'Singular' ];
    ?><div class='card'>
      <div class='card-header'><?php \Icons::getInstance()->$Singular();?> <?php echo $Singular;?></div>
      <div class='card-body'>
        <div class='row'>
        <?php
          $first = false;
          $i = 0;
          if( is_array( parent::__get( 'Columns' ) ) ){foreach( parent::__get( 'Columns' ) as $index => $Column ){
            if($i %  3 == 0 && $first){
              ?></div><?php
              ?><div class='row'><?php
            }
            if(!$first){$first = !$first;}?>
              <div class='col'><?php echo $Column[ 'Name' ];?></div>
              <div class='col'><input type='text' value='<?php echo parent::__get( 'Data' )[ $Column[ 'Name' ] ];?>' /></div>
            <?php $i++;
          }}
        ?></div>
      </div>
    </div><?php
  }
}?>
