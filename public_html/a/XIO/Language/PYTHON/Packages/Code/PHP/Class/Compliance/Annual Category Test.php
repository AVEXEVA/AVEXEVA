<?php
/*******************Requires******************/
//Classes
if(!class_exists('object')){require(ELEVATE_ROOT.'php/classes/object/index.php');}
/*******************object******************/
if(!class_exists('object_permit')){
	class object_permit extends object {
		//Server
		protected $server 		= '172.16.12.45';
		protected $database 	= 'Portal';
		//Table
		protected $table 		= 'Permit';
		protected $primary_key  = 'ID';
		protected $columns 		= array(
			'ID'=>'int',
			'fDesc'=>'varchar',
			'fDate'=>'date',
			'bHour'=>'float',
			'Remarks'=>'varchar',
			'Type'=>'varchar'
		);
		//Columns
		protected $ID       	= Null;
		protected $fDesc    	= Null;
		protected $fDate    	= Null;
		protected $bHour    	= Null;
		protected $Remarks  	= Null;
		protected $Type     	= Null;
		//Meta
		protected $name 		= 'ID';
		protected $singular 	= 'permit';
		protected $plural   	= 'permits';
		//Relationships
		protected $aliases 		= array();
		protected $parents 		= array();
		protected $children 	= array();
		protected $tranversal 	= array();
		//Aethestics
		protected $icon 		= 'fas fa-id-badge';
		//View
		protected $viewport 	= array();
	}
}?> 