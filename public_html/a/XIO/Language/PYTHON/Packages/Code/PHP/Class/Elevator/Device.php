<?php
/*******************Requires******************/
//Classes
if(!class_exists('object')){require(ELEVATE_ROOT.'php/classes/object/index.php');}
/*******************object******************/
if(!class_exists('object_device')){
	class object_device extends object {
		/*******************Variables******************/
		//Server
		protected $server       = '172.16.12.45';
		protected $database 	= 'Device'; 
		//Table
		protected $table 		= 'device';
		protected $columns 		= array(
				'ID'=>'int',
				'Location'=>'int',
				'Bank'=>'int',
				'Common_Pit'=>'int',
				'Type'=>'int',
				'Name'=>'varchar',
				'Description'=>'text',
				'State'=>'varchar',
				'Status'=>'int',
				'Shutdown'=>'datetime',
				'Created'=>'datetime',
				'Installed'=>'datetime',
				'Decommissioned'=>'datetime',
				'Maintained'=>'datetime',
				'Notes'=>'text',
				'Machine_Room'=>'int',
				'Secondary'=>'int',
				'Destination_Dispatch'=>'int'
		);
		//Columns
		protected $ID = Null;
		protected $Location = Null;
		protected $Bank = Null;
		protected $Common_Pit = Null;
		protected $Type = Null;
		protected $Name = Null;
		protected $Description = Null;
		protected $State = Null;
		protected $Status = Null;
		protected $Shutdown = Null;
		protected $Created = Null;
		protected $Installed = Null;
		protected $Decommissioned = Null;
		protected $Maintained = Null;
		protected $Notes = Null;
		protected $Machine_Room = Null;
		protected $Secondary = Null;
		protected $Destination_Dispatch = Null;
		//Meta
		protected $singular     = 'device';
		protected $plural 		= 'devices';
		//Relationships
		protected $aliases 		= array();
		protected $parents 		= array();
		protected $children     = array();
		protected $tranversal	= array();
		//Aesthetics
		protected $icon 		= 'fa fa-cogs';
		//View
		protected $viewport 	= array();
		/*******************Functions******************/
	}
}?>