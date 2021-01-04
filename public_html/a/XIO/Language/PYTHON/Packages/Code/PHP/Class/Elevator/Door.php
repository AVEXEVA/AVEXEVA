<?php
/*******************Requires******************/
//Classes
if(!class_exists('object')){require(ELEVATE_ROOT.'php/classes/object/index.php');}
/*******************object******************/
if(!class_exists('object_car_door')){
	class iot_car_door extends object {
		/*******************Variables******************/
		//Server
		protected $server      = '172.16.12.44';
		protected $database 		= 'Device';
		//Table
		protected $table 			= 'Car_Door';
		protected $primary_key = 'ID';
		protected $columns 		= array(
						'ID'=>'int',
				'Item'=>'int',
				'Length'=>'float',
				'Width'=>'float',
				'Height'=>'float'
		);
		//Columns
					protected $ID = Null;
			protected $Item = Null;
			protected $Length = Null;
			protected $Width = Null;
			protected $Height = Null;

		//Meta
		protected $singular    = 'car_door';
		protected $plural			= 'car_doors';
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