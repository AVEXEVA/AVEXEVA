<?php class Session extends Magic {
	//Variables
	///Databasing
	//SESSION
	protected $SESSION    = NULL;
	protected $GET				= NULL;
	protected $POST       = NULL;
	protected $DATA 			= NULL;
	//Other
	protected $Reference  = NULL;
	///Columns
	protected $Database   = NULL;
	protected $User       = NULL;
	protected $Connection = NULL;
	protected $Privileges  = array();
	public function __construct( $_ARGS  = array() ){
		if( session_id() == '' || !isset( $_SESSION )) { session_start(); }
		parent::__construct (
			array (
				'SESSION' => $_SESSION,
				'POST' => $_POST,
				'GET' => $_GET,
				'DATA' => $_ARGS
			)
		);
	}
	public function __validate(){ return is_a( parent::__get( 'User' ), 'User' ) && is_a( parent::__get( 'Connection' ), 'Connection' ) && parent::__get( 'User' )->__validate() && parent::__get( 'Connection' )->__validate(); }
}?>
