<?php
Class User extends Magic {
	//VARIABLES
        ///ARGUMENTS
	protected $ID;
	protected $Name;
	protected $Password;
	protected $Email;
	protected $Person;
	//LISTS
	protected $Friends 	= array();
	protected $Followers 	= array();
	protected $Groups 	= array();
	//FUNCTIONS
        ///MAGIC
	public function __construct( $_ARGS = NULL ){
          parent::__construct( $_ARGS );
        } 
}?>
