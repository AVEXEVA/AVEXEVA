<?php
/*******************Requires******************/
//Classes
if(!class_exists('object')){require(ELEVATE_ROOT.'php/classes/object/index.php');}
/*******************object******************/
if(!class_exists('object_device_pit')){
	class iot_device_pit extends object {
		/*******************Variables******************/
		//Server
		protected $server      = '172.16.12.44';
		protected $database 		= 'Device';
		//Table
		protected $table 			= 'Device_Pit';
		protected $primary_key = 'ID';
		protected $columns 		= array(
						'Device'=>'int',
				'Cleaned'=>'datetime',
				'Inspected'=>'datetime',
				'Maintained'=>'datetime',
				'Built'=>'datetime',
				'Notes'=>'text'
		);
		//Columns
					protected $Device = Null;
			protected $Cleaned = Null;
			protected $Inspected = Null;
			protected $Maintained = Null;
			protected $Built = Null;
			protected $Notes = Null;

		//Meta
		protected $singular    = 'device_pit';
		protected $plural			= 'device_pits';
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