<?php
/*******************Requires******************/
//Classes
if(!class_exists('object')){require(ELEVATE_ROOT.'php/classes/object/index.php');}
/*******************object******************/
if(!class_exists('object_deficiency')){
	class object_deficiency extends object {
		/*******************Variables******************/
		//Server
		protected $server       = '172.16.12.45';
		protected $database 	= 'Portal'; 
		//Table
		protected $table 		= 'Deficiency';
		protected $primary_key  = 'ID ';
		protected $columns 		= array(
			'ID'		=>'primary_key',
			'Violation' =>'foreign_key',
			'Part'		=>'foreign_key',
			'Condition'	=>'foreign_key',
			'Remedy'	=>'foreign_key'
		);
		//Columns
		protected $ID 			= Null;
		protected $Violation 	= Null;
		protected $Part 		= Null;
		protected $Condition 	= Null;
		protected $Remedy 		= Null;
		//Meta
		protected $singular     = 'deficiency';
		protected $plural 		= 'deficiencies';
		protected $name         = 'ID';
		//Relationships
		protected $aliases 		= array();
		protected $parents 		= array('violation');
		protected $children     = array();
		protected $tranversal	= array(
			'Violation' =>'Violation',
			'Part'		=>'Part',
			'Condition'	=>'Condition',
			'Remedy'	=>'Remedy'
		);
		//Aesethetics
		protected $icon			= 'fas fa-thermometer-empty';
		//View
		protected $viewport 	= array();
		/*******************Functions******************/
		/*EMPTY*/
	}
}?>