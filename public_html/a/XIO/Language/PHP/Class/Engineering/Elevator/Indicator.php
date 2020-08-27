<?php
/*******************Requires******************/
//Classes
if(!class_exists('object')){require(ELEVATE_ROOT.'php/classes/object/index.php');}
/*******************object******************/
if(!class_exists('object_hall_target_indicator')){
	class iot_hall_target_indicator extends object {
		/*******************Variables******************/
		//Server
		protected $server      = '172.16.12.44';
		protected $database 		= 'Device';
		//Table
		protected $table 			= 'Hall_Target_Indicator';
		protected $primary_key = 'ID';
		protected $columns 		= array(
						'ID'=>'int',
				'Item'=>'int',
				'Floor'=>'int',
				'Position'=>'int'
		);
		//Columns
					protected $ID = Null;
			protected $Item = Null;
			protected $Floor = Null;
			protected $Position = Null;

		//Meta
		protected $singular    = 'hall_target_indicator';
		protected $plural			= 'hall_target_indicators';
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