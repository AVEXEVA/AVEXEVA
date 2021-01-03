<?php
namespace Page;
class index extends \Magic {
  protected $Session = NULL;
  public function __construct( $Session = NULL ){
    parent::__construct( array('Session' => $Session ) );
    if($Session->__validate()){
        new \Log( parent::__sleep() );
    ?><!DOCTYPE html>
    <html lang="en">
    <?php new \Head( parent::__sleep() );?>
    <body onload='loadPage();'>
      <?php new \Header( parent::__sleep() );?>
      <?php new \Loading( parent::__sleep() );?>
      <div id="page-wrapper" class='content'>
        <?php new \Menu( parent::__sleep() );?>
        <?php self::Page();?>
      </div>
    </body>
    </html><?php
    }
  }
  private function Page(){
    if( substr( parent::__get( 'Session' )->__get( 'Reference' ), 0, 10) == 'HTML/Table' ){
      new \Page\Table( parent::__sleep() );
    } elseif( substr( parent::__get( 'Session' )->__get( 'Reference' ), 0, 9) == 'HTML/Form' ){
      new \Page\Form( parent::__sleep() );
    } else {
      $Page = substr( parent::__get( 'Session' )->__get( 'Reference' ), 0, 4 ) == 'HTML' ? 'Page\\' . substr( parent::__get( 'Session' )->__get( 'Reference' ), 5) : 'HTML\\Home';
      new $Page( parent::__sleep() );
    }
  }
}
?>
