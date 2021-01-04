<?php
namespace SQL;
Class Server extends \Magic {
  //VARAIBLES
  ///ARGUMENTS
  protected $ID   = NULL;
  protected $Name = NULL;
  protected $IP   = NULL;
  //Functions
  ///Magic 
  public function __construct( $_ARGS = NULL ) {
    parent::__construct( $_ARGS );
  }
  private function __connect( ){
    self::__DATABASES( );
  }
  private function __DATABASES( ){ }
}
?>
