<?php
/*******************Requires******************/
//Classes
if(!class_exists('object')){require(ELEVATE_ROOT.'php/classes/object/index.php');}
/*******************object******************/
if(!class_exists('object_product')){
	class iot_product extends object {
		/*******************Variables******************/
		//Server
		protected $server      = '172.16.12.44';
		protected $database 		= 'Device';
		//Table
		protected $table 			= 'Product';
		protected $primary_key = 'ID';
		protected $columns 		= array(
						'ID'=>'int',
				'Type'=>'int',
				'Name'=>'varchar',
				'Description'=>'text',
				'Manufacturer'=>'int',
				'Model'=>'varchar',
				'Model_Number'=>'varchar',
				'Notes'=>'text',
				'Image'=>'image',
				'Link'=>'nvarchar',
				'Category'=>'int'
		);
		//Columns
					protected $ID = Null;
			protected $Type = Null;
			protected $Name = Null;
			protected $Description = Null;
			protected $Manufacturer = Null;
			protected $Model = Null;
			protected $Model_Number = Null;
			protected $Notes = Null;
			protected $Image = Null;
			protected $Link = Null;
			protected $Category = Null;

		//Meta
		protected $singular    = 'product';
		protected $plural			= 'products';
		protected $name 			= 'Name';
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
