<?php
/*******************Requires******************/
//Classes
if(!class_exists('object')){require(ELEVATE_ROOT.'php/classes/object/index.php');}
/*******************object******************/
if(!class_exists('object_stop_switch')){
	class iot_stop_switch extends object {
		/*******************Variables******************/
		//Server
		protected $server      = '172.16.12.44';
		protected $database 		= 'Device';
		//Table
		protected $table 			= 'Stop_Switch';
		protected $primary_key = 'ID';
		protected $columns 		= array(
						'ID'=>'int',
				'Item'=>'int',
				'Position'=>'int'
		);
		//Columns
					protected $ID = Null;
			protected $Item = Null;
			protected $Position = Null;

		//Meta
		protected $singular    = 'stop_switch';
		protected $plural			= 'stop_switchs';
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