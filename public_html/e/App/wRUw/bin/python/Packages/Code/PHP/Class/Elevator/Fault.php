<?php
/*******************Requires******************/
//Classes
if(!class_exists('object')){require(ELEVATE_ROOT.'php/classes/object/index.php');}
/*******************object******************/
if(!class_exists('object_fault')){
	class iot_fault extends object {
		/*******************Variables******************/
		//Server
		protected $server      = '172.16.12.44';
		protected $database 		= 'Device';
		//Table
		protected $table 			= 'Fault';
		protected $primary_key = 'ID';
		protected $columns 		= array(
						'ID'=>'int',
				'Name'=>'varchar',
				'Description'=>'text',
				'Priority'=>'int',
				'Severity'=>'int'
		);
		//Columns
					protected $ID = Null;
			protected $Name = Null;
			protected $Description = Null;
			protected $Priority = Null;
			protected $Severity = Null;

		//Meta
		protected $singular    = 'fault';
		protected $plural			= 'faults';
		//Relationships
		protected $aliases 		= array();
		protected $parents 		= array();
		protected $children    = array();
		protected $tranversal	= array();
		//Aesthetics
		protected $icon 		= 'link';
		/*******************Functions******************/
	}
}?>