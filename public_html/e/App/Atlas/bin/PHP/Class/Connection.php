<?php class Connection extends Magic {
	//Variables
	///Columns
	protected $ID 		= NULL;
	protected $User 	= NULL;
	protected $Token 	= NULL;
	//fann_get_cascade_activation_functions
	public function __validate(){
		if(!is_numeric(parent::__get('ID'))){echo 'invalid id';return false;}
		if(!is_numeric(parent::__get('User'))){echo 'invalid user';return false;}
		if(!is_string(parent::__get('Token'))){echo 'invalid token';return false;}
		return true;
	}
}?>
