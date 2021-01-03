<?php class User extends Magic {
	//Variables
	///Columns
	protected $ID = NULL;
	protected $First_Name = NULL;
	protected $Last_Name 	= NULL;
	protected $Employee_ID = NULL;
	protected $Database = NULL;
	//Functions
	public function __validate(){
		if(!is_numeric(parent::__get('ID'))){echo 'invalid id';return false;}
		if(!is_string(parent::__get('First_Name'))){echo 'invalid first name';return false;}
		if(!is_string(parent::__get('Last_Name'))){echo 'invalid last name';return false;}
		if(!is_numeric(parent::__get('Employee_ID'))){echo 'invalid employee id';return false;}
		if(!is_string(parent::__get('Database'))){echo 'invalid database';return false;}
		return true;
	}

}?>
