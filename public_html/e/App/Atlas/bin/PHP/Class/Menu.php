<?php
Class Menu extends \Magic {
  //Variables
  protected $Session = NULL;
  //Functions
    public function __construct( $Data = NULL){
      parent::__construct( $Data );
      ?><div id='Menu' class='card'>
      <div class='card-body'>
        <?php
        $r = sqlsrv_query(
          parent::__get( 'Session' )->__get( 'Database' )->__get( 'Resource' ),
          " SELECT  *
            FROM    [Portal2].dbo.[Datatable]
            WHERE   [Datatable].[Menu] = 1
          ;"
        );
        $Objects[] = array(
          'URL' => 'Datatable.php',
          'Icon' => 'Datatable',
          'Text' => 'Datatable',
          'Display' => 'Screen',
          'Buttons' => null
        );
        if($r){while( $row = sqlsrv_fetch_array($r) ) {
          $Objects[] = array(
            'URL' => 'Table.php?ID=' . $row['ID'],
            'Icon' => $row['Name'],
            'Text' => $row['Plural'],
            'Display' => 'Screen',
            'Buttons' => null
          );
        }}
        $Buttons = array(
          'HTML/Home' => array(
            'URL' => 'Home.php',
            'Icon' => 'Home',
            'Text' => 'Home',
            'Type' => 'URL',
            'Display' => null,
            'Buttons' => null
          ),
          'HTML/Work' => array(
            'URL' => 'Tickets.php',
            'Icon' => 'Ticket',
            'Text' => 'Work',
            'Display' => 'Mobile',
            'Buttons' => null
          ),
          'HTML/Objects' => array(
            'URL' => '#',
            'Icon' => 'Object',
            'Text' => 'Objects',
            'Display' => 'Screen',
            'Buttons' => $Objects
          ),
          'HTML/Logout' => array(
            'URL' => '../login.php?Logout',
            'Icon' => 'Logout',
            'Text' => 'Log Out',
            'Display' => 'Screen',
            'Buttons' => null
          )
        );
        self::Button($Buttons);?>
      </div>
      <?php self::Javascript();?>
    </div><?php }
    public function Button( $Buttons ){
      if( count( $Buttons ) > 0 ){foreach($Buttons as $Reference=>$Button){
        $Icon = $Button[ 'Icon' ];
        ?><div class='Button <?php
            $Classes = array( $Button[ 'Display' ] );
            if(     parent::__get( 'Session' )->__get( 'Reference' ) == $Reference
                ||  ( is_array( $Button[ 'Buttons' ]) && in_array( parent::__get( 'Session' )->__get( 'Reference' ), self::returnButtons( $Button[ 'Buttons' ] ) ) )
            ){
              $Classes[] = 'Active';
            }
            if(     parent::__get( 'Session' )->__get( 'Reference' ) != $Reference
                &&  $Button['Type'] == 'Object'
            ){
              $Classes[] = 'Data-Object';
            }
            echo implode( ' ', $Classes );
          ?>'<?php
        ?>>
          <div class='Info' onClick="<?php
            if(     isset($Button['Type'])
                &&  $Button['Type'] == 'Object'
                &&  parent::__get( 'Session' )->__get( 'Reference' ) == $Reference ){
                          ?>document.location.href='<?php echo $Button['URL'] . '?ID=' . parent::__get( 'Session' )->__get('ID');?>';<?php }
            elseif( !isset($Button['Type']) && (isset($Button['Type']) && !in_array($Button['Type'], array('Objects', 'URL')))){ ?>Activate($(this).parent());<?php }
            else {?>document.location.href='<?php echo $Button['URL']; ?>';<?php }
          ?>">
            <div class='Icon'><?php
              if($Button['Type'] == 'Object' && parent::__get( 'Session' )->__get( 'Reference' ) == $Reference){
                \Icons::getInstance()->$Icon();
              } elseif($Button['Type'] == 'Object'){
                \Icons::getInstance()->Create();
              } else {
                \Icons::getInstance()->$Icon();
              }
            ?></div>
            <div class='Text'><?php
              if($Button[ 'Type' ] == 'Object' && parent::__get( 'Session' )->__get( 'Reference' ) == $Reference){
                echo parent::__get('Session')->__get('Name');
              } elseif($Button[ 'Type' ] == 'Object'){
                echo 'Create';
              } else {
                echo $Button[ 'Text' ];
              }?></div>
          </div>
          <div class='Buttons'><?php if( count( $Button[ 'Buttons' ] ) > 0 ){ self::Button( $Button[ 'Buttons' ] ); }?></div>
        </div><?php
      }}
    }
    public function Javascript(){}
    public function returnButtons( $Buttons, $Keys = array() ){
      if( is_array($Buttons) && count( $Buttons ) > 0){foreach( $Buttons as $Key=>$Button ){
        //note-possibleError
        $Keys[] = $Key;
        $Keys = self::returnButtons($Button['Buttons'], $Keys);
      }}
      return $Keys;
    }
}?>
