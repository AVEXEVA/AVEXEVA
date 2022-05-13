<?php
/*******************Requires******************/
//Classes
if(!class_exists('object')){require(ELEVATE_ROOT.'php/classes/object/index.php');}
/*******************object******************/
if(!class_exists('object_buck')){
	class iot_buck extends object {
		/*******************Variables******************/
		//Server
		protected $server      = '172.16.12.44';
		protected $database 		= 'Device';
		//Table
		protected $table 			= 'Buck';
		protected $primary_key = 'ID';
		protected $columns 		= array(
						'ID'=>'int',
				'Item'=>'int',
				'Length'=>'float',
				'Width'=>'float',
				'Height'=>'float',
				'Opening_Width'=>'float',
				'Opening_Height'=>'float',
				'Opening_Length'=>'float'
		);
		//Columns
					protected $ID = Null;
			protected $Item = Null;
			protected $Length = Null;
			protected $Width = Null;
			protected $Height = Null;
			protected $Opening_Width = Null;
			protected $Opening_Height = Null;
			protected $Opening_Length = Null;

		//Meta
		protected $singular    = 'buck';
		protected $plural			= 'bucks';
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