<?php
/*******************Requires******************/
//Classes
if(!class_exists('object')){require(ELEVATE_ROOT.'php/classes/object/index.php');}
/*******************Object******************/
if(!class_exists('object_contact')){
	class object_contact extends object {
		/*******************Variables******************/
		//Database
		protected $server       = '172.16.12.45';
		protected $database 	= 'nei';
		//Table
		protected $table 		= 'Rol';
		protected $primary_key  = 'ID';
		protected $columns 		= array(
			'ID'				=>'primary_key',
			'Name'				=>'varchar',
			'Street'			=>'varchar',
			'City' 				=>'varchar',
			'State'				=>'varchar',
			'Zip'				=>'int',
			'Phone'				=>'phone',
			'Contact'			=>'varchar',
			'Fax'				=>'varchar',
			'EMail'				=>'varchar'
		);	
		//Columns
		protected $ID			= Null;
		protected $Name			= Null;
		protected $Street		= Null;
		protected $City 		= Null;
		protected $State		= Null;
		protected $Zip			= Null;
		protected $Phone		= Null;
		protected $Contact		= Null;
		protected $Fax 			= Null;
		protected $EMail        = Null;
		//Meta
		protected $singular     = 'contact';
		protected $plural 		= 'contacts';
		protected $name         = 'Name';
		//Relationships
		protected $aliases 		= array();
		protected $parents 		= array();
		protected $children     = array();
		protected $tranversal   = array();
		//Aesthetics
		protected $icon 		= 'fa fa-address-book';
		//View
		protected $viewport 	= array('tabs');
		/*******************Functions******************/
		//Magic Methods
		/*Empty*/
	}
}?>