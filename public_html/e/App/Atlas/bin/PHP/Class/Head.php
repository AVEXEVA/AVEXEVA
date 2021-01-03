<?php
class Head extends Magic {
  //Variables
  protected $Session = NULL;
  //Functions
  public function __construct( $Data ){
    parent::__construct( $Data );
    $Page = !is_null( parent::__get( 'Session' )->__get( 'Reference') )
      && substr( parent::__get( 'Session')->__get( 'Reference' ), 0, 5) == 'HTML/'
      && strpos( substr(parent::__get( 'Session' )->__get( 'Reference' ), 5), '/') === false
        ?   substr(parent::__get('Session')->__get('Reference'), 5)
        :   NULL;
    ?><head>
        <title>Nouveau Elevator Portal</title>
        <?php require(PROJECT_ROOT.'/META/index.php');?>
        <?php if( !is_null($Page) && file_exists( PROJECT_ROOT . '/CSS/Page/'. $Page . '.css' )){?><link href='bin/CSS/Page/<?php echo $Page;?>.css?<?php echo rand(0,999999999999);?>' rel='stylesheet'><?php }?>
        <?php require(PROJECT_ROOT.'/CSS/index.php');?>
        <?php require(PROJECT_ROOT.'/JS/index.php');?>
        <?php if( !is_null($Page) &&  file_exists( PROJECT_ROOT . '/JS/Page/'. $Page . '.js' )){?><script src='bin/JS/Page/<?php echo $Page;?>.js?<?php echo rand(0,999999999999);?>'></script><?php }?>
    </head><?php
  }
}
?>
