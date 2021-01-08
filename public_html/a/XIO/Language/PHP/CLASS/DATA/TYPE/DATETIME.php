<?PHP
NAMESPACE \DATA\TYPE;
CLASS DATETIME EXTENDS \DATA\TYPE\STRING {
  //VARIABLES
  PROTECTED $DATE = NULL;
  PROTECTED $TIME = NULL;
  //Functions
  ///Magic
  PUBLIC FUNCTION __construct( $_ARGS = NULL ){
    parent::__construct( $_ARGS );
    self::__constructor( );
  }
  PUBLIC FUNCTION __constructor( ){
    if( self::__check( ) ){
      preg_match(
        '/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/',
        parent::__get( 'STRING' ),
        $MATCHES
      );
      parent::__set(
        'DATE',
        new \DATA\TYPE\DATE( ARRAY(
          'STRING' => $MATCHES[ 1 ] . '-' . $MATCHES[ 2 ] . '-' . $MATCHES[ 3 ],
          'YEAR'   => $MATCHES[ 1 ],
          'MONTH'  => $MATCHES[ 2 ],
          'DAY'    => $MATCHES[ 3 ],
        ) )
      );
      parent::__set(
        'TIME',
        new \DATA\TYPE\TIME( ARRAY(
          'STRING' => $MATCHES[ 4 ] . ':' . $MATCHES[ 5 ] . ':' . $MATCHES[ 6 ],
          'HOUR'   => $MATCHES[ 4 ],
          'MINUTE' => $MATCHES[ 5 ],
          'SECOND' => $MATCHES[ 6 ]
        ) )
      );
    }
  }
  PUBLIC FUNCTION __check( ){
    return    parent::__check( )
           && preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", parent::__get( 'STRING' ) );
  }
  PUBLIC FUNCTION __strtotime( $STRING = NULL ){
    if( is_string( $STRING ) ){
      self::__construct(
        ARRAY(
          'STRING' => date(
            parent::__get( 'STRING' ),
            strtotime( $STRING )
          )
        )
      );
    }
  }
}
?>
