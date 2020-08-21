<?php
/*******************Requires******************/
//Classes
if(!class_exists('object')){require(ELEVATE_ROOT.'php/classes/object/index.php');}
/*******************object******************/
if(!class_exists('object_secondary')){
	class iot_secondary extends object {
		/*******************Variables******************/
		//Server
		protected $server      = '172.16.12.44';
		protected $database 		= 'Device';
		//Table
		protected $table 			= 'Secondary';
		protected $primary_key = 'ID';
		protected $columns 		= array(
						'ID'=>'int',
				'Length'=>'float',
				'Width'=>'float',
				'Height'=>'float',
				'Key'=>'int',
				'Air_Conditioning'=>'int',
				'Notes'=>'text'
		);
		//Columns
					protected $ID = Null;
			protected $Length = Null;
			protected $Width = Null;
			protected $Height = Null;
			protected $Key = Null;
			protected $Air_Conditioning = Null;
			protected $Notes = Null;

		//Meta
		protected $singular    = 'secondary';
		protected $plural			= 'secondarys';
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