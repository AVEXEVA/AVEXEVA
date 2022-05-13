<?php
/*******************Requires******************/
//Classes
if(!class_exists('object')){require(ELEVATE_ROOT.'php/classes/object/index.php');}
/*******************object******************/
if(!class_exists('object_toe_guard')){
	class iot_toe_guard extends object {
		/*******************Variables******************/
		//Server
		protected $server      = '172.16.12.44';
		protected $database 		= 'Device';
		//Table
		protected $table 			= 'Toe_Guard';
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
		protected $singular    = 'toe_guard';
		protected $plural			= 'toe_guards';
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