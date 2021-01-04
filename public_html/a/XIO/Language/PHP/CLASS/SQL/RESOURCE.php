<?php
namespace SQL;
Class Resource extends \Magic {
   //VARIABLES
   protected $DATABASE = NULL;
   //SQLSRV
   protected $LINK = NULL;
   protected $TYPE = NULL;
   //FUNCTIONS
   public function __construct( $_ARGS = Array( ) ){
     parent::__construct( $_ARGS );
     self::__connect();
   }
   private function __connect(){
     switch( parent::__get( 'TYPE' ) ){
       case 'SQLSRV' : self::__sqlsrv( ); break;
       case 'MYSQLI' : self::__mysqli( ); break;
       default       : self::__mysqli( ); break;
     }
   }
   private function __sqlsrv( ){
     parent::__set( 
       'LINK',
       sqlsrv_connect(
         parent::__get( 'DATABASE' )->__get( 'IP' ),
         parent::__get( 'DATABASE' )->__get( 'Username' ),
         parent::__get( 'DATABASE' )->__get( 'Password' ),
         parent::__get( 'DATABASE' )->__get( 'Name' )
       )
     )
   }
   private function __mysqli( ){ 
     parent::__set(
       'LINK',
       mysqli_connect(
          parent::__get( 'DATABASE' )->__get( 'IP' ),
          parent::__get( 'DATABASE' )->__get( 'Username' ),
          parent::__get( 'DATABASE' )->__get( 'Password' ),
          parent::__get( 'DATABASE' )->__get( 'Name' )
       )
     );
   }
}
?>
