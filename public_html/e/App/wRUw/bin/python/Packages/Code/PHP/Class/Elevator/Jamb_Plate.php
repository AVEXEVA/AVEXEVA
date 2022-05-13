<?php
/*******************Requires******************/
//Classes
if(!class_exists('object')){require(ELEVATE_ROOT.'php/classes/object/index.php');}
/*******************object******************/
if(!class_exists('object_jamb_plate')){
	class iot_jamb_plate extends object {
		/*******************Variables******************/
		//Server
		protected $server      = '172.16.12.44';
		protected $database 		= 'Device';
		//Table
		protected $table 			= 'Jamb_Plate';
		protected $primary_key = 'ID';
		protected $columns 		= array(
						'ID'=>'int',
				'Product'=>'int',
				'Type'=>'varchar'
		);
		//Columns
					protected $ID = Null;
			protected $Product = Null;
			protected $Type = Null;

		//Meta
		protected $singular    = 'jamb_plate';
		protected $plural			= 'jamb_plates';
		//Relationships
		protected $aliases 		= array();
		protected $parents 		= array();
		protected $children    = array();
		protected $tranversal	= array('product'=>'Product');
		//Aesthetics
		protected $icon 		= 'link';
		/*******************Functions******************/
	}
}?>