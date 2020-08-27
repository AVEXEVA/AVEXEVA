<?php
/*******************Requires******************/
//Classes
if(!class_exists('object')){require(ELEVATE_ROOT.'php/classes/object/index.php');}
/*******************object******************/
if(!class_exists('object_elevator_identification')){
	class iot_elevator_identification extends object {
		/*******************Variables******************/
		//Server
		protected $server      = '172.16.12.44';
		protected $database 		= 'Device';
		//Table
		protected $table 			= 'Elevator_Identification';
		protected $primary_key = 'ID';
		protected $columns 		= array(
						'ID'=>'int',
				'Item'=>'int',
				'Label'=>'varchar'
		);
		//Columns
					protected $ID = Null;
			protected $Item = Null;
			protected $Label = Null;

		//Meta
		protected $singular    = 'elevator_identification';
		protected $plural			= 'elevator_identifications';
		//Relationships
		protected $aliases 		= array();
		protected $parents 		= array();
		protected $children    = array();
		protected $tranversal	= array('item'=>'Item');
		//Aesthetics
		protected $icon 		= 'link';
		/*******************Functions******************/
	}
}?>