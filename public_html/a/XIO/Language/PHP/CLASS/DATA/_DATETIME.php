<?PHP
NAMESPACE DATA;
CLASS _DATETIME EXTENDS \DATA\_STRING {
  //VARIABLES
  PROTECTED $DATE = NULL;
  PROTECTED $TIME = NULL;
  //FUNCTIONS
  ///MAGIC
  PUBLIC FUNCTION __CONSTRUCT( $_ARGS = NULL ){
    PARENT::__CONSTRUCT( $_ARGS );
    SELF::__CONSTRUCTOR( );
  }
  PUBLIC FUNCTION __CONSTRUCTOR( ){
    IF( SELF::__VALIDATE( ) ){
      PREG_MATCH(
        '/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/',
        PARENT::__GET( 'STRING' ),
        $MATCHES
      );
      PARENT::__SET(
        'DATE',
        NEW \DATA\TYPE\DATE( ARRAY(
          'STRING' => $MATCHES[ 1 ] . '-' . $MATCHES[ 2 ] . '-' . $MATCHES[ 3 ],
          'YEAR'   => $MATCHES[ 1 ],
          'MONTH'  => $MATCHES[ 2 ],
          'DAY'    => $MATCHES[ 3 ],
        ) )
      );
      PARENT::__SET(
        'TIME',
        NEW \DATA\TYPE\TIME( ARRAY(
          'STRING' => $MATCHES[ 4 ] . ':' . $MATCHES[ 5 ] . ':' . $MATCHES[ 6 ],
          'HOUR'   => $MATCHES[ 4 ],
          'MINUTE' => $MATCHES[ 5 ],
          'SECOND' => $MATCHES[ 6 ]
        ) )
      );
    }
  }
  PUBLIC FUNCTION __VALIDATE( ){
    RETURN    PARENT::__CHECK( )
           && PREG_MATCH("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", PARENT::__GET( 'STRING' ) );
  }
  PUBLIC FUNCTION __STRTOTIME( $STRING = NULL ){
    IF( IS_STRING( $STRING ) ){
      SELF::__CONSTRUCT(
        ARRAY(
          'STRING' => DATE(
            PARENT::__GET( 'STRING' ),
            STRTOTIME( $STRING )
          )
        )
      );
    }
  }
}
?>
