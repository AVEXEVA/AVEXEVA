<?php
/*******************Requires******************/
//Classes
if(!class_exists('object')){require(ELEVATE_ROOT.'php/classes/object/index.php');}
/*******************object******************/
if(!class_exists('object_common_pit')){
	class iot_common_pit extends object {
		/*******************Variables******************/
		//Server
		protected $server      = '172.16.12.44';
		protected $database 		= 'Device';
		//Table
		protected $table 			= 'Common_Pit';
		protected $primary_key = 'ID';
		protected $columns 		= array(
						'ID'=>'int',
				'Location'=>'int',
				'Name'=>'varchar',
				'Description'=>'text',
				'Notes'=>'text',
				'Key'=>'int',
				'Stop_Switch'=>'int',
				'Type'=>'int'
		);
		//Columns
					protected $ID = Null;
			protected $Location = Null;
			protected $Name = Null;
			protected $Description = Null;
			protected $Notes = Null;
			protected $Key = Null;
			protected $Stop_Switch = Null;
			protected $Type = Null;

		//Meta
		protected $singular    = 'common_pit';
		protected $plural			= 'common_pits';
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