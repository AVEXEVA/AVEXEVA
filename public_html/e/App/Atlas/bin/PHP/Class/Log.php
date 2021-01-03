<?php
class Log extends Magic {
  protected $Session = NULL;
  public function __construct( $Data ){
    parent::__construct( $Data );
    sqlsrv_query(
      parent::__get( 'Session' )->__get( 'Database')->__get( 'Resource' ),
      "INSERT INTO Portal.dbo.Activity([User], [Date], [Page]) VALUES(?,?,?);",
      array(
        parent::__get( 'Session' )->__get( 'User' )->__get( 'ID' ),
        date( 'Y-m-d H:i:s' ),
        parent::__get( 'Session' )->__get( 'Reference' )
      )
    );
  }
}
?>
