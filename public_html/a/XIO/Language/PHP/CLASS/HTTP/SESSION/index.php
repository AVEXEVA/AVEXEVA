<?php class Session extends Magic {
	//Variables
	protected $SESSION    = NULL;
	protected $GET	      = NULL;
	protected $POST       = NULL;
	protected $DATA       = NULL;
	protected $DATABASE   = NULL;
	public function __construct( $_ARGS  = array() ){
		if( session_id() == '' || !isset( $_SESSION )) { session_start(); }
		parent::__construct (
			array (
				'SESSION' => $_SESSION,
				'POST' => $_POST,
				'GET' => $_GET,
				'DATA' => $_ARGS,
			)
		);
		self::connect();
	}
	public function connect(){ parent::__set( 'DATABASE', new \SQL\DATABASE( parent::__get( 'SESSION' ) ); }
}?>

