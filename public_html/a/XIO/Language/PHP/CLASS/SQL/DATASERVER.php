<?php
NAMESPACE SQL;
CLASS DATASERVER EXTENDS \NETWORK\SERVER {
  //VARAIBLES
  ///ARGUMENTS
  PROTECTED $ID   = NULL;
  PROTECTED $NAME = NULL;
  PROTECTED $DESCRIPTION = NULL;
  PROTECTED $TYPE = NULL;
  //Functions
  ///Magic 
  PUBLIC FUNCTION __CONSTRUCT( $_ARGS = NULL ) {
    PARENT::__CONSTRUCT( $_ARGS );
    SELF::__CONSTRUCTORS( );
  }
  PRIVATE FUNCTION __CONSTRUCTORS( ){
    SELF::__DATABASES( );
  }
  PRIVATE FUNCTION __DATABASES( ){
    SWITCH( PARENT::__GET( 'TYPE' ) ){
      CASE 'MYSQL'      : SELF::__DATABASES_MYSQL( );      BREAK;
      CASE 'MSSQL'      : SELF::__DATABASES_MSSQL( );      BREAK;
      CASE 'MARIADB'    : SELF::__DATABSAES_MARIADB( );    BREAK;
      CASE 'MONGODB'    : SELF::__DATABASES_MONGODB( );    BREAK;
      CASE 'POSTGRESQL' : SELF::__DATABASES_POSTGRESQL( ); BREAK;
    }
  }
}?>
