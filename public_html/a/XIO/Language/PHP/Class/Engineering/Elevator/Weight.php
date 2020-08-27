<?php
/*******************Requires******************/
//Classes
if(!class_exists('object')){require(ELEVATE_ROOT.'php/classes/object/index.php');}
/*******************object******************/
if(!class_exists('object_weight')){
	class iot_weight extends object {
		/*******************Variables******************/
		//Server
		protected $server      = '172.16.12.44';
		protected $database 		= 'Device';
		//Table
		protected $table 			= 'Weight';
		protected $primary_key = 'ID';
		protected $columns 		= array(
						'ID'=>'int',
				'Item'=>'int',
				'Pounds'=>'float'
		);
		//Columns
					protected $ID = Null;
			protected $Item = Null;
			protected $Pounds = Null;

		//Meta
		protected $singular    = 'weight';
		protected $plural			= 'weights';
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