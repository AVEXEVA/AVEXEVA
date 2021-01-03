<?php
namespace XE;
class HTTP extends \Magic {
	//Variables
	protected $FILES    = NULL;
	protected $GET      = NULL;
	protected $POST     = NUL;
	protected $SERVER   = NULL;
	protected $SESSION  = NULL;
	//Functions
	//Magic
	public function __construct( ){ self::__constructor( );	}
	public function __constructor( ){
		parent::__construct( array(
			'FILES' 	=> 	new \HTTP\FILES\index( ),
			'GET'		=>	new \HTTP\GET\index( ),
			'POST'		=>	new \HTTP\POST\index( ),
			'SERVER'	=>	new \HTTP\SERVER\index( ),
			'SESSION'	=>	new \HTTP\SESSION\index( )
		) );
	}
