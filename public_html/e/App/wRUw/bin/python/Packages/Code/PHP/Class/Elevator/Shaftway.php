<?php
/*******************Requires******************/
//Classes
if(!class_exists('object')){require(ELEVATE_ROOT.'php/classes/object/index.php');}
/*******************object******************/
if(!class_exists('object_shaftway')){
	class iot_shaftway extends object {
		/*******************Variables******************/
		//Server
		protected $server      = '172.16.12.44';
		protected $database 		= 'Device';
		//Table
		protected $table 			= 'Shaftway';
		protected $primary_key = 'ID';
		protected $columns 		= array(
						'Device'=>'int',
				'Length'=>'float',
				'Width'=>'float',
				'Height'=>'float',
				'Built'=>'datetime',
				'Notes'=>'text',
				'Top_Refugee'=>'float',
				'Bottom_Refugee'=>'float'
		);
		//Columns
					protected $Device = Null;
			protected $Length = Null;
			protected $Width = Null;
			protected $Height = Null;
			protected $Built = Null;
			protected $Notes = Null;
			protected $Top_Refugee = Null;
			protected $Bottom_Refugee = Null;

		//Meta
		protected $singular    = 'shaftway';
		protected $plural			= 'shaftways';
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