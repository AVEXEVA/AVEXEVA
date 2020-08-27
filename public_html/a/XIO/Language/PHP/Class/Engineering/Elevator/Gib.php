<?php
/*******************Requires******************/
//Classes
if(!class_exists('object')){require(ELEVATE_ROOT.'php/classes/object/index.php');}
/*******************object******************/
if(!class_exists('object_gib')){
	class iot_gib extends object {
		/*******************Variables******************/
		//Server
		protected $server      = '172.16.12.44';
		protected $database 		= 'Device';
		//Table
		protected $table 			= 'Gib';
		protected $primary_key = 'ID';
		protected $columns 		= array(
						'ID'=>'int',
				'Item'=>'int'
		);
		//Columns
					protected $ID = Null;
			protected $Item = Null;

		//Meta
		protected $singular    = 'gib';
		protected $plural			= 'gibs';
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